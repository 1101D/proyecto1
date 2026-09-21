<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\TicketType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function store(Request $request, Event $event): JsonResponse
    {
        abort_unless($event->is_public && $event->status === 'active', 404);

        $data = $request->validate([
            'quantities' => ['required', 'array'],
            'quantities.*' => ['nullable', 'integer', 'min:0', 'max:20'],
        ]);

        $quantities = array_filter($data['quantities'], fn ($qty) => (int) $qty > 0);

        if (empty($quantities)) {
            return response()->json([
                'message' => 'Elegí al menos una entrada para reservar.',
            ], 422);
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
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $order->load('tickets.ticketType', 'event');

        return response()->json($order, 201);
    }

    public function index(Request $request): JsonResponse
    {
        $orders = $request->user()
            ->orders()
            ->with(['event', 'tickets.ticketType'])
            ->latest()
            ->paginate($request->integer('per_page', 20));

        return response()->json($orders);
    }

    public function show(Request $request, \App\Models\Order $order): JsonResponse
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        $order->load(['event', 'tickets.ticketType']);

        return response()->json($order);
    }
}
