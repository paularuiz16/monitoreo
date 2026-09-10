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
        // 1. Crear Usuario Administrador Principal
        $admin = User::firstOrCreate(
            ['email' => 'admin@poultrysense.io'],
            [
                'name' => 'Carlos',
                'last_name' => 'Mendoza',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'created_by' => null,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // 2. Crear Técnico de Operaciones (creado internamente por el Admin)
        User::firstOrCreate(
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

        // 3. Crear Operador de Galpón (creado internamente por el Admin)
        User::firstOrCreate(
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

        // 4. Crear un usuario autoregistrado (created_by = null) para contrastar
        User::firstOrCreate(
            ['email' => 'juan.perez@empresa.com'],
            [
                'name' => 'Juan',
                'last_name' => 'Pérez',
                'password' => Hash::make('password123'),
                'role' => 'operador',
                'created_by' => null,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // 5. Sembrar telemetría ambiental (temperatura, humedad, presión atmosférica, etc.)
        $this->call(TelemetryDataSeeder::class);
    }
}
