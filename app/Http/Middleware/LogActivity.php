<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Solo registrar si el usuario está autenticado
        if (auth()->check()) {
            $this->logActivity($request);
        }

        return $response;
    }

    /**
     * Registrar la actividad
     */
    protected function logActivity(Request $request): void
    {
        // No registrar peticiones AJAX de polling o assets
        if ($request->ajax() || $this->shouldSkip($request)) {
            return;
        }

        $user = auth()->user();
        $action = $this->determineAction($request);
        $module = $this->determineModule($request);
        $description = $this->generateDescription($request, $action, $module);

        ActivityLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'user_role' => $user->roles->first()?->name ?? 'Sin rol',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'url' => $request->fullUrl(),
            'method' => $request->method(),
        ]);
    }

    /**
     * Determinar si debe omitirse el registro
     */
    protected function shouldSkip(Request $request): bool
    {
        $skipPaths = [
            'api/notifications',
            'livewire',
            '_debugbar',
            'telescope',
        ];

        foreach ($skipPaths as $path) {
            if (str_contains($request->path(), $path)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determinar la acción basada en el método HTTP
     */
    protected function determineAction(Request $request): string
    {
        return match($request->method()) {
            'GET' => 'view',
            'POST' => 'create',
            'PUT', 'PATCH' => 'update',
            'DELETE' => 'delete',
            default => 'unknown',
        };
    }

    /**
     * Determinar el módulo basado en la URL
     */
    protected function determineModule(Request $request): string
    {
        $path = $request->path();

        if (str_contains($path, 'dashboard')) return 'dashboard';
        if (str_contains($path, 'teachers') || str_contains($path, 'docentes')) return 'teachers';
        if (str_contains($path, 'students') || str_contains($path, 'estudiantes')) return 'students';
        if (str_contains($path, 'subjects') || str_contains($path, 'materias')) return 'subjects';
        if (str_contains($path, 'groups') || str_contains($path, 'grupos')) return 'groups';
        if (str_contains($path, 'rooms') || str_contains($path, 'aulas')) return 'rooms';
        if (str_contains($path, 'schedules') || str_contains($path, 'horarios')) return 'schedules';
        if (str_contains($path, 'attendance') || str_contains($path, 'asistencia')) return 'attendance';
        if (str_contains($path, 'reports') || str_contains($path, 'reportes')) return 'reports';
        if (str_contains($path, 'periods') || str_contains($path, 'periodos')) return 'periods';
        if (str_contains($path, 'settings') || str_contains($path, 'configuracion')) return 'settings';

        return 'system';
    }

    /**
     * Generar descripción de la actividad
     */
    protected function generateDescription(Request $request, string $action, string $module): string
    {
        $user = auth()->user();
        $actionText = match($action) {
            'view' => 'consultó',
            'create' => 'creó',
            'update' => 'actualizó',
            'delete' => 'eliminó',
            default => 'accedió a',
        };

        $moduleText = match($module) {
            'dashboard' => 'el dashboard',
            'teachers' => 'docentes',
            'students' => 'estudiantes',
            'subjects' => 'materias',
            'groups' => 'grupos',
            'rooms' => 'aulas',
            'schedules' => 'horarios',
            'attendance' => 'asistencia',
            'reports' => 'reportes',
            'periods' => 'periodos académicos',
            'settings' => 'configuración',
            default => 'el sistema',
        };

        return "{$user->name} {$actionText} {$moduleText}";
    }
}
