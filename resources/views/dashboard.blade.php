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

<!-- 1. Summary Cards Section (Sin presión atmosférica) -->
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

    <!-- Índice de Salud del Galpón (Reemplaza presión atmosférica) -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stitch-card telemetry-summary-card p-3 h-100 d-flex align-items-center gap-3">
            <div class="telemetry-icon-box shadow-sm" style="background-color: #d1fae5; color: #059669;">
                <span class="material-symbols-outlined fs-2">verified</span>
            </div>
            <div class="flex-grow-1">
                <span class="font-label-caps">SALUD DEL GALPÓN</span>
                <div class="d-flex align-items-baseline gap-2">
                    <h2 class="metric-value mb-0">{{ round($latest->health_index ?? 94, 0) }} <small class="fs-6 text-muted">/100</small></h2>
                    <span class="badge bg-success bg-opacity-10 text-success small">Ideal</span>
                </div>
                <div class="text-muted small mt-1" style="font-size: 11px;">
                    Ventilación: <strong>{{ $latest->ventilation_status ?? 'OPTIMO' }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 2. Acceso Directo a los Paneles Especializados (Control Climático y Tendencias SCADA) -->
<div class="row g-4 mb-4">
    <!-- Panel Especializado: Control Climático -->
    <div class="col-12 col-lg-6">
        <div class="stitch-card p-4 h-100 d-flex flex-column justify-content-between interactive-hover" style="border-left: 5px solid var(--stitch-secondary);">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-3 p-2 text-white shadow-sm" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%);">
                            <span class="material-symbols-outlined fs-4">thermostat</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-0">Panel de Control Climático</h5>
                    </div>
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 rounded-pill small">
                        Especializado
                    </span>
                </div>
                <p class="text-muted small mb-3">
                    Monitoree el diferencial térmico, controle los extractores, verifique el estado del enfriamiento evaporativo y gestione la climatización automatizada del lote.
                </p>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="p-2 bg-light rounded-3 border small">
                            <div class="text-muted" style="font-size: 11px;">Diferencial Térmico</div>
                            <div class="fw-bold text-dark">+{{ round(($latest->external_temperature ?? 28) - $latest->temperature, 1) }}°C</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 bg-light rounded-3 border small">
                            <div class="text-muted" style="font-size: 11px;">Compuertas / Cortinas</div>
                            <div class="fw-bold text-success">{{ round($latest->curtain_position, 0) }}% ({{ $latest->curtain_mode }})</div>
                        </div>
                    </div>
                </div>
            </div>

            <a href="{{ route('climate.index') }}" class="btn btn-green-gradient py-2 d-flex align-items-center justify-content-center gap-2 text-decoration-none shadow-sm">
                <span>Ingresar al Panel de Control Climático</span>
                <span class="material-symbols-outlined fs-5">arrow_forward</span>
            </a>
        </div>
    </div>

    <!-- Panel Especializado: Tendencias SCADA -->
    <div class="col-12 col-lg-6">
        <div class="stitch-card p-4 h-100 d-flex flex-column justify-content-between interactive-hover" style="border-left: 5px solid var(--stitch-blue);">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-3 p-2 text-white shadow-sm" style="background: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%);">
                            <span class="material-symbols-outlined fs-4">leaderboard</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-0">Tendencias SCADA & Curvas</h5>
                    </div>
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1 rounded-pill small">
                        Analítica 24h
                    </span>
                </div>
                <p class="text-muted small mb-3">
                    Analice el historial térmico continuo, gráficos comparativos de temperatura vs. humedad y la bitácora cronológica completa de telemetría IoT.
                </p>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="p-2 bg-light rounded-3 border small">
                            <div class="text-muted" style="font-size: 11px;">Temp. Media 24h</div>
                            <div class="fw-bold text-dark">{{ $stats24h['temp_avg'] }}°C</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 bg-light rounded-3 border small">
                            <div class="text-muted" style="font-size: 11px;">Humedad Media 24h</div>
                            <div class="fw-bold text-primary">{{ $stats24h['hum_avg'] }}%</div>
                        </div>
                    </div>
                </div>
            </div>

            <a href="{{ route('scada.index') }}" class="btn btn-blue-gradient py-2 d-flex align-items-center justify-content-center gap-2 text-decoration-none shadow-sm">
                <span>Ver Curvas y Tendencias SCADA</span>
                <span class="material-symbols-outlined fs-5">arrow_forward</span>
            </a>
        </div>
    </div>
