<div class="hstack align-items-start justify-content-center justify-content-sm-start text-center text-sm-start gap-4 flex-wrap flex-sm-nowrap">
    <div class="position-relative w-max">
        <div class="d-flex align-items-center flex-wrap gap-3" data-uploader>
            <div class="avatar-item avatar-xl">
                <img class="img-fluid avatar-xl" alt="avatar image" data-default-src="{{ asset('assets/images/avatar/avatar-1.jpg') }}" data-action="avatar-image">
            </div>

            <div class="file-upload position-absolute end-0 bottom-0">
                <span class="border-3 cursor-pointer border border-white h-30px w-30px rounded-circle bg-success d-flex align-items-center justify-content-center text-white" data-action="choose-file">
                    <i class="ri-camera-fill"></i>
                </span>
                <input class="file-upload-item" type="file" accept="image/*" data-action="file-input">
            </div>
        </div>
    </div>

    <div class="flex-grow-1">
        <div class="vstack gap-5 flex-sm-row mb-5 justify-content-center justify-content-sm-start">
            <div class="flex-grow-1">
                <h4 class="mb-2 fs-5 fw-semibold">{{ auth()->user()->name }}</h4>
                <ul class="d-flex flex-wrap gap-2 text-muted p-0 mb-0 justify-content-center justify-content-sm-start">
                    <li class="d-flex align-items-center gap-1">
                        <i class="ri-mail-line"></i>
                        <p class="mb-0">{{ auth()->user()->email }}</p>
                    </li>
                    <li class="d-flex align-items-center gap-1">
                        <i class="ri-calendar-2-line"></i>
                        <p class="mb-0">Miembro desde {{ auth()->user()->created_at->format('d/m/Y') }}</p>
                    </li>
                </ul>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-4 justify-content-center justify-content-sm-start">
            <div class="d-flex gap-3 align-items-center justify-content-center justify-content-sm-start flex-wrap text-center flex-grow-1">
                <div class="border border-dashed rounded p-3 min-w-176px">
                    <h5 class="fw-semibold text-primary fs-20 mb-1">{{ auth()->user()->events()->count() }}</h5>
                    <p class="text-muted mb-0 fw-medium">Eventos creados</p>
                </div>
                <div class="border border-dashed rounded p-3 min-w-176px">
                    <h5 class="fw-semibold text-success fs-20 mb-1">{{ auth()->user()->tickets()->where('status', 'active')->count() }}</h5>
                    <p class="text-muted mb-0 fw-medium">Entradas reservadas</p>
                </div>
                <div class="border border-dashed rounded p-3 min-w-176px">
                    <h5 class="fw-semibold text-info fs-20 mb-1">${{ number_format(\App\Models\Order::whereIn('event_id', auth()->user()->events()->pluck('id'))->where('status', 'paid')->sum('total'), 2) }}</h5>
                    <p class="text-muted mb-0 fw-medium">Ingresos totales</p>
                </div>
            </div>
        </div>

    </div>
</div>
