<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $tickets = $request->user()
            ->tickets()
            ->with(['event', 'ticketType', 'order'])
            ->latest()
            ->get();

        return response()->json($tickets);
    }

    public function show(Request $request, Ticket $ticket): JsonResponse
    {
        abort_unless($ticket->user_id === $request->user()->id, 403);

        $ticket->load('event', 'ticketType', 'order');

        return response()->json($ticket);
    }

    public function cancel(Request $request, Ticket $ticket): JsonResponse
    {
        abort_unless($ticket->user_id === $request->user()->id, 403);

        if ($ticket->status === 'active') {
            DB::transaction(function () use ($ticket) {
                $ticket->update(['status' => 'cancelled']);
                $ticket->ticketType()->increment('quantity_available');
            });
        }

        return response()->json($ticket->fresh());
    }

    public function qr(Request $request, Ticket $ticket): Response
    {
        abort_unless($ticket->user_id === $request->user()->id, 403);

        $svg = QrCode::size(220)->generate($ticket->code);

        return response($svg)->header('Content-Type', 'image/svg+xml');
    }
}
