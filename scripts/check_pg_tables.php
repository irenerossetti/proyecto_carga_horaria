<?php
// Usage: php scripts/check_pg_tables.php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$schema = $app['db']->connection('pgsql')->getSchemaBuilder();
$tables = ['attendances','schedules','class_cancellations'];
foreach ($tables as $t) {
    echo $t.':' . ($schema->hasTable($t) ? 'yes' : 'no') . PHP_EOL;
}
