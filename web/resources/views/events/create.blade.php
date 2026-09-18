@extends('partials.layouts.master')

@section('title', 'Crear evento')
@section('sub-title', 'Crear evento')
@section('pagetitle', 'Eventos')
@section('buttonTitle', 'Ver mis eventos')
@section('link', route('events.index'))

@section('content')

    <div class="row g-4">
        <div class="col-md-8 col-xl-9">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Información del evento</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('events.store') }}">
                        @csrf
                        @include('events.partials.form')

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Crear evento</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
