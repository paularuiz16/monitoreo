<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Muestra la vista de autoregistro público.
     */
    public function showRegistrationForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    /**
     * Procesa el autoregistro de un nuevo operador/técnico.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['nullable', 'string', 'in:operador,tecnico'],
            'password' => ['required', 'string', Password::min(8), 'confirmed'],
            'terms' => ['accepted'],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.unique' => 'Este correo electrónico ya se encuentra registrado.',
            'password.required' => 'La contraseña es requerida.',
            'password.min' => 'La contraseña debe contener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'terms.accepted' => 'Debe aceptar los términos y condiciones de AvícolaPro Control.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'last_name' => $validated['last_name'] ?? null,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'] ?? 'operador',
            'created_by' => null, // Distingue el autoregistro del alta por administrador
            'is_active' => true,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('status', '¡Registro exitoso! Bienvenido a la plataforma AvícolaPro Control.');
    }
}
