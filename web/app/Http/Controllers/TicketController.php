<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\TicketType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketController extends Controller
{
    public function reserve(Request $request, Event $event): RedirectResponse
    {
        abort_unless($event->is_public && $event->status === 'active', 404);

        $data = $request->validate([
            'quantities' => ['required', 'array'],
            'quantities.*' => ['nullable', 'integer', 'min:0', 'max:20'],
        ]);

        $quantities = array_filter($data['quantities'], fn ($qty) => (int) $qty > 0);

        if (empty($quantities)) {
            return back()->withErrors(['quantities' => 'Elegí al menos una entrada para reservar.']);
        }

        $user = $request->user();

        try {
            $order = DB::transaction(function () use ($quantities, $event, $user) {
                $order = $event->orders()->create([
                    'user_id' => $user->id,
                    'total' => 0,
                    'status' => 'paid',
                ]);

                $total = 0;

                foreach ($quantities as $ticketTypeId => $quantity) {
                    $ticketType = TicketType::where('event_id', $event->id)
                        ->lockForUpdate()
                        ->findOrFail($ticketTypeId);

                    if ($ticketType->quantity_available < $quantity) {
                        throw new \RuntimeException("No hay suficientes entradas disponibles para \"{$ticketType->name}\".");
                    }

                    $ticketType->decrement('quantity_available', $quantity);

                    for ($i = 0; $i < $quantity; $i++) {
                        Ticket::create([
                            'order_id' => $order->id,
                            'ticket_type_id' => $ticketType->id,
                            'event_id' => $event->id,
                            'user_id' => $user->id,
                            'code' => (string) Str::uuid(),
                            'status' => 'active',
                        ]);
                    }

                    $total += $ticketType->price * $quantity;
                }

                $order->update(['total' => $total]);

                return $order;
            });
        } catch (\RuntimeException $e) {
            return back()->withErrors(['quantities' => $e->getMessage()]);
        }

        return redirect()->route('tickets.index')->with('status', 'Reserva confirmada. Tus entradas ya están disponibles con su código QR.');
    }

    public function index(Request $request): View
    {
        $tickets = $request->user()
            ->tickets()
            ->with(['event', 'ticketType', 'order'])
            ->latest()
            ->get()
            ->groupBy('order_id');

        return view('tickets.index', compact('tickets'));
    }

    public function cancel(Request $request, Ticket $ticket): RedirectResponse
    {
        abort_unless($ticket->user_id === $request->user()->id, 403);

        if ($ticket->status === 'active') {
            DB::transaction(function () use ($ticket) {
                $ticket->update(['status' => 'cancelled']);
                $ticket->ticketType()->increment('quantity_available');
            });
        }

        return back()->with('status', 'Entrada cancelada. El cupo vuelve a estar disponible.');
    }

    public function qr(Request $request, Ticket $ticket): Response
    {
        abort_unless($ticket->user_id === $request->user()->id, 403);

        $svg = QrCode::size(220)->generate($ticket->code);

        return response($svg)->header('Content-Type', 'image/svg+xml');
    }
}
