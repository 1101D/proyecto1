<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrganizerController extends Controller
{
    public function dashboard(Request $request): JsonResponse
    {
        $events = $request->user()
            ->events()
            ->withCount(['ticketTypes', 'tickets as tickets_sold_count' => function ($query) {
                $query->where('status', 'active');
            }])
            ->orderBy('start_at')
            ->get();

        $totalRevenue = $events->sum(fn (Event $event) => $event->revenue());
        $upcomingEvents = $events->where('start_at', '>=', now())->count();

        return response()->json([
            'total_events' => $events->count(),
            'upcoming_events' => $upcomingEvents,
            'total_revenue' => $totalRevenue,
            'total_tickets_sold' => $events->sum('tickets_sold_count'),
            'events' => $events,
        ]);
    }

    public function eventDashboard(Event $event): JsonResponse
    {
        $this->authorizeOwner($event);

        $event->load('ticketTypes');

        $tickets = $event->tickets()->with(['user', 'ticketType'])->latest()->get();

        $salesByType = $event->ticketTypes->map(function ($ticketType) {
            $sold = $ticketType->quantity - $ticketType->quantity_available;

            return [
                'name' => $ticketType->name,
                'price' => $ticketType->price,
                'sold' => $sold,
                'quantity' => $ticketType->quantity,
                'revenue' => $sold * $ticketType->price,
            ];
        });

        $activeTickets = $tickets->where('status', 'active');

        return response()->json([
            'event' => $event,
            'revenue' => $event->revenue(),
            'tickets_sold' => $activeTickets->count(),
            'tickets_cancelled' => $tickets->where('status', 'cancelled')->count(),
            'sales_by_type' => $salesByType->values(),
        ]);
    }

    public function attendees(Event $event): JsonResponse
    {
        $this->authorizeOwner($event);

        $tickets = $event->tickets()
            ->with(['user:id,name,email', 'ticketType:id,name'])
            ->where('status', 'active')
            ->get(['id', 'user_id', 'ticket_type_id', 'code', 'status']);

        return response()->json($tickets);
    }

    public function orders(Event $event): JsonResponse
    {
        $this->authorizeOwner($event);

        $orders = $event->orders()
            ->with(['user:id,name,email', 'tickets.ticketType'])
            ->latest()
            ->get();

        return response()->json($orders);
    }

    public function sales(Event $event): JsonResponse
    {
        $this->authorizeOwner($event);

        $event->load('ticketTypes');

        $salesByType = $event->ticketTypes->map(function ($ticketType) {
            $sold = $ticketType->quantity - $ticketType->quantity_available;

            return [
                'ticket_type' => $ticketType->name,
                'sold' => $sold,
                'available' => $ticketType->quantity_available,
                'revenue' => $sold * $ticketType->price,
            ];
        });

        return response()->json([
            'event_id' => $event->id,
            'gross_revenue' => $event->revenue(),
            'by_ticket_type' => $salesByType->values(),
        ]);
    }

    public function scan(Request $request): JsonResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string'],
        ]);

        $ticket = Ticket::where('code', $data['code'])
            ->with(['event', 'user', 'ticketType'])
            ->first();

        if (! $ticket) {
            return response()->json(['message' => 'Entrada no encontrada.'], 404);
        }

        $this->authorizeOwner($ticket->event);

        if ($ticket->status === 'cancelled') {
            return response()->json(['message' => 'Esta entrada fue cancelada.', 'ticket' => $ticket], 422);
        }

        if ($ticket->status === 'used') {
            return response()->json(['message' => 'Esta entrada ya fue escaneada.', 'ticket' => $ticket], 422);
        }

        $ticket->update(['status' => 'used']);

        return response()->json([
            'message' => 'Entrada válida. Acceso concedido.',
            'ticket' => $ticket->fresh(['event', 'user', 'ticketType']),
        ]);
    }

    private function authorizeOwner(Event $event): void
    {
        abort_unless($event->user_id === auth()->id(), 403, 'No tenés permiso sobre este evento.');
    }
}
