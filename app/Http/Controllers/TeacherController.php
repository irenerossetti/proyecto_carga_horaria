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
        return response()->json(Teacher::orderBy('created_at', 'desc')->get());
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
        $this->ensureAdmin();
        $teacher = Teacher::findOrFail($id);
        $teacher->delete();
        return response()->json(['message' => 'Teacher deleted', 'id' => $id]);
    }
}
