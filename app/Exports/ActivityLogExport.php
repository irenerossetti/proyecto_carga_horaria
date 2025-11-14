<?php

namespace App\Exports;

use App\Models\ActivityLog;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class ActivityLogExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * Query para obtener los datos
     */
    public function query()
    {
        $query = ActivityLog::query();

        // Aplicar filtros
        if (!empty($this->filters['user'])) {
            $query->where(function($q) {
                $q->where('user_name', 'like', '%' . $this->filters['user'] . '%')
                  ->orWhere('user_email', 'like', '%' . $this->filters['user'] . '%');
            });
        }

        if (!empty($this->filters['action'])) {
            $query->where('action', $this->filters['action']);
        }

        if (!empty($this->filters['module'])) {
            $query->where('module', $this->filters['module']);
        }

        if (!empty($this->filters['date_from'])) {
            $query->whereDate('created_at', '>=', $this->filters['date_from']);
        }

        if (!empty($this->filters['date_to'])) {
            $query->whereDate('created_at', '<=', $this->filters['date_to']);
        }

        if (!empty($this->filters['ip'])) {
            $query->where('ip_address', $this->filters['ip']);
        }

        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Encabezados de las columnas
     */
    public function headings(): array
    {
        return [
            'ID',
            'Fecha',
            'Hora',
            'Usuario',
            'Email',
            'Rol',
            'Dirección IP',
            'Acción',
            'Módulo',
            'Descripción',
            'URL',
            'Método HTTP',
        ];
    }

    /**
     * Mapear los datos
     */
    public function map($log): array
    {
        return [
            $log->id,
            $log->created_at->format('d/m/Y'),
            $log->created_at->format('H:i:s'),
            $log->user_name,
            $log->user_email,
            $log->user_role,
            $log->ip_address,
            $this->translateAction($log->action),
            $this->translateModule($log->module),
            $log->description,
            $log->url,
            $log->method,
        ];
    }

    /**
     * Estilos para el Excel
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo para la fila de encabezados
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '881F34'], // Brand primary
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }

    /**
     * Título de la hoja
     */
    public function title(): string
    {
        return 'Bitácora del Sistema';
    }

    /**
     * Traducir acciones
     */
    private function translateAction(string $action): string
    {
        $translations = [
            'login' => 'Inicio de Sesión',
            'logout' => 'Cierre de Sesión',
            'create' => 'Crear',
            'update' => 'Actualizar',
            'delete' => 'Eliminar',
            'view' => 'Consultar',
        ];

        return $translations[$action] ?? $action;
    }

    /**
     * Traducir módulos
     */
    private function translateModule(string $module): string
    {
        $translations = [
            'auth' => 'Autenticación',
            'dashboard' => 'Panel Principal',
            'teachers' => 'Docentes',
            'students' => 'Estudiantes',
            'subjects' => 'Materias',
            'groups' => 'Grupos',
            'rooms' => 'Aulas',
            'schedules' => 'Horarios',
            'attendance' => 'Asistencia',
            'reports' => 'Reportes',
            'periods' => 'Periodos Académicos',
            'settings' => 'Configuración',
        ];

        return $translations[$module] ?? $module;
    }
}
