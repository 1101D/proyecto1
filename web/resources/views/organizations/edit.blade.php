@extends('partials.layouts.master')

@section('title', 'Editar organización')
@section('sub-title', 'Editar organización')
@section('pagetitle', 'Eventos')
@section('buttonTitle', 'Ver organizaciones')
@section('link', route('organizations.index'))

@section('content')

    <div class="row g-4">
        <div class="col-md-8 col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Editar organización</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif

                    <form method="POST" action="{{ route('organizations.update', $organization) }}">
                        @csrf
                        @method('PUT')
                        @include('organizations.partials.form', ['organization' => $organization])

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
