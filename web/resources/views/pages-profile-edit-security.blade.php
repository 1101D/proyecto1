@extends('partials.layouts.master2')

    @section('title', 'Seguridad | Eventz')
    @section('sub-title', 'Seguridad' )
    @section('pagetitle', 'Home')
    @section('buttonTitle', 'Share')
    @section('modalTarget', 'shareModal')

    @section('content')


  <div class="card">
    <div class="card-body pb-0">

      @include('partials.pages-profile-user-section')

      <!-- Nav tabs -->
      <ul class="nav nav-tabs-bordered border-0 justify-content-center mt-5" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link" href="pages-profile-edit-overview">Basic Information</a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link active" href="pages-profile-edit-security">Security</a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" href="pages-profile-edit-billing-plans">Billing & Plans</a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" href="pages-profile-edit-notifications">Notifications</a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" href="pages-profile-edit-connections">Connections</a>
        </li>
      </ul>

    </div>
  </div>

  <!-- Tab panes -->
  @if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif
  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0 ps-3">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card mb-0">
    <div class="card-header">
      <h5 class="card-title mb-0">Cambiar contraseña</h5>
    </div>
    <div class="card-body">
      <form method="POST" action="{{ route('profile.password') }}">
        @csrf
        @method('PUT')
        <div class="row">
          <div class="mb-5 col-md-6">
            <label class="form-label" for="current_password">Contraseña actual</label>
            <div class="input-group">
              <input type="password" id="current_password" class="form-control" name="current_password" placeholder="Tu contraseña actual" required data-visible="false">
              <a class="input-group-text toggle-password" href="javascript:;" data-target="password">
                <i class="ri-eye-off-line text-muted toggle-icon"></i>
              </a>
            </div>
          </div>
        </div>
        <div class="row g-5 mb-6">
          <div class="col-md-6">
            <label class="form-label" for="password">Nueva contraseña</label>
            <div class="input-group">
              <input type="password" id="password" class="form-control" name="password" placeholder="8+ caracteres" required minlength="8" data-visible="false">
              <a class="input-group-text toggle-password" href="javascript:;" data-target="password">
                <i class="ri-eye-off-line text-muted toggle-icon"></i>
              </a>
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label" for="password_confirmation">Confirmar contraseña</label>
            <div class="input-group">
              <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" placeholder="Repetí la nueva contraseña" required minlength="8" data-visible="false">
              <a class="input-group-text toggle-password" href="javascript:;" data-target="password">
                <i class="ri-eye-off-line text-muted toggle-icon"></i>
              </a>
            </div>
          </div>
        </div>
        <div class="mt-6">
          <button type="submit" class="btn btn-primary me-3">Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>

@endsection

@section('js')

  <!-- Countup init -->
  <script type="module" src="{{ asset('assets/js/pages/countup.init.js') }}"></script>

  <!-- Profile init -->
  <script src="{{ asset('assets/js/pages/pages-profile.init.js') }}"></script>

  <!-- App js -->
  <script type="module" src="{{ asset('assets/js/app.js') }}"></script>
  @endsection
