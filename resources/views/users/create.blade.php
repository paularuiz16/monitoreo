@extends('layouts.app')

@section('title', 'Crear Usuario Interno | AvícolaPro Control')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-9">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <a href="{{ route('users.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center gap-1 mb-1">
                    <span class="material-symbols-outlined fs-6">arrow_back</span> Volver al listado
                </a>
                <h3 class="fw-bold text-dark mb-0">Alta Manual de Usuario Interno</h3>
            </div>
            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2 rounded-pill">
                <span class="material-symbols-outlined fs-6 me-1">admin_panel_settings</span> Operador Autorizado: {{ Auth::user()->name }}
            </span>
        </div>

        <div class="stitch-card p-4 p-md-5">
            <!-- Informational Banner distinguishing from self-registration -->
            <div class="p-3 rounded-3 mb-4 d-flex align-items-start gap-3" style="background-color: var(--stitch-surface-container); border-left: 4px solid var(--stitch-secondary);">
                <span class="material-symbols-outlined text-success fs-3 mt-1">info</span>
                <div>
                    <div class="fw-bold text-dark">Módulo de Alta Administrativa Interna</div>
                    <div class="text-muted small">
                        A diferencia del autoregistro público, este formulario permite registrar directamente operadores, técnicos o nuevos administradores con sus credenciales operativas listas y vincula permanentemente tu cuenta como autor del alta.
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('users.store') }}">
                @csrf

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label font-label-caps">Nombre <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               class="form-control form-control-stitch @error('name') is-invalid @enderror" 
                               placeholder="Ej. Roberto" 
                               value="{{ old('name') }}" 
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="last_name" class="form-label font-label-caps">Apellido</label>
                        <input type="text" 
                               name="last_name" 
                               id="last_name" 
                               class="form-control form-control-stitch @error('last_name') is-invalid @enderror" 
                               placeholder="Ej. Vargas" 
                               value="{{ old('last_name') }}">
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="email" class="form-label font-label-caps">Correo Electrónico de Trabajo <span class="text-danger">*</span></label>
                        <div class="input-group input-group-stitch">
                            <span class="input-group-text">
                                <span class="material-symbols-outlined fs-5">mail</span>
                            </span>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   class="form-control form-control-stitch @error('email') is-invalid @enderror" 
                                   placeholder="rvargas@poultrysense.io" 
                                   value="{{ old('email') }}" 
                                   required>
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="role" class="form-label font-label-caps">Rol Operativo Asignado <span class="text-danger">*</span></label>
                        <div class="input-group input-group-stitch">
                            <span class="input-group-text">
                                <span class="material-symbols-outlined fs-5">shield_person</span>
                            </span>
                            <select name="role" id="role" class="form-select form-control-stitch @error('role') is-invalid @enderror" required>
                                <option value="operador" {{ old('role') == 'operador' ? 'selected' : '' }}>Operador de Galpón (Monitoreo y Reportes)</option>
                                <option value="tecnico" {{ old('role') == 'tecnico' ? 'selected' : '' }}>Técnico de Automatización (Ajuste de Variables)</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador General (Control Total)</option>
                            </select>
                        </div>
                        @error('role')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="password" class="form-label font-label-caps">Contraseña Temporal / Inicial <span class="text-danger">*</span></label>
                        <div class="input-group input-group-stitch">
                            <span class="input-group-text">
                                <span class="material-symbols-outlined fs-5">lock</span>
                            </span>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="form-control form-control-stitch @error('password') is-invalid @enderror" 
                                   placeholder="Mínimo 8 caracteres" 
                                   required>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label font-label-caps">Confirmar Contraseña <span class="text-danger">*</span></label>
                        <div class="input-group input-group-stitch">
                            <span class="input-group-text">
                                <span class="material-symbols-outlined fs-5">lock_reset</span>
                            </span>
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   class="form-control form-control-stitch" 
                                   placeholder="Repetir contraseña" 
                                   required>
                        </div>
                    </div>
                </div>

                <div class="mb-4 p-3 bg-light rounded-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                        <label class="form-check-label fw-medium text-dark" for="is_active">
                            Habilitar acceso inmediato al sistema (Cuenta activa)
                        </label>
                    </div>
                    <div class="text-muted small ps-5">Si se desmarca, el usuario no podrá iniciar sesión hasta que sea autorizado expresamente.</div>
                </div>

                <div class="d-flex justify-content-end gap-3 pt-3 border-top">
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary px-4 py-2 rounded-3">
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-green-gradient px-4 py-2 rounded-3 d-inline-flex align-items-center gap-2">
                        <span class="material-symbols-outlined fs-5">save</span>
                        <span>Registrar Usuario en Sistema</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
