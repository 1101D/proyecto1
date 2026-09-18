<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(): View
    {
        $events = Event::with('user')->withCount('tickets')->latest()->get();

        return view('admin.events.index', compact('events'));
    }

    public function block(Event $event): RedirectResponse
    {
        $event->update(['status' => 'blocked']);

        return back()->with('status', 'Evento bloqueado.');
    }

    public function unblock(Event $event): RedirectResponse
    {
        $event->update(['status' => 'active']);

        return back()->with('status', 'Evento desbloqueado.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $event->delete();

        return back()->with('status', 'Evento eliminado.');
    }
}
