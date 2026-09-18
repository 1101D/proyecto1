@extends('partials.layouts.master')

@section('title', 'Panel de administración')
@section('sub-title', 'Panel de administración')
@section('pagetitle', 'Admin')
@section('buttonTitle', 'Ver usuarios')
@section('link', route('admin.users.index'))

@section('content')

    <div class="row g-4 mb-1">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <span class="text-muted fs-12">Usuarios</span>
                    <h3 class="mb-0">{{ $usersCount }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <span class="text-muted fs-12">Eventos ({{ $activeEventsCount }} activos)</span>
                    <h3 class="mb-0">{{ $eventsCount }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <span class="text-muted fs-12">Entradas vendidas</span>
                    <h3 class="mb-0">{{ $ticketsSold }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <span class="text-muted fs-12">Ingresos de la plataforma</span>
                    <h3 class="mb-0">${{ number_format($totalRevenue, 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header hstack justify-content-between">
                    <h5 class="mb-0">Últimos usuarios</h5>
                    <a href="{{ route('admin.users.index') }}" class="fs-13">Ver todos →</a>
                </div>
                <div class="card-body">
                    <table class="table table-nowrap align-middle mb-0">
                        <tbody>
                            @foreach ($latestUsers as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td class="text-muted">{{ $user->email }}</td>
                                    <td>
                                        <span class="badge {{ $user->status === 'active' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header hstack justify-content-between">
                    <h5 class="mb-0">Últimos eventos</h5>
                    <a href="{{ route('admin.events.index') }}" class="fs-13">Ver todos →</a>
                </div>
                <div class="card-body">
                    <table class="table table-nowrap align-middle mb-0">
                        <tbody>
                            @foreach ($latestEvents as $event)
                                <tr>
                                    <td>{{ $event->title }}</td>
                                    <td class="text-muted">{{ $event->user->name }}</td>
                                    <td>
                                        <span class="badge {{ $event->status === 'active' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                            {{ ucfirst($event->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
