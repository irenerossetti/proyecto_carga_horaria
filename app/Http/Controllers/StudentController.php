<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    protected function ensureAdmin()
    {
        $user = auth()->user();
        
        if (!$user) {
            abort(401, 'No autenticado');
        }
        
        if (!$user->relationLoaded('roles')) {
            $user->load('roles');
        }
        
        $hasAdminRole = $user->roles->contains(function ($role) {
            return in_array(strtoupper($role->name), ['ADMIN', 'ADMINISTRADOR']);
        });
        
        if (!$hasAdminRole) {
            abort(403, 'No tienes permisos de administrador');
        }
    }

    /**
     * Listar estudiantes
     */
    public function index()
    {
        $this->ensureAdmin();
        
        // Obtener usuarios con rol ESTUDIANTE usando SQL directo
        $students = DB::select("
            SELECT u.id, u.name, u.email, u.registration_number, u.created_at, u.updated_at
            FROM users u
            INNER JOIN role_user ru ON u.id = ru.user_id
            INNER JOIN roles r ON ru.role_id = r.id
            WHERE r.name = 'ESTUDIANTE'
            ORDER BY u.created_at DESC
        ");
        
        return response()->json($students);
    }

    /**
     * Crear estudiante
     */
    public function store(Request $request)
    {
        try {
            $this->ensureAdmin();

            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'registration_number' => 'required|string|size:9|unique:users,registration_number|regex:/^[0-9]{9}$/',
            ]);

            // Crear usuario
            $userId = DB::table('users')->insertGetId([
                'name' => $data['name'],
                'email' => $data['email'],
                'registration_number' => $data['registration_number'],
                'password' => Hash::make('password123'), // Contraseña por defecto
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Asignar rol ESTUDIANTE
            $roleId = DB::table('roles')->where('name', 'ESTUDIANTE')->value('id');
            if ($roleId) {
                DB::table('role_user')->insert([
                    'user_id' => $userId,
                    'role_id' => $roleId,
                ]);
            }

            // Obtener el estudiante creado
            $student = DB::select("SELECT * FROM users WHERE id = ?", [$userId])[0] ?? null;

            return response()->json([
                'success' => true,
                'message' => 'Estudiante creado exitosamente',
                'data' => $student
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el estudiante: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar estudiante
     */
    public function update(Request $request, $id)
    {
        try {
            $this->ensureAdmin();

            // Verificar que el estudiante existe
            $existing = DB::select("SELECT id FROM users WHERE id = ?", [$id]);
            if (empty($existing)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Estudiante no encontrado'
                ], 404);
            }

            $data = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|unique:users,email,' . $id,
                'registration_number' => 'nullable|string|size:9|unique:users,registration_number,' . $id . '|regex:/^[0-9]{9}$/',
            ]);

            // Construir la consulta de actualización
            $updates = [];
            $params = [];
            
            if (isset($data['name'])) {
                $updates[] = "name = ?";
                $params[] = $data['name'];
            }
            if (isset($data['email'])) {
                $updates[] = "email = ?";
                $params[] = $data['email'];
            }
            if (isset($data['registration_number'])) {
                $updates[] = "registration_number = ?";
                $params[] = $data['registration_number'];
            }
            
            $updates[] = "updated_at = CURRENT_TIMESTAMP";
            $params[] = $id;
            
            DB::update("UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?", $params);
            
            // Obtener el estudiante actualizado
            $student = DB::select("SELECT * FROM users WHERE id = ?", [$id])[0] ?? null;

            return response()->json([
                'success' => true,
                'message' => 'Estudiante actualizado exitosamente',
                'data' => $student
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el estudiante: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar estudiante
     */
    public function destroy($id)
    {
        try {
            $this->ensureAdmin();

            // Verificar que el estudiante existe
            $existing = DB::select("SELECT id FROM users WHERE id = ?", [$id]);
            if (empty($existing)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Estudiante no encontrado'
                ], 404);
            }

            // Eliminar relaciones de roles primero
            DB::delete("DELETE FROM role_user WHERE user_id = ?", [$id]);
            
            // Eliminar el usuario
            DB::delete("DELETE FROM users WHERE id = ?", [$id]);

            return response()->json([
                'success' => true,
                'message' => 'Estudiante eliminado exitosamente',
                'id' => $id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el estudiante: ' . $e->getMessage()
            ], 500);
        }
    }
}
