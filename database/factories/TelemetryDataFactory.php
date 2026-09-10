<?php

namespace Database\Factories;

use App\Models\TelemetryData;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TelemetryData>
 */
class TelemetryDataFactory extends Factory
{
    protected $model = TelemetryData::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'temperature' => fake()->randomFloat(2, 22.0, 27.5),
            'external_temperature' => fake()->randomFloat(2, 24.0, 31.0),
            'humidity' => fake()->randomFloat(2, 58.0, 72.0),
            'atmospheric_pressure' => fake()->randomFloat(2, 1010.0, 1018.0),
            'curtain_position' => fake()->randomFloat(2, 35.0, 65.0),
            'curtain_mode' => fake()->randomElement(['AUTO', 'AUTO', 'AUTO', 'MANUAL']),
            'ventilation_status' => fake()->randomElement(['OPTIMO', 'OPTIMO', 'MODERADO']),
            'health_index' => fake()->randomFloat(2, 92.0, 97.0),
            'device_id' => 'ESP32-SGalpon-01',
            'recorded_at' => now(),
        ];
    }
}
