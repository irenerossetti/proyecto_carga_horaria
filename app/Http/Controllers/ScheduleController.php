<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = Schedule::query();
        if ($request->has('group_id')) $query->where('group_id', $request->query('group_id'));
        if ($request->has('teacher_id')) $query->where('teacher_id', $request->query('teacher_id'));
        return response()->json($query->with(['group','room','teacher'])->get());
    }

    /**
     * Return weekly schedule grouped by day. Accepts teacher_id or group_id as filter.
     */
    public function weekly(Request $request)
    {
        $query = Schedule::query();
        if ($request->has('teacher_id')) $query->where('teacher_id', $request->query('teacher_id'));
        if ($request->has('group_id')) $query->where('group_id', $request->query('group_id'));
        $schedules = $query->with(['group','room','teacher'])->get()->groupBy('day_of_week');
        return response()->json($schedules);
    }

    /**
     * Export schedules to Excel. Accepts teacher_id or group_id filters.
     */
    public function export(Request $request)
    {
        $query = Schedule::query();
        if ($request->has('teacher_id')) $query->where('teacher_id', $request->query('teacher_id'));
        if ($request->has('group_id')) $query->where('group_id', $request->query('group_id'));
        $items = $query->with(['group','room','teacher'])->orderBy('day_of_week')->orderBy('start_time')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray(['ID','Day','Start','End','Group','Subject','Room','Teacher'], null, 'A1');
        $row = 2;
        foreach ($items as $it) {
            $sheet->setCellValue("A{$row}", $it->id);
            $sheet->setCellValue("B{$row}", $it->day_of_week);
            $sheet->setCellValue("C{$row}", $it->start_time);
            $sheet->setCellValue("D{$row}", $it->end_time);
            $sheet->setCellValue("E{$row}", $it->group->name ?? $it->group_id);
            $sheet->setCellValue("F{$row}", $it->group->subject_id ?? '');
            $sheet->setCellValue("G{$row}", $it->room->name ?? $it->room_id);
            $sheet->setCellValue("H{$row}", $it->teacher->name ?? $it->teacher_id);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);

        $fileName = 'schedules_export_'.date('Ymd_His').'.xlsx';

        $response = new StreamedResponse(function() use ($writer) {
            $writer->save('php://output');
        });

        $disposition = 'attachment; filename="'.$fileName.'"';
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }

    /**
     * Export schedules to PDF. Requires barryvdh/laravel-dompdf or dompdf installed.
     */
    public function exportPdf(Request $request)
    {
        $query = Schedule::query();
        if ($request->has('teacher_id')) $query->where('teacher_id', $request->query('teacher_id'));
        if ($request->has('group_id')) $query->where('group_id', $request->query('group_id'));
        $items = $query->with(['group','room','teacher'])->orderBy('day_of_week')->orderBy('start_time')->get();

        // Build a simple HTML table
        $html = '<h2>Schedules</h2>';
        $html .= '<table border="1" cellpadding="4" cellspacing="0" style="border-collapse:collapse;width:100%">';
        $html .= '<thead><tr><th>ID</th><th>Day</th><th>Start</th><th>End</th><th>Group</th><th>Subject</th><th>Room</th><th>Teacher</th></tr></thead><tbody>';
        foreach ($items as $it) {
            $html .= '<tr>';
            $html .= '<td>'.e($it->id).'</td>';
            $html .= '<td>'.e($it->day_of_week).'</td>';
            $html .= '<td>'.e($it->start_time).'</td>';
            $html .= '<td>'.e($it->end_time).'</td>';
            $html .= '<td>'.e($it->group->name ?? $it->group_id).'</td>';
            $html .= '<td>'.e($it->group->subject_id ?? '').'</td>';
            $html .= '<td>'.e($it->room->name ?? $it->room_id).'</td>';
            $html .= '<td>'.e($it->teacher->name ?? $it->teacher_id).'</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';

        $fileName = 'schedules_export_'.date('Ymd_His').'.pdf';

        // Prefer Laravel DomPDF wrapper if available (facade class)
        if (class_exists('Barryvdh\\DomPDF\\Facade\\Pdf')) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
            return $pdf->stream($fileName);
        }

        // Try the wrapper service name (if provider registered)
        try {
            if (app()->bound('dompdf.wrapper')) {
                $pdf = app('dompdf.wrapper');
                $pdf->loadHTML($html);
                return $pdf->stream($fileName);
            }
        } catch (\Throwable $e) {
            // continue to fallback
        }

        // Fallback to using Dompdf directly if installed
        if (class_exists('\Dompdf\Dompdf')) {
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->render();
            $output = $dompdf->output();
            return response($output, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="'.$fileName.'"'
            ]);
        }

        // Not available: tell the user how to install
        return response()->json([
            'message' => 'PDF export not available. Please install barryvdh/laravel-dompdf (composer require barryvdh/laravel-dompdf) and register the service provider if needed.'
        ], 501);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'group_id' => 'required|integer|exists:groups,id',
            'room_id' => 'nullable|integer|exists:rooms,id',
            'teacher_id' => 'nullable|integer|exists:teachers,id',
            'day_of_week' => 'required|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
        ]);

        if ($data['end_time'] <= $data['start_time']) {
            return response()->json(['message' => 'end_time must be after start_time'], 422);
        }
        // Apply a configurable tolerance (minutes) around times for conflict checks.
        $tolerance = (int) env('SCHEDULE_CONFLICT_TOLERANCE_MINUTES', 0);
        $start = Carbon::createFromFormat('H:i', $data['start_time'])->startOfMinute()->subMinutes($tolerance)->format('H:i:s');
        $end = Carbon::createFromFormat('H:i', $data['end_time'])->startOfMinute()->addMinutes($tolerance)->format('H:i:s');

        // Conflict validation for the same group on the same day (expanded by tolerance)
        $groupConflict = Schedule::where('group_id', $data['group_id'])
            ->where('day_of_week', $data['day_of_week'])
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_time', [$start, $end])
                  ->orWhereBetween('end_time', [$start, $end])
                  ->orWhere(function($qq) use ($start, $end){
                      $qq->where('start_time','<=',$start)
                         ->where('end_time','>=',$end);
                  });
            })->exists();

        if ($groupConflict) {
            return response()->json(['message' => 'Schedule conflict for group'], 409);
        }

        // Conflict validation for same room if provided
        if (!empty($data['room_id'])) {
            $roomConflict = Schedule::where('room_id', $data['room_id'])
                ->where('day_of_week', $data['day_of_week'])
                ->where(function ($q) use ($start, $end) {
                    $q->whereBetween('start_time', [$start, $end])
                      ->orWhereBetween('end_time', [$start, $end])
                      ->orWhere(function($qq) use ($start, $end){
                          $qq->where('start_time','<=',$start)
                             ->where('end_time','>=',$end);
                      });
                })->exists();

            if ($roomConflict) {
                return response()->json(['message' => 'Schedule conflict for room'], 409);
            }
        }

        // Conflict validation for same teacher if provided
        if (!empty($data['teacher_id'])) {
            $teacherConflict = Schedule::where('teacher_id', $data['teacher_id'])
                ->where('day_of_week', $data['day_of_week'])
                ->where(function ($q) use ($start, $end) {
                    $q->whereBetween('start_time', [$start, $end])
                      ->orWhereBetween('end_time', [$start, $end])
                      ->orWhere(function($qq) use ($start, $end){
                          $qq->where('start_time','<=',$start)
                             ->where('end_time','>=',$end);
                      });
                })->exists();

            if ($teacherConflict) {
                return response()->json(['message' => 'Schedule conflict for teacher'], 409);
            }
        }

        $data['assigned_by'] = Auth::id();
        $schedule = Schedule::create($data);
        return response()->json($schedule, 201);
    }

    public function show($id)
    {
        $schedule = Schedule::with(['group','room','teacher'])->findOrFail($id);
        return response()->json($schedule);
    }

    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);
        $data = $request->validate([
            'group_id' => 'nullable|integer|exists:groups,id',
            'room_id' => 'nullable|integer|exists:rooms,id',
            'teacher_id' => 'nullable|integer|exists:teachers,id',
            'day_of_week' => 'nullable|string',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
        ]);

        $new = array_merge($schedule->toArray(), $data);
        if (!empty($new['start_time']) && !empty($new['end_time']) && $new['end_time'] <= $new['start_time']) {
            return response()->json(['message' => 'end_time must be after start_time'], 422);
        }

        $tolerance = (int) env('SCHEDULE_CONFLICT_TOLERANCE_MINUTES', 0);
        $start = Carbon::createFromFormat('H:i', $new['start_time'])->startOfMinute()->subMinutes($tolerance)->format('H:i:s');
        $end = Carbon::createFromFormat('H:i', $new['end_time'])->startOfMinute()->addMinutes($tolerance)->format('H:i:s');

        // Conflict check for group
        $groupConflict = Schedule::where('group_id', $new['group_id'])
            ->where('day_of_week', $new['day_of_week'])
            ->where('id', '!=', $schedule->id)
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_time', [$start, $end])
                  ->orWhereBetween('end_time', [$start, $end])
                  ->orWhere(function($qq) use ($start, $end){
                      $qq->where('start_time','<=',$start)
                         ->where('end_time','>=',$end);
                  });
            })->exists();

        if ($groupConflict) {
            return response()->json(['message' => 'Schedule conflict for group'], 409);
        }

        // Conflict check for room if present
        if (!empty($new['room_id'])) {
            $roomConflict = Schedule::where('room_id', $new['room_id'])
                ->where('day_of_week', $new['day_of_week'])
                ->where('id', '!=', $schedule->id)
                ->where(function ($q) use ($start, $end) {
                    $q->whereBetween('start_time', [$start, $end])
                      ->orWhereBetween('end_time', [$start, $end])
                      ->orWhere(function($qq) use ($start, $end){
                          $qq->where('start_time','<=',$start)
                             ->where('end_time','>=',$end);
                      });
                })->exists();

            if ($roomConflict) {
                return response()->json(['message' => 'Schedule conflict for room'], 409);
            }
        }

        // Conflict check for teacher if present
        if (!empty($new['teacher_id'])) {
            $teacherConflict = Schedule::where('teacher_id', $new['teacher_id'])
                ->where('day_of_week', $new['day_of_week'])
                ->where('id', '!=', $schedule->id)
                ->where(function ($q) use ($start, $end) {
                    $q->whereBetween('start_time', [$start, $end])
                      ->orWhereBetween('end_time', [$start, $end])
                      ->orWhere(function($qq) use ($start, $end){
                          $qq->where('start_time','<=',$start)
                             ->where('end_time','>=',$end);
                      });
                })->exists();

            if ($teacherConflict) {
                return response()->json(['message' => 'Schedule conflict for teacher'], 409);
            }
        }

        $schedule->update($data);
        return response()->json($schedule);
    }

    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();
        return response()->json(['message' => 'Schedule deleted', 'id' => (int)$id]);
    }
}
