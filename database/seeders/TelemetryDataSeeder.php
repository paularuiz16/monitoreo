<?php

namespace Database\Seeders;

use App\Models\TelemetryData;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TelemetryDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TelemetryData::truncate();

        $now = Carbon::now();

        // Generar 48 horas de telemetría continua para visualización de tendencias
        for ($i = 48; $i >= 0; $i--) {
            $recordTime = (clone $now)->subHours($i);
            $hour = (int) $recordTime->format('H');

            // Simulación de curva térmica diurna natural
            // Máximas hacia las 14:00 - 16:00, mínimas hacia las 04:00 - 06:00
            $tempOffset = sin(deg2rad(($hour - 8) * 15)); // oscilación de -1 a 1
            $interiorTemp = round(23.5 + ($tempOffset * 2.0) + (mt_rand(-5, 5) / 10), 1);
            $exteriorTemp = round(25.0 + ($tempOffset * 4.5) + (mt_rand(-8, 8) / 10), 1);
            
            // Humedad inversamente proporcional a la temperatura
            $humidity = round(64.0 - ($tempOffset * 7.0) + (mt_rand(-10, 10) / 10), 1);
            
            // Presión atmosférica alrededor de 1013 hPa
            $pressure = round(1013.2 + (sin(deg2rad($hour * 15)) * 1.8) + (mt_rand(-5, 5) / 10), 1);

            // Cortinas y ventilación ajustadas según calor
            $curtain = ($interiorTemp > 24.5) ? 65.0 : (($interiorTemp < 22.5) ? 25.0 : 45.0);
            $curtain += mt_rand(-3, 3);
            $curtain = max(0, min(100, $curtain));

            $ventilation = ($interiorTemp > 25.0) ? 'ALTO' : (($interiorTemp < 22.0) ? 'MODERADO' : 'OPTIMO');
            $healthIndex = round(94.0 + (mt_rand(-3, 4)), 0);

            TelemetryData::create([
                'temperature' => $interiorTemp,
                'external_temperature' => $exteriorTemp,
                'humidity' => $humidity,
                'atmospheric_pressure' => $pressure,
                'curtain_position' => $curtain,
                'curtain_mode' => 'AUTO',
                'ventilation_status' => $ventilation,
                'health_index' => min(100, max(85, $healthIndex)),
                'device_id' => 'ESP32-SGalpon-01',
                'recorded_at' => $recordTime,
                'created_at' => $recordTime,
                'updated_at' => $recordTime,
            ]);
        }

        // Asegurar que la última lectura coincida con los valores destacados del diseño Stitch
        $latest = TelemetryData::orderBy('recorded_at', 'desc')->first();
        if ($latest) {
            $latest->update([
                'temperature' => 24.0,
                'external_temperature' => 28.0,
                'humidity' => 65.0,
                'atmospheric_pressure' => 1014.2,
                'curtain_position' => 45.0,
                'curtain_mode' => 'AUTO',
                'ventilation_status' => 'OPTIMO',
                'health_index' => 94.0,
                'recorded_at' => Carbon::now(),
            ]);
        }
    }
}
