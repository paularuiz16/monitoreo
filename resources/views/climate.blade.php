@extends('layouts.app')

@section('title', 'Control Climático Especializado | AvícolaPro Control')

@section('content')
<!-- Page Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 rounded-pill small d-inline-flex align-items-center gap-1">
                <span class="telemetry-pulse-dot me-1"></span>
                Sensores Climáticos en Vivo
            </span>
            <span class="text-muted small">&bull; Subestación {{ $latest->device_id ?? 'ESP32-SGalpon-01' }}</span>
        </div>
        <h3 class="fw-bold text-dark mb-0">Control Climático y Automatización Térmica</h3>
    </div>
    
    <div class="d-flex align-items-center gap-2">
        <span class="text-muted small d-none d-sm-inline">Sincronizado: <strong>{{ $latest->recorded_at ? $latest->recorded_at->format('H:i:s d/m/Y') : now()->format('H:i:s') }}</strong></span>
        <a href="{{ route('climate.index') }}" class="btn btn-outline-secondary btn-sm rounded-3 d-inline-flex align-items-center gap-1 shadow-sm" title="Recargar lecturas">
            <span class="material-symbols-outlined fs-5">refresh</span>
            <span class="d-none d-md-inline">Actualizar</span>
        </a>
    </div>
</div>

<!-- 1. Climate Metric Cards (Verde y Azul Tecnológico) -->
<div class="row g-3 mb-4">
    <!-- Temp. Interior -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stitch-card telemetry-summary-card p-3 h-100 d-flex align-items-center gap-3">
            <div class="telemetry-icon-box shadow-sm" style="background-color: var(--stitch-secondary-container); color: var(--stitch-secondary);">
                <span class="material-symbols-outlined fs-2">thermostat</span>
            </div>
            <div class="flex-grow-1">
                <span class="font-label-caps">TEMP. INTERIOR</span>
                <div class="d-flex align-items-baseline gap-2">
                    <h2 class="metric-value mb-0">{{ $climateStatus['temp_indoor'] }}°C</h2>
                    <span class="badge bg-success bg-opacity-10 text-success small">Galpón</span>
                </div>
                <div class="text-muted small mt-1" style="font-size: 11px;">
                    Objetivo térmico: <strong>{{ $climateStatus['target_temp'] }}°C</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Temp. Exterior -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stitch-card telemetry-summary-card p-3 h-100 d-flex align-items-center gap-3">
            <div class="telemetry-icon-box shadow-sm" style="background-color: var(--stitch-surface-container); color: var(--stitch-primary);">
                <span class="material-symbols-outlined fs-2">wb_sunny</span>
            </div>
            <div class="flex-grow-1">
                <span class="font-label-caps">TEMP. EXTERIOR</span>
                <div class="d-flex align-items-baseline gap-2">
                    <h2 class="metric-value mb-0">{{ $climateStatus['temp_outdoor'] }}°C</h2>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary small">Ambiente</span>
                </div>
                <div class="text-muted small mt-1" style="font-size: 11px;">
                    Diferencial térmico: <strong>+{{ $climateStatus['temp_diff'] }}°C</strong>
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
                    <h2 class="metric-value mb-0">{{ $climateStatus['humidity'] }}%</h2>
                    <span class="badge bg-info bg-opacity-10 text-info small">Humedad</span>
                </div>
                <div class="text-muted small mt-1" style="font-size: 11px;">
                    Rango óptimo: <strong>50% - 70%</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Condición de Confort Avícola -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stitch-card telemetry-summary-card p-3 h-100 d-flex align-items-center gap-3" style="background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%);">
            <div class="telemetry-icon-box shadow-sm" style="background-color: #d1fae5; color: #059669;">
                <span class="material-symbols-outlined fs-2">eco</span>
            </div>
            <div class="flex-grow-1">
                <span class="font-label-caps">ESTADO DE BIENESTAR</span>
                <div class="d-flex align-items-center gap-2 mt-1">
                    <span class="badge bg-{{ $climateStatus['comfort_type'] }} bg-opacity-15 text-{{ $climateStatus['comfort_type'] }} fw-bold">
                        {{ $climateStatus['comfort_label'] }}
                    </span>
                </div>
                <div class="text-muted small mt-1" style="font-size: 11px;">
                    Índice de Salud: <strong>{{ round($latest->health_index, 0) }}/100</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 2. Panel de Control de Climatización & Actuadores -->
