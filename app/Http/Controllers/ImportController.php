<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ImportController extends Controller
{
    protected function ensureAdmin()
    {
        $user = auth()->user();
        if (!$user || ! (
            ($user->hasRole('ADMIN') || $user->hasRole('administrador'))
        )) {
            abort(response()->json(['message' => 'Forbidden'], 403));
        }
    }

    /**
     * CU12 - Import CSV for teachers, subjects, groups
     * Accepts multipart/form-data: file (csv), type (teachers|subjects|groups)
     */
    public function import(Request $request)
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'type' => 'required|string|in:teachers,subjects,groups',
            'file' => 'required|file',
        ]);

        $path = $request->file('file')->getRealPath();

        $extension = strtolower($request->file('file')->getClientOriginalExtension() ?? '');

        // If Excel file and PhpSpreadsheet is available, use it. Otherwise fall back to CSV parser.
        if (in_array($extension, ['xlsx', 'xls'])) {
            if (class_exists(\PhpOffice\PhpSpreadsheet\IOFactory::class)) {
                $rows = $this->parseExcel($path);
            } else {
                return response()->json([
                    'message' => 'Upload is an Excel file but the server does not have PhpSpreadsheet installed. Please upload CSV or install phpoffice/phpspreadsheet.'
                ], 422);
            }
        } else {
            $rows = $this->parseCsv($path);
        }
        $created = 0;
        $updated = 0;
        $errors = [];

        foreach ($rows as $idx => $row) {
            try {
                if ($data['type'] === 'teachers') {
                    $email = trim($row['email'] ?? '');
                    if (!$email) continue;
                    $teacher = Teacher::where('email', $email)->first();
                    $attrs = [
                        'name' => $row['name'] ?? $row['full_name'] ?? 'Unnamed',
                        'email' => $email,
                        'dni' => $row['dni'] ?? null,
                        'phone' => $row['phone'] ?? null,
                        'department' => $row['department'] ?? null,
                    ];
                    if ($teacher) {
                        $teacher->update($attrs);
                        $updated++;
                    } else {
                        Teacher::create($attrs);
                        $created++;
                    }
                } elseif ($data['type'] === 'subjects') {
                    $code = trim($row['code'] ?? '');
                    if (!$code) continue;
                    $subject = Subject::where('code', $code)->first();
                    $attrs = [
                        'code' => $code,
                        'name' => $row['name'] ?? $row['title'] ?? 'Untitled',
                        'credits' => intval($row['credits'] ?? 0),
                        'description' => $row['description'] ?? null,
                    ];
                    if ($subject) {
                        $subject->update($attrs);
                        $updated++;
                    } else {
                        Subject::create($attrs);
                        $created++;
                    }
                } else { // groups
                    $code = trim($row['code'] ?? '');
                    if (!$code) continue;
                    $group = Group::where('code', $code)->first();
                    $attrs = [
                        'subject_id' => $row['subject_id'] ? intval($row['subject_id']) : null,
                        'code' => $code,
                        'name' => $row['name'] ?? $row['title'] ?? 'Group',
                        'capacity' => $row['capacity'] ? intval($row['capacity']) : null,
                        'schedule' => $row['schedule'] ?? null,
                    ];
                    if ($group) {
                        $group->update($attrs);
                        $updated++;
                    } else {
                        Group::create($attrs);
                        $created++;
                    }
                }
            } catch (\Exception $e) {
                $errors[] = ['row' => $idx + 1, 'error' => $e->getMessage()];
            }
        }

        return response()->json(['created' => $created, 'updated' => $updated, 'errors' => $errors]);
    }

    protected function parseCsv(string $path): array
    {
        $rows = [];
        if (! file_exists($path)) return $rows;

        if (($handle = fopen($path, 'r')) !== false) {
            $headers = null;
            while (($data = fgetcsv($handle, 0, ',')) !== false) {
                if (! $headers) {
                    // normalize headers
                    $headers = array_map(function ($h) {
                        return Str::of($h)->trim()->lower()->replace(' ', '_')->__toString();
                    }, $data);
                    continue;
                }
                $row = [];
                foreach ($data as $i => $cell) {
                    $key = $headers[$i] ?? 'col_'.$i;
                    $row[$key] = $cell;
                }
                $rows[] = $row;
            }
            fclose($handle);
        }
        return $rows;
    }

    protected function parseExcel(string $path): array
    {
        $rows = [];
        try {
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($path);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($path);
            $sheet = $spreadsheet->getActiveSheet();
            $highestRow = $sheet->getHighestDataRow();
            $highestColumn = $sheet->getHighestDataColumn();
            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

            $headers = [];
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $val = $sheet->getCellByColumnAndRow($col, 1)->getValue();
                $headers[] = (string)\Illuminate\Support\Str::of($val)->trim()->lower()->replace(' ', '_');
            }

            for ($row = 2; $row <= $highestRow; $row++) {
                $item = [];
                for ($col = 1; $col <= $highestColumnIndex; $col++) {
                    $key = $headers[$col - 1] ?? 'col_'.($col-1);
                    $cell = $sheet->getCellByColumnAndRow($col, $row)->getValue();
                    $item[$key] = $cell;
                }
                $rows[] = $item;
            }
        } catch (\Throwable $e) {
            // Graceful fallback: return empty and let caller handle
        }
        return $rows;
    }
}
