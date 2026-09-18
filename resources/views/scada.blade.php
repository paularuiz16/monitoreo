@extends('layouts.app')

@section('title', 'Tendencias SCADA & Telemetría Histórica | AvícolaPro Control')

@section('content')
<!-- Page Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1 rounded-pill small d-inline-flex align-items-center gap-1">
                <span class="telemetry-pulse-dot me-1" style="background-color: var(--stitch-blue);"></span>
                Historial SCADA 24 Horas
            </span>
            <span class="text-muted small">&bull; {{ $stats24h['readings_count'] }} muestras registradas</span>
        </div>
        <h3 class="fw-bold text-dark mb-0">Tendencias SCADA & Curvas Ambientales</h3>
    </div>
    
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('scada.index') }}" class="btn btn-outline-secondary btn-sm rounded-3 d-inline-flex align-items-center gap-1 shadow-sm">
            <span class="material-symbols-outlined fs-5">refresh</span>
            <span>Actualizar Curvas</span>
        </a>
    </div>
</div>

<!-- 1. Stats SCADA Grid (Verdes y Azules) -->
<div class="row g-3 mb-4">
    <!-- Temp Promedio -->
    <div class="col-6 col-md-3">
        <div class="stitch-card p-3 h-100">
            <span class="font-label-caps text-success">Temp. Promedio 24h</span>
            <div class="metric-value mt-1 text-success">{{ $stats24h['temp_avg'] }}°C</div>
            <div class="text-muted small mt-1" style="font-size: 11px;">
                Min: {{ $stats24h['temp_min'] }}°C &bull; Max: {{ $stats24h['temp_max'] }}°C
            </div>
        </div>
    </div>

    <!-- Humedad Promedio -->
    <div class="col-6 col-md-3">
        <div class="stitch-card p-3 h-100">
            <span class="font-label-caps text-primary">Humedad Promedio 24h</span>
            <div class="metric-value mt-1 text-primary">{{ $stats24h['hum_avg'] }}%</div>
            <div class="text-muted small mt-1" style="font-size: 11px;">
                Min: {{ $stats24h['hum_min'] }}% &bull; Max: {{ $stats24h['hum_max'] }}%
            </div>
        </div>
    </div>

    <!-- Rango Térmico -->
    <div class="col-6 col-md-3">
        <div class="stitch-card p-3 h-100">
            <span class="font-label-caps">Oscilación Térmica</span>
            <div class="metric-value mt-1 text-dark">{{ round($stats24h['temp_max'] - $stats24h['temp_min'], 1) }}°C</div>
            <div class="text-muted small mt-1" style="font-size: 11px;">
                Estabilidad térmica óptima
            </div>
        </div>
    </div>

    <!-- Estado de Sincronización -->
    <div class="col-6 col-md-3">
        <div class="stitch-card p-3 h-100">
            <span class="font-label-caps">Frecuencia Muestreo</span>
            <div class="metric-value mt-1" style="color: var(--stitch-blue);">60s</div>
            <div class="text-muted small mt-1" style="font-size: 11px;">
                Protocolo IoT MQTT / HTTP
            </div>
        </div>
    </div>
</div>

<!-- 2. SCADA Chart Canvas -->
<div class="stitch-card p-4 mb-4">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-4">
        <div>
            <h5 class="fw-bold text-dark mb-1">Curvas Temporales de Climatización en Galpón</h5>
            <p class="text-muted small mb-0">Comportamiento dinámico de temperatura y humedad en las últimas 24 Horas</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center gap-1">
                <span class="rounded-circle d-inline-block" style="width: 10px; height: 10px; background-color: #059669;"></span>
                <span class="small fw-semibold text-muted">Temp. Interior (°C)</span>
            </div>
            <div class="d-flex align-items-center gap-1">
                <span class="rounded-circle d-inline-block" style="width: 10px; height: 10px; background-color: #0284c7;"></span>
                <span class="small fw-semibold text-muted">Humedad Relativa (%)</span>
            </div>
        </div>
    </div>

    <div class="chart-container-scada" style="height: 340px;">
        <canvas id="scadaTrendsChart"></canvas>
    </div>