<div class="row g-4 mb-4">
    <!-- Ajuste de Cortinas y Ventilación Dinámica -->
    <div class="col-12 col-lg-7">
        <div class="stitch-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined fs-4 text-success">air</span>
                    <h5 class="fw-bold text-dark mb-0">Control de Ventilación & Cortinas Automáticas</h5>
                </div>
                <span class="badge badge-stitch-secondary">Modo {{ $latest->curtain_mode }}</span>
            </div>
            <p class="text-muted small mb-4">
                Comando digital de las compuertas de ventilación del galpón. El sistema modula la entrada de aire fresco según la temperatura interna y humedad del lote.
            </p>

            <form method="POST" action="{{ route('dashboard.curtain') }}" id="climateCurtainForm">
                @csrf
                <div class="p-3 bg-light rounded-3 border mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label for="climateCurtainRange" class="form-label font-label-caps mb-0">Apertura de Compuertas de Cortina</label>
                        <span class="badge bg-success text-white fw-bold px-3 py-1 fs-6" id="climateCurtainBadge">
                            {{ round($latest->curtain_position, 0) }}%
                        </span>
                    </div>
                    <input type="range" 
                           name="curtain_position" 
                           class="form-range form-range-curtain" 
                           id="climateCurtainRange" 
                           min="0" 
                           max="100" 
                           step="5" 
                           value="{{ round($latest->curtain_position, 0) }}">
                    <div class="d-flex justify-content-between text-muted" style="font-size: 11px; font-weight: 700;">
                        <span>0% (CERRADAS)</span>
                        <span>50% (MODERADA)</span>
                        <span>100% (MÁXIMA VENTILACIÓN)</span>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <label class="form-label font-label-caps mb-1">Modo de Operación</label>
                        <select name="curtain_mode" class="form-select form-control-stitch">
                            <option value="AUTO" {{ $latest->curtain_mode == 'AUTO' ? 'selected' : '' }}>Automático (Basado en sensores)</option>
                            <option value="MANUAL" {{ $latest->curtain_mode == 'MANUAL' ? 'selected' : '' }}>Manual (Control directo del operador)</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label font-label-caps mb-1">Nivel de Ventilación</label>
                        <div class="p-2 bg-white rounded-3 border d-flex align-items-center justify-content-between">
                            <span class="fw-bold text-dark small">{{ $climateStatus['ventilation_level'] }}</span>
                            <span class="badge bg-success bg-opacity-10 text-success small">Operativo</span>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary-gradient w-100 py-3 rounded-3 font-label-caps text-white fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2">
                    <span class="material-symbols-outlined fs-5">tune</span>
                    <span>Actualizar Parámetros Climáticos</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Estado de Infraestructura de Climatización -->
    <div class="col-12 col-lg-5">
        <div class="stitch-card p-4 h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined fs-4 text-primary">hvac</span>
                        <h5 class="fw-bold text-dark mb-0">Actuadores Térmicos</h5>
                    </div>
                    <span class="badge bg-primary bg-opacity-10 text-primary font-label-caps">SCADA NODO 01</span>
                </div>
                <p class="text-muted small mb-3">Estado en tiempo real de los equipos electromecánicos de acondicionamiento de aire.</p>

                <div class="d-flex flex-column gap-3">
                    <!-- Extractores Túnel -->
                    <div class="p-3 bg-light rounded-3 border d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle p-2 bg-white text-primary border shadow-sm">
                                <span class="material-symbols-outlined fs-4">mode_fan</span>
                            </div>
                            <div>
                                <div class="fw-bold text-dark small">Extractores de Túnel</div>
                                <div class="text-muted" style="font-size: 11px;">{{ $climateStatus['exhaust_fans'] }}</div>
                            </div>
                        </div>
                        <span class="badge bg-success bg-opacity-10 text-success small">Velocidad Óptima</span>
                    </div>

                    <!-- Paneles Evaporativos / Nebulización -->
                    <div class="p-3 bg-light rounded-3 border d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle p-2 bg-white text-info border shadow-sm">
                                <span class="material-symbols-outlined fs-4">water_drop</span>
                            </div>
                            <div>
                                <div class="fw-bold text-dark small">Enfriamiento Evaporativo</div>
                                <div class="text-muted" style="font-size: 11px;">{{ $climateStatus['cooling_pads'] }}</div>
                            </div>
                        </div>
                        <span class="badge bg-info bg-opacity-10 text-info small">Calibrado</span>
                    </div>

                    <!-- Calefactores / Criadoras -->
                    <div class="p-3 bg-light rounded-3 border d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle p-2 bg-white text-warning border shadow-sm">
                                <span class="material-symbols-outlined fs-4">local_fire_department</span>
                            </div>
                            <div>
                                <div class="fw-bold text-dark small">Sistema de Calefacción</div>
                                <div class="text-muted" style="font-size: 11px;">Temperatura exterior estable</div>
                            </div>
                        </div>
                        <span class="badge bg-secondary bg-opacity-10 text-secondary small">En Reposo</span>
                    </div>
                </div>
            </div>

            <div class="pt-3 border-top mt-4 d-flex align-items-center justify-content-between text-muted small">
                <span>Protocolo de Protección:</span>
                <span class="text-success fw-bold d-flex align-items-center gap-1">
                    <span class="material-symbols-outlined fs-6">security</span> Automático Activo
                </span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const slider = document.getElementById('climateCurtainRange');
        const badge = document.getElementById('climateCurtainBadge');
        if (slider && badge) {
            slider.addEventListener('input', function () {
                badge.textContent = this.value + '%';
            });
        }
    });
</script>
@endsection
