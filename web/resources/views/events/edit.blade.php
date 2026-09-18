@extends('partials.layouts.master')

@section('title', 'Editar evento')
@section('sub-title', 'Editar evento')
@section('pagetitle', 'Eventos')
@section('buttonTitle', 'Ver mis eventos')
@section('link', route('events.index'))

@section('content')

    <div class="row g-4">
        <div class="col-md-8 col-xl-9">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Editar evento</h5>
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

                    <form method="POST" action="{{ route('events.update', $event) }}">
                        @csrf
                        @method('PUT')
                        @include('events.partials.form', ['event' => $event])

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if ($event->ticketTypes->isNotEmpty())
            <div class="col-md-4 col-xl-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Tipos de entrada</h5>
                    </div>
                    <div class="card-body">
                        @foreach ($event->ticketTypes as $ticketType)
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <div>
                                    <div class="fw-semibold">{{ $ticketType->name }}</div>
                                    <div class="fs-12 text-muted">${{ number_format($ticketType->price, 2) }}</div>
                                </div>
                                <div class="text-end">
                                    <div class="fs-12 text-muted">{{ $ticketType->quantity_available }} / {{ $ticketType->quantity }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection
