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

    public function test_login_screen_renders_successfully_with_logo_and_without_iot_badge(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('AvícolaPro Control');
        $response->assertSee('logo.png');
        $response->assertDontSee('Plataforma IoT Empresarial');
        // Verifica presencia de las cuentas precargadas (operadores y técnico)
        $response->assertSee('tecnico@poultrysense.io');
        $response->assertSee('operator@poultrysense.io');
        $response->assertSee('operator2@poultrysense.io');
    }

    public function test_admin_name_is_maria_paula_ruiz(): void
    {
        $admin = User::where('email', 'admin@poultrysense.io')->first();
        $this->assertNotNull($admin);
        $this->assertEquals('María Paula', $admin->name);
        $this->assertEquals('Ruiz', $admin->last_name);
        $this->assertEquals('María Paula Ruiz', $admin->full_name);
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
        $response->assertSee('logo.png');
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
            'created_by' => null,
        ]);
    }

    public function test_authenticated_user_can_view_dashboard_without_pressure_and_with_panel_links(): void
    {
        $user = User::where('email', 'admin@poultrysense.io')->first();

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('TEMP. INTERIOR');
        $response->assertSee('TEMP. EXTERIOR');
        $response->assertSee('HUMEDAD RELATIVA');
        $response->assertSee('SALUD DEL GALPÓN');
        // Verifica que se removió la presión atmosférica
        $response->assertDontSee('PRESIÓN ATMOSFÉRICA');
        // Verifica enlaces a paneles especializados
        $response->assertSee('Panel de Control Climático');
        $response->assertSee('Tendencias SCADA');
    }

    public function test_authenticated_user_can_access_dedicated_climate_panel(): void
    {
        $user = User::where('email', 'operator@poultrysense.io')->first();

        $response = $this->actingAs($user)->get('/control-climatico');
        $response->assertStatus(200);
        $response->assertSee('Control Climático y Automatización Térmica');
        $response->assertSee('Extractores de Túnel');
        $response->assertSee('TEMP. INTERIOR');
        $response->assertDontSee('PRESIÓN ATMOSFÉRICA');
    }

    public function test_authenticated_user_can_access_dedicated_scada_trends_panel(): void
    {
        $user = User::where('email', 'operator@poultrysense.io')->first();

        $response = $this->actingAs($user)->get('/tendencias-scada');
        $response->assertStatus(200);
        $response->assertSee('Tendencias SCADA');
        $response->assertSee('Curvas Temporales de Climatización');
        $response->assertSee('Registro Cronológico de Muestras de Telemetría');
    }

    public function test_admin_can_access_and_manage_staff(): void
    {
        $admin = User::where('email', 'admin@poultrysense.io')->first();

        $response = $this->actingAs($admin)->get('/users');
        $response->assertStatus(200);
        $response->assertSee('Módulo Administrativo de Usuarios');
        $response->assertSee('Directorio de Usuarios del Sistema');

        // Admin can create staff
        $createResponse = $this->actingAs($admin)->post('/users', [
            'name' => 'Fernando',
            'last_name' => 'Castro',
            'email' => 'fcastro@poultrysense.io',
            'role' => 'tecnico',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'is_active' => '1',
        ]);

        $createResponse->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'fcastro@poultrysense.io',
            'role' => 'tecnico',
            'created_by' => $admin->id,
        ]);
    }

    public function test_operator_is_strictly_forbidden_from_staff_management(): void
    {
        $operator = User::where('email', 'operator@poultrysense.io')->first();

        // 1. Acceso a listado denegado
        $responseIndex = $this->actingAs($operator)->get('/users');
        $responseIndex->assertStatus(403);

        // 2. Acceso a formulario de creación denegado
        $responseCreate = $this->actingAs($operator)->get('/users/create');
        $responseCreate->assertStatus(403);

        // 3. Intento de crear usuario denegado
        $responseStore = $this->actingAs($operator)->post('/users', [
            'name' => 'Intruso',
            'last_name' => 'Prueba',
            'email' => 'intruso@poultrysense.io',
            'role' => 'operador',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $responseStore->assertStatus(403);

        // 4. Intento de alternar estado denegado
        $targetUser = User::where('email', 'operator2@poultrysense.io')->first();
        $responseToggle = $this->actingAs($operator)->patch("/users/{$targetUser->id}/toggle");
        $responseToggle->assertStatus(403);
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

    public function test_token_mismatch_exception_redirects_gracefully_to_login(): void
    {
        $handler = app(\Illuminate\Contracts\Debug\ExceptionHandler::class);
        $request = \Illuminate\Http\Request::create('/login', 'POST');

        // 1. Direct TokenMismatchException
        $exception = new \Illuminate\Session\TokenMismatchException('CSRF token mismatch.');
        $response = $handler->render($request, $exception);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(route('login'), $response->headers->get('Location'));

        // 2. HttpException 419
        $httpException = new \Symfony\Component\HttpKernel\Exception\HttpException(419, 'Page Expired');
        $responseHttp = $handler->render($request, $httpException);
        $this->assertEquals(302, $responseHttp->getStatusCode());
        $this->assertEquals(route('login'), $responseHttp->headers->get('Location'));
    }
}
