<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Teacher;
use App\Models\Schedule;
use App\Models\Attendance;
use App\Models\ClassCancellation;
use Illuminate\Support\Facades\Log; // Importamos Log para depurar si sigue fallando

class DocenteController extends Controller
{
    public function dashboard()
    {
        try {
            $user = Auth::user();
            
            // 1. Verificar la relación con el docente
            $teacher = $user->teacher; // Usamos la relación definida en User.php

            if (!$teacher) {
                // Si el usuario no tiene un perfil de docente, lo sacamos.
                if ($user->hasRole('ADMIN')) {
                    return redirect()->route('dashboard')->with('status', 'Logueado como Admin (sin perfil docente).');
                }
                
                Auth::logout();
                return redirect('/login')->with('error', 'No se encontró un perfil de docente asociado a este usuario.');
            }

            // 2. Mapeo de Días (ISO a Español en MAYÚSCULAS)
            // ¡¡AQUÍ ESTABA EL ERROR DE '4.md'!!
            $dayMap = [
                '1' => 'LUNES',
                '2' => 'MARTES',
                '3' => 'MIERCOLES',
                '4' => 'JUEVES', // <-- ¡CORREGIDO!
                '5' => 'VIERNES',
                '6' => 'SABADO',
                '7' => 'DOMINGO',
            ];
            
            $dayOfWeekNumber = now()->isoFormat('E'); 
            $dayOfWeekName = $dayMap[$dayOfWeekNumber] ?? 'LUNES'; // ej: 'LUNES'

            // 3. Obtener horarios de HOY
            $schedulesToday = Schedule::query()
                ->whereHas('assignment', function($q) use ($teacher) {
                    $q->where('teacher_id', $teacher->id);
                })
                ->where('day_of_week', $dayOfWeekName) // Buscamos 'LUNES', no '1'
                ->with([
                    'assignment.group.subject', // Esta cadena debe funcionar si Group.php está corregido
                    'room', 
                    'attendanceToday',
                    'cancellation'
                ])
                ->orderBy('start_time')
                ->get();

            $now = now()->format('H:i:s');

            // 4. Filtrar clases
            $currentClass = $schedulesToday->filter(function($schedule) use ($now) {
                return $schedule->start_time <= $now && $schedule->end_time >= $now;
            })->first();

            $upcomingClasses = $schedulesToday->filter(function($schedule) use ($now) {
                return $schedule->start_time > $now;
            });
            
            // 5. ¡Finalmente, cargar la vista!
            return view('docente.dashboard', compact('schedulesToday', 'currentClass', 'upcomingClasses'));
        
        } catch (\Exception $e) {
            // Si algo MÁS falla (ej. la consulta 'assignment.group.subject'),
            // lo veremos en el log.
            Log::error("Error en DocenteController@dashboard: " . $e->getMessage());
            // Mostramos el error en pantalla
            return response("Error 500 en el controlador de docente: " . $e->getMessage(), 500);
        }
    }

    /**
     * Acción para el botón "Marcar Asistencia"
     */
    public function marcarAsistencia(Request $request, Schedule $schedule)
    {
        $teacher = Auth::user()->teacher;
        if (!$teacher) {
            return back()->with('error', 'No eres un docente válido.');
        }

        $exists = Attendance::where('schedule_id', $schedule->id)
            ->where('teacher_id', $teacher->id)
            ->whereDate('attendance_time', Carbon::today())
            ->exists();

        if (!$exists) {
            Attendance::create([
                'schedule_id' => $schedule->id,
                'teacher_id' => $teacher->id,
                'status' => 'presente',
                'attendance_time' => now(),
            ]);
        }

        return back()->with('status', '¡Asistencia marcada correctamente!');
    }

    /**
     * Acción para el botón "Cambiar a Virtual"
     */
    public function cambiarVirtual(Request $request, Schedule $schedule)
    {
        $exists = ClassCancellation::where('schedule_id', $schedule->id)
            ->whereDate('cancelled_at', Carbon::today())
            ->exists();

        if (!$exists) {
            ClassCancellation::create([
                'schedule_id' => $schedule->id,
                'cancellation_type' => 'virtual',
                'reason' => 'Solicitud del docente (desde dashboard)',
                'cancelled_at' => now(),
            ]);
        }

        return back()->with('status', 'La clase se ha cambiado a modalidad virtual.');
    }
}