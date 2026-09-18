@extends('layouts.auth')

@section('title', 'Crear Cuenta | AvícolaPro Control IoT')

@section('content')
<div class="container py-4 my-auto">
    <div class="stitch-card p-0 overflow-hidden mx-auto" style="max-width: 1080px;">
        <div class="row g-0">
            <!-- Left Side: Visual/Context Area (Fiel a Stitch Registro) -->
            <div class="col-lg-5 d-none d-lg-flex flex-column justify-content-between p-5 text-white auth-hero-panel">
                <div>
                    <h1 class="display-6 fw-bold mb-3 text-white lh-tight">
                        Optimización <br><span style="color: var(--stitch-secondary-fixed);">en tiempo real.</span>
                    </h1>

                    <p class="text-white-50 lead fs-6 mb-4">
                        Monitoreo inteligente para la industria avícola de precisión. Controle el clima, la ventilación y el bienestar de sus galpones desde una única interfaz centralizada.
                    </p>
                </div>

                <!-- Industrial Status Badges -->
                <div class="pt-4 border-top border-secondary border-opacity-25">
                    <div class="d-flex align-items-center gap-3">
                        <span class="material-symbols-outlined text-success fs-3">verified</span>
                        <div>
                            <div class="fw-bold text-white small">Sensores Calibrados</div>
                            <div class="text-white-50" style="font-size: 12px;">Sincronización SCADA grado industrial</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Registration Form -->
            <div class="col-12 col-lg-7 p-4 p-md-5 bg-white">
                <!-- Logo Header con el nuevo logo generado -->
                <div class="text-center text-md-start mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="AvícolaPro Control" class="rounded-4 shadow-sm mb-3" style="max-height: 80px; width: auto; object-fit: contain;" />
                    <h2 class="fw-bold text-dark mb-1">Crear cuenta</h2>
                    <p class="text-muted small">Gestiona y monitorea el estado de las cortinas y el ambiente de tus galpones en tiempo real.</p>
                </div>

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

                <form method="POST" action="{{ route('register.submit') }}">
                    @csrf

                    <!-- Name and Last Name in 2 columns -->
                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label for="name" class="form-label font-label-caps">Nombre</label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   class="form-control form-control-stitch @error('name') is-invalid @enderror" 
                                   placeholder="Juan" 
                                   value="{{ old('name') }}" 
                                   required>
                        </div>
                        <div class="col-sm-6">
                            <label for="last_name" class="form-label font-label-caps">Apellido</label>
                            <input type="text" 
                                   name="last_name" 
                                   id="last_name" 
                                   class="form-control form-control-stitch @error('last_name') is-invalid @enderror" 
                                   placeholder="Pérez" 
                                   value="{{ old('last_name') }}">
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label font-label-caps">Correo electrónico</label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               class="form-control form-control-stitch @error('email') is-invalid @enderror" 
                               placeholder="usuario@empresa.com" 
                               value="{{ old('email') }}" 
                               required>
                    </div>

                    <!-- Rol -->
                    <div class="mb-3">
                        <label for="role" class="form-label font-label-caps">Rol de Operación</label>
                        <select name="role" id="role" class="form-select form-control-stitch @error('role') is-invalid @enderror">
                            <option value="operador" {{ old('role') == 'operador' ? 'selected' : '' }}>Operador de Galpón (Monitoreo de Variables)</option>
                            <option value="tecnico" {{ old('role') == 'tecnico' ? 'selected' : '' }}>Técnico de Campo (Ajustes y Calibración)</option>
                        </select>
                    </div>

                    <!-- Passwords -->
                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label for="password" class="form-label font-label-caps">Contraseña</label>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="form-control form-control-stitch @error('password') is-invalid @enderror" 
                                   placeholder="••••••••" 
                                   required>
                        </div>
                        <div class="col-sm-6">
                            <label for="password_confirmation" class="form-label font-label-caps">Confirmar Contraseña</label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   class="form-control form-control-stitch" 
                                   placeholder="••••••••" 
                                   required>
                        </div>
                    </div>

                    <!-- Password strength visual indicator (Animado en auth.js) -->
                    <div class="mb-3">
                        <div class="d-flex gap-1 mb-1">
                            <div class="strength-meter-bar"></div>
                            <div class="strength-meter-bar"></div>
                            <div class="strength-meter-bar"></div>
                            <div class="strength-meter-bar"></div>
                        </div>
                        <span id="strengthText" class="small text-muted">Ingrese una contraseña segura</span>
                    </div>

                    <!-- Terms & Conditions -->
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="terms" id="terms" required checked>
                        <label class="form-check-label text-muted small" for="terms">
                            Acepto los <a href="#" class="fw-semibold text-decoration-none" style="color: var(--stitch-secondary);">Términos y Condiciones</a> y la Política de Privacidad de AvícolaPro Control.
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary-gradient w-100 py-3 rounded-3 d-flex align-items-center justify-content-center gap-2 fw-semibold mb-3">
                        <span>Crear cuenta en el sistema</span>
                        <span class="material-symbols-outlined fs-5">arrow_forward</span>
                    </button>

                    <div class="text-center pt-2 border-top">
                        <p class="text-muted small mb-0">
                            ¿Ya tienes cuenta activa?
                            <a href="{{ route('login') }}" class="fw-bold text-decoration-none ms-1" style="color: var(--stitch-secondary);">
                                Inicia sesión aquí
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
