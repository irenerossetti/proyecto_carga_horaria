<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use App\Models\Teacher;
use App\Models\Schedule;
use Illuminate\Support\Facades\Cache;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::query();
        if ($request->has('teacher_id')) $query->where('teacher_id', $request->query('teacher_id'));
        if ($request->has('date')) $query->where('date', $request->query('date'));
        $user = $request->user();
        // If not admin and requesting all, restrict to own teacher records
        if (! $user->hasRole('administrador')) {
            if ($user->hasRole('docente')) {
                $teacher = Teacher::where('email', $user->email)->first();
                if ($teacher) {
                    $query->where('teacher_id', $teacher->id);
                }
            }
        }

        return response()->json($query->with(['teacher','schedule'])->orderBy('date','desc')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'teacher_id' => 'required|integer|exists:teachers,id',
            'schedule_id' => 'nullable|integer|exists:schedules,id',
            'date' => 'required|date',
            'time' => 'nullable|date_format:H:i',
            'status' => 'required|string|in:present,absent,late',
            'notes' => 'nullable|string',
        ]);

        $user = $request->user();
        $isAdmin = $user->hasRole('administrador');

        if (! $isAdmin) {
            // teacher can only record for themselves (match by email)
            $teacher = Teacher::where('email', $user->email)->first();
            if (! $teacher || $teacher->id != $data['teacher_id']) {
                return response()->json(['message' => 'Forbidden - cannot record attendance for another teacher'], 403);
            }
        }

        $data['recorded_by'] = Auth::id();

        $attendance = Attendance::create($data);
        return response()->json($attendance, 201);
    }

    public function show($id)
    {
        $att = Attendance::with(['teacher','schedule'])->findOrFail($id);
        return response()->json($att);
    }

    public function update(Request $request, $id)
    {
        $att = Attendance::findOrFail($id);
        $data = $request->validate([
            'status' => 'nullable|string|in:present,absent,late',
            'notes' => 'nullable|string',
            'time' => 'nullable|date_format:H:i',
        ]);
        $user = $request->user();
        if (! $user->hasRole('administrador')) {
            // teacher can only update own attendance
            $teacher = Teacher::where('email', $user->email)->first();
            if (! $teacher || $teacher->id != $att->teacher_id) {
                return response()->json(['message' => 'Forbidden - cannot update this record'], 403);
            }
        }

        $att->update($data);
        return response()->json($att);
    }

    public function destroy($id)
    {
        $att = Attendance::findOrFail($id);
        $att->delete();
        return response()->json(['message'=>'Attendance deleted','id'=>(int)$id]);
    }

    /**
     * CU18 - Register attendance via QR by teacher (or admin).
     * Expected payload: { schedule_id: int, qr_payload?: string }
     */
    public function registerQr(Request $request)
    {
        $data = $request->validate([
            // Either a signed qr_payload is provided OR schedule_id is provided (legacy).
            'schedule_id' => 'nullable|integer|exists:schedules,id',
            'qr_payload' => 'nullable|string',
        ]);

        $user = $request->user();
        $isAdmin = $user->hasRole('administrador');

        // If a signed QR payload is provided, prefer it and validate signature & expiry
        $scheduleIdFromToken = null;
        if (! empty($data['qr_payload'])) {
            $qr = $data['qr_payload'];
            $parts = explode('.', $qr);
            if (count($parts) !== 2) {
                return response()->json(['message' => 'Invalid QR payload format'], 422);
            }
            [$payloadB64, $sig] = $parts;
            // restore standard base64
            $payloadJson = base64_decode(strtr($payloadB64, '-_', '+/'));
            $payload = json_decode($payloadJson, true);
            if (! is_array($payload) || empty($payload['schedule_id'])) {
                return response()->json(['message' => 'Invalid QR payload content'], 422);
            }
            $expected = hash_hmac('sha256', $payloadB64, env('QR_SECRET', 'CHANGE_ME'));
            if (! hash_equals($expected, $sig)) {
                return response()->json(['message' => 'Invalid QR signature'], 422);
            }
            if (isset($payload['exp']) && $payload['exp'] < time()) {
                return response()->json(['message' => 'QR expired'], 422);
            }

            // optional replay protection: cache the signature until expiry
            if (filter_var(env('QR_PREVENT_REPLAY', true), FILTER_VALIDATE_BOOLEAN)) {
                $cacheKey = 'qr_used_'.sha1($sig);
                if (Cache::get($cacheKey)) {
                    return response()->json(['message' => 'QR already used'], 409);
                }
                $ttl = max(30, (int) (($payload['exp'] ?? (time()+300)) - time()));
                Cache::put($cacheKey, true, $ttl);
            }

            $scheduleIdFromToken = (int) $payload['schedule_id'];
        }

        $schedule_id = $scheduleIdFromToken ?? $data['schedule_id'];
        if (empty($schedule_id)) {
            return response()->json(['message' => 'schedule_id missing'], 422);
        }

        // Resolve teacher: for non-admins, match by authenticated user's email
        if (! $isAdmin) {
            $teacher = Teacher::where('email', $user->email)->first();
            if (! $teacher) {
                return response()->json(['message' => 'Teacher profile not found for authenticated user'], 404);
            }
        } else {
            // admin may optionally supply teacher via schedule assignment
            $sched = Schedule::find($schedule_id);
            $teacher = $sched ? $sched->teacher : null;
            if (! $teacher) {
                return response()->json(['message' => 'No teacher linked to schedule; admin must provide teacher_id via attendance endpoint instead'], 422);
            }
        }

        $attendance = Attendance::create([
            'teacher_id' => $teacher->id,
            'schedule_id' => $schedule_id,
            'date' => date('Y-m-d'),
            'time' => date('H:i'),
            'status' => 'present',
            'notes' => 'QR: '.($data['qr_payload'] ?? ''),
            'recorded_by' => Auth::id(),
        ]);

        return response()->json($attendance, 201);
    }
}
