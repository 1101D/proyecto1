@extends('partials.layouts.master')

@section('title', 'Crear organización')
@section('sub-title', 'Crear organización')
@section('pagetitle', 'Eventos')
@section('buttonTitle', 'Ver organizaciones')
@section('link', route('organizations.index'))

@section('content')

    <div class="row g-4">
        <div class="col-md-8 col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Nueva organización</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif

                    <form method="POST" action="{{ route('organizations.store') }}">
                        @csrf
                        @include('organizations.partials.form')

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Crear organización</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
