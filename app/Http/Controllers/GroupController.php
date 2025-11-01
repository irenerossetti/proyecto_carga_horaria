<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
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
        return response()->json(Group::orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'subject_id' => 'required|integer',
            'code' => 'required|string',
            'name' => 'required|string',
            'capacity' => 'nullable|integer',
            'schedule' => 'nullable|string',
        ]);

        $group = Group::create($data);
        return response()->json($group, 201);
    }

    public function show($id)
    {
        $this->ensureAdmin();
        return response()->json(Group::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $this->ensureAdmin();
        $group = Group::findOrFail($id);

        $data = $request->validate([
            'subject_id' => 'sometimes|required|integer',
            'code' => 'sometimes|required|string',
            'name' => 'sometimes|required|string',
            'capacity' => 'nullable|integer',
            'schedule' => 'nullable|string',
        ]);

        $group->fill($data);
        $group->save();

        return response()->json($group);
    }

    public function destroy($id)
    {
        $this->ensureAdmin();
        $group = Group::findOrFail($id);
        $group->delete();
        return response()->json(['message' => 'Group deleted', 'id' => $id]);
    }
}
