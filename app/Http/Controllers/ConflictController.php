<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Conflict;
use Illuminate\Support\Facades\Auth;

class ConflictController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/conflicts",
     *     summary="CU20 - Visualizar panel de conflictos horarios",
     *     tags={"Conflictos"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Parameter(name="only_unresolved", in="query", @OA\Schema(type="boolean")),
     *     @OA\Response(response=200, description="Lista de conflictos detectados (y resueltos)" )
     * )
     */
    public function index(Request $request)
    {
        // Only admins should access this in routes (middleware). We still enforce check.
        $user = $request->user();
        if (! $user || ! $user->hasRole('administrador')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        // Load all schedules and detect overlaps by day, room, teacher.
        $schedules = Schedule::select('id','day','date','start_time','end_time','teacher_id','room_id')->get();

        $conflicts = [];

        // naive O(n^2) overlap detection — acceptable for modest dataset
        for ($i = 0; $i < $schedules->count(); $i++) {
            for ($j = $i + 1; $j < $schedules->count(); $j++) {
                $a = $schedules[$i];
                $b = $schedules[$j];

                // Consider same day or same date
                if (! empty($a->date) && ! empty($b->date) && $a->date !== $b->date) continue;
                if (! empty($a->day) && ! empty($b->day) && $a->day !== $b->day) continue;

                // Check time overlap
                if (empty($a->start_time) || empty($a->end_time) || empty($b->start_time) || empty($b->end_time)) continue;
                if (! ($a->start_time < $b->end_time && $a->end_time > $b->start_time)) continue;

                $type = null;
                if ($a->teacher_id && $b->teacher_id && $a->teacher_id == $b->teacher_id) $type = 'teacher';
                if ($a->room_id && $b->room_id && $a->room_id == $b->room_id) {
                    $type = $type ? $type.'|room' : 'room';
                }

                if ($type) {
                    $conflicts[] = [
                        'schedule_a_id' => $a->id,
                        'schedule_b_id' => $b->id,
                        'type' => $type,
                        'resolved' => false,
                    ];
                }
            }
        }

        // Merge with persisted resolutions
        $persisted = Conflict::all()->keyBy(function($c){
            return $c->schedule_a_id.':'.$c->schedule_b_id;
        });

        $results = [];
        foreach ($conflicts as $c) {
            $key = $c['schedule_a_id'].':'.$c['schedule_b_id'];
            if (isset($persisted[$key])) {
                $rec = $persisted[$key];
                $results[] = array_merge($c, [
                    'resolved' => (bool) $rec->resolved,
                    'resolution_note' => $rec->resolution_note,
                    'resolved_by' => $rec->resolved_by,
                    'resolved_at' => $rec->resolved_at,
                ]);
            } else {
                $results[] = $c;
            }
        }

        if ($request->query('only_unresolved')) {
            $results = array_filter($results, function($r){ return empty($r['resolved']); });
            $results = array_values($results);
        }

        return response()->json($results);
    }

    /**
     * @OA\Post(
     *     path="/api/conflicts",
     *     summary="Marcar/Resolver un conflicto manualmente",
     *     tags={"Conflictos"},
     *     security={{"cookieAuth": {}}},
     *     @OA\RequestBody(required=true, @OA\JsonContent(
     *         required={"schedule_a_id","schedule_b_id"},
     *         @OA\Property(property="schedule_a_id", type="integer"),
     *         @OA\Property(property="schedule_b_id", type="integer"),
     *         @OA\Property(property="resolution_note", type="string", nullable=true)
     *     )),
     *     @OA\Response(response=201, description="Conflicto marcado/creado"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function store(Request $request)
    {
        $user = $request->user();
        if (! $user || ! $user->hasRole('administrador')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'schedule_a_id' => 'required|integer|exists:schedules,id',
            'schedule_b_id' => 'required|integer|exists:schedules,id',
            'resolution_note' => 'nullable|string',
        ]);

        // Ensure consistent ordering to avoid duplicate pairs
        if ($data['schedule_a_id'] > $data['schedule_b_id']) {
            [$data['schedule_a_id'],$data['schedule_b_id']] = [$data['schedule_b_id'],$data['schedule_a_id']];
        }

        $conflict = Conflict::updateOrCreate([
            'schedule_a_id' => $data['schedule_a_id'],
            'schedule_b_id' => $data['schedule_b_id'],
        ], [
            'type' => null,
            'resolved' => true,
            'resolution_note' => $data['resolution_note'] ?? null,
            'resolved_by' => Auth::id(),
            'resolved_at' => now(),
        ]);

        return response()->json($conflict, 201);
    }
}
