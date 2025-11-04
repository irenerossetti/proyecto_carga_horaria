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

    /**
     * @OA\Get(
     *     path="/api/groups",
     *     summary="CU09 - Listar grupos",
     *     tags={"Grupos"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Response(response=200, description="Lista de grupos"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function index()
    {
        $this->ensureAdmin();
        return response()->json(Group::orderBy('created_at', 'desc')->get());
    }

    /**
     * @OA\Post(
     *     path="/api/groups",
     *     summary="CU09 - Crear grupo",
     *     tags={"Grupos"},
     *     security={{"cookieAuth": {}}},
     *     @OA\RequestBody(required=true, @OA\JsonContent(
     *         required={"subject_id", "code", "name"},
     *         @OA\Property(property="subject_id", type="integer", example=1),
     *         @OA\Property(property="code", type="string", example="SIS-101-A"),
     *         @OA\Property(property="name", type="string", example="Grupo A"),
     *         @OA\Property(property="capacity", type="integer", nullable=true, example=30),
     *         @OA\Property(property="schedule", type="string", nullable=true)
     *     )),
     *     @OA\Response(response=201, description="Grupo creado"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=422, description="Validación fallida")
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/groups/{id}",
     *     summary="CU09 - Ver grupo",
     *     tags={"Grupos"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Grupo encontrado"),
     *     @OA\Response(response=404, description="No encontrado")
     * )
     */
    public function show($id)
    {
        $this->ensureAdmin();
        return response()->json(Group::findOrFail($id));
    }

    /**
     * @OA\Patch(
     *     path="/api/groups/{id}",
     *     summary="CU09 - Actualizar grupo",
     *     tags={"Grupos"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(@OA\JsonContent(
     *         @OA\Property(property="subject_id", type="integer"),
     *         @OA\Property(property="code", type="string"),
     *         @OA\Property(property="name", type="string"),
     *         @OA\Property(property="capacity", type="integer", nullable=true),
     *         @OA\Property(property="schedule", type="string", nullable=true)
     *     )),
     *     @OA\Response(response=200, description="Grupo actualizado"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/groups/{id}",
     *     summary="CU09 - Eliminar grupo",
     *     tags={"Grupos"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Grupo eliminado"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function destroy($id)
    {
        $this->ensureAdmin();
        $group = Group::findOrFail($id);
        $group->delete();
        return response()->json(['message' => 'Group deleted', 'id' => $id]);
    }
}
