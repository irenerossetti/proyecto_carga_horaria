<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Attendance;
use App\Models\Teacher;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/reports/schedules",
     *     summary="CU26 - Generar reporte de horarios (PDF/Excel)",
     *     tags={"Reportes"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Parameter(name="format", in="query", @OA\Schema(type="string", enum={"pdf","xlsx"}), description="Formato de salida"),
     *     @OA\Parameter(name="from", in="query", @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="to", in="query", @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="teacher_id", in="query", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="group_id", in="query", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Archivo descargable PDF o Excel")
     * )
     */
    public function schedules(Request $request)
    {
        $data = $request->validate([
            'format' => 'nullable|string|in:pdf,xlsx',
            'from' => 'nullable|date',
            'to' => 'nullable|date',
            'teacher_id' => 'nullable|integer|exists:teachers,id',
            'group_id' => 'nullable|integer',
        ]);

        $q = Schedule::with(['teacher','group','room']);
        if (! empty($data['from'])) $q->where('date', '>=', $data['from']);
        if (! empty($data['to'])) $q->where('date', '<=', $data['to']);
        if (! empty($data['teacher_id'])) $q->where('teacher_id', $data['teacher_id']);
        if (! empty($data['group_id'])) $q->where('group_id', $data['group_id']);

        $schedules = $q->orderBy('date')->get();

        $format = $data['format'] ?? 'pdf';

        // Build rows
        $rows = [];
        $rows[] = ['Date','Day','Start','End','Teacher','Group','Room','Subject'];
        foreach ($schedules as $s) {
            $rows[] = [
                $s->date,
                $s->day ?? '',
                $s->start_time ?? '',
                $s->end_time ?? '',
                $s->teacher->name ?? '',
                $s->group->name ?? '',
                $s->room->name ?? '',
                $s->subject->name ?? '',
            ];
        }

        $filename = 'schedules_report_'.date('Ymd_His');
        if ($format === 'xlsx') {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $r = 1;
            foreach ($rows as $row) {
                $c = 'A';
                foreach ($row as $cell) {
                    $sheet->setCellValue($c.$r, $cell);
                    $c++;
                }
                $r++;
            }
            $writer = new Xlsx($spreadsheet);
            $temp = tmpfile();
            $meta = stream_get_meta_data($temp);
            $tmpFilename = $meta['uri'];
            $writer->save($tmpFilename);
            return Response::download($tmpFilename, $filename.'.xlsx')->deleteFileAfterSend(true);
        }

        // PDF
        $html = '<h2>Reporte de Horarios</h2><table border="1" cellpadding="4" cellspacing="0">';
        foreach ($rows as $i => $row) {
            $html .= '<tr>';
            foreach ($row as $cell) $html .= '<td>'.htmlspecialchars($cell).'</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $pdf = $dompdf->output();
        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"{$filename}.pdf\"",
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/reports/attendances",
     *     summary="CU27 - Generar reporte de asistencia (PDF/Excel)",
     *     tags={"Reportes"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Parameter(name="format", in="query", @OA\Schema(type="string", enum={"pdf","xlsx"})),
     *     @OA\Parameter(name="from", in="query", @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="to", in="query", @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="teacher_id", in="query", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Archivo descargable PDF o Excel")
     * )
     */
    public function attendances(Request $request)
    {
        $data = $request->validate([
            'format' => 'nullable|string|in:pdf,xlsx',
            'from' => 'nullable|date',
            'to' => 'nullable|date',
            'teacher_id' => 'nullable|integer|exists:teachers,id',
        ]);

        $q = Attendance::with(['teacher','schedule']);
        if (! empty($data['from'])) $q->where('date', '>=', $data['from']);
        if (! empty($data['to'])) $q->where('date', '<=', $data['to']);
        if (! empty($data['teacher_id'])) $q->where('teacher_id', $data['teacher_id']);
        $att = $q->orderBy('date','desc')->get();

        $rows = [];
        $rows[] = ['Date','Time','Teacher','Schedule','Status','Notes'];
        foreach ($att as $a) {
            $rows[] = [
                $a->date,
                $a->time,
                $a->teacher->name ?? '',
                $a->schedule ? ($a->schedule->group->name ?? '') : '',
                $a->status,
                $a->notes ?? '',
            ];
        }

        $format = $data['format'] ?? 'pdf';
        $filename = 'attendance_report_'.date('Ymd_His');

        if ($format === 'xlsx') {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $r = 1;
            foreach ($rows as $row) {
                $c = 'A';
                foreach ($row as $cell) { $sheet->setCellValue($c.$r, $cell); $c++; }
                $r++;
            }
            $writer = new Xlsx($spreadsheet);
            $temp = tmpfile(); $meta = stream_get_meta_data($temp); $tmpFilename = $meta['uri']; $writer->save($tmpFilename);
            return Response::download($tmpFilename, $filename.'.xlsx')->deleteFileAfterSend(true);
        }

        $html = '<h2>Reporte de Asistencias</h2><table border="1" cellpadding="4" cellspacing="0">';
        foreach ($rows as $row) { $html .= '<tr>'; foreach ($row as $cell) $html .= '<td>'.htmlspecialchars($cell).'</td>'; $html .= '</tr>'; }
        $html .= '</table>';
        $dompdf = new Dompdf(); $dompdf->loadHtml($html); $dompdf->setPaper('A4','portrait'); $dompdf->render();
        $pdf = $dompdf->output();
        return response($pdf,200,['Content-Type'=>'application/pdf','Content-Disposition'=>"attachment; filename=\"{$filename}.pdf\""]);
    }

    /**
     * @OA\Get(
     *     path="/api/reports/workload",
     *     summary="CU28 - Generar reporte de carga horaria por docente (PDF/Excel)",
     *     tags={"Reportes"},
     *     security={{"cookieAuth": {}}},
     *     @OA\Parameter(name="format", in="query", @OA\Schema(type="string", enum={"pdf","xlsx"})),
     *     @OA\Parameter(name="from", in="query", @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="to", in="query", @OA\Schema(type="string", format="date")),
     *     @OA\Response(response=200, description="Archivo descargable PDF o Excel")
     * )
     */
    public function workload(Request $request)
    {
        $data = $request->validate([
            'format' => 'nullable|string|in:pdf,xlsx',
            'from' => 'nullable|date',
            'to' => 'nullable|date',
        ]);

        $q = Schedule::with(['teacher','subject']);
        if (! empty($data['from'])) $q->where('date','>=',$data['from']);
        if (! empty($data['to'])) $q->where('date','<=',$data['to']);
        $schedules = $q->get();

        $byTeacher = [];
        foreach ($schedules as $s) {
            $tid = $s->teacher_id ?: 0;
            $dur = 0;
            if ($s->start_time && $s->end_time) {
                $dur = Carbon::parse($s->end_time)->diffInMinutes(Carbon::parse($s->start_time))/60;
            }
            if (! isset($byTeacher[$tid])) $byTeacher[$tid] = ['teacher'=>$s->teacher->name ?? 'Unassigned','hours'=>0,'subjects'=>[]];
            $byTeacher[$tid]['hours'] += $dur;
            $subj = $s->subject->name ?? null;
            if ($subj) $byTeacher[$tid]['subjects'][$subj] = ($byTeacher[$tid]['subjects'][$subj] ?? 0) + 1;
        }

        $rows = [];
        $rows[] = ['Teacher','Total Hours','Subjects (count)'];
        foreach ($byTeacher as $t) {
            $subs = [];
            foreach ($t['subjects'] as $name => $cnt) $subs[] = "$name ($cnt)";
            $rows[] = [$t['teacher'], round($t['hours'],2), implode('; ', $subs)];
        }

        $format = $data['format'] ?? 'pdf';
        $filename = 'workload_report_'.date('Ymd_His');
        if ($format === 'xlsx') {
            $spreadsheet = new Spreadsheet(); $sheet = $spreadsheet->getActiveSheet(); $r=1; foreach($rows as $row){ $c='A'; foreach($row as $cell){ $sheet->setCellValue($c.$r,$cell); $c++; } $r++; }
            $writer = new Xlsx($spreadsheet); $temp = tmpfile(); $meta = stream_get_meta_data($temp); $tmpFilename = $meta['uri']; $writer->save($tmpFilename);
            return Response::download($tmpFilename, $filename.'.xlsx')->deleteFileAfterSend(true);
        }

        $html = '<h2>Reporte de Carga Horaria por Docente</h2><table border="1" cellpadding="4" cellspacing="0">'; foreach($rows as $row){ $html .= '<tr>'; foreach($row as $cell) $html .= '<td>'.htmlspecialchars($cell).'</td>'; $html .= '</tr>'; } $html .= '</table>';
        $dompdf = new Dompdf(); $dompdf->loadHtml($html); $dompdf->setPaper('A4','portrait'); $dompdf->render(); $pdf = $dompdf->output();
        return response($pdf,200,['Content-Type'=>'application/pdf','Content-Disposition'=>"attachment; filename=\"{$filename}.pdf\""]);
    }
}
