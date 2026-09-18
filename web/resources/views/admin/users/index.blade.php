@extends('partials.layouts.master')

@section('title', 'Usuarios')
@section('sub-title', 'Usuarios')
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
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Eventos</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="fw-semibold">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge {{ $user->role === 'admin' ? 'bg-primary-subtle text-primary' : 'bg-secondary-subtle text-secondary' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>{{ $user->events_count }}</td>
                                <td>
                                    <span class="badge {{ $user->status === 'active' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="hstack gap-2">
                                        @if ($user->id !== auth()->id())
                                            @if ($user->status === 'active')
                                                <form action="{{ route('admin.users.block', $user) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-light-danger">Bloquear</button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.users.unblock', $user) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-light-success">Desbloquear</button>
                                                </form>
                                            @endif

                                            @if ($user->role === 'admin')
                                                <form action="{{ route('admin.users.revoke-admin', $user) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-light-secondary">Quitar admin</button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.users.make-admin', $user) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-light-primary">Hacer admin</button>
                                                </form>
                                            @endif
                                        @else
                                            <span class="fs-12 text-muted">Vos</span>
                                        @endif
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
