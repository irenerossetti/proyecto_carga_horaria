<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Room;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;
// Modelos comentados porque las tablas no existen en Neon:
// use App\Models\Subject;
// use App\Models\Attendance;
// use App\Models\Reservation;
// use App\Models\Conflict;

class AdminDashboardController extends Controller
{
    /**
     * Muestra la vista del dashboard web con datos.
     */
    public function index()
    {
        // Verificar que el usuario esté autenticado
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        
        // Verificar que el usuario tenga rol de ADMIN
        if (!$user->hasRole('ADMIN')) {
            Auth::logout();
            return redirect('/login')->with('error', 'No tienes permisos de administrador.');
        }

        // 1. Obtenemos los datos
        $data = $this->getDashboardData();

        // 2. Retornamos la VISTA 'admin-dashboard' y le pasamos los datos
        return view('admin-dashboard', $data);
    }

    /**
     * Lógica centralizada para obtener las estadísticas.
     */
    private function getDashboardData(): array
    {
        try {
            $totalTeachers = Teacher::count();
        } catch (\Exception $e) {
            $totalTeachers = 0;
        }
        
        try {
            $totalRooms = Room::count();
        } catch (\Exception $e) {
            $totalRooms = 0;
        }
        
        // Contar materias - TABLA NO EXISTE EN LA BD
        $totalSubjects = 0;
        
        // Contar estudiantes
        try {
            $totalStudents = User::whereHas('roles', function($q) {
                $q->where('name', 'ESTUDIANTE');
            })->count();
        } catch (\Exception $e) {
            $totalStudents = 0;
        }

        // Contar aulas libres hoy (sin horarios asignados en este momento)
        $now = now();
        $currentDay = $now->dayOfWeek; // 0=Domingo, 1=Lunes, etc.
        $currentTime = $now->format('H:i:s');
        
        try {
            $busyRooms = Schedule::where('day_of_week', $currentDay)
                ->where('start_time', '<=', $currentTime)
                ->where('end_time', '>=', $currentTime)
                ->whereNotNull('room_id')
                ->distinct('room_id')
                ->count('room_id');
        } catch (\Exception $e) {
            $busyRooms = 0;
        }
            
        $freeRoomsToday = max(0, $totalRooms - $busyRooms);
        
        // Horarios totales
        try {
            $totalSchedules = Schedule::count();
        } catch (\Exception $e) {
            $totalSchedules = 0;
        }
        
        // Asistencias de hoy (tabla NO EXISTE, retornar 0)
        $todayAttendances = 0;
        
        // Conflictos abiertos (tabla NO EXISTE, retornar 0)
        $openConflicts = 0;
        
        // Reservas activas (tabla NO EXISTE, retornar 0)
        $activeReservations = 0;

        // Mapeo de días
        $daysMap = ['DOMINGO', 'LUNES', 'MARTES', 'MIÉRCOLES', 'JUEVES', 'VIERNES', 'SÁBADO'];
        $currentDayName = $daysMap[$currentDay] ?? null;

        // Clases en curso y próximas (solo si hay horarios y día válido)
        $upcomingSchedules = collect([]);
        $currentClass = null;
        
        if ($currentDayName && $totalSchedules > 0) {
            try {
                $upcomingSchedules = Schedule::where('day_of_week', $currentDayName)
                    ->where('start_time', '>=', $currentTime)
                    ->orderBy('start_time', 'asc')
                    ->take(3)
                    ->get();

                // Clase actual
                $currentClass = Schedule::where('day_of_week', $currentDayName)
                    ->where('start_time', '<=', $currentTime)
                    ->where('end_time', '>=', $currentTime)
                    ->first();
            } catch (\Exception $e) {
                \Log::warning('Error al obtener horarios: ' . $e->getMessage());
            }
        }

        return [
            'totalTeachers' => $totalTeachers,
            'totalRooms' => $totalRooms,
            'freeRoomsToday' => $freeRoomsToday,
            'totalSubjects' => $totalSubjects,
            'totalStudents' => $totalStudents,
            'totalSchedules' => $totalSchedules,
            'todayAttendances' => $todayAttendances,
            'openConflicts' => $openConflicts,
            'activeReservations' => $activeReservations,
            'currentClass' => $currentClass,
            'upcomingSchedules' => $upcomingSchedules,
        ];
    }

    /**
     * @OA\Get(
     * path="/api/admin/dashboard",
     * summary="CU23 - Panel de Control Administrativo (API)",
     * tags={"Admin"},
     * security={{"cookieAuth": {}}},
     * @OA\Response(response=200, description="Estadísticas resumidas para admin (API)")
     * )
     */
    public function indexApi()
    {
        // Esta función mantiene tu endpoint de API original por si lo necesitas
        return response()->json($this->getDashboardData());
    }
}