</div>

<!-- 3. Telemetry Log Audit Table -->
<div class="stitch-card p-0 overflow-hidden mb-4">
    <div class="p-3 px-4 border-bottom d-flex justify-content-between align-items-center bg-light">
        <h6 class="fw-bold mb-0 text-dark">Registro Cronológico de Muestras de Telemetría</h6>
        <span class="badge bg-white text-dark border px-2 py-1">Últimos registros</span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr class="font-label-caps">
                    <th class="ps-4">Hora de Captura</th>
                    <th>Temp. Interior</th>
                    <th>Temp. Exterior</th>
                    <th>Humedad</th>
                    <th class="text-end pe-4">Salud Galpón</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($historyTable as $row)
                    <tr>
                        <td class="ps-4 fw-semibold text-dark">
                            {{ Carbon\Carbon::parse($row->recorded_at)->format('d/m/Y H:i:s') }}
                        </td>
                        <td>
                            <span class="badge bg-success bg-opacity-10 text-success fw-bold">
                                {{ round($row->temperature, 1) }}°C
                            </span>
                        </td>
                        <td class="text-muted">
                            {{ round($row->external_temperature ?? 28.0, 1) }}°C
                        </td>
                        <td>
                            <span class="badge bg-info bg-opacity-10 text-info fw-bold">
                                {{ round($row->humidity, 1) }}%
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <span class="fw-bold text-success">{{ round($row->health_index, 0) }}/100</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            No hay registros de telemetría disponibles.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($historyTable->hasPages())
        <div class="p-3 border-top bg-light d-flex justify-content-center">
            {{ $historyTable->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const chartCanvas = document.getElementById('scadaTrendsChart');
        if (chartCanvas) {
            const labels = {!! json_encode($chartLabels) !!};
            const tempData = {!! json_encode($chartTemp) !!};
            const humData = {!! json_encode($chartHumidity) !!};

            new Chart(chartCanvas, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Temp. Interior (°C)',
                            data: tempData,
                            borderColor: '#059669',
                            backgroundColor: 'rgba(5, 150, 105, 0.08)',
                            borderWidth: 2.5,
                            fill: true,
                            tension: 0.35,
                            pointRadius: 2,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#059669',
                            yAxisID: 'yTemp'
                        },
                        {
                            label: 'Humedad (%)',
                            data: humData,
                            borderColor: '#0284c7',
                            backgroundColor: 'rgba(2, 132, 199, 0.05)',
                            borderWidth: 2,
                            borderDash: [4, 4],
                            fill: false,
                            tension: 0.35,
                            pointRadius: 2,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#0284c7',
                            yAxisID: 'yHum'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 1000,
                        easing: 'easeOutQuart'
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f2744',
                            titleFont: { family: 'Inter', size: 12, weight: 'bold' },
                            bodyFont: { family: 'Inter', size: 12 },
                            padding: 12,
                            cornerRadius: 10,
                            displayColors: true
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: 'rgba(226, 232, 240, 0.6)', drawBorder: false },
                            ticks: { font: { family: 'Inter', size: 11 }, color: '#64748b', maxTicksLimit: 10 }
                        },
                        yTemp: {
                            type: 'linear',
                            position: 'left',
                            min: 15,
                            max: 35,
                            grid: { color: 'rgba(226, 232, 240, 0.6)', drawBorder: false },
                            ticks: { font: { family: 'Inter', size: 11 }, color: '#059669', callback: (val) => val + '°C' }
                        },
                        yHum: {
                            type: 'linear',
                            position: 'right',
                            min: 35,
                            max: 95,
                            grid: { drawOnChartArea: false },
                            ticks: { font: { family: 'Inter', size: 11 }, color: '#0284c7', callback: (val) => val + '%' }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
