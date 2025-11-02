<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array<int, class-string>
     */
    // Keep global middleware minimal to avoid referencing app-specific classes that may not exist
    protected $middleware = [];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string>>
     */
    protected $middlewareGroups = [
        // Minimal groups: keep api group so route middleware can work
        'web' => [],
        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string>
     */
    protected $routeMiddleware = [
        // Only register the custom aliases here; other middleware aliases are left to the app defaults
        'ensure.admin' => \App\Http\Middleware\EnsureAdmin::class,
        'ensure.teacher_or_admin' => \App\Http\Middleware\EnsureTeacherOrAdmin::class,
    ];
}
