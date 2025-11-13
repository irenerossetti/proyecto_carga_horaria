<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Schedule extends Model
{
    protected $table = 'public.schedules';
    protected $fillable = ['assignment_id', 'room_id', 'day_of_week', 'start_time', 'end_time'];
    public $timestamps = false;

    /**
     * Relación inversa con TeacherAssignment (un horario pertenece a una asignación)
     */
    public function assignment()
    {
        return $this->belongsTo(TeacherAssignment::class, 'assignment_id');
    }

    /**
     * Relación inversa con Room (un horario pertenece a un Aula)
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
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
     *
     * --- ¡CORRECCIÓN CLAVE AQUÍ! ---
     * No podemos filtrar por teacher_id aquí, porque $this->assignment no está disponible
     * durante el "eager loading". El DocenteController ya filtra los horarios
     * por docente, así que esta relación solo necesita traer la asistencia
     * de ESTE horario para HOY.
     */
    public function attendanceToday()
    {
        return $this->hasOne(Attendance::class, 'schedule_id')
                    ->whereDate('attendance_time', Carbon::today());
    }
}