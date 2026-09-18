<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AvícolaPro Control - Sistema de Monitoreo IoT')</title>

    <!-- Favicon del Proyecto -->
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}?v=20">
    <link rel="shortcut icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}?v=20">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=20">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=20">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Material Symbols Outlined -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Estilos CSS Organizados por Capas de Relación -->
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <!-- Chart.js para telemetría interactiva -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <!-- Top Navigation Bar -->
    <header class="stitch-topbar d-flex justify-content-between align-items-center px-3 px-md-4">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm d-lg-none p-1 border-0" id="sidebarToggle" type="button" aria-label="Toggle Sidebar">
                <span class="material-symbols-outlined text-dark fs-3">menu</span>
            </button>
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none" title="AvícolaPro Control">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="rounded-2 shadow-sm" style="height: 42px; width: auto;" />
            </a>
        </div>

        <div class="d-flex align-items-center gap-3 gap-md-4">
            <!-- Telemetry connection indicators -->
            <div class="d-none d-sm-flex align-items-center gap-2 text-muted small">
                <span class="d-flex align-items-center text-success" title="Conexión IoT Estable">
                    <span class="telemetry-pulse-dot me-2"></span> Red Activa
                </span>
                <span class="text-secondary mx-1">|</span>
                <span class="d-flex align-items-center text-muted" title="Batería Nodo Principal">
                    <span class="material-symbols-outlined fs-5 me-1 text-success">battery_full</span> 100%
                </span>
            </div>

            <!-- User Menu & Logout -->
            @auth
            <div class="dropdown">
                <button class="btn btn-outline-light border d-flex align-items-center gap-2 py-1 px-2 rounded-pill bg-white shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width: 32px; height: 32px; background: linear-gradient(135deg, #059669 0%, #0284c7 100%);">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="d-none d-md-block text-start pe-2">
                        <div class="fw-semibold text-dark small lh-1">{{ Auth::user()->full_name }}</div>
                        <span class="badge badge-stitch-{{ Auth::user()->role }} p-0 px-1 mt-1">{{ strtoupper(Auth::user()->role) }}</span>
                    </div>
                    <span class="material-symbols-outlined text-muted fs-6">expand_more</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 rounded-3 p-2" style="min-width: 220px;">
                    <li class="px-3 py-2 border-bottom mb-1">
                        <div class="fw-bold text-dark">{{ Auth::user()->name }} {{ Auth::user()->last_name }}</div>
                        <div class="text-muted small text-truncate">{{ Auth::user()->email }}</div>
                    </li>
                    <li>
                        <a class="dropdown-item rounded-2 d-flex align-items-center gap-2 py-2" href="{{ route('dashboard') }}">
                            <span class="material-symbols-outlined text-secondary fs-5">dashboard</span> Panel de Control
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item rounded-2 d-flex align-items-center gap-2 py-2" href="{{ route('climate.index') }}">
                            <span class="material-symbols-outlined text-success fs-5">thermostat</span> Control Climático
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item rounded-2 d-flex align-items-center gap-2 py-2" href="{{ route('scada.index') }}">
                            <span class="material-symbols-outlined text-primary fs-5">leaderboard</span> Tendencias SCADA
                        </a>
                    </li>
                    @if (Auth::user()->isAdmin())
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item rounded-2 d-flex align-items-center gap-2 py-2" href="{{ route('users.index') }}">
                            <span class="material-symbols-outlined text-primary fs-5">group</span> Gestión de Personal
                        </a>
                    </li>
                    @endif
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger rounded-2 d-flex align-items-center gap-2 py-2">
                                <span class="material-symbols-outlined fs-5">logout</span> Cerrar Sesión
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
            @endauth
        </div>
    </header>

    <div class="d-flex">
        <!-- Sidebar Navigation -->
        <aside class="stitch-sidebar" id="stitchSidebar">
            <div class="mb-4 px-2">
                <h6 class="fw-bold text-dark text-uppercase small mb-1">Operaciones Galpón</h6>
                <p class="text-muted small mb-0">Red de Sensores Activa (Galpón 01)</p>
            </div>

            <nav class="nav flex-column mb-auto">
                <a class="nav-link-stitch {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <span class="material-symbols-outlined fs-5">dashboard</span>
                    <span>Panel de Control</span>
                </a>
                <a class="nav-link-stitch {{ request()->routeIs('climate.*') ? 'active' : '' }}" href="{{ route('climate.index') }}">
                    <span class="material-symbols-outlined fs-5">thermostat</span>
                    <span>Control Climático</span>
                </a>
                <a class="nav-link-stitch {{ request()->routeIs('scada.*') ? 'active' : '' }}" href="{{ route('scada.index') }}">
                    <span class="material-symbols-outlined fs-5">leaderboard</span>
                    <span>Tendencias SCADA</span>
                </a>

                @if (Auth::user()->isAdmin())
                <div class="pt-3 pb-1 px-2 border-top mt-2">
                    <span class="font-label-caps text-muted" style="font-size: 10px;">Administración</span>
                </div>
                <a class="nav-link-stitch {{ request()->routeIs('users.index') ? 'active' : '' }}" href="{{ route('users.index') }}">
                    <span class="material-symbols-outlined fs-5">group</span>
                    <span>Gestión de Personal</span>
                </a>
                <a class="nav-link-stitch {{ request()->routeIs('users.create') ? 'active' : '' }}" href="{{ route('users.create') }}">
                    <span class="material-symbols-outlined fs-5">person_add</span>
                    <span>Crear Usuario Interno</span>
                </a>
                @endif
            </nav>

            <div class="mt-auto px-1 pt-3 border-top">
                <div class="d-flex align-items-center gap-2 mb-2 p-2 bg-light rounded-3">
                    <span class="material-symbols-outlined text-success fs-5">sensors</span>
                    <div class="small">
                        <div class="fw-semibold">ESP32-SGalpon-01</div>
                        <span class="text-muted" style="font-size: 11px;">En línea &bull; Muestreo 60s</span>
                    </div>
                </div>
                <button type="button" id="emergencyStopBtn" class="btn btn-outline-danger w-full d-flex align-items-center justify-content-center gap-2 py-2 rounded-3 w-100 fw-bold small shadow-sm">
                    <span class="material-symbols-outlined fs-5">emergency_home</span> Parada de Emergencia
                </button>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="stitch-main-content flex-grow-1 animate-fade-in">
            <div class="container-fluid p-0">
                <!-- Flash messages -->
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 d-flex align-items-center gap-2 animate-slide-down" role="alert">
                        <span class="material-symbols-outlined text-success fs-4">check_circle</span>
                        <div class="fw-medium">{{ session('status') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0 d-flex align-items-center gap-2 animate-slide-down" role="alert">
                        <span class="material-symbols-outlined text-danger fs-4">error</span>
                        <div class="fw-medium">{{ session('error') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0 animate-slide-down" role="alert">
                        <div class="d-flex align-items-center gap-2 mb-1 fw-bold">
                            <span class="material-symbols-outlined fs-4">warning</span> Errores en la solicitud:
                        </div>
                        <ul class="mb-0 small ps-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Scripts JS Organizados -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
</body>
</html>
