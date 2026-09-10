@extends('layouts.app')

@section('title', 'Panel de Control - Monitoreo Avícola | AvícolaPro Control')

@section('content')
<!-- Page Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 rounded-pill small d-inline-flex align-items-center gap-1">
                <span class="telemetry-pulse-dot me-1"></span>
                Telemetría en Vivo
            </span>
            <span class="text-muted small">&bull; Nodo {{ $latest->device_id ?? 'ESP32-SGalpon-01' }}</span>
        </div>
        <h3 class="fw-bold text-dark mb-0">Panel de Control &bull; Monitoreo Ambiental Avícola</h3>
    </div>
    
    <div class="d-flex align-items-center gap-2">
        <span class="text-muted small d-none d-sm-inline">Último reporte: <strong>{{ $latest->recorded_at ? $latest->recorded_at->format('H:i:s d/m/Y') : now()->format('H:i:s') }}</strong></span>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm rounded-3 d-inline-flex align-items-center gap-1 shadow-sm" title="Recargar métricas">
            <span class="material-symbols-outlined fs-5">refresh</span>
            <span class="d-none d-md-inline">Actualizar</span>
        </a>
    </div>
</div>

<!-- 1. Summary Cards Section (4 Métricas Clave de Stitch) -->
<div class="row g-3 mb-4" id="seccion-sensores">
    <!-- Temp. Interior -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stitch-card telemetry-summary-card p-3 h-100 d-flex align-items-center gap-3">
            <div class="telemetry-icon-box text-success shadow-sm" style="background-color: var(--stitch-secondary-container);">
                <span class="material-symbols-outlined fs-2">thermometer</span>
            </div>
            <div class="flex-grow-1">
                <span class="font-label-caps">TEMP. INTERIOR</span>
                <div class="d-flex align-items-baseline gap-2">
                    <h2 class="metric-value mb-0">{{ round($latest->temperature, 1) }}°C</h2>
                    <span class="badge bg-success bg-opacity-10 text-success small">Óptimo</span>
                </div>
                <div class="text-muted small mt-1" style="font-size: 11px;">
                    Min: {{ $stats24h['temp_min'] }}°C &bull; Max: {{ $stats24h['temp_max'] }}°C
                </div>
            </div>
        </div>
    </div>

    <!-- Temp. Exterior -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stitch-card telemetry-summary-card p-3 h-100 d-flex align-items-center gap-3">
            <div class="telemetry-icon-box text-muted shadow-sm" style="background-color: var(--stitch-surface-container);">
                <span class="material-symbols-outlined fs-2">cloud</span>
            </div>
            <div class="flex-grow-1">
                <span class="font-label-caps">TEMP. EXTERIOR</span>
                <div class="d-flex align-items-baseline gap-2">
                    <h2 class="metric-value mb-0">{{ round($latest->external_temperature ?? 28.0, 1) }}°C</h2>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary small">Ambiente</span>
                </div>
                <div class="text-muted small mt-1" style="font-size: 11px;">
                    Dif. térmico: +{{ round(($latest->external_temperature ?? 28) - $latest->temperature, 1) }}°C
                </div>
            </div>
        </div>
    </div>

    <!-- Humedad Relativa -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stitch-card telemetry-summary-card p-3 h-100 d-flex align-items-center gap-3">
            <div class="telemetry-icon-box shadow-sm" style="background-color: var(--stitch-info-bg); color: var(--stitch-info);">
                <span class="material-symbols-outlined fs-2">humidity_mid</span>
            </div>
            <div class="flex-grow-1">
                <span class="font-label-caps">HUMEDAD RELATIVA</span>
                <div class="d-flex align-items-baseline gap-2">
                    <h2 class="metric-value mb-0">{{ round($latest->humidity, 1) }}%</h2>
                    <span class="badge bg-info bg-opacity-10 text-info small">Estable</span>
                </div>
                <div class="text-muted small mt-1" style="font-size: 11px;">
                    Promedio 24h: {{ $stats24h['hum_avg'] }}%
                </div>
            </div>
        </div>
    </div>

    <!-- Presión Atmosférica & Cortinas -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stitch-card telemetry-summary-card p-3 h-100 d-flex align-items-center gap-3">
            <div class="telemetry-icon-box shadow-sm" style="background-color: #fef3c7; color: #b45309;">
                <span class="material-symbols-outlined fs-2">speed</span>
            </div>
            <div class="flex-grow-1">
                <span class="font-label-caps">PRESIÓN ATMOSFÉRICA</span>
                <div class="d-flex align-items-baseline gap-2">
                    <h2 class="metric-value mb-0" style="font-size: 1.6rem;">{{ round($latest->atmospheric_pressure, 1) }} <small class="fs-6 text-muted">hPa</small></h2>
                </div>
                <div class="d-flex align-items-center gap-1 mt-1 text-muted" style="font-size: 11px;">
                    <span>Cortinas: <strong>{{ round($latest->curtain_position, 0) }}%</strong></span>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary p-0 px-1">{{ $latest->curtain_mode }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 2. Main Row: SCADA Climate Trends Chart & Control Panel -->
