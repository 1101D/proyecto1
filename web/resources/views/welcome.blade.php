@extends('partials.layouts.master-public')

@section('title', 'Eventz - Encontrá y creá eventos')

@section('content')

    <section class="py-5" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
        <div class="container py-5 text-center text-white">
            <h1 class="display-5 fw-bold mb-3">Creá tu evento. Vendé tus entradas. Sin complicaciones.</h1>
            <p class="fs-16 mb-5 opacity-75">
                Publicá tu evento en minutos, gestioná las reservas y generá entradas con código QR — todo en un solo lugar.
            </p>

            <form method="GET" action="{{ route('explore.index') }}" class="mx-auto" style="max-width: 560px;">
                <div class="input-group input-group-lg shadow">
                    <input type="text" name="q" class="form-control border-0" placeholder="Buscar eventos por nombre...">
                    <button class="btn btn-dark px-4" type="submit">
                        <i class="ri-search-line"></i>
                    </button>
                </div>
            </form>

            <div class="mt-5 d-flex gap-3 justify-content-center flex-wrap">
                <a href="{{ route('explore.index') }}" class="btn btn-light btn-lg">Explorar eventos</a>
                <a href="{{ auth()->check() ? route('events.create') : route('register') }}" class="btn btn-outline-light btn-lg">
                    Crear mi evento
                </a>
            </div>
        </div>
    </section>

    <section class="container py-5">
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body py-5">
                        <i class="ri-calendar-event-line fs-40 text-primary mb-3 d-block"></i>
                        <h5>Creá tu evento</h5>
                        <p class="text-muted fs-13 mb-0">Configurá nombre, fecha, ubicación y tipos de entrada en minutos.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body py-5">
                        <i class="ri-ticket-2-line fs-40 text-primary mb-3 d-block"></i>
                        <h5>Vendé entradas</h5>
                        <p class="text-muted fs-13 mb-0">Tus asistentes reservan online y reciben su entrada con código QR.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body py-5">
                        <i class="ri-line-chart-line fs-40 text-primary mb-3 d-block"></i>
                        <h5>Seguí tus resultados</h5>
                        <p class="text-muted fs-13 mb-0">Mirá ingresos, entradas vendidas y disponibilidad en tiempo real.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($events->isNotEmpty())
        <section class="container pb-5">
            <div class="hstack justify-content-between mb-4">
                <h4 class="mb-0">Próximos eventos</h4>
                <a href="{{ route('explore.index') }}" class="fs-13">Ver todos →</a>
            </div>
            <div class="row g-4">
                @foreach ($events as $event)
                    <div class="col-md-6 col-xl-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="hstack justify-content-between mb-2">
                                    <span class="badge bg-primary-subtle text-primary">{{ $event->category ?? 'General' }}</span>
                                    <span class="fs-12 text-muted">{{ $event->start_at->format('d/m/Y') }}</span>
                                </div>
                                <h5 class="mb-2">{{ $event->title }}</h5>
                                <a href="{{ route('explore.show', $event) }}" class="btn btn-sm btn-light-primary w-100">Ver evento</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

@endsection
