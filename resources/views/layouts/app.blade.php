<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestor-Convenios FIO')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>

    </style>
</head>

<body>
    @include('layouts.header')
    <div class="container-fluid flex-grow-1 d-flex flex-column">
        <div class="row flex-grow-1">
            @include('layouts.sidebar')
            <main  class="main-content" style="margin-left: 220px; padding-top: 90px;">
                @yield('content')
            </main>
        </div>
    </div>
    @include('layouts.footer')
    @yield('scripts')
    <script src="{{ asset('js/gestionAcademica.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
