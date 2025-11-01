<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
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
        return response()->json(Subject::orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'code' => 'required|string|unique:subjects,code',
            'name' => 'required|string',
            'credits' => 'nullable|integer',
            'description' => 'nullable|string',
        ]);

        $subject = Subject::create($data);
        return response()->json($subject, 201);
    }

    public function show($id)
    {
        $this->ensureAdmin();
        return response()->json(Subject::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $this->ensureAdmin();
        $subject = Subject::findOrFail($id);

        $data = $request->validate([
            'code' => 'sometimes|required|string|unique:subjects,code,' . $id,
            'name' => 'sometimes|required|string',
            'credits' => 'nullable|integer',
            'description' => 'nullable|string',
        ]);

        $subject->fill($data);
        $subject->save();

        return response()->json($subject);
    }

    public function destroy($id)
    {
        $this->ensureAdmin();
        $subject = Subject::findOrFail($id);
        $subject->delete();
        return response()->json(['message' => 'Subject deleted', 'id' => $id]);
    }
}
