<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('telemetry_data', function (Blueprint $table) {
            $table->id();
            $table->decimal('temperature', 5, 2); // Temperatura Interior (°C)
            $table->decimal('external_temperature', 5, 2)->nullable(); // Temperatura Exterior (°C)
            $table->decimal('humidity', 5, 2); // Humedad relativa (%)
            $table->decimal('atmospheric_pressure', 6, 2); // Presión atmosférica (hPa)
            $table->decimal('curtain_position', 5, 2)->default(0); // Posición cortina (0 - 100%)
            $table->string('curtain_mode', 20)->default('AUTO'); // AUTO / MANUAL
            $table->string('ventilation_status', 50)->default('OPTIMO'); // OPTIMO, MODERADO, ALTO, APAGADO
            $table->decimal('health_index', 5, 2)->default(95.0); // 0 - 100
            $table->string('device_id', 50)->default('ESP32-SGalpon-01');
            $table->timestamp('recorded_at')->useCurrent();
            $table->timestamps();

            // Índices para optimizar consultas de telemetría y gráficos temporales
            $table->index('recorded_at');
            $table->index('device_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telemetry_data');
    }
};