<div class="row g-4 mb-4" id="seccion-tendencias">
    <!-- Climate Trends Chart (8 Columns) -->
    <div class="col-12 col-xl-8">
        <div class="stitch-card h-100 p-4">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-4">
                <div>
                    <h5 class="fw-bold text-dark mb-1">Tendencias Climáticas del Galpón</h5>
                    <p class="text-muted small mb-0">Rendimiento e historial de telemetría de las últimas 24 Horas</p>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center gap-1">
                        <span class="rounded-circle d-inline-block" style="width: 10px; height: 10px; background-color: var(--stitch-secondary);"></span>
                        <span class="small fw-semibold text-muted">Temp (°C)</span>
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        <span class="rounded-circle d-inline-block" style="width: 10px; height: 10px; background-color: var(--stitch-primary);"></span>
                        <span class="small fw-semibold text-muted">Humedad (%)</span>
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        <span class="rounded-circle d-inline-block" style="width: 10px; height: 10px; background-color: var(--stitch-info);"></span>
                        <span class="small fw-semibold text-muted">Presión (hPa)</span>
                    </div>
                </div>
            </div>

            <!-- Canvas for Chart.js (Estilizado en dashboard.css) -->
            <div class="chart-container-scada">
                <canvas id="scadaChart"></canvas>
            </div>

            <div class="d-flex justify-content-between align-items-center pt-3 mt-3 border-top text-muted font-label-caps" style="font-size: 11px;">
                <span>00:00</span>
                <span>04:00</span>
                <span>08:00</span>
                <span>12:00</span>
                <span>16:00</span>
                <span>20:00</span>
                <span>Ahora</span>
            </div>
        </div>
    </div>

    <!-- Curtain & Ventilation Control Panel (4 Columns) -->
    <div class="col-12 col-xl-4">
        <div class="stitch-card h-100 p-4 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0">Panel de Control</h5>
                    <span class="badge badge-stitch-secondary">
                        Modo {{ $latest->curtain_mode }}
                    </span>
                </div>
                <p class="text-muted small mb-4">Comando manual de actuadores electromecánicos y compuertas de ventilación.</p>

                <!-- Curtain Adjustment Form -->
                <form method="POST" action="{{ route('dashboard.curtain') }}" id="curtainForm">
                    @csrf
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label for="curtainRange" class="form-label font-label-caps mb-0">Apertura de Cortinas</label>
                            <span class="badge bg-dark text-white fw-bold px-2 py-1" id="curtainValueBadge">
                                {{ round($latest->curtain_position, 0) }}%
                            </span>
                        </div>
                        <input type="range" 
                               name="curtain_position" 
                               class="form-range form-range-curtain" 
                               id="curtainRange" 
                               min="0" 
                               max="100" 
                               step="5" 
                               value="{{ round($latest->curtain_position, 0) }}">
                        <div class="d-flex justify-content-between text-muted" style="font-size: 10px; font-weight: 700;">
                            <span>CERRADA (0%)</span>
                            <span>INTERMEDIA (50%)</span>
                            <span>ABIERTA (100%)</span>
                        </div>
                    </div>

                    <input type="hidden" name="curtain_mode" value="MANUAL">

                    <div class="row g-2 mb-4">
                        <div class="col-6">
                            <button type="button" class="btn btn-light border w-100 p-3 rounded-3 d-flex flex-column align-items-center gap-1" onclick="notifyActuator('Iluminación de Galpón', 'Nivel fotoperiodo fijado en 85%. Protocolo activo.')">
                                <span class="material-symbols-outlined text-warning fs-3">lightbulb</span>
                                <span class="font-label-caps mb-0 text-dark">Iluminación</span>
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn btn-light border w-100 p-3 rounded-3 d-flex flex-column align-items-center gap-1" onclick="notifyActuator('Ventilación Túnel', 'Extractores en velocidad variable óptima. Caudal regulado.')">
                                <span class="material-symbols-outlined text-primary fs-3">air</span>
                                <span class="font-label-caps mb-0 text-dark">Ventilación</span>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary-gradient w-100 py-3 rounded-3 font-label-caps text-white fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2">
                        <span class="material-symbols-outlined fs-5">tune</span>
                        <span>Aplicar Cambios a Actuadores</span>
                    </button>
                </form>
            </div>

            <div class="mt-4 pt-3 border-top d-flex align-items-center justify-content-between text-muted small">
                <span>Estado Servomotor:</span>
                <span class="text-success fw-bold d-flex align-items-center gap-1">
                    <span class="material-symbols-outlined fs-6">check_circle</span> Calibrado
                </span>
            </div>
        </div>
    </div>
