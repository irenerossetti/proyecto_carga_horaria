<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "Roles table columns:\n";
try {
    if (!Schema::hasTable('roles')) {
        echo "  (roles table does not exist)\n";
        exit(0);
    }
    $cols = Schema::getColumnListing('roles');
    foreach ($cols as $c) {
        echo "  - {$c}\n";
    }

    echo "\nForeign key constraints referencing roles (other tables):\n";
    $sql = <<<'SQL'
SELECT tc.constraint_name, tc.table_name, kcu.column_name
FROM information_schema.table_constraints tc
JOIN information_schema.key_column_usage kcu ON tc.constraint_name = kcu.constraint_name AND tc.table_schema = kcu.table_schema
JOIN information_schema.referential_constraints rc ON tc.constraint_name = rc.constraint_name AND tc.table_schema = rc.constraint_schema
JOIN information_schema.constraint_column_usage ccu ON rc.unique_constraint_name = ccu.constraint_name AND rc.unique_constraint_schema = ccu.constraint_schema
WHERE ccu.table_name = 'roles' AND tc.constraint_type = 'FOREIGN KEY';
SQL;

    $rows = DB::select($sql);
    if (empty($rows)) {
        echo "  (none)\n";
    } else {
        foreach ($rows as $r) {
            echo "  - constraint={$r->constraint_name}, table={$r->table_name}, column={$r->column_name}\n";
        }
    }
} catch (Throwable $e) {
    echo "Error inspecting roles: " . $e->getMessage() . "\n";
    exit(1);
}
