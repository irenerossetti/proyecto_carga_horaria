<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use App\Models\Role;
use Illuminate\Support\Facades\Schema;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $table = 'usuarios';
    protected $primaryKey = 'usuario_id';

    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'password_hash',
        'rol_id',
    ];
    public $timestamps = false;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
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
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Roles relation (many-to-many)
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /**
     * Check if user has a role by name
     */
    public function hasRole(string $roleName): bool
    {
        // check legacy single-role column
        if (Schema::hasColumn($this->getTable(), 'rol_id')) {
            if (!empty($this->rol_id)) {
                $role = Role::find($this->rol_id);
                if ($role && ($role->nombre === $roleName)) {
                    return true;
                }
            }
        }

        // check pivot relation
        if (Schema::hasTable('role_user')) {
            if ($this->roles()->where('nombre', $roleName)->exists()) {
                return true;
            }
        }

        return ($this->is_admin ?? false);
    }

    /**
     * Assign roles by names (sync if pivot exists, otherwise set legacy rol_id)
     */
    public function assignRoles(array $roleNames)
    {
        $roles = Role::whereIn('nombre', $roleNames)->get();

        if (Schema::hasTable('role_user')) {
            $this->roles()->sync($roles->pluck('rol_id')->toArray());
            return $this->roles()->pluck('nombre')->toArray();
        }

        // legacy single-role assignment: set rol_id to first role
        if ($roles->isNotEmpty()) {
            $this->rol_id = $roles->first()->rol_id;
            $this->save();
            return [$roles->first()->nombre];
        }

        return [];
    }

    /**
     * Legacy single relation to roles table via usuarios.rol_id
     */
    public function roleSingle()
    {
        return $this->belongsTo(Role::class, 'rol_id', 'rol_id');
    }
}
