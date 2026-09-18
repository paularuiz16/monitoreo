@extends('layouts.auth')

{{-- Título de la pestaña: Nombre del sitio web en vez de los números --}}
@section('title', 'AvícolaPro Control')

@section('content')
<div class="container-fluid min-vh-100 auth-fullscreen-container p-0 d-flex flex-column flex-md-row">
    <!-- Left Column: Contexto visual con la imagen de fondo del logotipo e información del sistema -->
    <div class="col-12 col-md-6 col-lg-7 d-none d-md-flex flex-column justify-content-between p-4 p-xl-5 text-white auth-hero-panel">
        <div style="position: relative; z-index: 2; max-width: 520px;">
            <h1 class="h2 fw-bold lh-tight mb-3 text-white">
                Control de Precisión para la <span style="color: var(--stitch-secondary-fixed);">Agricultura Moderna</span>
            </h1>
            
            <p class="text-white-50 small mb-4" style="max-width: 520px; font-size: 0.95rem; line-height: 1.5;">
                Monitoree las constantes ambientales en tiempo real, automatice los ciclos de ventilación y cortinas, y maximice el rendimiento avícola con AvícolaPro Control.
            </p>

            <div class="row g-3" style="max-width: 480px;">
                <div class="col-6">
                    <div class="auth-metric-pill py-2 px-3">
                        <span class="material-symbols-outlined mb-1 fs-5" style="color: var(--stitch-secondary-fixed);">sensors</span>
                        <div class="h4 fw-bold mb-0 text-white">24/7</div>
                        <div class="text-white-50 small" style="font-size: 11px;">Monitoreo Continuo</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="auth-metric-pill py-2 px-3">
                        <span class="material-symbols-outlined mb-1 fs-5" style="color: #38bdf8;">bolt</span>
                        <div class="h4 fw-bold mb-0 text-white">&lt; 50ms</div>
                        <div class="text-white-50 small" style="font-size: 11px;">Latencia de Red</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-white-50 small" style="font-size: 11px; position: relative; z-index: 2;">
            &copy; {{ date('Y') }} AvícolaPro Control &bull; Monitoreo SCADA
        </div>
    </div>

    <!-- Right Column: Login Form -->
    <div class="col-12 col-md-6 col-lg-5 d-flex align-items-center justify-content-center p-3 p-xl-4 bg-white auth-form-scrollable">
        <div class="w-100" style="max-width: 390px;">
            <!-- Parte de arriba: Solo el logo sin nada de letras -->
            <div class="text-center mb-3">
                <img src="{{ asset('images/logo.png') }}" alt="AvícolaPro Control" class="rounded-3 shadow-sm" style="max-height: 70px; width: auto; max-width: 100%; object-fit: contain;" />
            </div>

            <!-- Flash alerts -->
            @if (session('status'))
                <div class="alert alert-success d-flex align-items-center gap-2 py-1.5 px-3 rounded-3 small mb-2 border-0">
                    <span class="material-symbols-outlined fs-5 text-success">check_circle</span>
                    <div class="small">{{ session('status') }}</div>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-warning d-flex align-items-center gap-2 py-1.5 px-3 rounded-3 small mb-2 border-0">
                    <span class="material-symbols-outlined fs-5 text-warning">warning</span>
                    <div class="small">{{ session('error') }}</div>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger py-1.5 px-3 rounded-3 small mb-2 border-0">
                    @foreach ($errors->all() as $error)
                        <div class="d-flex align-items-center gap-1 small">
                            <span class="material-symbols-outlined fs-6 text-danger">error</span>
                            <span>{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf

                <!-- Email -->
                <div class="mb-2">
                    <label for="email" class="form-label font-label-caps mb-1" style="font-size: 11px;">Correo del Operador</label>
                    <div class="input-group input-group-stitch">
                        <span class="input-group-text py-1 px-2">
                            <span class="material-symbols-outlined fs-5">mail</span>
                        </span>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               class="form-control form-control-stitch py-1.5 @error('email') is-invalid @enderror" 
                               placeholder="operator@poultrysense.io" 
                               value="{{ old('email', 'admin@poultrysense.io') }}" 
                               required 
                               autofocus>
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-2">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label for="password" class="form-label font-label-caps mb-0" style="font-size: 11px;">Credenciales de Seguridad</label>
                        <a href="#" class="font-label-caps text-decoration-none" style="color: var(--stitch-secondary); font-size: 11px;" onclick="alert('Contacte con el administrador para restablecer sus credenciales.'); return false;">¿Olvidó contraseña?</a>
                    </div>
                    <div class="input-group input-group-stitch">
                        <span class="input-group-text py-1 px-2">
                            <span class="material-symbols-outlined fs-5">lock</span>
                        </span>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               class="form-control form-control-stitch py-1.5 @error('password') is-invalid @enderror" 
                               placeholder="••••••••••••" 
                               value="password123" 
                               required>
                        <button class="btn btn-outline-secondary border py-1 px-2" type="button" id="togglePasswordBtn">
                            <span class="material-symbols-outlined fs-5 text-muted" id="togglePasswordIcon">visibility</span>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" checked>
                    <label class="form-check-label text-muted small" for="remember" style="font-size: 12px;">
                        Mantener sesión activa en este terminal
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary-gradient w-100 py-2 rounded-3 d-flex align-items-center justify-content-center gap-2 fw-semibold mb-2">
                    <span>Iniciar Sesión en el Panel</span>
                    <span class="material-symbols-outlined fs-5">arrow_forward</span>
                </button>

                <!-- Quick Seed Account Selectors for Testing (Admin + Técnico + 2 Operadores) -->
                <div class="p-2 bg-light rounded-3 mb-2 border">
                    <div class="font-label-caps mb-1 text-muted" style="font-size: 10px;">Cuentas precargadas de prueba:</div>
                    <div class="d-flex flex-wrap gap-1">
                        <button type="button" class="btn btn-outline-dark btn-credential-pill" onclick="fillCredentials('admin@poultrysense.io', 'password123')">
                            <strong>Admin</strong>
                        </button>
                        <button type="button" class="btn btn-outline-info btn-credential-pill" onclick="fillCredentials('tecnico@poultrysense.io', 'password123')">
                            <strong>Técnico</strong>
                        </button>
                        <button type="button" class="btn btn-outline-success btn-credential-pill" onclick="fillCredentials('operator@poultrysense.io', 'password123')">
                            <strong>Operador 1</strong>
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-credential-pill" onclick="fillCredentials('operator2@poultrysense.io', 'password123')">
                            <strong>Operador 2</strong>
                        </button>
                    </div>
                </div>

                <div class="text-center pt-2 border-top">
                    <p class="text-muted small mb-0" style="font-size: 12px;">
                        ¿No tiene acceso aún?
                        <a href="{{ route('register') }}" class="fw-bold text-decoration-none ms-1" style="color: var(--stitch-blue);">
                            Crear cuenta nueva
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
