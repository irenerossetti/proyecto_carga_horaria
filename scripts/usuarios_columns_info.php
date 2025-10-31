<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$rows = DB::select("select column_name, is_nullable, data_type from information_schema.columns where table_name='usuarios' and table_schema = current_schema()");
echo json_encode(array_map(function($r){ return (array)$r; }, $rows), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
