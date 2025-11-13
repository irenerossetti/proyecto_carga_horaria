<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Fortify\TwoFactorAuthenticatable; // <-- Vi que tu proyecto usa esto

class User extends Authenticatable
{
    // Usamos los traits que tu proyecto necesita
    use HasApiTokens, HasFactory, Notifiable, TwoFactorAuthenticatable; 

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret', // <--- Estaba en tu original
        'two_factor_recovery_codes', // <--- Estaba en tu original
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // RELACIÓN CON ROLES - ESPECIFICANDO LA TABLA PIVOT
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    // VERIFICAR ROL
    public function hasRole($roleName)
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    // VERIFICAR MÚLTIPLES ROLES
    public function hasAnyRole($roles)
    {
        return $this->roles()->whereIn('name', (array)$roles)->exists();
    }
    // Tu relación con Teacher
    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'user_id', 'id');
    }
}