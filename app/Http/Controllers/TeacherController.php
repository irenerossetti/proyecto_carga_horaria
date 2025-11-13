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

    /**
     * @OA\Get(
     *     path="/api/teachers",
     *     summary="CU06 - Listar docentes",
     *     description="Lista todos los docentes registrados en el sistema",
     *     tags={"Docentes"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de docentes",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="email", type="string", format="email"),
     *                 @OA\Property(property="dni", type="string", nullable=true),
     *                 @OA\Property(property="phone", type="string", nullable=true),
     *                 @OA\Property(property="department", type="string", nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=403, description="Forbidden - Solo administradores")
     * )
     */
    public function index()
    {
        $this->ensureAdmin();
        
        // Usar SQL directo para evitar caché de PostgreSQL
        $teachers = \DB::select('SELECT * FROM teachers ORDER BY created_at DESC');
        
        return response()->json($teachers);
    }

    /**
     * @OA\Post(
     *     path="/api/teachers",
     *     summary="CU06 - Crear docente",
     *     description="Registra un nuevo docente en el sistema",
     *     tags={"Docentes"},
     *     security={{"cookieAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email"},
     *             @OA\Property(property="name", type="string", maxLength=255, example="Juan Pérez"),
     *             @OA\Property(property="email", type="string", format="email", example="juan.perez@universidad.edu"),
     *             @OA\Property(property="dni", type="string", nullable=true, example="12345678"),
     *             @OA\Property(property="phone", type="string", nullable=true, example="+591 70123456"),
     *             @OA\Property(property="department", type="string", nullable=true, example="Ingeniería de Sistemas")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Docente creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=422, description="Validación fallida - Email duplicado")
     * )
     */
    public function store(Request $request)
    {
        try {
            $this->ensureAdmin();

            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:teachers,email',
                'code' => 'nullable|string',
                'phone' => 'nullable|string',
                'department' => 'nullable|string',
                'specialization' => 'nullable|string',
            ]);

            // Por ahora, user_id será el ID del usuario autenticado
            $userId = auth()->id();

            // Insertar usando SQL directo
            \DB::insert(
                'INSERT INTO teachers (user_id, name, email, dni, phone, department, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)',
                [
                    $userId,
                    $data['name'],
                    $data['email'],
                    $data['code'] ?? null,
                    $data['phone'] ?? null,
                    $data['department'] ?? null
                ]
            );

            // Obtener el docente recién creado
            $teacher = \DB::select('SELECT * FROM teachers WHERE email = ? ORDER BY id DESC LIMIT 1', [$data['email']])[0] ?? null;

            return response()->json([
                'success' => true,
                'message' => 'Docente creado exitosamente',
                'data' => $teacher
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
                'message' => 'Error al crear el docente: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/teachers/{id}",
     *     summary="CU06 - Ver docente",
     *     description="Obtiene los detalles de un docente específico",
     *     tags={"Docentes"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Docente encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="dni", type="string", nullable=true),
     *             @OA\Property(property="phone", type="string", nullable=true),
     *             @OA\Property(property="department", type="string", nullable=true)
     *         )
     *     ),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Docente no encontrado")
     * )
     */
    public function show($id)
    {
        $this->ensureAdmin();
        $t = Teacher::findOrFail($id);
        return response()->json($t);
    }

    /**
     * @OA\Get(
     *     path="/api/teachers/me",
     *     summary="CU07 - Ver mi perfil de docente",
     *     description="El docente autenticado visualiza su propio perfil",
     *     tags={"Docentes"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Perfil del docente",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="dni", type="string", nullable=true),
     *             @OA\Property(property="phone", type="string", nullable=true),
     *             @OA\Property(property="department", type="string", nullable=true)
     *         )
     *     ),
     *     @OA\Response(response=401, description="No autenticado"),
     *     @OA\Response(response=404, description="Perfil de docente no encontrado")
     * )
     */
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

    /**
     * @OA\Patch(
     *     path="/api/teachers/me",
     *     summary="CU07 - Actualizar mi perfil de docente",
     *     description="El docente autenticado edita su propio perfil",
     *     tags={"Docentes"},
     *     security={{"cookieAuth": {}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", maxLength=255),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="dni", type="string", nullable=true),
     *             @OA\Property(property="phone", type="string", nullable=true),
     *             @OA\Property(property="department", type="string", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Perfil actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string")
     *         )
     *     ),
     *     @OA\Response(response=401, description="No autenticado"),
     *     @OA\Response(response=422, description="Validación fallida")
     * )
     */
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

    /**
     * @OA\Patch(
     *     path="/api/teachers/{id}",
     *     summary="CU06 - Actualizar docente (Admin)",
     *     description="Actualiza los datos de un docente (solo administradores)",
     *     tags={"Docentes"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", maxLength=255),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="dni", type="string", nullable=true),
     *             @OA\Property(property="phone", type="string", nullable=true),
     *             @OA\Property(property="department", type="string", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Docente actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Docente no encontrado"),
     *     @OA\Response(response=422, description="Validación fallida")
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $this->ensureAdmin();

            // Verificar que el docente existe
            $existing = \DB::select("SELECT id FROM teachers WHERE id = ?", [$id]);
            if (empty($existing)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Docente no encontrado'
                ], 404);
            }

            $data = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|unique:teachers,email,' . $id,
                'code' => 'nullable|string',
                'phone' => 'nullable|string',
                'department' => 'nullable|string',
                'specialization' => 'nullable|string',
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
            if (isset($data['code'])) {
                $updates[] = "dni = ?";
                $params[] = $data['code'];
            }
            if (isset($data['phone'])) {
                $updates[] = "phone = ?";
                $params[] = $data['phone'];
            }
            if (isset($data['department'])) {
                $updates[] = "department = ?";
                $params[] = $data['department'];
            }
            
            $updates[] = "updated_at = CURRENT_TIMESTAMP";
            $params[] = $id;
            
            \DB::update("UPDATE teachers SET " . implode(', ', $updates) . " WHERE id = ?", $params);
            
            // Obtener el docente actualizado
            $teacher = \DB::select("SELECT * FROM teachers WHERE id = ?", [$id])[0] ?? null;

            return response()->json([
                'success' => true,
                'message' => 'Docente actualizado exitosamente',
                'data' => $teacher
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
                'message' => 'Error al actualizar el docente: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/teachers/{id}",
     *     summary="CU06 - Eliminar docente",
     *     description="Elimina un docente del sistema (solo administradores)",
     *     tags={"Docentes"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Docente eliminado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Teacher deleted"),
     *             @OA\Property(property="id", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Docente no encontrado")
     * )
     */
    public function destroy($id)
    {
        try {
            $this->ensureAdmin();

            // Verificar que el docente existe
            $existing = \DB::select("SELECT id FROM teachers WHERE id = ?", [$id]);
            if (empty($existing)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Docente no encontrado'
                ], 404);
            }

            // Eliminar el docente
            \DB::delete("DELETE FROM teachers WHERE id = ?", [$id]);

            return response()->json([
                'success' => true,
                'message' => 'Docente eliminado exitosamente',
                'id' => $id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el docente: ' . $e->getMessage()
            ], 500);
        }
    }
}
