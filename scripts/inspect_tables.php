<?php
// Script simple para inspeccionar columnas de tablas en la BD usando el bootstrap de Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$tables = ['rooms', 'subjects', 'groups'];
$output = [];
foreach ($tables as $table) {
    $cols = DB::select("SELECT column_name, data_type, is_nullable, column_default
        FROM information_schema.columns
        WHERE table_schema = current_schema() AND table_name = ?
        ORDER BY ordinal_position", [$table]);
    $output[$table] = array_map(function($c){
        return (array) $c;
    }, $cols);
}

echo json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;
