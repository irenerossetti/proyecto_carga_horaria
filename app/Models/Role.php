<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'rol_id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = ['nombre', 'descripcion', 'privilegios', 'activo', 'fecha_creacion'];

    public function users()
    {
        // pivot may or may not exist; keep relation for when present
        return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id');
    }
}
