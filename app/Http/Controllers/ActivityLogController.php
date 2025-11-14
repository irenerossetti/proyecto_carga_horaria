<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ActivityLogExport;

class ActivityLogController extends Controller
{
    /**
     * Obtener logs con filtros
     */
    public function index(Request $request): JsonResponse
    {
        $query = ActivityLog::query()->with('user');

        // Filtros
        if ($request->filled('user')) {
            $query->where(function($q) use ($request) {
                $q->where('user_name', 'like', '%' . $request->user . '%')
                  ->orWhere('user_email', 'like', '%' . $request->user . '%');
            });
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('ip')) {
            $query->where('ip_address', $request->ip);
        }

        // Ordenar por más reciente
        $query->orderBy('created_at', 'desc');

        // Paginación
        $logs = $query->paginate($request->get('per_page', 50));

        return response()->json($logs);
    }

    /**
     * Exportar a Excel
     */
    public function exportExcel(Request $request)
    {
        $filters = $request->only(['user', 'action', 'module', 'date_from', 'date_to', 'ip']);
        
        $filename = 'bitacora_' . date('Y-m-d_His') . '.xlsx';
        
        return Excel::download(new ActivityLogExport($filters), $filename);
    }

    /**
     * Exportar a PDF
     */
    public function exportPdf(Request $request)
    {
        $query = ActivityLog::query();

        // Aplicar filtros
        if ($request->filled('user')) {
            $query->where(function($q) use ($request) {
                $q->where('user_name', 'like', '%' . $request->user . '%')
                  ->orWhere('user_email', 'like', '%' . $request->user . '%');
            });
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->orderBy('created_at', 'desc')->limit(1000)->get();

        $pdf = Pdf::loadView('admin.activity-log-pdf', [
            'logs' => $logs,
            'filters' => $request->only(['user', 'action', 'module', 'date_from', 'date_to']),
            'generated_at' => now()->format('d/m/Y H:i:s')
        ]);

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('bitacora_' . date('Y-m-d_His') . '.pdf');
    }

    /**
     * Limpiar logs antiguos
     */
    public function clearOld(Request $request): JsonResponse
    {
        $days = $request->get('days', 90);
        
        $deleted = ActivityLog::where('created_at', '<', now()->subDays($days))->delete();

        return response()->json([
            'success' => true,
            'message' => "Se eliminaron {$deleted} registros antiguos",
            'deleted' => $deleted
        ]);
    }

    /**
     * Obtener estadísticas
     */
    public function stats(Request $request): JsonResponse
    {
        $query = ActivityLog::query();

        // Aplicar filtros de fecha si existen
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $stats = [
            'total' => $query->count(),
            'by_action' => $query->selectRaw('action, COUNT(*) as count')
                ->groupBy('action')
                ->pluck('count', 'action'),
            'by_module' => $query->selectRaw('module, COUNT(*) as count')
                ->groupBy('module')
                ->pluck('count', 'module'),
            'unique_users' => $query->distinct('user_id')->count('user_id'),
            'unique_ips' => $query->distinct('ip_address')->count('ip_address'),
        ];

        return response()->json($stats);
    }
}
