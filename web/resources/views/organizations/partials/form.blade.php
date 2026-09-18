@php $organization = $organization ?? null; @endphp

<div class="mb-4">
    <label class="form-label" for="name">Nombre de la organización<span class="text-danger ms-1">*</span></label>
    <input type="text" class="form-control" id="name" name="name"
        value="{{ old('name', $organization->name ?? '') }}" placeholder="Ej: Mi Productora" required>
</div>

<div class="mb-0">
    <label class="form-label" for="description">Descripción</label>
    <textarea class="form-control" id="description" name="description" rows="4"
        placeholder="Contale a la gente quién organiza estos eventos">{{ old('description', $organization->description ?? '') }}</textarea>
</div>
