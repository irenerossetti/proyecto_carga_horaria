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

    /**
     * @OA\Get(
     *     path="/api/rooms",
     *     summary="CU10 - Listar aulas",
     *     tags={"Aulas"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Response(response=200, description="Lista de aulas"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function index()
    {
        $this->ensureAdmin();
        return response()->json(Room::orderBy('created_at', 'desc')->get());
    }

    /**
     * @OA\Post(
     *     path="/api/rooms",
     *     summary="CU10 - Crear aula",
     *     tags={"Aulas"},
     *     security={{"cookieAuth": {}}},
     *     @OA\RequestBody(required=true, @OA\JsonContent(
     *         required={"name"},
     *         @OA\Property(property="name", type="string", example="Aula 101"),
     *         @OA\Property(property="capacity", type="integer", nullable=true, example=40),
     *         @OA\Property(property="location", type="string", nullable=true, example="Edificio A, Piso 1"),
     *         @OA\Property(property="resources", type="string", nullable=true)
     *     )),
     *     @OA\Response(response=201, description="Aula creada"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/rooms/{id}",
     *     summary="CU10 - Ver aula",
     *     tags={"Aulas"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Aula encontrada"),
     *     @OA\Response(response=404, description="No encontrada")
     * )
     */
    public function show($id)
    {
        $this->ensureAdmin();
        return response()->json(Room::findOrFail($id));
    }

    /**
     * @OA\Get(
     *     path="/api/rooms/{id}/equipment",
     *     summary="CU11 - Ver equipamiento del aula",
     *     tags={"Aulas"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Equipamiento del aula",
     *         @OA\JsonContent(@OA\Property(property="equipment", type="array", @OA\Items(type="string")))
     *     ),
     *     @OA\Response(response=404, description="No encontrada")
     * )
     */
    // CU11 - Obtener equipamiento del aula
    public function equipment($id)
    {
        $this->ensureAdmin();
        $room = Room::findOrFail($id);
        $resources = $room->resources ? json_decode($room->resources, true) : [];
        return response()->json(['equipment' => $resources]);
    }

    /**
     * @OA\Patch(
     *     path="/api/rooms/{id}",
     *     summary="CU10 - Actualizar aula",
     *     tags={"Aulas"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(@OA\JsonContent(
     *         @OA\Property(property="name", type="string"),
     *         @OA\Property(property="capacity", type="integer", nullable=true),
     *         @OA\Property(property="location", type="string", nullable=true),
     *         @OA\Property(property="resources", type="string", nullable=true)
     *     )),
     *     @OA\Response(response=200, description="Aula actualizada"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/api/rooms/{id}/equipment",
     *     summary="CU11 - Actualizar equipamiento del aula",
     *     tags={"Aulas"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true, @OA\JsonContent(
     *         required={"equipment"},
     *         @OA\Property(
     *             property="equipment",
     *             type="array",
     *             @OA\Items(type="string"),
     *             example={"Proyector", "Pizarra digital", "30 computadoras"}
     *         )
     *     )),
     *     @OA\Response(response=200, description="Equipamiento actualizado"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/rooms/{id}",
     *     summary="CU10 - Eliminar aula",
     *     tags={"Aulas"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Aula eliminada"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function destroy($id)
    {
        $this->ensureAdmin();
        $room = Room::findOrFail($id);
        $room->delete();
        return response()->json(['message' => 'Room deleted', 'id' => $id]);
    }
}
