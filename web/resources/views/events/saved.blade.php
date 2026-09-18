@extends('partials.layouts.master')

@section('title', 'Eventos guardados')
@section('sub-title', 'Eventos guardados')
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
                <p class="text-muted mb-0">No guardaste ningún evento todavía.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-nowrap w-100">
                        <thead class="bg-light bg-opacity-30">
                            <tr>
                                <th>Evento</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($events as $event)
                                <tr>
                                    <td class="fw-semibold">{{ $event->title }}</td>
                                    <td>{{ $event->start_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <form action="{{ route('events.unsave', $event) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light-danger">Quitar</button>
                                        </form>
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
