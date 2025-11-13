<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DIAGNÓSTICO DEL SISTEMA ===\n\n";

echo "1. Verificando archivos de controladores...\n";
$controllers = [
    'AdminDashboardController' => app_path('Http/Controllers/AdminDashboardController.php'),
    'CoordinatorDashboardController' => app_path('Http/Controllers/CoordinatorDashboardController.php'),
    'StudentDashboardController' => app_path('Http/Controllers/StudentDashboardController.php'),
];

foreach ($controllers as $name => $path) {
    if (file_exists($path)) {
        echo "  ✓ {$name}: EXISTE\n";
        
        // Verificar que no tenga errores de sintaxis
        try {
            include_once $path;
            echo "    → Sin errores de sintaxis\n";
        } catch (\Exception $e) {
            echo "    ✗ ERROR: " . $e->getMessage() . "\n";
        }
    } else {
        echo "  ✗ {$name}: NO ENCONTRADO\n";
    }
}

echo "\n2. Verificando modelos que existen...\n";
$models = [
    'User' => app_path('Models/User.php'),
    'Role' => app_path('Models/Role.php'),
    'Teacher' => app_path('Models/Teacher.php'),
    'Room' => app_path('Models/Room.php'),
    'Schedule' => app_path('Models/Schedule.php'),
];

foreach ($models as $name => $path) {
    echo (file_exists($path) ? "  ✓" : "  ✗") . " {$name}\n";
}

echo "\n3. Verificando modelos que NO existen (deben estar comentados)...\n";
$missingModels = ['Subject', 'Conflict', 'Group', 'Attendance', 'Reservation'];
foreach ($missingModels as $model) {
    $path = app_path("Models/{$model}.php");
    if (file_exists($path)) {
        echo "  ⚠ {$model}: EXISTE (pero tabla no existe en BD)\n";
    } else {
        echo "  ✓ {$model}: NO EXISTE (correcto)\n";
    }
}

echo "\n4. Probando AdminDashboardController...\n";
try {
    $controller = new \App\Http\Controllers\AdminDashboardController();
    echo "  ✓ Instancia creada correctamente\n";
} catch (\Exception $e) {
    echo "  ✗ ERROR: " . $e->getMessage() . "\n";
    echo "    Stack: " . $e->getTraceAsString() . "\n";
}

echo "\n5. Verificando rutas...\n";
try {
    $routes = \Illuminate\Support\Facades\Route::getRoutes();
    $dashboardRoute = $routes->getByName('dashboard');
    if ($dashboardRoute) {
        echo "  ✓ Ruta 'dashboard' registrada\n";
    } else {
        echo "  ✗ Ruta 'dashboard' NO encontrada\n";
    }
} catch (\Exception $e) {
    echo "  ✗ ERROR al verificar rutas: " . $e->getMessage() . "\n";
}

echo "\n6. Verificando usuario admin...\n";
try {
    $admin = \App\Models\User::where('email', 'admin@ficct.edu.bo')->first();
    if ($admin) {
        echo "  ✓ Usuario admin existe (ID: {$admin->id})\n";
        $roles = $admin->roles()->pluck('name')->toArray();
        echo "    Roles: " . implode(', ', $roles) . "\n";
    } else {
        echo "  ✗ Usuario admin NO encontrado\n";
    }
} catch (\Exception $e) {
    echo "  ✗ ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== FIN DEL DIAGNÓSTICO ===\n";
