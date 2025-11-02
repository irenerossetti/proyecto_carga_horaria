<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureTeacherOrAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Admin passes
        if ($user->hasRole('administrador')) {
            return $next($request);
        }

        // Teacher must have role docente
        if (! $user->hasRole('docente')) {
            return response()->json(['message' => 'Forbidden - teacher or admin only'], 403);
        }

        return $next($request);
    }
}
