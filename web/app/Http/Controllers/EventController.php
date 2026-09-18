<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $events = $request->user()
            ->events()
            ->withCount('ticketTypes')
            ->latest('start_at')
            ->get();

        return view('events.index', compact('events'));
    }

    public function create(Request $request): View
    {
        $organizations = $request->user()->organizations()->get();

        return view('events.create', compact('organizations'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateEvent($request);

        $event = $request->user()->events()->create([
            ...$data,
            'slug' => $this->uniqueSlug($data['title']),
        ]);

        foreach ($this->ticketTypesFromRequest($request) as $ticketType) {
            $event->ticketTypes()->create($ticketType);
        }

        return redirect()->route('events.index')->with('status', 'Evento creado correctamente.');
    }

    public function edit(Request $request, Event $event): View
    {
        $this->authorizeOwner($event);

        $event->load('ticketTypes');
        $organizations = $request->user()->organizations()->get();

        return view('events.edit', compact('event', 'organizations'));
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        $this->authorizeOwner($event);

        $data = $this->validateEvent($request);

        if ($data['title'] !== $event->title) {
            $data['slug'] = $this->uniqueSlug($data['title'], $event->id);
        }

        $event->update($data);

        return redirect()->route('events.index')->with('status', 'Evento actualizado correctamente.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $this->authorizeOwner($event);

        $event->delete();

        return redirect()->route('events.index')->with('status', 'Evento eliminado.');
    }

    public function dashboard(Event $event): View
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

        return view('events.dashboard', [
            'event' => $event,
            'tickets' => $tickets,
            'salesByType' => $salesByType,
            'revenue' => $event->revenue(),
            'ticketsSold' => $activeTickets->count(),
            'ticketsCancelled' => $tickets->where('status', 'cancelled')->count(),
        ]);
    }

    public function attendees(Event $event): Response
    {
        $this->authorizeOwner($event);

        $tickets = $event->tickets()->with(['user', 'ticketType'])->where('status', 'active')->get();

        $csv = "Nombre,Email,Tipo de entrada,Código,Estado\n";

        foreach ($tickets as $ticket) {
            $csv .= implode(',', [
                '"'.str_replace('"', '""', $ticket->user->name).'"',
                $ticket->user->email,
                '"'.str_replace('"', '""', $ticket->ticketType->name).'"',
                $ticket->code,
                $ticket->status,
            ])."\n";
        }

        $filename = Str::slug($event->title).'-asistentes.csv';

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function save(Request $request, Event $event): RedirectResponse
    {
        $request->user()->savedEvents()->syncWithoutDetaching([$event->id]);

        return back()->with('status', 'Evento guardado.');
    }

    public function unsave(Request $request, Event $event): RedirectResponse
    {
        $request->user()->savedEvents()->detach($event->id);

        return back()->with('status', 'Evento quitado de guardados.');
    }

    public function saved(Request $request): View
    {
        $events = $request->user()->savedEvents()->latest('start_at')->get();

        return view('events.saved', compact('events'));
    }

    private function authorizeOwner(Event $event): void
    {
        if ($event->user_id !== auth()->id()) {
            abort(403, 'No tenés permiso para modificar este evento.');
        }
    }

    private function validateEvent(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['nullable', 'string', 'max:120'],
            'subcategory' => ['nullable', 'string', 'max:120'],
            'organization_id' => [
                'nullable',
                Rule::exists('organizations', 'id')->where('user_id', $request->user()->id),
            ],
            'address' => ['nullable', 'string', 'max:255'],
            'start_at' => ['required', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
            'is_public' => ['sometimes', 'boolean'],
        ]) + ['is_public' => $request->boolean('is_public')];
    }

    private function ticketTypesFromRequest(Request $request): array
    {
        $names = $request->input('ticket_name', []);
        $prices = $request->input('ticket_price', []);
        $quantities = $request->input('ticket_quantity', []);

        $ticketTypes = [];

        foreach ($names as $index => $name) {
            if (blank($name)) {
                continue;
            }

            $quantity = (int) ($quantities[$index] ?? 0);

            $ticketTypes[] = [
                'name' => $name,
                'price' => (float) ($prices[$index] ?? 0),
                'quantity' => $quantity,
                'quantity_available' => $quantity,
            ];
        }

        return $ticketTypes;
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;

        while (Event::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
