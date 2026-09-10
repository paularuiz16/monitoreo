@extends('layouts.auth')

@section('title', 'Iniciar Sesión | AvícolaPro Control IoT')

@section('content')
<div class="container-fluid min-vh-100 p-0 d-flex flex-column flex-md-row">
    <!-- Left Column: Industrial SCADA Context (Fiel a Stitch) -->
    <div class="col-12 col-md-6 col-lg-7 d-none d-md-flex flex-column justify-content-between p-4 p-lg-5 text-white auth-hero-panel">
        <div>
            <div class="platform-tag-badge mb-4">
                <span class="material-symbols-outlined fs-6">sensors</span>
                <span class="font-label-caps text-white">Plataforma IoT Empresarial</span>
            </div>
            
            <h1 class="display-4 fw-bold lh-tight mb-4 text-white">
                Control de Precisión para la <span style="color: var(--stitch-secondary-fixed);">Agricultura Moderna</span>
            </h1>
            
            <p class="lead text-white-50" style="max-width: 580px;">
                Monitoree las constantes ambientales en tiempo real, automatice los ciclos de ventilación y cortinas, y maximice el rendimiento avícola con AvícolaPro Control.
            </p>

            <div class="row g-3 mt-4" style="max-width: 540px;">
                <div class="col-6">
                    <div class="auth-metric-pill">
                        <span class="material-symbols-outlined mb-2" style="color: var(--stitch-secondary-fixed);">sensors</span>
                        <div class="h3 fw-bold mb-0 text-white">24/7</div>
                        <div class="text-white-50 small">Monitoreo Continuo</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="auth-metric-pill">
                        <span class="material-symbols-outlined mb-2" style="color: var(--stitch-secondary-fixed);">bolt</span>
                        <div class="h3 fw-bold mb-0 text-white">&lt; 50ms</div>
                        <div class="text-white-50 small">Latencia de Red</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subtle Brand Anchor -->
        <div class="d-flex align-items-center gap-3 pt-4">
            <div class="rounded-3 d-flex align-items-center justify-content-center text-white shadow" 
                 style="width: 44px; height: 44px; background: var(--stitch-secondary);">
                <span class="material-symbols-outlined fs-4">analytics</span>
            </div>
            <div>
                <div class="h5 fw-bold mb-0 text-white tracking-tight">AvícolaPro Control</div>
                <small class="text-white-50">Sistema Centralizado de Telemetría Ambiental</small>
            </div>
        </div>
    </div>

    <!-- Right Column: Login Form -->
    <div class="col-12 col-md-6 col-lg-5 d-flex align-items-center justify-content-center p-4 p-md-5 bg-white">
        <div class="w-100" style="max-width: 440px;">
            <!-- Mobile Brand Logo -->
            <div class="d-md-none text-center mb-4">
                <div class="rounded-3 d-inline-flex align-items-center justify-content-center text-white mb-2 shadow" 
                     style="width: 50px; height: 50px; background: var(--stitch-secondary);">
                    <span class="material-symbols-outlined fs-3">precision_manufacturing</span>
                </div>
                <h3 class="fw-bold text-dark">AvícolaPro Control</h3>
            </div>

            <div class="mb-4">
                <h2 class="fw-bold text-dark mb-1">Bienvenido de nuevo</h2>
                <p class="text-muted">Acceda a su panel operativo y red de sensores climáticos.</p>
            </div>

            <!-- Flash alerts -->
            @if (session('status'))
                <div class="alert alert-success d-flex align-items-center gap-2 py-2 px-3 rounded-3 small mb-3 border-0">
                    <span class="material-symbols-outlined fs-5 text-success">check_circle</span>
                    <div>{{ session('status') }}</div>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger py-2 px-3 rounded-3 small mb-3 border-0">
                    @foreach ($errors->all() as $error)
                        <div class="d-flex align-items-center gap-1">
                            <span class="material-symbols-outlined fs-6 text-danger">error</span>
                            <span>{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label font-label-caps">Correo del Operador</label>
                    <div class="input-group input-group-stitch">
                        <span class="input-group-text">
                            <span class="material-symbols-outlined fs-5">mail</span>
                        </span>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               class="form-control form-control-stitch @error('email') is-invalid @enderror" 
                               placeholder="operator@poultrysense.io" 
                               value="{{ old('email', 'admin@poultrysense.io') }}" 
                               required 
                               autofocus>
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label for="password" class="form-label font-label-caps mb-0">Credenciales de Seguridad</label>
                        <a href="#" class="font-label-caps text-decoration-none" style="color: var(--stitch-secondary);" onclick="alert('Contacte con el administrador para restablecer sus credenciales.'); return false;">¿Olvidó contraseña?</a>
                    </div>
                    <div class="input-group input-group-stitch">
                        <span class="input-group-text">
                            <span class="material-symbols-outlined fs-5">lock</span>
                        </span>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               class="form-control form-control-stitch @error('password') is-invalid @enderror" 
                               placeholder="••••••••••••" 
                               value="password123" 
                               required>
                        <button class="btn btn-outline-secondary border" type="button" id="togglePasswordBtn">
                            <span class="material-symbols-outlined fs-5 text-muted" id="togglePasswordIcon">visibility</span>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" checked>
                    <label class="form-check-label text-muted small" for="remember">
                        Mantener sesión activa en este terminal
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-green-gradient w-100 py-3 rounded-3 d-flex align-items-center justify-content-center gap-2 fw-semibold mb-3">
                    <span>Iniciar Sesión en el Panel</span>
                    <span class="material-symbols-outlined fs-5">arrow_forward</span>
                </button>

                <!-- Quick Seed Account Selectors for Testing -->
                <div class="p-3 bg-light rounded-3 mb-4 border">
                    <div class="font-label-caps mb-2 text-muted">Cuentas precargadas de prueba:</div>
                    <div class="d-flex flex-wrap gap-1">
                        <button type="button" class="btn btn-outline-dark btn-credential-pill" onclick="fillCredentials('admin@poultrysense.io', 'password123')">
                            <strong>Admin</strong> (admin@poultrysense.io)
                        </button>
                        <button type="button" class="btn btn-outline-success btn-credential-pill" onclick="fillCredentials('operator@poultrysense.io', 'password123')">
                            <strong>Operador</strong> (operator@poultrysense.io)
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-credential-pill" onclick="fillCredentials('tecnico@poultrysense.io', 'password123')">
                            <strong>Técnico</strong> (tecnico@poultrysense.io)
                        </button>
                    </div>
                </div>

                <div class="text-center pt-2 border-top">
                    <p class="text-muted small mb-0">
                        ¿No tiene acceso aún?
                        <a href="{{ route('register') }}" class="fw-bold text-decoration-none ms-1" style="color: var(--stitch-secondary);">
                            Crear cuenta nueva
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
