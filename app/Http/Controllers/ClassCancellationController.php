<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassCancellation;
use Illuminate\Support\Facades\Auth;

class ClassCancellationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/cancellations",
     *     summary="CU19 - Listar cancelaciones de clases",
     *     description="Lista todas las cancelaciones. Admin ve todas, docentes solo las propias",
     *     tags={"Cancelaciones"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Parameter(name="teacher_id", in="query", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="schedule_id", in="query", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Lista de cancelaciones"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    // List cancellations (admin sees all; teacher sees own cancellations)
    public function index(Request $request)
    {
        $query = ClassCancellation::query();
        if ($request->has('teacher_id')) $query->where('teacher_id', $request->query('teacher_id'));
        if ($request->has('schedule_id')) $query->where('schedule_id', $request->query('schedule_id'));

        $user = $request->user();
        if (! $user->hasRole('administrador')) {
            // teacher: filter to their teacher record if possible
            $teacher = \App\Models\Teacher::where('email', $user->email)->first();
            if ($teacher) {
                $query->where('teacher_id', $teacher->id);
            } else {
                // not admin nor teacher -> empty
                return response()->json([], 200);
            }
        }

        return response()->json($query->with(['schedule','teacher','canceledBy'])->orderBy('created_at','desc')->get());
    }

    /**
     * @OA\Get(
     *     path="/api/cancellations/{id}",
     *     summary="CU19 - Ver cancelación por ID",
     *     description="Obtiene los detalles de una cancelación específica",
     *     tags={"Cancelaciones"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Cancelación encontrada"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="No encontrada")
     * )
     */
    public function show($id)
    {
        $c = ClassCancellation::with(['schedule','teacher','canceledBy'])->findOrFail($id);
        $user = request()->user();
        if (! $user->hasRole('administrador')) {
            $teacher = \App\Models\Teacher::where('email', $user->email)->first();
            if (! $teacher || $teacher->id != $c->teacher_id) {
                return response()->json(['message' => 'Forbidden'], 403);
            }
        }
        return response()->json($c);
    }

    /**
     * @OA\Delete(
     *     path="/api/cancellations/{id}",
     *     summary="CU19 - Eliminar cancelación (Admin)",
     *     description="Elimina un registro de cancelación. Solo administradores",
     *     tags={"Cancelaciones"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Cancelación eliminada"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="No encontrada")
     * )
     */
    // Delete a cancellation (admin only)
    public function destroy($id)
    {
        $user = request()->user();
        if (! $user->hasRole('administrador')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $c = ClassCancellation::findOrFail($id);
        $c->delete();
        return response()->json(['message'=>'Cancellation deleted','id'=>(int)$id]);
    }
}
