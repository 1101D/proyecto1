@extends('partials.layouts.master')

@section('title', 'Eventos')
@section('sub-title', 'Eventos')
@section('pagetitle', 'Admin')
@section('buttonTitle', 'Panel')
@section('link', route('admin.dashboard'))

@section('content')

    <div class="card">
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle table-nowrap w-100">
                    <thead class="bg-light bg-opacity-30">
                        <tr>
                            <th>Evento</th>
                            <th>Organizador</th>
                            <th>Fecha</th>
                            <th>Entradas vendidas</th>
                            <th>Visibilidad</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($events as $event)
                            <tr>
                                <td class="fw-semibold">{{ $event->title }}</td>
                                <td>{{ $event->user->name }}</td>
                                <td>{{ $event->start_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $event->tickets_count }}</td>
                                <td>
                                    <span class="badge {{ $event->is_public ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                        {{ $event->is_public ? 'Pública' : 'Privada' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $event->status === 'active' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                        {{ ucfirst($event->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="hstack gap-2">
                                        @if ($event->status === 'active')
                                            <form action="{{ route('admin.events.block', $event) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-light-danger">Bloquear</button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.events.unblock', $event) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-light-success">Desbloquear</button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.events.destroy', $event) }}" method="POST"
                                            onsubmit="return confirm('¿Eliminar este evento definitivamente?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light-secondary">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
