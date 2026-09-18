<!DOCTYPE html>
<html lang="es" class="h-100">

<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Eventz - Encontrá y creá eventos')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Eventz te permite crear, publicar y vender entradas para tus eventos de forma simple.">
    <link rel="shortcut icon" href="{{ asset('assets/images/Favicon.png') }}">
    @include('partials.head-css', ['auth' => 'layout-auth'])
    @yield('css')
</head>

<body>

    <nav class="navbar navbar-expand-lg bg-white border-bottom py-3">
        <div class="container">
            <a class="navbar-brand fw-bold fs-20 text-primary" href="{{ url('/') }}">
                <i class="ri-calendar-event-fill me-1"></i> Eventz
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#publicNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="publicNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('explore.index') }}">Explorar eventos</a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('events.create') }}">Crear evento</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('events.index') }}">Mis eventos</a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-light-danger">Cerrar sesión</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Ingresar</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-sm btn-primary" href="{{ route('register') }}">Crear cuenta</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <footer class="border-top py-4 mt-5">
        <div class="container text-center text-muted fs-13">
            © <script>document.write(new Date().getFullYear())</script> Eventz. Todos los derechos reservados.
        </div>
    </footer>

    @include('partials.vendor-scripts')
    @yield('js')
    <script type="module" src="{{ asset('assets/js/app.js') }}"></script>
</body>

</html>
