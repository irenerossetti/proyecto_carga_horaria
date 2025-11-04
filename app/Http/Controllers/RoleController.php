<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
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
     *     path="/api/roles",
     *     summary="CU05 - Listar roles",
     *     description="Lista todos los roles disponibles en el sistema",
     *     tags={"Roles"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de roles",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=403, description="Forbidden - Solo administradores")
     * )
     */
    public function index()
    {
        $this->ensureAdmin();
        $roles = Role::all()->map(function ($r) {
            return ['id' => $r->rol_id, 'name' => $r->nombre];
        });
        return response()->json($roles);
    }

    /**
     * @OA\Post(
     *     path="/api/roles",
     *     summary="CU05 - Crear rol",
     *     description="Crea un nuevo rol en el sistema",
     *     tags={"Roles"},
     *     security={{"cookieAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="coordinador")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Rol creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="rol_id", type="integer"),
     *             @OA\Property(property="nombre", type="string")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=422, description="Validación fallida")
     * )
     */
    public function store(Request $request)
    {
        $this->ensureAdmin();
        $data = $request->validate(['name' => 'required|string']);
        // map API 'name' to DB 'nombre'
        $role = Role::create(['nombre' => $data['name']]);
        return response()->json($role, 201);
    }

    /**
     * @OA\Post(
     *     path="/api/users/{id}/roles",
     *     summary="CU05 - Asignar roles a usuario",
     *     description="Asigna uno o más roles a un usuario específico",
     *     tags={"Roles"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del usuario",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"roles"},
     *             @OA\Property(
     *                 property="roles",
     *                 type="array",
     *                 @OA\Items(type="string"),
     *                 example={"docente", "coordinador"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Roles asignados exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="roles", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Usuario o roles no encontrados")
     * )
     */
    public function assignToUser(Request $request, $userId)
    {
        $this->ensureAdmin();

        $user = User::findOrFail($userId);
        $data = $request->validate(['roles' => 'required|array']);

        // find roles by nombre (legacy column)
        $roles = Role::whereIn('nombre', $data['roles'])->get();

        if (Schema::hasTable('role_user')) {
            $user->roles()->sync($roles->pluck('rol_id')->toArray());
            return response()->json(['message' => 'Roles assigned', 'roles' => $user->roles()->pluck('nombre')]);
        }

        // fallback: legacy single-role column on users (rol_id)
        if ($roles->isNotEmpty()) {
            $user->rol_id = $roles->first()->rol_id;
            $user->save();
            return response()->json(['message' => 'Role assigned (legacy)', 'roles' => [$roles->first()->nombre]]);
        }

        return response()->json(['message' => 'No roles found to assign'], 404);
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}/roles",
     *     summary="CU05 - Obtener roles de usuario",
     *     description="Lista los roles asignados a un usuario específico",
     *     tags={"Roles"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del usuario",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de roles del usuario",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(type="string"),
     *             example={"docente", "coordinador"}
     *         )
     *     ),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Usuario no encontrado")
     * )
     */
    public function getUserRoles($userId)
    {
        $this->ensureAdmin();
        $user = User::findOrFail($userId);
        if (Schema::hasTable('role_user')) {
            return response()->json($user->roles()->pluck('nombre'));
        }

        // fallback to legacy single-role
        if (!empty($user->rol_id)) {
            $role = Role::find($user->rol_id);
            return response()->json($role ? [$role->nombre] : []);
        }

        return response()->json([]);
    }

    /**
     * @OA\Patch(
     *     path="/api/roles/{id}",
     *     summary="CU05 - Actualizar rol",
     *     description="Actualiza el nombre de un rol existente",
     *     tags={"Roles"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="coordinador_académico")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rol actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="name", type="string")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Rol no encontrado")
     * )
     */
    public function update(Request $request, $id)
    {
        $this->ensureAdmin();
        $data = $request->validate(['name' => 'required|string']);

        $role = Role::find($id);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        $role->nombre = $data['name'];
        $role->save();

        return response()->json(['id' => $role->rol_id, 'name' => $role->nombre]);
    }

    /**
     * @OA\Delete(
     *     path="/api/roles/{id}",
     *     summary="CU05 - Eliminar rol",
     *     description="Elimina un rol si no está asignado a ningún usuario",
     *     tags={"Roles"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rol eliminado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Role deleted"),
     *             @OA\Property(property="id", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Rol no encontrado"),
     *     @OA\Response(response=409, description="Rol está asignado a usuarios, no se puede eliminar")
     * )
     */
    public function destroy($id)
    {
        $this->ensureAdmin();

        $role = Role::find($id);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        // If pivot exists, prevent deletion while assigned
        if (Schema::hasTable('role_user')) {
            $assigned = DB::table('role_user')->where('role_id', $role->rol_id)->count();
            if ($assigned > 0) {
                return response()->json(['message' => 'Role is assigned to users, cannot delete'], 409);
            }
        }

        // Check legacy usuarios table
        if (Schema::hasTable('usuarios')) {
            $assignedLegacy = DB::table('usuarios')->where('rol_id', $role->rol_id)->count();
            if ($assignedLegacy > 0) {
                return response()->json(['message' => 'Role is assigned to usuarios, cannot delete'], 409);
            }
        }

        $role->delete();
        return response()->json(['message' => 'Role deleted', 'id' => $id]);
    }
}
