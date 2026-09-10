<?php

namespace Tests\Feature;

use App\Models\TelemetryData;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AvicolaProMonitoringTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/');
        $response->assertRedirect(route('login'));
    }

    public function test_login_screen_renders_successfully(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('AvícolaPro Control');
        $response->assertSee('Bienvenido de nuevo');
        $response->assertSee('Plataforma IoT Empresarial');
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::where('email', 'admin@poultrysense.io')->first();

        $response = $this->post('/login', [
            'email' => 'admin@poultrysense.io',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_public_registration_screen_renders(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Crear cuenta');
        $response->assertSee('Optimización');
    }

    public function test_user_can_register_via_public_form(): void
    {
        $response = $this->post('/register', [
            'name' => 'Mariana',
            'last_name' => 'Suárez',
            'email' => 'mariana.suarez@empresa.com',
            'role' => 'operador',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => 'on',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('users', [
            'email' => 'mariana.suarez@empresa.com',
            'role' => 'operador',
            'created_by' => null, // Confirma autoregistro público
        ]);
    }

    public function test_authenticated_user_can_view_dashboard_with_telemetry(): void
    {
        $user = User::where('email', 'admin@poultrysense.io')->first();

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('TEMP. INTERIOR');
        $response->assertSee('TEMP. EXTERIOR');
        $response->assertSee('HUMEDAD RELATIVA');
        $response->assertSee('PRESIÓN ATMOSFÉRICA');
        $response->assertSee('Tendencias Climáticas del Galpón');
        $response->assertSee('Panel de Control');
    }

    public function test_authenticated_user_can_access_user_management_module(): void
    {
        $admin = User::where('email', 'admin@poultrysense.io')->first();

        $response = $this->actingAs($admin)->get('/users');
        $response->assertStatus(200);
        $response->assertSee('Módulo Administrativo de Usuarios');
        $response->assertSee('Directorio de Usuarios del Sistema');
    }

    public function test_admin_can_create_internal_user_with_assigned_created_by(): void
    {
        $admin = User::where('email', 'admin@poultrysense.io')->first();

        $response = $this->actingAs($admin)->post('/users', [
            'name' => 'Fernando',
            'last_name' => 'Castro',
            'email' => 'fcastro@poultrysense.io',
            'role' => 'tecnico',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'fcastro@poultrysense.io',
            'role' => 'tecnico',
            'created_by' => $admin->id, // Distingue creación interna
        ]);
    }

    public function test_curtain_adjustment_updates_telemetry(): void
    {
        $user = User::where('email', 'admin@poultrysense.io')->first();

        $response = $this->actingAs($user)->post('/dashboard/curtain', [
            'curtain_position' => 75,
            'curtain_mode' => 'MANUAL',
        ]);

        $response->assertRedirect();
        $latest = TelemetryData::orderBy('recorded_at', 'desc')->first();
        $this->assertEquals(75.0, (float) $latest->curtain_position);
        $this->assertEquals('MANUAL', $latest->curtain_mode);
    }
}
