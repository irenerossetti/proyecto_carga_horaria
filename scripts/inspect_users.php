<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Checking 'users' table existence and constraints:\n";
$exists = DB::select("SELECT to_regclass('public.users') as reg");
print_r($exists);

echo "\nColumns:\n";
$cols = DB::select("SELECT column_name, data_type FROM information_schema.columns WHERE table_name='users' ORDER BY ordinal_position");
print_r($cols);

echo "\nUnique constraints on users:\n";
$consts = DB::select("SELECT conname, pg_get_constraintdef(c.oid) as def FROM pg_constraint c JOIN pg_class t ON c.conrelid = t.oid WHERE t.relname = 'users' AND c.contype = 'u'");
print_r($consts);
