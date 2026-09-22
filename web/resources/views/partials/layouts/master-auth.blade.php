{{-- master_auth.blade.php  file  --}}
<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <title>@yield('title', ' Eventz - Plataforma de gestión de eventos')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Eventz te permite crear, publicar y vender entradas para tus eventos de forma simple.">
    <meta name="keywords"
        content="gestión de eventos, venta de entradas, organizador de eventos, tickets, reservas online">
    <meta content="Eventz" name="author">
    <link rel="shortcut icon" href="{{ asset('assets/images/Favicon.png') }}">">

    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="article">
    <meta property="og:title" content="Eventz">
    <meta property="og:description"
        content="Eventz te permite crear, publicar y vender entradas para tus eventos de forma simple.">
    <meta property="og:url" content="https://themeforest.net/user/srbthemes/portfolio">
    <meta property="og:site_name" content="Eventz">
    <script>
        window.DEFAULT_VALUES = window.DEFAULT_VALUES || {};
        window.DEFAULT_VALUES.AUTH_LAYOUT = true;
    </script>
    @yield('css')
</head>

<body>
    @include('partials.header')
    @include('partials.sidebar')
    @include('partials.preloader')

    <!-- end page title -->

    @yield('content')

    @include('partials.vendor-scripts')

    @yield('js')

</body>

</html>
