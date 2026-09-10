<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelemetryData extends Model
{
    use HasFactory;

    protected $table = 'telemetry_data';

    protected $fillable = [
        'temperature',
        'external_temperature',
        'humidity',
        'atmospheric_pressure',
        'curtain_position',
        'curtain_mode',
        'ventilation_status',
        'health_index',
        'device_id',
        'recorded_at',
    ];

    protected function casts(): array
    {
        return [
            'temperature' => 'float',
            'external_temperature' => 'float',
            'humidity' => 'float',
            'atmospheric_pressure' => 'float',
            'curtain_position' => 'float',
            'health_index' => 'float',
            'recorded_at' => 'datetime',
        ];
    }

    /**
     * Obtener registros de las últimas N horas.
     */
    public function scopeRecent($query, int $hours = 24)
    {
        return $query->where('recorded_at', '>=', now()->subHours($hours))
                     ->orderBy('recorded_at', 'asc');
    }

    /**
     * Obtener la última lectura registrada.
     */
    public function scopeLatestReading($query)
    {
        return $query->orderBy('recorded_at', 'desc')->first();
    }
}
