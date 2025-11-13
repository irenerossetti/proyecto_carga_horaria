<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'public.roles';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = ['name', 'description'];

    public function users()
    {
        return $this->belongsToMany(
            User::class, 
            'public.role_user', 
            'role_id',    // Esta debe ser 'rol_id' si es diferente en tu DB
            'user_id'
        );
    }
}