<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Router $router): void
    {
        // Register route middleware aliases so we can use them by name in routes
        $router->aliasMiddleware('ensure.admin', \App\Http\Middleware\EnsureAdmin::class);
        $router->aliasMiddleware('ensure.teacher_or_admin', \App\Http\Middleware\EnsureTeacherOrAdmin::class);
    }
}
