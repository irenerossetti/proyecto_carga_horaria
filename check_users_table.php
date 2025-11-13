<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== ESTRUCTURA DE TABLA USERS ===\n\n";

$columns = DB::select("SELECT column_name, data_type, is_nullable FROM information_schema.columns WHERE table_name = 'users' ORDER BY ordinal_position");

echo "Columnas:\n";
foreach ($columns as $column) {
    echo "  - {$column->column_name} ({$column->data_type}) " . ($column->is_nullable === 'NO' ? 'NOT NULL' : 'NULL') . "\n";
}
