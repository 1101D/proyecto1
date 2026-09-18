@extends('partials.layouts.master')

@section('title', 'Mis entradas')
@section('sub-title', 'Mis entradas')
@section('pagetitle', 'Eventos')
@section('buttonTitle', 'Explorar eventos')
@section('link', route('explore.index'))

@section('content')

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @forelse ($tickets as $orderId => $orderTickets)
        @php $order = $orderTickets->first()->order; @endphp
        <div class="card">
            <div class="card-header hstack justify-content-between">
                <div>
                    <h5 class="mb-0">{{ $orderTickets->first()->event->title }}</h5>
                    <span class="fs-12 text-muted">Pedido #{{ $order->id }} · {{ $order->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <span class="badge bg-success-subtle text-success">{{ ucfirst($order->status) }}</span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach ($orderTickets as $ticket)
                        <div class="col-md-6 col-xl-4">
                            <div class="border rounded p-3 text-center">
                                @if ($ticket->status === 'active')
                                    <img src="{{ route('tickets.qr', $ticket) }}" alt="QR" class="img-fluid mb-2"
                                        style="max-width: 160px;">
                                @endif
                                <div class="fw-semibold">{{ $ticket->ticketType->name }}</div>
                                <div class="fs-12 text-muted mb-2">Código: {{ Str::limit($ticket->code, 13) }}</div>
                                <span class="badge {{ $ticket->status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} mb-2">
                                    {{ ucfirst($ticket->status) }}
                                </span>
                                @if ($ticket->status === 'active')
                                    <form method="POST" action="{{ route('tickets.cancel', $ticket) }}"
                                        onsubmit="return confirm('¿Cancelar esta entrada?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-light-danger w-100">Cancelar</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="card-body text-center py-5">
                <p class="text-muted mb-3">Todavía no reservaste ninguna entrada.</p>
                <a href="{{ route('explore.index') }}" class="btn btn-primary">Explorar eventos</a>
            </div>
        </div>
    @endforelse

@endsection
