<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    const UPDATED_AT = null; // Solo usamos created_at
    
    protected $fillable = [
        'user_id',
        'user_name',
        'user_email',
        'user_role',
        'ip_address',
        'user_agent',
        'action',
        'module',
        'description',
        'url',
        'method',
        'old_values',
        'new_values',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Relación con el usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Registrar una actividad
     */
    public static function log(
        string $action,
        string $module,
        string $description,
        ?array $oldValues = null,
        ?array $newValues = null
    ): void {
        $user = auth()->user();
        
        self::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name,
            'user_email' => $user?->email,
            'user_role' => $user?->roles->first()?->name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'old_values' => $oldValues,
            'new_values' => $newValues,
        ]);
    }

    /**
     * Obtener el color del badge según la acción
     */
    public function getActionColorAttribute(): string
    {
        return match($this->action) {
            'login' => 'bg-green-100 text-green-700',
            'logout' => 'bg-gray-100 text-gray-700',
            'create' => 'bg-blue-100 text-blue-700',
            'update' => 'bg-yellow-100 text-yellow-700',
            'delete' => 'bg-red-100 text-red-700',
            'view' => 'bg-purple-100 text-purple-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    /**
     * Obtener el icono según la acción
     */
    public function getActionIconAttribute(): string
    {
        return match($this->action) {
            'login' => 'fa-sign-in-alt',
            'logout' => 'fa-sign-out-alt',
            'create' => 'fa-plus-circle',
            'update' => 'fa-edit',
            'delete' => 'fa-trash',
            'view' => 'fa-eye',
            default => 'fa-circle',
        };
    }
}
