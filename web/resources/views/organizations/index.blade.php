@extends('partials.layouts.master')

@section('title', 'Organizaciones')
@section('sub-title', 'Organizaciones')
@section('pagetitle', 'Eventos')
@section('buttonTitle', 'Crear organización')
@section('link', route('organizations.create'))

@section('content')

    <div class="card">
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            @if ($organizations->isEmpty())
                <div class="text-center py-5">
                    <p class="text-muted mb-3">Todavía no creaste ninguna organización.</p>
                    <a href="{{ route('organizations.create') }}" class="btn btn-primary">Crear mi primera organización</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-nowrap w-100">
                        <thead class="bg-light bg-opacity-30">
                            <tr>
                                <th>Organización</th>
                                <th>Eventos</th>
                                <th>Creada</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($organizations as $organization)
                                <tr>
                                    <td class="fw-semibold">{{ $organization->name }}</td>
                                    <td>{{ $organization->events_count }}</td>
                                    <td>{{ $organization->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="hstack gap-2">
                                            <a href="{{ route('organizations.edit', $organization) }}" class="btn btn-sm btn-light-primary">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                            <form action="{{ route('organizations.destroy', $organization) }}" method="POST"
                                                onsubmit="return confirm('¿Eliminar esta organización? Los eventos asociados no se eliminan.');">
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
