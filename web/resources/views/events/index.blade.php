@extends('partials.layouts.master')

@section('title', 'Mis eventos')
@section('sub-title', 'Mis eventos')
@section('pagetitle', 'Eventos')
@section('buttonTitle', 'Crear evento')
@section('link', route('events.create'))

@section('content')

    <div class="card">
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            @if ($events->isEmpty())
                <div class="text-center py-5">
                    <p class="text-muted mb-3">Todavía no creaste ningún evento.</p>
                    <a href="{{ route('events.create') }}" class="btn btn-primary">Crear mi primer evento</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-nowrap w-100">
                        <thead class="bg-light bg-opacity-30">
                            <tr>
                                <th>Evento</th>
                                <th>Categoría</th>
                                <th>Fecha</th>
                                <th>Tipos de entrada</th>
                                <th>Visibilidad</th>
                                <th>Estado</th>
                                <th>Ingresos</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($events as $event)
                                <tr>
                                    <td class="fw-semibold">{{ $event->title }}</td>
                                    <td>{{ $event->category ?? '—' }}</td>
                                    <td>{{ $event->start_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $event->ticket_types_count }}</td>
                                    <td>
                                        @if ($event->is_public)
                                            <span class="badge bg-success-subtle text-success">Pública</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">Privada</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $event->status === 'active' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                            {{ ucfirst($event->status) }}
                                        </span>
                                    </td>
                                    <td>${{ number_format($event->revenue(), 2) }}</td>
                                    <td>
                                        <div class="hstack gap-2">
                                            <a href="{{ route('events.dashboard', $event) }}" class="btn btn-sm btn-light-info" title="Panel del evento">
                                                <i class="ri-dashboard-line"></i>
                                            </a>
                                            <a href="{{ route('events.edit', $event) }}" class="btn btn-sm btn-light-primary" title="Editar">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                            <form action="{{ route('events.destroy', $event) }}" method="POST"
                                                onsubmit="return confirm('¿Eliminar este evento?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light-danger">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

@endsection
