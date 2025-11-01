<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    protected function ensureAdmin()
    {
        $user = auth()->user();
        if (!$user || ! (
            ($user->hasRole('ADMIN') || $user->hasRole('administrador'))
            || (property_exists($user, 'is_admin') ? $user->is_admin : ($user->is_admin ?? false))
        )) {
            abort(response()->json(['message' => 'Forbidden'], 403));
        }
    }

    public function index()
    {
        $this->ensureAdmin();
        return response()->json(Teacher::orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email',
            'dni' => 'nullable|string',
            'phone' => 'nullable|string',
            'department' => 'nullable|string',
        ]);

        $teacher = Teacher::create($data);
        return response()->json($teacher, 201);
    }

    public function show($id)
    {
        $this->ensureAdmin();
        $t = Teacher::findOrFail($id);
        return response()->json($t);
    }

    // CU07 - Docente visualiza su propio perfil
    public function me()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $teacher = Teacher::where('email', $user->email)->first();
        if (!$teacher) {
            return response()->json(['message' => 'Teacher profile not found'], 404);
        }

        return response()->json($teacher);
    }

    // CU07 - Docente edita su propio perfil
    public function updateMe(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $teacher = Teacher::where('email', $user->email)->firstOrFail();

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:teachers,email,' . $teacher->id,
            'dni' => 'nullable|string',
            'phone' => 'nullable|string',
            'department' => 'nullable|string',
        ]);

        $teacher->fill($data);
        $teacher->save();

        return response()->json($teacher);
    }

    public function update(Request $request, $id)
    {
        $this->ensureAdmin();
        $teacher = Teacher::findOrFail($id);

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:teachers,email,' . $id,
            'dni' => 'nullable|string',
            'phone' => 'nullable|string',
            'department' => 'nullable|string',
        ]);

        $teacher->fill($data);
        $teacher->save();

        return response()->json($teacher);
    }

    public function destroy($id)
    {
        $this->ensureAdmin();
        $teacher = Teacher::findOrFail($id);
        $teacher->delete();
        return response()->json(['message' => 'Teacher deleted', 'id' => $id]);
    }
}
