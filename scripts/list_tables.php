<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$rows = DB::select("select tablename from pg_tables where schemaname = current_schema()");
echo json_encode(array_map(fn($r) => (array)$r, $rows), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