</div>

<!-- 3. Alertas Recientes & Resumen Operativo -->
<div class="row g-4 mb-4">
    <!-- Alertas Recientes (Fiel a Stitch) -->
    <div class="col-12 col-xl-6">
        <div class="stitch-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark mb-0">Alertas Recientes del Sistema</h5>
                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1 rounded-pill small font-label-caps">
                    2 ACTIVAS
                </span>
            </div>

            <div class="d-flex flex-column gap-3">
                @foreach ($alerts as $alert)
                    <div class="p-3 rounded-3 border-start alert-feed-item {{ $alert['type'] == 'warning' ? 'bg-warning bg-opacity-10 border-warning' : 'bg-light border-secondary' }}">
                        <div class="d-flex align-items-start gap-3">
                            <span class="material-symbols-outlined {{ $alert['type'] == 'warning' ? 'text-warning' : 'text-secondary' }} fs-4 mt-1">
                                {{ $alert['icon'] }}
                            </span>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="fw-bold text-dark mb-0 small">{{ $alert['title'] }}</h6>
                                    <span class="text-muted" style="font-size: 11px;">{{ $alert['time_ago'] }}</span>
                                </div>
                                <p class="text-muted small mb-0 mt-1">{{ $alert['location'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" class="btn btn-link text-decoration-none w-100 mt-3 p-0 small fw-bold" style="color: var(--stitch-primary);" onclick="notifyActuator('Registro de Eventos', 'Descargando log completo de telemetría y diagnósticos de sensores.')">
                Ver Todos los Registros de Notificación &rarr;
            </button>
        </div>
    </div>

    <!-- Quick User Creation Module Callout (Módulo Requerido 2) -->
    <div class="col-12 col-xl-6">
        <div class="stitch-card p-4 h-100 d-flex flex-column justify-content-between" style="background: linear-gradient(135deg, #ffffff 0%, #f8fbf8 100%);">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0">Gestión de Personal & Roles</h5>
                    <span class="badge badge-stitch-admin">Acceso Administrativo</span>
                </div>
                <p class="text-muted small mb-3">
                    Como usuario autenticado, puedes dar de alta a nuevos operadores y técnicos directamente desde el panel interno, garantizando la trazabilidad de accesos.
                </p>

                <div class="row g-2 mb-3">
                    <div class="col-sm-6">
                        <div class="p-3 bg-white rounded-3 border shadow-sm">
                            <div class="font-label-caps text-muted">Sesión Actual</div>
                            <div class="fw-bold text-dark text-truncate">{{ Auth::user()->name }} {{ Auth::user()->last_name }}</div>
                            <span class="badge badge-stitch-{{ Auth::user()->role }} mt-1">{{ strtoupper(Auth::user()->role) }}</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-white rounded-3 border shadow-sm">
                            <div class="font-label-caps text-muted">Usuarios Registrados</div>
                            <div class="fw-bold text-dark">{{ \App\Models\User::count() }} Cuentas en Sistema</div>
                            <span class="badge bg-success bg-opacity-10 text-success mt-1">Activas</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 pt-2">
                <a href="{{ route('users.create') }}" class="btn btn-green-gradient flex-grow-1 py-2 text-center text-decoration-none">
                    <span class="material-symbols-outlined fs-5 align-middle me-1">person_add</span> Dar de Alta Nuevo Usuario
                </a>
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary py-2 text-decoration-none">
                    Ver Directorio
                </a>
            </div>
        </div>
    </div>
</div>

<!-- 4. Footer Stats SCADA Grid (Fiel a Stitch) -->
<div class="row g-3">
    <!-- Health Index Card -->
    <div class="col-12 col-lg-4">
        <div class="stitch-card p-4 h-100 d-flex flex-column justify-content-between" style="background-color: var(--stitch-surface-container);">
            <div>
                <h5 class="fw-bold text-dark mb-1">Índice de Salud del Galpón</h5>
                <p class="text-muted small mb-0">Puntuación ambiental agregada basada en sensores de gases, temperatura, humedad y presión.</p>
            </div>
            <div class="mt-4 d-flex align-items-baseline gap-2">
                <span class="display-4 fw-bold" style="color: var(--stitch-secondary);">{{ round($latest->health_index ?? 94, 0) }}</span>
                <span class="h5 fw-bold text-muted mb-0">/ 100</span>
                <span class="badge bg-success bg-opacity-25 text-success ms-2 px-2 py-1 rounded-pill small">Condición Ideal</span>
            </div>
        </div>
    </div>

    <!-- 4 Operational SCADA Tiles -->
    <div class="col-12 col-lg-8">
        <div class="row g-3 h-100">
            <div class="col-6 col-sm-3">
                <div class="stitch-card operational-tile p-3 h-100 text-center d-flex flex-column justify-content-center">
                    <span class="font-label-caps">Sensores</span>
                    <h3 class="fw-bold text-dark mb-0 my-1">{{ $systemStatus['sensors_count'] }}</h3>
                    <span class="badge bg-success bg-opacity-10 text-success small mx-auto">{{ $systemStatus['sensors_status'] }}</span>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="stitch-card operational-tile p-3 h-100 text-center d-flex flex-column justify-content-center">
                    <span class="font-label-caps">Tiempo de Actividad</span>
                    <h3 class="fw-bold text-dark mb-0 my-1">{{ $systemStatus['uptime'] }}</h3>
                    <span class="badge bg-success bg-opacity-10 text-success small mx-auto">{{ $systemStatus['uptime_status'] }}</span>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="stitch-card operational-tile p-3 h-100 text-center d-flex flex-column justify-content-center">
                    <span class="font-label-caps">Alimentación</span>
                    <h3 class="fw-bold text-dark mb-0 my-1">{{ $systemStatus['power'] }}</h3>
                    <span class="badge bg-primary bg-opacity-10 text-primary small mx-auto">{{ $systemStatus['power_status'] }}</span>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="stitch-card operational-tile p-3 h-100 text-center d-flex flex-column justify-content-center">
                    <span class="font-label-caps">Eficiencia</span>
                    <h3 class="fw-bold text-dark mb-0 my-1">{{ $systemStatus['efficiency'] }}</h3>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary small mx-auto">{{ $systemStatus['efficiency_unit'] }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Configuración de telemetría inyectada para dashboard.js
    window.SCADA_TELEMETRY_DATA = {
        labels: {!! json_encode($chartLabels) !!},
        temp: {!! json_encode($chartTemp) !!},
        humidity: {!! json_encode($chartHumidity) !!},
        pressure: {!! json_encode($chartPressure) !!}
    };
</script>
<script src="{{ asset('js/dashboard.js') }}"></script>
@endsection
