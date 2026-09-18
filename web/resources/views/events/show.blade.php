@extends('partials.layouts.master')

@section('title', $event->title)
@section('sub-title', $event->title)
@section('pagetitle', 'Eventos')
@section('buttonTitle', 'Explorar eventos')
@section('link', route('explore.index'))

@section('content')

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif

                    <span class="badge bg-primary-subtle text-primary mb-2">{{ $event->category ?? 'General' }}</span>
                    <h3>{{ $event->title }}</h3>
                    <p class="text-muted mb-1"><i class="ri-calendar-line me-1"></i> {{ $event->start_at->format('d/m/Y H:i') }}
                        @if ($event->end_at) — {{ $event->end_at->format('d/m/Y H:i') }} @endif
                    </p>
                    @if ($event->address)
                        <p class="text-muted mb-3"><i class="ri-map-pin-line me-1"></i> {{ $event->address }}</p>
                    @endif

                    <hr>

                    <p>{{ $event->description }}</p>

                    @if ($event->address)
                        <div class="ratio ratio-16x9 mt-4">
                            <iframe
                                src="https://www.google.com/maps?q={{ urlencode($event->address) }}&output=embed"
                                allowfullscreen loading="lazy"></iframe>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Entradas</h5>
                </div>
                <div class="card-body">
                    @auth
                        @if ($event->ticketTypes->isEmpty())
                            <p class="text-muted mb-0">Este evento todavía no tiene entradas configuradas.</p>
                        @else
                            <form method="POST" action="{{ route('tickets.reserve', $event) }}">
                                @csrf
                                @foreach ($event->ticketTypes as $ticketType)
                                    <div class="mb-3 pb-3 border-bottom">
                                        <div class="hstack justify-content-between mb-1">
                                            <span class="fw-semibold">{{ $ticketType->name }}</span>
                                            <span>${{ number_format($ticketType->price, 2) }}</span>
                                        </div>
                                        <div class="fs-12 text-muted mb-2">
                                            {{ $ticketType->quantity_available }} disponibles
                                        </div>
                                        <input type="number" min="0" max="{{ $ticketType->quantity_available }}"
                                            class="form-control form-control-sm"
                                            name="quantities[{{ $ticketType->id }}]"
                                            {{ $ticketType->quantity_available < 1 ? 'disabled' : '' }}
                                            placeholder="Cantidad" value="0">
                                    </div>
                                @endforeach
                                <button type="submit" class="btn btn-primary w-100">Reservar entradas</button>
                            </form>
                        @endif

                        <form method="POST"
                            action="{{ route($isSaved ? 'events.unsave' : 'events.save', $event) }}" class="mt-3">
                            @csrf
                            @if ($isSaved) @method('DELETE') @endif
                            <button type="submit" class="btn btn-light w-100">
                                <i class="ri-bookmark-{{ $isSaved ? 'fill' : 'line' }} me-1"></i>
                                {{ $isSaved ? 'Quitar de guardados' : 'Guardar evento' }}
                            </button>
                        </form>
                    @else
                        <p class="text-muted">Iniciá sesión para reservar entradas.</p>
                        <a href="{{ route('login') }}" class="btn btn-primary w-100">Ingresar</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

@endsection
