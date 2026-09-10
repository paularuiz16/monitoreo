@extends('layouts.app')

@section('title', 'Gestión de Usuarios | AvícolaPro Control')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Módulo Administrativo de Usuarios</h3>
        <p class="text-muted small mb-0">Gestión de roles, permisos y control de autoría (Altas Administrativas vs Autoregistros).</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-green-gradient d-inline-flex align-items-center gap-2 py-2 px-3 shadow-sm">
        <span class="material-symbols-outlined fs-5">person_add</span>
        <span>Crear Usuario Interno</span>
    </a>
</div>

<!-- Stats Counters -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stitch-card p-3 h-100">
            <span class="font-label-caps">Total Usuarios</span>
            <div class="metric-value mt-1">{{ $stats['total'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stitch-card p-3 h-100">
            <span class="font-label-caps">Administradores</span>
            <div class="metric-value mt-1 text-primary">{{ $stats['admins'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stitch-card p-3 h-100">
            <span class="font-label-caps">Técnicos</span>
            <div class="metric-value mt-1 text-info">{{ $stats['tecnicos'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stitch-card p-3 h-100">
            <span class="font-label-caps">Operadores</span>
            <div class="metric-value mt-1 text-success">{{ $stats['operadores'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stitch-card p-3 h-100 border-start border-success border-3">
            <span class="font-label-caps">Altas Internas</span>
            <div class="metric-value mt-1 text-success">{{ $stats['internal'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stitch-card p-3 h-100 border-start border-warning border-3">
            <span class="font-label-caps">Autoregistro</span>
            <div class="metric-value mt-1 text-warning">{{ $stats['self_registered'] }}</div>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="stitch-card p-0 overflow-hidden mb-4">
    <div class="p-3 px-4 border-bottom d-flex justify-content-between align-items-center bg-light">
        <h6 class="fw-bold mb-0 text-dark">Directorio de Usuarios del Sistema</h6>
        <span class="badge bg-white text-dark border px-2 py-1">Mostrando {{ $users->count() }} usuarios</span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr class="font-label-caps">
                    <th class="ps-4">Usuario</th>
                    <th>Rol</th>
                    <th>Origen / Autoría</th>
                    <th>Estado</th>
                    <th>Fecha de Alta</th>
                    <th class="text-end pe-4">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" 
                                     style="width: 40px; height: 40px; background: {{ $user->isAdmin() ? 'var(--stitch-primary)' : ($user->isTecnico() ? '#00695c' : 'var(--stitch-secondary)') }};">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark">{{ $user->full_name ?: $user->name }}</div>
                                    <div class="text-muted small">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-stitch-{{ $user->role }}">
                                {{ strtoupper($user->role) }}
                            </span>
                        </td>
                        <td>
                            @if ($user->created_by && $user->creator)
                                <div class="d-flex align-items-center gap-1 text-success small fw-medium">
                                    <span class="material-symbols-outlined fs-6">admin_panel_settings</span>
                                    <span>Alta Interna: <strong>{{ $user->creator->name }}</strong></span>
                                </div>
                            @elseif ($user->id === 1)
                                <span class="badge bg-dark text-white">Superadmin Inicial</span>
                            @else
                                <div class="d-flex align-items-center gap-1 text-muted small">
                                    <span class="material-symbols-outlined fs-6">public</span>
                                    <span>Autoregistro Web</span>
                                </div>
                            @endif
                        </td>
                        <td>
                            @if ($user->is_active)
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 rounded-pill small">
                                    <span class="material-symbols-outlined fs-6 me-1" style="font-size: 14px;">check_circle</span> Activo
                                </span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1 rounded-pill small">
                                    <span class="material-symbols-outlined fs-6 me-1" style="font-size: 14px;">cancel</span> Inactivo
                                </span>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted small">{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A' }}</span>
                        </td>
                        <td class="text-end pe-4">
                            @if (Auth::id() !== $user->id)
                                <form method="POST" action="{{ route('users.toggle', $user->id) }}" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-secondary py-1 px-2 rounded-2" title="{{ $user->is_active ? 'Desactivar acceso' : 'Habilitar acceso' }}">
                                        <span class="material-symbols-outlined fs-6">{{ $user->is_active ? 'lock' : 'lock_open' }}</span>
                                        <span class="small">{{ $user->is_active ? 'Desactivar' : 'Activar' }}</span>
                                    </button>
                                </form>
                            @else
                                <span class="badge bg-light text-muted border px-2 py-1">Tu usuario actual</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            No se encontraron usuarios registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($users->hasPages())
        <div class="p-3 border-top bg-light">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
