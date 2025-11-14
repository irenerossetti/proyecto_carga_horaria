<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Schedule extends Model
{
    protected $fillable = [
        'group_id',
        'room_id', 
        'teacher_id',
        'day_of_week',
        'start_time',
        'end_time',
        'assigned_by'
    ];

    /**
     * Relación con Group (un horario pertenece a un grupo)
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    /**
     * Relación con Room (un horario pertenece a un Aula)
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    /**
     * Relación con Teacher (un horario pertenece a un docente)
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    /**
     * Relación con User (quien asignó el horario)
     */
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Relación uno a uno con ClassCancellation (un horario puede tener una cancelación)
     * Solo trae la cancelación del DÍA DE HOY.
     */
    public function cancellation()
    {
        return $this->hasOne(ClassCancellation::class, 'schedule_id')
                    ->whereDate('cancelled_at', Carbon::today());
    }

    /**
     * Relación uno a uno con Attendance (un horario puede tener una asistencia)
     * Solo trae la asistencia del DÍA DE HOY.
     */
    public function attendanceToday()
    {
        return $this->hasOne(Attendance::class, 'schedule_id')
                    ->whereDate('attendance_time', Carbon::today());
    }
}