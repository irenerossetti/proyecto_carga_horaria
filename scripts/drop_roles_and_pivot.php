<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "Dropping role_user and roles tables if they exist...\n";

try {
    if (Schema::hasTable('role_user')) {
        Schema::dropIfExists('role_user');
        echo "Dropped role_user\n";
    } else {
        echo "role_user does not exist\n";
    }

    if (Schema::hasTable('roles')) {
        Schema::dropIfExists('roles');
        echo "Dropped roles\n";
    } else {
        echo "roles does not exist\n";
    }

    exit(0);
} catch (Throwable $e) {
    echo "Error dropping tables: " . $e->getMessage() . "\n";
    exit(1);
}
