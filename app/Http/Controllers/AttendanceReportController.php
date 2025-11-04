<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Teacher;
use App\Models\Group;

class AttendanceReportController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/reports/attendances/teacher/{id}",
     *     summary="CU24 - Visualizar asistencia por docente",
     *     tags={"Reportes"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="from", in="query", @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="to", in="query", @OA\Schema(type="string", format="date")),
     *     @OA\Response(response=200, description="Reporte de asistencia por docente")
     * )
     */
    public function byTeacher(Request $request, $id)
    {
        $user = $request->user();
        if (! $user || ! $user->hasRole('administrador')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $teacher = Teacher::findOrFail($id);

        $from = $request->query('from');
        $to = $request->query('to');

        $q = Attendance::where('teacher_id', $teacher->id);
        if ($from) $q->where('date', '>=', $from);
        if ($to) $q->where('date', '<=', $to);

        $attendances = $q->orderBy('date','desc')->get();

        $summary = [
            'teacher' => ['id'=>$teacher->id,'name'=>$teacher->name ?? null],
            'total' => $attendances->count(),
            'present' => $attendances->where('status','present')->count(),
            'absent' => $attendances->where('status','absent')->count(),
            'late' => $attendances->where('status','late')->count(),
            'records' => $attendances,
        ];

        return response()->json($summary);
    }

    /**
     * @OA\Get(
     *     path="/api/reports/attendances/group/{id}",
     *     summary="CU25 - Visualizar asistencia por grupo",
     *     tags={"Reportes"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="from", in="query", @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="to", in="query", @OA\Schema(type="string", format="date")),
     *     @OA\Response(response=200, description="Reporte de asistencia por grupo")
     * )
     */
    public function byGroup(Request $request, $id)
    {
        // Admin or coordinator (use teacher_or_admin middleware in route)
        $user = $request->user();
        if (! $user) return response()->json(['message' => 'Forbidden'], 403);

        $group = \App\Models\Group::findOrFail($id);

        $from = $request->query('from');
        $to = $request->query('to');

        // Find schedules for the group then attendances
        $scheduleIds = \App\Models\Schedule::where('group_id', $group->id)->pluck('id');

        $q = Attendance::whereIn('schedule_id', $scheduleIds->toArray());
        if ($from) $q->where('date', '>=', $from);
        if ($to) $q->where('date', '<=', $to);

        $attendances = $q->orderBy('date','desc')->get();

        $summary = [
            'group' => ['id'=>$group->id,'name'=>$group->name ?? null],
            'total' => $attendances->count(),
            'present' => $attendances->where('status','present')->count(),
            'absent' => $attendances->where('status','absent')->count(),
            'late' => $attendances->where('status','late')->count(),
            'records' => $attendances,
        ];

        return response()->json($summary);
    }
}