</div>

<!-- 3. Alertas Recientes & Resumen Operativo / Personal (Exclusivo Administrador) -->
<div class="row g-4 mb-4">
    <!-- Alertas Recientes -->
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

            <button type="button" class="btn btn-link text-decoration-none w-100 mt-3 p-0 small fw-bold" style="color: var(--stitch-blue);" onclick="alert('Registro de Eventos:\nDescargando historial completo de notificaciones y diagnósticos.')">
                Ver Todos los Registros de Notificación &rarr;
            </button>
        </div>
    </div>

    <!-- Gestión de Personal (SOLO Administrador) o Tarjeta de Turno para Operadores -->
    <div class="col-12 col-xl-6">
        @if (Auth::user()->isAdmin())
            <div class="stitch-card p-4 h-100 d-flex flex-column justify-content-between" style="background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%);">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0">Gestión de Personal & Roles</h5>
                        <span class="badge badge-stitch-admin">Acceso Exclusivo de Administrador</span>
                    </div>
                    <p class="text-muted small mb-3">
                        Como administrador del sistema, eres el único autorizado para registrar nuevo personal, habilitar o suspender operadores y técnicos de galpón.
                    </p>

                    <div class="row g-2 mb-3">
                        <div class="col-sm-6">
                            <div class="p-3 bg-white rounded-3 border shadow-sm">
                                <div class="font-label-caps text-muted">Sesión Actual</div>
                                <div class="fw-bold text-dark text-truncate">{{ Auth::user()->name }} {{ Auth::user()->last_name }}</div>
                                <span class="badge badge-stitch-admin mt-1">ADMINISTRADOR</span>
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
                        <span class="material-symbols-outlined fs-5 align-middle me-1">person_add</span> Dar de Alta Nuevo Personal
                    </a>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary py-2 text-decoration-none">
                        Directorio
                    </a>
                </div>
            </div>
        @else
            <!-- Vista para Operadores y Técnicos: Estado de Turno Operativo -->
            <div class="stitch-card p-4 h-100 d-flex flex-column justify-content-between" style="background: linear-gradient(135deg, #ffffff 0%, #f0f9ff 100%);">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0">Terminal Operativo de Galpón</h5>
                        <span class="badge badge-stitch-operador">Operador Activo</span>
                    </div>
                    <p class="text-muted small mb-3">
                        Has iniciado sesión con credenciales de operador. Tienes acceso completo al monitoreo en tiempo real y protocolos de seguridad del galpón.
                    </p>

                    <div class="row g-2 mb-3">
                        <div class="col-sm-6">
                            <div class="p-3 bg-white rounded-3 border shadow-sm">
                                <div class="font-label-caps text-muted">Operador en Turno</div>
                                <div class="fw-bold text-dark text-truncate">{{ Auth::user()->name }} {{ Auth::user()->last_name }}</div>
                                <span class="badge bg-success bg-opacity-10 text-success mt-1">En Servicio</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 bg-white rounded-3 border shadow-sm">
                                <div class="font-label-caps text-muted">Control de Personal</div>
                                <div class="small text-muted mt-1">Reservado exclusivamente para la Administración.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-3 bg-white rounded-3 border d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined text-success fs-5">lock</span>
                        <span class="small text-muted">Protocolo de seguridad: Trazabilidad por operador activa.</span>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- 4. Footer Stats SCADA Grid -->
<div class="row g-3">
    <!-- Health Index Card -->
    <div class="col-12 col-lg-4">
        <div class="stitch-card p-4 h-100 d-flex flex-column justify-content-between" style="background-color: var(--stitch-surface-container);">
            <div>
                <h5 class="fw-bold text-dark mb-1">Índice de Salud del Galpón</h5>
                <p class="text-muted small mb-0">Puntuación ambiental calculada a partir de sensores de temperatura, gases y ventilación.</p>
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
                    <span class="font-label-caps">Tiempo Actividad</span>
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
