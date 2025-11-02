<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
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
        return response()->json(Room::orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'name' => 'required|string',
            'capacity' => 'nullable|integer',
            'location' => 'nullable|string',
            'resources' => 'nullable|string',
        ]);

        $room = Room::create($data);
        return response()->json($room, 201);
    }

    public function show($id)
    {
        $this->ensureAdmin();
        return response()->json(Room::findOrFail($id));
    }

    // CU11 - Obtener equipamiento del aula
    public function equipment($id)
    {
        $this->ensureAdmin();
        $room = Room::findOrFail($id);
        $resources = $room->resources ? json_decode($room->resources, true) : [];
        return response()->json(['equipment' => $resources]);
    }

    public function update(Request $request, $id)
    {
        $this->ensureAdmin();
        $room = Room::findOrFail($id);

        $data = $request->validate([
            'name' => 'sometimes|required|string',
            'capacity' => 'nullable|integer',
            'location' => 'nullable|string',
            'resources' => 'nullable|string',
        ]);

        $room->fill($data);
        $room->save();

        return response()->json($room);
    }

    // CU11 - Actualizar equipamiento del aula (sustituye/setea JSON)
    public function updateEquipment(Request $request, $id)
    {
        $this->ensureAdmin();
        $room = Room::findOrFail($id);

        $data = $request->validate([
            'equipment' => 'required|array',
        ]);

        $room->resources = json_encode($data['equipment']);
        $room->save();

        return response()->json(['id' => $room->id, 'equipment' => $data['equipment']]);
    }

    public function destroy($id)
    {
        $this->ensureAdmin();
        $room = Room::findOrFail($id);
        $room->delete();
        return response()->json(['message' => 'Room deleted', 'id' => $id]);
    }
}
