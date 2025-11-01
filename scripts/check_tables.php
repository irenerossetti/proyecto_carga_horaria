<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;

$tables = [
    'users', 'usuarios', 'sessions', 'cache', 'jobs', 'role_user', 'roles', 'teachers', 'subjects', 'groups', 'rooms', 'migrations'
];

echo "Checking tables in DB:\n";
foreach ($tables as $t) {
    echo str_pad($t, 12) . ': ' . (Schema::hasTable($t) ? "YES" : "NO") . "\n";
}
