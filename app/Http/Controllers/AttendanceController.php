<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use App\Models\Teacher;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::query();
        if ($request->has('teacher_id')) $query->where('teacher_id', $request->query('teacher_id'));
        if ($request->has('date')) $query->where('date', $request->query('date'));
        $user = $request->user();
        // If not admin and requesting all, restrict to own teacher records
        if (! $user->hasRole('administrador')) {
            if ($user->hasRole('docente')) {
                $teacher = Teacher::where('email', $user->email)->first();
                if ($teacher) {
                    $query->where('teacher_id', $teacher->id);
                }
            }
        }

        return response()->json($query->with(['teacher','schedule'])->orderBy('date','desc')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'teacher_id' => 'required|integer|exists:teachers,id',
            'schedule_id' => 'nullable|integer|exists:schedules,id',
            'date' => 'required|date',
            'time' => 'nullable|date_format:H:i',
            'status' => 'required|string|in:present,absent,late',
            'notes' => 'nullable|string',
        ]);

        $user = $request->user();
        $isAdmin = $user->hasRole('administrador');

        if (! $isAdmin) {
            // teacher can only record for themselves (match by email)
            $teacher = Teacher::where('email', $user->email)->first();
            if (! $teacher || $teacher->id != $data['teacher_id']) {
                return response()->json(['message' => 'Forbidden - cannot record attendance for another teacher'], 403);
            }
        }

        $data['recorded_by'] = Auth::id();

        $attendance = Attendance::create($data);
        return response()->json($attendance, 201);
    }

    public function show($id)
    {
        $att = Attendance::with(['teacher','schedule'])->findOrFail($id);
        return response()->json($att);
    }

    public function update(Request $request, $id)
    {
        $att = Attendance::findOrFail($id);
        $data = $request->validate([
            'status' => 'nullable|string|in:present,absent,late',
            'notes' => 'nullable|string',
            'time' => 'nullable|date_format:H:i',
        ]);
        $user = $request->user();
        if (! $user->hasRole('administrador')) {
            // teacher can only update own attendance
            $teacher = Teacher::where('email', $user->email)->first();
            if (! $teacher || $teacher->id != $att->teacher_id) {
                return response()->json(['message' => 'Forbidden - cannot update this record'], 403);
            }
        }

        $att->update($data);
        return response()->json($att);
    }

    public function destroy($id)
    {
        $att = Attendance::findOrFail($id);
        $att->delete();
        return response()->json(['message'=>'Attendance deleted','id'=>(int)$id]);
    }
}
