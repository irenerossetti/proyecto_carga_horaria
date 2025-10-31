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

    public function index()
    {
        $this->ensureAdmin();
        $roles = Role::all()->map(function ($r) {
            return ['id' => $r->rol_id, 'name' => $r->nombre];
        });
        return response()->json($roles);
    }

    public function store(Request $request)
    {
        $this->ensureAdmin();
        $data = $request->validate(['name' => 'required|string']);
        // map API 'name' to DB 'nombre'
        $role = Role::create(['nombre' => $data['name']]);
        return response()->json($role, 201);
    }

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
