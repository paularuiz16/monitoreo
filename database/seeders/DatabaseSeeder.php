<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Crear Usuario Administrador Principal (Actualizado)
        $admin = User::updateOrCreate(
            ['email' => 'admin@poultrysense.io'],
            [
                'name' => 'María Paula',
                'last_name' => 'Ruiz',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'created_by' => null,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // 2. Crear Operador 1 (creado internamente por el Admin)
        User::updateOrCreate(
            ['email' => 'operator@poultrysense.io'],
            [
                'name' => 'María',
                'last_name' => 'López',
                'password' => Hash::make('password123'),
                'role' => 'operador',
                'created_by' => $admin->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // 3. Crear Operador 2 (creado internamente por el Admin)
        User::updateOrCreate(
            ['email' => 'operator2@poultrysense.io'],
            [
                'name' => 'Juan',
                'last_name' => 'Pérez',
                'password' => Hash::make('password123'),
                'role' => 'operador',
                'created_by' => $admin->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // 4. Crear Técnico de Operaciones (soporte técnico)
        User::updateOrCreate(
            ['email' => 'tecnico@poultrysense.io'],
            [
                'name' => 'Andrés',
                'last_name' => 'Gómez',
                'password' => Hash::make('password123'),
                'role' => 'tecnico',
                'created_by' => $admin->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // 5. Sembrar telemetría ambiental (temperatura, humedad, presión atmosférica, etc.)
        $this->call(TelemetryDataSeeder::class);
    }
}
