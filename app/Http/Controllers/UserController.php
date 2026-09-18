<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Listado general de usuarios (distinguiendo autoregistros vs altas administrativas).
     */
    public function index(): View
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acceso denegado: Solo el Administrador tiene autorización para gestionar el personal del sistema.');
        }

        $users = User::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $stats = [
            'total' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'tecnicos' => User::where('role', 'tecnico')->count(),
            'operadores' => User::where('role', 'operador')->count(),
            'internal' => User::whereNotNull('created_by')->count(),
            'self_registered' => User::whereNull('created_by')->count(),
        ];

        return view('users.index', compact('users', 'stats'));
    }

    /**
     * Formulario interno de alta de usuario administrativo/operativo.
     */
    public function create(): View
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acceso denegado: Solo el Administrador tiene autorización para dar de alta nuevo personal.');
        }

        return view('users.create');
    }

    /**
     * Guardar usuario creado internamente por un administrador autenticado.
     */
    public function store(Request $request): RedirectResponse
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acceso denegado: Solo el Administrador puede registrar nuevo personal en el sistema.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'string', 'in:admin,tecnico,operador'],
            'password' => ['required', 'string', Password::min(8), 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'El nombre del usuario es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.unique' => 'Este correo electrónico ya está registrado en el sistema.',
            'role.required' => 'Seleccione el rol que tendrá el usuario.',
            'role.in' => 'El rol seleccionado no es válido.',
            'password.required' => 'Asigne una contraseña inicial.',
            'password.min' => 'La contraseña debe tener mínimo 8 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'last_name' => $validated['last_name'] ?? null,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'created_by' => Auth::id(), // Registra la autoría administrativa interna
            'is_active' => $request->boolean('is_active', true),
            'email_verified_at' => now(),
        ]);

        return redirect()->route('users.index')
            ->with('status', "Usuario {$user->full_name} ({$user->email}) creado exitosamente por " . Auth::user()->name . '.');
    }

    /**
     * Activar o suspender un usuario del sistema (solo administrador).
     */
    public function toggleStatus(User $user): RedirectResponse
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acceso denegado: Solo el Administrador puede modificar el estado del personal.');
        }

        // Evitar que el usuario se inhabilite a sí mismo
        if ($user->id === Auth::id()) {
            return back()->with('error', 'No puedes desactivar tu propia cuenta activa.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $estado = $user->is_active ? 'activado' : 'desactivado';
        return back()->with('status', "El usuario {$user->email} ha sido {$estado}.");
    }
}
