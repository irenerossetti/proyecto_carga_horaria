<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    /**
     * Apuntando al schema correcto
     */
    protected $table = 'public.teachers';
    
    // Asegúrate que 'user_id' esté en $fillable si lo creas manualmente
    protected $fillable = ['user_id', 'name', 'email', 'phone'];
    public $timestamps = false; // Basado en tu migración

    /**
     * Relación uno a uno con User (un Docente ES un User)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación uno a muchos con TeacherAssignment (un Docente tiene muchas asignaciones)
     */
    public function assignments()
    {
        return $this->hasMany(TeacherAssignment::class, 'teacher_id');
    }

    /**
     * Relación uno a muchos con Attendance (un Docente tiene muchas asistencias)
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'teacher_id');
    }
}