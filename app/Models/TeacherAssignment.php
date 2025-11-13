<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherAssignment extends Model
{
    protected $table = 'public.teacher_assignments';
    protected $fillable = ['teacher_id', 'group_id', 'academic_period_id'];
    public $timestamps = false;

    /**
     * Relación inversa con Teacher (una asignación pertenece a un Docente)
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    /**
     * Relación inversa con Group (una asignación pertenece a un Grupo)
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    /**
     * Relación uno a muchos con Schedule (una asignación tiene muchos horarios)
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'assignment_id');
    }
}