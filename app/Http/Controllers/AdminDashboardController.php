<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Attendance;
use App\Models\Reservation;
use App\Models\Conflict;

class AdminDashboardController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/admin/dashboard",
     *     summary="CU23 - Panel de Control Administrativo",
     *     tags={"Admin"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Response(response=200, description="Estadísticas resumidas para admin")
     * )
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if (! $user || ! $user->hasRole('administrador')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $totalTeachers = Teacher::count();
        $totalRooms = Room::count();
        $totalSchedules = Schedule::count();
        $todayAttendances = Attendance::whereDate('date', now()->toDateString())->count();
        $openConflicts = Conflict::where('resolved', false)->count();
        $activeReservations = Reservation::where('expires_at', '>', now())->count();

        return response()->json([
            'total_teachers' => $totalTeachers,
            'total_rooms' => $totalRooms,
            'total_schedules' => $totalSchedules,
            'today_attendances' => $todayAttendances,
            'open_conflicts' => $openConflicts,
            'active_reservations' => $activeReservations,
        ]);
    }
}
