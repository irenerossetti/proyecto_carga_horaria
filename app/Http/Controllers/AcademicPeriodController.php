<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcademicPeriodController extends Controller
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
     *     path="/api/periods",
     *     summary="CU04 - Listar periodos académicos",
     *     description="Lista todos los periodos académicos ordenados por fecha de creación descendente",
     *     tags={"Periodos Académicos"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de periodos académicos",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="start_date", type="string", format="date", nullable=true),
     *                 @OA\Property(property="end_date", type="string", format="date", nullable=true),
     *                 @OA\Property(property="status", type="string", enum={"draft", "active", "closed"})
     *             )
     *         )
     *     ),
     *     @OA\Response(response=403, description="Forbidden - Solo administradores")
     * )
     */
    public function index()
    {
        $this->ensureAdmin();
        return response()->json(AcademicPeriod::orderBy('created_at', 'desc')->get());
    }

    /**
     * @OA\Post(
     *     path="/api/periods",
     *     summary="CU04 - Crear periodo académico",
     *     description="Crea un nuevo periodo académico con estado 'draft'",
     *     tags={"Periodos Académicos"},
     *     security={{"cookieAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", maxLength=255, example="2025-1"),
     *             @OA\Property(property="start_date", type="string", format="date", nullable=true, example="2025-03-01"),
     *             @OA\Property(property="end_date", type="string", format="date", nullable=true, example="2025-07-31")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Periodo creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="status", type="string", example="draft")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=422, description="Validación fallida")
     * )
     */
    public function store(Request $request)
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $period = AcademicPeriod::create(array_merge($data, ['status' => 'draft']));

        return response()->json($period, 201);
    }

    /**
     * @OA\Post(
     *     path="/api/periods/{id}/activate",
     *     summary="CU04 - Activar periodo académico",
     *     description="Activa un periodo y cierra automáticamente cualquier periodo activo anterior",
     *     tags={"Periodos Académicos"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Periodo activado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Period activated"),
     *             @OA\Property(property="period", type="object")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Periodo no encontrado")
     * )
     */
    public function activate($id)
    {
        $this->ensureAdmin();

        return DB::transaction(function () use ($id) {
            // close any currently active period
            AcademicPeriod::where('status', 'active')->update(['status' => 'closed']);

            $period = AcademicPeriod::findOrFail($id);
            $period->status = 'active';
            $period->save();

            return response()->json(['message' => 'Period activated', 'period' => $period]);
        });
    }

    /**
     * @OA\Post(
     *     path="/api/periods/{id}/close",
     *     summary="CU04 - Cerrar periodo académico",
     *     description="Cierra un periodo académico",
     *     tags={"Periodos Académicos"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Periodo cerrado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Period closed"),
     *             @OA\Property(property="period", type="object")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Periodo no encontrado")
     * )
     */
    public function close($id)
    {
        $this->ensureAdmin();

        $period = AcademicPeriod::findOrFail($id);
        $period->status = 'closed';
        $period->save();

        return response()->json(['message' => 'Period closed', 'period' => $period]);
    }

    /**
     * @OA\Patch(
     *     path="/api/periods/{id}",
     *     summary="CU04 - Actualizar periodo académico",
     *     description="Actualiza los datos de un periodo académico. Si se cambia el status a 'active', cierra automáticamente otros periodos activos",
     *     tags={"Periodos Académicos"},
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
     *             @OA\Property(property="start_date", type="string", format="date", nullable=true),
     *             @OA\Property(property="end_date", type="string", format="date", nullable=true),
     *             @OA\Property(property="status", type="string", enum={"draft", "active", "closed"}, nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Periodo actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="period", type="object")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Periodo no encontrado"),
     *     @OA\Response(response=422, description="Validación fallida - start_date debe ser antes de end_date")
     * )
     */
    public function update(Request $request, $id)
    {
        $this->ensureAdmin();

        $period = AcademicPeriod::findOrFail($id);

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'nullable|in:draft,active,closed',
        ]);

        // validate date order if both provided
        if (!empty($data['start_date']) && !empty($data['end_date'])) {
            if (strtotime($data['start_date']) > strtotime($data['end_date'])) {
                return response()->json(['message' => 'start_date must be before or equal to end_date'], 422);
            }
        }

        // If status is set to active, reuse activation logic to close others
        if (isset($data['status']) && $data['status'] === 'active') {
            return DB::transaction(function () use ($period, $data) {
                AcademicPeriod::where('status', 'active')->update(['status' => 'closed']);

                $period->fill($data);
                $period->status = 'active';
                $period->save();

                return response()->json(['message' => 'Period updated and activated', 'period' => $period]);
            });
        }

        $period->fill($data);
        $period->save();

        return response()->json(['message' => 'Period updated', 'period' => $period]);
    }

    /**
     * @OA\Delete(
     *     path="/api/periods/{id}",
     *     summary="CU04 - Eliminar periodo académico",
     *     description="Elimina un periodo académico",
     *     tags={"Periodos Académicos"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Periodo eliminado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Period deleted"),
     *             @OA\Property(property="id", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Periodo no encontrado")
     * )
     */
    public function destroy($id)
    {
        $this->ensureAdmin();

        $period = AcademicPeriod::findOrFail($id);
        $period->delete();

        return response()->json(['message' => 'Period deleted', 'id' => $id]);
    }
}
