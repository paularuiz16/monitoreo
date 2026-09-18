<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AvícolaPro Control')</title>

    <!-- Favicon del Proyecto con el logo en JPG -->
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}?v=11">
    <link rel="shortcut icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}?v=11">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=11">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=11">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.jpg') }}?v=11">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Material Symbols Outlined -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Estilos CSS Organizados -->
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body class="d-flex align-items-center justify-content-center p-0 m-0">
    @yield('content')

    <!-- Scripts JS Organizados -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/auth.js') }}"></script>
    @yield('scripts')
</body>
</html>
