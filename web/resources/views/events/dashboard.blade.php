@extends('partials.layouts.master')

@section('title', 'Panel del evento')
@section('sub-title', $event->title)
@section('pagetitle', 'Eventos')
@section('buttonTitle', 'Editar evento')
@section('link', route('events.edit', $event))

@section('content')

    <div class="row g-4 mb-1">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <span class="text-muted fs-12">Ingresos</span>
                    <h3 class="mb-0">${{ number_format($revenue, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <span class="text-muted fs-12">Entradas vendidas</span>
                    <h3 class="mb-0">{{ $ticketsSold }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <span class="text-muted fs-12">Entradas canceladas</span>
                    <h3 class="mb-0">{{ $ticketsCancelled }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Ventas por tipo de entrada</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-nowrap align-middle mb-0">
                            <thead class="bg-light bg-opacity-30">
                                <tr>
                                    <th>Tipo</th>
                                    <th>Vendidas</th>
                                    <th>Ingresos</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($salesByType as $row)
                                    <tr>
                                        <td>{{ $row['name'] }}</td>
                                        <td>{{ $row['sold'] }} / {{ $row['quantity'] }}</td>
                                        <td>${{ number_format($row['revenue'], 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">Sin tipos de entrada.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card">
                <div class="card-header hstack justify-content-between">
                    <h5 class="mb-0">Asistentes</h5>
                    <a href="{{ route('events.attendees', $event) }}" class="btn btn-sm btn-light-primary">
                        <i class="ri-download-line"></i> Descargar lista
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-nowrap align-middle mb-0">
                            <thead class="bg-light bg-opacity-30">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Entrada</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tickets as $ticket)
                                    <tr>
                                        <td>{{ $ticket->user->name }}</td>
                                        <td>{{ $ticket->user->email }}</td>
                                        <td>{{ $ticket->ticketType->name }}</td>
                                        <td>
                                            <span class="badge {{ $ticket->status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                                {{ ucfirst($ticket->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Todavía no hay reservas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
