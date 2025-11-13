<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'public.attendances';
    // 'attendance_time' debe ser manejado por $timestamps
    public $timestamps = true;
    const CREATED_AT = 'attendance_time'; // Asumiendo que 'attendance_time' es tu 'created_at'
    const UPDATED_AT = null; // Asumiendo que no hay 'updated_at'


    protected $fillable = [
        'schedule_id',
        'teacher_id',
        'status', // 'presente', 'ausente', 'licencia'
        'attendance_time'
    ];

    protected $casts = [
        'attendance_time' => 'datetime',
    ];

    /**
     * Relación inversa con Schedule (una asistencia pertenece a un horario)
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    /**
     * Relación inversa con Teacher (una asistencia pertenece a un docente)
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}