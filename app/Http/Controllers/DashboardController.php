<?php

namespace App\Http\Controllers;

use App\Models\TelemetryData;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Muestra el panel principal de telemetría y control ambiental (Stitch UI).
     */
    public function index(): View
    {
        // 1. Obtener la última lectura de telemetría disponible o crear una predeterminada
        $latest = TelemetryData::orderBy('recorded_at', 'desc')->first();

        if (!$latest) {
            $latest = TelemetryData::create([
                'temperature' => 24.0,
                'external_temperature' => 28.0,
                'humidity' => 65.0,
                'atmospheric_pressure' => 1014.2,
                'curtain_position' => 45.0,
                'curtain_mode' => 'AUTO',
                'ventilation_status' => 'OPTIMO',
                'health_index' => 94.0,
                'device_id' => 'ESP32-SGalpon-01',
                'recorded_at' => now(),
            ]);
        }

        // 2. Obtener lecturas de las últimas 24 horas para tendencias climáticas
        $recentReadings = TelemetryData::where('recorded_at', '>=', now()->subHours(24))
            ->orderBy('recorded_at', 'asc')
            ->get();

        // Si hay pocas lecturas, tomar las últimas 12 existentes
        if ($recentReadings->count() < 6) {
            $recentReadings = TelemetryData::orderBy('recorded_at', 'desc')
                ->take(12)
                ->get()
                ->reverse()
                ->values();
        }

        // 3. Preparar vectores para el gráfico de tendencias (labels, temp, humedad, presión)
        $chartLabels = [];
        $chartTemp = [];
        $chartHumidity = [];
        $chartPressure = [];

        foreach ($recentReadings as $reading) {
            $chartLabels[] = Carbon::parse($reading->recorded_at)->format('H:i');
            $chartTemp[] = (float) $reading->temperature;
            $chartHumidity[] = (float) $reading->humidity;
            $chartPressure[] = (float) $reading->atmospheric_pressure;
        }

        // 4. Estadísticas agregadas de 24h
        $stats24h = [
            'temp_avg' => round($recentReadings->avg('temperature') ?? 24.0, 1),
            'temp_max' => round($recentReadings->max('temperature') ?? 27.5, 1),
            'temp_min' => round($recentReadings->min('temperature') ?? 21.0, 1),
            'hum_avg' => round($recentReadings->avg('humidity') ?? 65.0, 1),
            'hum_max' => round($recentReadings->max('humidity') ?? 75.0, 1),
            'hum_min' => round($recentReadings->min('humidity') ?? 55.0, 1),
            'press_avg' => round($recentReadings->avg('atmospheric_pressure') ?? 1013.5, 1),
        ];

        // 5. Alertas operativas del sistema (fieles al diseño de Stitch)
        $alerts = [
            [
                'id' => 1,
                'type' => 'warning',
                'title' => 'Baja presión de agua',
                'location' => 'Sector B - Línea de suministro principal',
                'time_ago' => 'Hace 14 min',
                'active' => true,
                'icon' => 'warning',
            ],
            [
                'id' => 2,
                'type' => 'warning',
                'title' => 'Desviación de temp.',
                'location' => 'Unidad 04 - Sensor 07 Alto',
                'time_ago' => 'Hace 28 min',
                'active' => true,
                'icon' => 'device_thermostat',
            ],
            [
                'id' => 3,
                'type' => 'info',
                'title' => 'Ciclo de alimentación completo',
                'location' => 'Todas las unidades reportan niveles estables',
                'time_ago' => 'Hace 2 horas',
                'active' => false,
                'icon' => 'check_circle',
            ],
        ];

        // 6. Resumen de sensores e infraestructura
        $systemStatus = [
            'sensors_count' => 128,
            'sensors_status' => 'En Línea',
            'uptime' => '99.9%',
            'uptime_status' => 'Óptimo',
            'power' => 'Main',
            'power_status' => 'Conectado a la Red',
            'efficiency' => '0.82',
            'efficiency_unit' => 'kW/Bird',
        ];

        return view('dashboard', compact(
            'latest',
            'stats24h',
            'alerts',
            'systemStatus'
        ));
    }

    /**
     * Módulo dedicado e interactivo: Panel de Control Climático.
     */
    public function climateControl(): View
    {
        $latest = TelemetryData::orderBy('recorded_at', 'desc')->first();

        if (!$latest) {
            $latest = TelemetryData::create([
                'temperature' => 24.0,
                'external_temperature' => 28.0,
                'humidity' => 65.0,
                'atmospheric_pressure' => 1014.2,
                'curtain_position' => 45.0,
                'curtain_mode' => 'AUTO',
                'ventilation_status' => 'OPTIMO',
                'health_index' => 94.0,
                'device_id' => 'ESP32-SGalpon-01',
                'recorded_at' => now(),
            ]);
        }

        $tempDiff = round(($latest->external_temperature ?? 28.0) - $latest->temperature, 1);
        $isComfort = ($latest->temperature >= 21.0 && $latest->temperature <= 26.5 && $latest->humidity >= 50.0 && $latest->humidity <= 75.0);

        $climateStatus = [
            'temp_indoor' => round($latest->temperature, 1),
            'temp_outdoor' => round($latest->external_temperature ?? 28.0, 1),
            'temp_diff' => $tempDiff,
            'humidity' => round($latest->humidity, 1),
            'comfort_label' => $isComfort ? 'Zona de Confort Ideal' : 'Fuera de Rango Óptimo',
            'comfort_type' => $isComfort ? 'success' : 'warning',
            'target_temp' => 23.5,
            'target_humidity' => 60.0,
            'ventilation_level' => $latest->ventilation_status ?? 'OPTIMO',
            'cooling_pads' => ($latest->temperature > 25.5) ? 'Nebulización Activa' : 'En Espera (Standby)',
            'exhaust_fans' => ($latest->temperature > 24.0) ? '3 de 4 en Operación' : '2 de 4 en Operación',
        ];

        return view('climate', compact('latest', 'climateStatus'));
    }

    /**
     * Módulo dedicado: Tendencias SCADA y Telemetría Histórica.
     */
    public function scadaTrends(): View
    {
        $latest = TelemetryData::orderBy('recorded_at', 'desc')->first();

        $recentReadings = TelemetryData::where('recorded_at', '>=', now()->subHours(24))
            ->orderBy('recorded_at', 'asc')
            ->get();

        if ($recentReadings->count() < 6) {
            $recentReadings = TelemetryData::orderBy('recorded_at', 'desc')
                ->take(18)
                ->get()
                ->reverse()
                ->values();
        }

        $chartLabels = [];
        $chartTemp = [];
        $chartHumidity = [];

        foreach ($recentReadings as $reading) {
            $chartLabels[] = Carbon::parse($reading->recorded_at)->format('H:i');
            $chartTemp[] = (float) $reading->temperature;
            $chartHumidity[] = (float) $reading->humidity;
        }

        $stats24h = [
            'temp_avg' => round($recentReadings->avg('temperature') ?? 24.0, 1),
            'temp_max' => round($recentReadings->max('temperature') ?? 27.5, 1),
            'temp_min' => round($recentReadings->min('temperature') ?? 21.0, 1),
            'hum_avg' => round($recentReadings->avg('humidity') ?? 65.0, 1),
            'hum_max' => round($recentReadings->max('humidity') ?? 75.0, 1),
            'hum_min' => round($recentReadings->min('humidity') ?? 55.0, 1),
            'readings_count' => $recentReadings->count(),
        ];

        $historyTable = TelemetryData::orderBy('recorded_at', 'desc')->paginate(12);

        return view('scada', compact(
            'latest',
            'stats24h',
            'chartLabels',
            'chartTemp',
            'chartHumidity',
            'historyTable'
        ));
    }

    /**
     * Actualiza la posición de las cortinas o modo de operación.
     */
    public function updateCurtain(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'curtain_position' => ['required', 'numeric', 'min:0', 'max:100'],
            'curtain_mode' => ['nullable', 'string', 'in:AUTO,MANUAL'],
        ]);

        $latest = TelemetryData::orderBy('recorded_at', 'desc')->first();

        TelemetryData::create([
            'temperature' => $latest ? $latest->temperature : 24.0,
            'external_temperature' => $latest ? $latest->external_temperature : 28.0,
            'humidity' => $latest ? $latest->humidity : 65.0,
            'atmospheric_pressure' => $latest ? $latest->atmospheric_pressure : 1014.2,
            'curtain_position' => (float) $validated['curtain_position'],
            'curtain_mode' => $validated['curtain_mode'] ?? 'MANUAL',
            'ventilation_status' => ($validated['curtain_position'] > 50) ? 'OPTIMO' : 'MODERADO',
            'health_index' => $latest ? $latest->health_index : 94.0,
            'device_id' => 'ESP32-SGalpon-01',
            'recorded_at' => now(),
        ]);

        return back()->with('status', 'Ajuste ambiental aplicado correctamente.');
    }

    /**
     * API JSON para refresco en tiempo real de métricas.
     */
    public function apiLatest(): JsonResponse
    {
        $latest = TelemetryData::orderBy('recorded_at', 'desc')->first();

        return response()->json([
            'success' => true,
            'data' => $latest,
        ]);
    }
}
