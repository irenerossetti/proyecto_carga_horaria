<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$migrations = [
    '2025_10_31_110000_create_roles_and_role_user_tables',
    '2025_10_31_111000_create_teachers_table',
];

foreach ($migrations as $migration) {
    $exists = DB::table('migrations')->where('migration', $migration)->exists();
    if ($exists) {
        echo "Migration {$migration} already recorded\n";
        continue;
    }

    $maxBatch = DB::table('migrations')->max('batch');
    $batch = ($maxBatch ?: 0) + 1;

    DB::table('migrations')->insert([
        'migration' => $migration,
        'batch' => $batch,
    ]);

    echo "Recorded migration {$migration} in batch {$batch}\n";
}
