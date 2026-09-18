@extends('partials.layouts.master')

@section('title', 'Explorar eventos')
@section('sub-title', 'Explorar eventos')
@section('pagetitle', 'Eventos')
@section('buttonTitle', auth()->check() ? 'Crear evento' : 'Ingresar')
@section('link', auth()->check() ? route('events.create') : route('login'))

@section('content')

    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('explore.index') }}" class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label">Buscar por nombre</label>
                    <input type="text" class="form-control" name="q" value="{{ request('q') }}"
                        placeholder="Nombre del evento">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Categoría</label>
                    <select name="category" class="form-select">
                        <option value="">Todas</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Buscar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4 mt-1">
        @forelse ($events as $event)
            <div class="col-md-6 col-xl-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="hstack justify-content-between mb-2">
                            <span class="badge bg-primary-subtle text-primary">{{ $event->category ?? 'General' }}</span>
                            <span class="fs-12 text-muted">{{ $event->start_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <h5 class="mb-2">{{ $event->title }}</h5>
                        <p class="text-muted fs-13 mb-3">{{ Str::limit($event->description, 100) }}</p>
                        <a href="{{ route('explore.show', $event) }}" class="btn btn-sm btn-light-primary w-100">Ver evento</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5 text-muted">
                        No hay eventos que coincidan con tu búsqueda.
                    </div>
                </div>
            </div>
        @endforelse
    </div>

@endsection
