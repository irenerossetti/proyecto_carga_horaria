<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Http\Controllers\AcademicPeriodController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TeacherController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

// Ruta para acceder rápidamente a la documentación Swagger UI
Route::get('/swagger', function () {
    return redirect('/api/documentation');
})->name('swagger.ui');

// Servir el archivo YAML directamente (útil para herramientas externas)
Route::get('/openapi.yaml', function () {
    return response()->file(base_path('docs/openapi.yaml'));
});

// API endpoints mínimos para CU04 - Gestión de Periodo Académico
Route::prefix('api')->middleware(['auth'])->group(function () {
    // Listar y crear periodos (solo admin comprobado en controller)
    Route::get('periods', [AcademicPeriodController::class, 'index']);
    Route::post('periods', [AcademicPeriodController::class, 'store']);

    // Activar y cerrar un periodo
    Route::post('periods/{id}/activate', [AcademicPeriodController::class, 'activate']);
    Route::post('periods/{id}/close', [AcademicPeriodController::class, 'close']);
    
    // Actualizar y eliminar periodo
    Route::patch('periods/{id}', [AcademicPeriodController::class, 'update']);
    Route::delete('periods/{id}', [AcademicPeriodController::class, 'destroy']);

    // CU05 - Roles
    Route::get('roles', [RoleController::class, 'index']);
    Route::post('roles', [RoleController::class, 'store']);
    // Editar y eliminar rol
    Route::patch('roles/{id}', [RoleController::class, 'update']);
    Route::delete('roles/{id}', [RoleController::class, 'destroy']);
    Route::get('users/{id}/roles', [RoleController::class, 'getUserRoles']);
    Route::post('users/{id}/roles', [RoleController::class, 'assignToUser']);

    // CU06 - Teachers CRUD
    Route::get('teachers', [TeacherController::class, 'index']);
    Route::post('teachers', [TeacherController::class, 'store']);
    Route::get('teachers/{id}', [TeacherController::class, 'show']);
    Route::patch('teachers/{id}', [TeacherController::class, 'update']);
    Route::delete('teachers/{id}', [TeacherController::class, 'destroy']);
});
