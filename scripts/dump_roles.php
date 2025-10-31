<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $rows = DB::select('select rol_id,nombre,descripcion,privilegios,activo,fecha_creacion from roles');
    echo json_encode(array_map(function($r){ return (array)$r; }, $rows), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
