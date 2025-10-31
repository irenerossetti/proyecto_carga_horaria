<?php

// Script helper: bootstrap the app and create academic_periods table idempotently.
// Run with: php scripts/create_academic_periods_table.php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

try {
    if (Schema::hasTable('academic_periods')) {
        echo "Table academic_periods already exists.\n";
        exit(0);
    }

    Schema::create('academic_periods', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('status')->default('draft');
        $table->date('start_date')->nullable();
        $table->date('end_date')->nullable();
        $table->timestamps();
    });

    echo "Created academic_periods table.\n";
} catch (Throwable $e) {
    echo "Error creating table: " . $e->getMessage() . "\n";
    exit(1);
}
