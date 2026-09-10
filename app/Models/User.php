<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
        'role',
        'created_by',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Usuario administrador que creó este usuario (si fue creado internamente).
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Usuarios creados por este usuario.
     */
    public function createdUsers(): HasMany
    {
        return $this->hasMany(User::class, 'created_by');
    }

    /**
     * Comprueba si el usuario tiene rol de administrador.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Comprueba si el usuario tiene rol de técnico.
     */
    public function isTecnico(): bool
    {
        return $this->role === 'tecnico';
    }

    /**
     * Comprueba si el usuario tiene rol de operador.
     */
    public function isOperador(): bool
    {
        return $this->role === 'operador';
    }

    /**
     * Obtener el nombre completo del usuario.
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->name} {$this->last_name}");
    }
}
