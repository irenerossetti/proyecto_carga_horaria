<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\ClassCancellation;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/reservations/available",
     *     summary="CU22 - Consultar aulas liberadas por anulación",
     *     tags={"Reservas"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Parameter(name="date", in="query", @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="start_time", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="end_time", in="query", @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Lista de aulas liberadas por anulaciones")
     * )
     */
    public function available(Request $request)
    {
        // Teachers and admins can query
        $user = $request->user();
        if (! $user || (! $user->hasRole('docente') && ! $user->hasRole('administrador'))) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'date' => 'nullable|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
        ]);

        // Find cancellations for the given date/time (if provided), otherwise return recent cancellations
        $query = ClassCancellation::query();
        if (! empty($data['date'])) {
            $query->whereHas('schedule', function($q) use ($data) {
                $q->where('date', $data['date']);
            });
        }
        $cancellations = $query->with('schedule.room')->get();

        $rooms = [];
        foreach ($cancellations as $c) {
            $room = $c->schedule->room ?? null;
            if ($room) {
                $rooms[$room->id] = $room;
            }
        }

        return response()->json(array_values($rooms));
    }

    /**
     * @OA\Post(
     *     path="/api/reservations",
     *     summary="CU22 - Reservar aula liberada por anulación",
     *     tags={"Reservas"},
     *     security={{"cookieAuth": {}}},
     *     @OA\RequestBody(required=true, @OA\JsonContent(
     *         required={"room_id","reserved_at","expires_at"},
     *         @OA\Property(property="room_id", type="integer"),
     *         @OA\Property(property="schedule_id", type="integer", nullable=true),
     *         @OA\Property(property="reserved_at", type="string", format="date-time"),
     *         @OA\Property(property="expires_at", type="string", format="date-time"),
     *         @OA\Property(property="notes", type="string", nullable=true)
     *     )),
     *     @OA\Response(response=201, description="Reserva creada"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function store(Request $request)
    {
        $user = $request->user();
        if (! $user || (! $user->hasRole('docente') && ! $user->hasRole('administrador'))) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'room_id' => 'required|integer|exists:rooms,id',
            'schedule_id' => 'nullable|integer|exists:schedules,id',
            'reserved_at' => 'required|date_format:Y-m-d H:i:s',
            'expires_at' => 'required|date_format:Y-m-d H:i:s',
            'notes' => 'nullable|string',
        ]);

        $reservation = Reservation::create([
            'room_id' => $data['room_id'],
            'schedule_id' => $data['schedule_id'] ?? null,
            'teacher_id' => $user->hasRole('docente') ? (\App\Models\Teacher::where('email',$user->email)->first()->id ?? null) : ($data['teacher_id'] ?? $user->id),
            'reserved_at' => $data['reserved_at'],
            'expires_at' => $data['expires_at'],
            'notes' => $data['notes'] ?? null,
        ]);

        return response()->json($reservation, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/reservations",
     *     summary="Listar reservas del usuario (docente) o todas (admin)",
     *     tags={"Reservas"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Response(response=200, description="Lista de reservas")
     * )
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if (! $user) return response()->json(['message' => 'Forbidden'], 403);

        if ($user->hasRole('administrador')) {
            $res = Reservation::with(['room','teacher'])->orderBy('created_at','desc')->get();
        } else {
            $teacher = \App\Models\Teacher::where('email',$user->email)->first();
            $res = $teacher ? Reservation::with('room')->where('teacher_id',$teacher->id)->get() : [];
        }

        return response()->json($res);
    }
}
