@extends('partials.layouts.master2')

    @section('title', 'Configuración | Eventz')
    @section('sub-title', 'Configuración' )
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
          <a class="nav-link active" href="pages-profile-edit-overview">Basic Information</a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" href="pages-profile-edit-security">Security</a>
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

  @if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif
  @if ($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
  @endif

  <!-- Tab panes -->
  <div class="row g-4 justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">Información básica</h5>
        </div>
        <form method="POST" action="{{ route('profile.update') }}">
          @csrf
          @method('PUT')
          <div class="card-body">
            <div class="row gy-3">
              <div class="col-xl-6">
                <label for="profile-name" class="form-label">Nombre :</label>
                <input type="text" class="form-control" id="profile-name" name="name" value="{{ old('name', auth()->user()->name) }}">
              </div>
              <div class="col-xl-6">
                <label for="profile-email" class="form-label">Email :</label>
                <input type="email" class="form-control" id="profile-email" name="email" value="{{ old('email', auth()->user()->email) }}">
              </div>
            </div>
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary float-end">Guardar cambios</button>
          </div>
        </form>
      </div>

      <div class="card mb-0">
        <div class="card-header">
          <h5 class="card-title mb-0">Eliminar cuenta</h5>
        </div>

        <div class="card-body">
          <p class="card-text">Al eliminar tu cuenta perdés acceso a Eventz y tus eventos, entradas y reservas se eliminan de forma permanente. Esta acción no se puede deshacer.</p>

          <form method="POST" action="{{ route('profile.destroy') }}"
            onsubmit="return confirm('¿Seguro que querés eliminar tu cuenta? Esta acción no se puede deshacer.');">
            @csrf
            @method('DELETE')
            <div class="d-flex justify-content-end">
              <button type="submit" class="btn btn-danger">Eliminar mi cuenta</button>
            </div>
          </form>
        </div>
      </div>
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
