<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== VERIFICANDO TABLAS EN LA BASE DE DATOS ===\n\n";

try {
    // Obtener todas las tablas
    $tables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' ORDER BY table_name");
    
    echo "Tablas encontradas en el esquema 'public':\n";
    echo "---------------------------------------\n";
    foreach ($tables as $table) {
        echo "✓ " . $table->table_name . "\n";
    }
    
    echo "\n=== VERIFICANDO TABLAS CRÍTICAS ===\n\n";
    
    $criticalTables = ['users', 'roles', 'role_user', 'teachers', 'subjects', 'rooms', 'schedules', 'attendances'];
    
    foreach ($criticalTables as $table) {
        $exists = Schema::hasTable($table);
        echo ($exists ? "✓" : "✗") . " Tabla '{$table}': " . ($exists ? "EXISTE" : "NO EXISTE") . "\n";
        
        if ($exists) {
            try {
                $count = DB::table($table)->count();
                echo "  → Registros: {$count}\n";
            } catch (\Exception $e) {
                echo "  → Error al contar: " . $e->getMessage() . "\n";
            }
        }
    }
    
    echo "\n=== VERIFICANDO USUARIOS ===\n\n";
    
    $users = DB::table('users')->select('id', 'name', 'email')->get();
    foreach ($users as $user) {
        echo "Usuario ID {$user->id}: {$user->name} ({$user->email})\n";
        
        // Verificar roles
        $roles = DB::table('role_user')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->where('role_user.user_id', $user->id)
            ->select('roles.name')
            ->get();
            
        if ($roles->count() > 0) {
            echo "  Roles: " . $roles->pluck('name')->implode(', ') . "\n";
        } else {
            echo "  Roles: NINGUNO ASIGNADO\n";
        }
    }
    
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== FINALIZADO ===\n";
