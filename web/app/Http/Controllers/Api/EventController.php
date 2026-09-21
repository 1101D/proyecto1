<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $events = Event::query()
            ->where('is_public', true)
            ->where('status', 'active')
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where('title', 'like', '%'.$request->input('q').'%');
            })
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->where('category', $request->input('category'));
            })
            ->withCount('ticketTypes')
            ->orderBy('start_at')
            ->paginate($request->integer('per_page', 20));

        return response()->json($events);
    }

    public function upcoming(Request $request): JsonResponse
    {
        $events = Event::query()
            ->where('is_public', true)
            ->where('status', 'active')
            ->where('start_at', '>=', now())
            ->orderBy('start_at')
            ->paginate($request->integer('per_page', 20));

        return response()->json($events);
    }

    public function categories(): JsonResponse
    {
        return response()->json(Event::CATEGORIES);
    }

    public function show(Request $request, Event $event): JsonResponse
    {
        abort_unless($event->is_public && $event->status === 'active', 404);

        $event->load('ticketTypes', 'organization');

        $isSaved = $request->user()
            && $request->user()->savedEvents()->where('event_id', $event->id)->exists();

        return response()->json([
            ...$event->toArray(),
            'is_saved' => $isSaved,
        ]);
    }

    public function like(Request $request, Event $event): JsonResponse
    {
        $request->user()->savedEvents()->syncWithoutDetaching([$event->id]);

        return response()->json(['message' => 'Evento guardado.']);
    }

    public function unlike(Request $request, Event $event): JsonResponse
    {
        $request->user()->savedEvents()->detach($event->id);

        return response()->json(['message' => 'Evento quitado de guardados.']);
    }

    public function saved(Request $request): JsonResponse
    {
        $events = $request->user()->savedEvents()->latest('start_at')->get();

        return response()->json($events);
    }
}
