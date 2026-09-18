@php
    $event = $event ?? null;
    $ticketTypes = $event?->ticketTypes ?? collect();
    $organizations = $organizations ?? collect();
    $selectedCategory = old('category', $event->category ?? '');
    $selectedSubcategory = old('subcategory', $event->subcategory ?? '');
@endphp

<div class="row g-4">
    <div class="col-md-8">
        <label class="form-label" for="title">Nombre del evento<span class="text-danger ms-1">*</span></label>
        <input type="text" class="form-control" id="title" name="title"
            value="{{ old('title', $event->title ?? '') }}" placeholder="Nombre del evento" required>
    </div>

    <div class="col-md-4">
        <label class="form-label" for="organization_id">Organización</label>
        <select class="form-select" id="organization_id" name="organization_id">
            <option value="">Sin organización</option>
            @foreach ($organizations as $organization)
                <option value="{{ $organization->id }}" {{ old('organization_id', $event->organization_id ?? '') == $organization->id ? 'selected' : '' }}>
                    {{ $organization->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label" for="category">Categoría</label>
        <select class="form-select" id="category" name="category">
            <option value="">Elegí una categoría</option>
            @foreach (array_keys(\App\Models\Event::CATEGORIES) as $category)
                <option value="{{ $category }}" {{ $selectedCategory === $category ? 'selected' : '' }}>
                    {{ $category }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label" for="subcategory">Subcategoría</label>
        <select class="form-select" id="subcategory" name="subcategory">
            <option value="">Elegí una subcategoría</option>
            @foreach (\App\Models\Event::CATEGORIES[$selectedCategory] ?? [] as $subcategory)
                <option value="{{ $subcategory }}" {{ $selectedSubcategory === $subcategory ? 'selected' : '' }}>
                    {{ $subcategory }}
                </option>
            @endforeach
        </select>
        <div class="form-text fs-12">Elegí primero una categoría y guardá para ver sus subcategorías.</div>
    </div>

    <div class="col-12">
        <label class="form-label" for="description">Descripción</label>
        <textarea class="form-control" id="description" name="description" rows="4"
            placeholder="Contale a la gente de qué se trata tu evento">{{ old('description', $event->description ?? '') }}</textarea>
    </div>

    <div class="col-md-8">
        <label class="form-label" for="address">Dirección</label>
        <input type="text" class="form-control" id="address" name="address"
            value="{{ old('address', $event->address ?? '') }}" placeholder="Dirección del evento">
        <div class="form-text fs-12">El mapa se agregará automáticamente a partir de esta dirección.</div>
    </div>

    <div class="col-md-4 d-flex align-items-end">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="is_public" name="is_public" value="1"
                {{ old('is_public', $event->is_public ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_public">Evento público</label>
        </div>
    </div>

    <div class="col-md-6">
        <label class="form-label" for="start_at">Fecha y hora de inicio<span class="text-danger ms-1">*</span></label>
        <input type="datetime-local" class="form-control" id="start_at" name="start_at"
            value="{{ old('start_at', optional($event->start_at ?? null)->format('Y-m-d\TH:i')) }}" required>
    </div>

    <div class="col-md-6">
        <label class="form-label" for="end_at">Fecha y hora de fin</label>
        <input type="datetime-local" class="form-control" id="end_at" name="end_at"
            value="{{ old('end_at', optional($event->end_at ?? null)->format('Y-m-d\TH:i')) }}">
    </div>
</div>

@unless ($event)
    <hr class="my-5">

    <div>
        <div class="hstack justify-content-between mb-3">
            <h5 class="mb-0">Tipos de entrada</h5>
            <button type="button" class="btn btn-sm btn-light-primary" id="add-ticket-type">
                <i class="ri-add-line"></i> Agregar tipo de entrada
            </button>
        </div>

        <div id="ticket-types-wrapper">
            <div class="row g-3 mb-3 ticket-type-row">
                <div class="col-md-5">
                    <input type="text" class="form-control" name="ticket_name[]" placeholder="Ej: General">
                </div>
                <div class="col-md-3">
                    <input type="number" step="0.01" min="0" class="form-control" name="ticket_price[]"
                        placeholder="Precio">
                </div>
                <div class="col-md-3">
                    <input type="number" min="1" class="form-control" name="ticket_quantity[]"
                        placeholder="Cantidad disponible">
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('add-ticket-type').addEventListener('click', function () {
            const wrapper = document.getElementById('ticket-types-wrapper');
            const row = wrapper.querySelector('.ticket-type-row').cloneNode(true);
            row.querySelectorAll('input').forEach(input => input.value = '');
            wrapper.appendChild(row);
        });
    </script>
@endunless
