<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Group;
use App\Models\TeacherAssignment;
use App\Models\ClassCancellation;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StudentDashboardController extends Controller
{
    /**
     * Muestra el dashboard del estudiante con sus materias.
     */
    public function index()
    {
        // Verificar que el usuario esté autenticado
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        
        // Verificar que el usuario tenga rol de ESTUDIANTE
        if (!$user->hasRole('ESTUDIANTE')) {
            Auth::logout();
            return redirect('/login')->with('error', 'No tienes permisos de estudiante.');
        }

        // Obtener datos del dashboard
        $data = $this->getDashboardData($user);

        return view('student.dashboard', $data);
    }

    /**
     * Obtiene los datos del dashboard del estudiante.
     */
    private function getDashboardData($user): array
    {
        // TODO: Implementar lógica para obtener las materias del estudiante
        // Por ahora retornamos datos de ejemplo
        
        // En una implementación real, necesitarías:
        // 1. Una tabla de inscripciones (enrollments) que relacione usuarios con grupos
        // 2. Obtener los grupos en los que está inscrito el estudiante
        // 3. Obtener los horarios de esos grupos
        // 4. Verificar si hay cancelaciones o cambios de modalidad

        // Ejemplo de cómo se haría si tuvieras la relación:
        // $studentGroups = $user->groups()->with(['subject', 'schedules.room', 'teacherAssignments.teacher'])->get();
        
        $today = Carbon::now();
        
        // Por ahora usamos un array vacío, pero la estructura estaría lista
        $enrolledSubjects = [];
        
        // Obtener notificaciones de clases virtuales o cambios para hoy
        $todayNotifications = [];
        
        return [
            'enrolledSubjects' => $enrolledSubjects,
            'todayNotifications' => $todayNotifications,
            'currentDate' => $today,
        ];
    }

    /**
     * Obtiene las materias en las que está inscrito el estudiante.
     * Esta es una función auxiliar que se usaría cuando implementes
     * la relación entre estudiantes y grupos.
     */
    private function getEnrolledSubjects($user)
    {
        // Ejemplo de estructura que retornaría:
        // return $user->groups()
        //     ->with([
        //         'subject',
        //         'schedules.room',
        //         'schedules.cancellation',
        //         'teacherAssignments.teacher'
        //     ])
        //     ->get()
        //     ->map(function ($group) {
        //         return [
        //             'subject_name' => $group->subject->name,
        //             'group_code' => $group->code,
        //             'teacher' => $group->teacherAssignments->first()?->teacher?->name,
        //             'schedules' => $group->schedules->map(function ($schedule) {
        //                 return [
        //                     'day' => $schedule->day_of_week,
        //                     'start_time' => $schedule->start_time,
        //                     'end_time' => $schedule->end_time,
        //                     'room' => $schedule->room?->name,
        //                     'is_virtual' => $schedule->cancellation?->cancellation_type === 'virtual',
        //                     'room_changed' => $schedule->room_changed ?? false,
        //                 ];
        //             }),
        //         ];
        //     });
        
        return collect([]);
    }
}
