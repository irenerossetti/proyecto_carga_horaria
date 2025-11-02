<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TeacherAssignment;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;

class TeacherAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $query = TeacherAssignment::query();
        if ($request->has('teacher_id')) {
            $query->where('teacher_id', $request->query('teacher_id'));
        }
        if ($request->has('period_id')) {
            $query->where('period_id', $request->query('period_id'));
        }
        return response()->json($query->with(['subject','group','teacher'])->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'teacher_id' => 'required|integer|exists:teachers,id',
            'subject_id' => 'nullable|integer|exists:subjects,id',
            'group_id' => 'nullable|integer|exists:groups,id',
            'period_id' => 'nullable|integer|exists:academic_periods,id',
        ]);

        $data['assigned_by'] = Auth::id();

        $assignment = TeacherAssignment::create($data);

        return response()->json($assignment, 201);
    }

    public function show($id)
    {
        $assignment = TeacherAssignment::with(['subject','group','teacher'])->findOrFail($id);
        return response()->json($assignment);
    }

    public function update(Request $request, $id)
    {
        $assignment = TeacherAssignment::findOrFail($id);
        $data = $request->validate([
            'subject_id' => 'nullable|integer|exists:subjects,id',
            'group_id' => 'nullable|integer|exists:groups,id',
            'period_id' => 'nullable|integer|exists:academic_periods,id',
        ]);
        $assignment->update($data);
        return response()->json($assignment);
    }

    public function destroy($id)
    {
        $assignment = TeacherAssignment::findOrFail($id);
        $assignment->delete();
        return response()->json(['message' => 'Assignment deleted', 'id' => (int) $id]);
    }
}
