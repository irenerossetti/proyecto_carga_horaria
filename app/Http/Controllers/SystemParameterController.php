<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemParameter;
use Illuminate\Support\Facades\Auth;

class SystemParameterController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/system-parameters",
     *     summary="CU29 - Listar parámetros del sistema",
     *     tags={"Configuración"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Response(response=200, description="Lista de parámetros")
     * )
     */
    public function index()
    {
        return response()->json(SystemParameter::orderBy('key')->get());
    }

    /**
     * @OA\Get(
     *     path="/api/system-parameters/{key}",
     *     summary="Obtener parámetro por clave",
     *     tags={"Configuración"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Parameter(name="key", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Parámetro encontrado"),
     *     @OA\Response(response=404, description="No encontrado")
     * )
     */
    public function show($key)
    {
        $p = SystemParameter::where('key', $key)->first();
        if (! $p) return response()->json(['message'=>'Not found'], 404);
        return response()->json($p);
    }

    /**
     * @OA\Post(
     *     path="/api/system-parameters",
     *     summary="CU29 - Crear/actualizar parámetro (Admin)",
     *     tags={"Configuración"},
     *     security={{"cookieAuth": {}}},
     *     @OA\RequestBody(@OA\JsonContent(
     *         required={"key","value"},
     *         @OA\Property(property="key", type="string"),
     *         @OA\Property(property="value", type="string"),
     *         @OA\Property(property="type", type="string", example="string"),
     *         @OA\Property(property="description", type="string")
     *     )),
     *     @OA\Response(response=200, description="Parámetro guardado")
     * )
     */
    public function store(Request $request)
    {
        // middleware ensure.admin expected on route
        $data = $request->validate([
            'key' => 'required|string',
            'value' => 'nullable|string',
            'type' => 'nullable|string',
            'description' => 'nullable|string',
        ]);
        $p = SystemParameter::updateOrCreate(['key'=>$data['key']], array_merge($data, ['updated_by'=>Auth::id()]));
        return response()->json($p);
    }
}
