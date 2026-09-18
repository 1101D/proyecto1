<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicEventController extends Controller
{
    public function home(): View
    {
        $events = Event::query()
            ->where('is_public', true)
            ->where('status', 'active')
            ->where('start_at', '>=', now())
            ->orderBy('start_at')
            ->limit(6)
            ->get();

        return view('welcome', compact('events'));
    }

    public function index(Request $request): View
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
            ->orderBy('start_at')
            ->get();

        $categories = Event::query()
            ->where('is_public', true)
            ->where('status', 'active')
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        return view('events.explore', compact('events', 'categories'));
    }

    public function show(Event $event): View
    {
        abort_unless($event->is_public && $event->status === 'active', 404);

        $event->load('ticketTypes');

        $isSaved = auth()->check() && auth()->user()->savedEvents()->where('event_id', $event->id)->exists();

        return view('events.show', compact('event', 'isSaved'));
    }
}
