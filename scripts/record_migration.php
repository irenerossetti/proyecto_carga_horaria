<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$migrationName = $argv[1] ?? null;
if (! $migrationName) {
    echo "Usage: php scripts/record_migration.php <migration_filename_without_php>\n";
    exit(1);
}

// Ensure migrations table exists
if (! DB::getSchemaBuilder()->hasTable('migrations')) {
    echo "migrations table does not exist.\n";
    exit(1);
}

$max = DB::table('migrations')->max('batch') ?? 0;
$batch = $max + 1;

DB::table('migrations')->insert([
    'migration' => $migrationName,
    'batch' => $batch,
]);

echo "Recorded migration '{$migrationName}' as batch={$batch}\n";
