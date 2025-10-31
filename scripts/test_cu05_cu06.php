<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Role;
use App\Models\User;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;

echo "Starting CU05/CU06 test script...\n";

// Create default roles (map to legacy 'nombre')
$roles = ['administrador', 'docente', 'estudiante'];
foreach ($roles as $r) {
    // case-insensitive lookup
    $role = Role::whereRaw('LOWER(nombre) = ?', [strtolower($r)])->first();
    if (!$role) {
        // compute next rol_id to avoid serial/sequence issues
        $max = Illuminate\Support\Facades\DB::table('roles')->max('rol_id') ?? 0;
        $next = $max + 1;
        Illuminate\Support\Facades\DB::table('roles')->insert([
            'rol_id' => $next,
            'nombre' => $r,
            'descripcion' => null,
            'privilegios' => null,
            'activo' => true,
            'fecha_creacion' => now(),
        ]);
        $role = Role::find($next);
        echo "Inserted role with rol_id={$next} nombre={$r}\n";
    } else {
        echo "Role exists: {$role->nombre}\n";
    }
}

// Create admin user in legacy 'usuarios' table
$adminEmail = 'admin@example.test';
$existing = Illuminate\Support\Facades\DB::table('usuarios')->where('email', $adminEmail)->first();
if ($existing) {
    $adminId = $existing->usuario_id;
    echo "Admin user exists: {$adminEmail} (id={$adminId})\n";
} else {
    $max = Illuminate\Support\Facades\DB::table('usuarios')->max('usuario_id') ?? 0;
    $adminId = $max + 1;

    // pick admin role id (prefer ADMIN or administrador)
    $adminRole = Role::whereRaw('LOWER(nombre) IN (?, ?)', ['admin', 'administrador'])->first();
    if (! $adminRole) {
        $adminRole = Role::first();
    }

    Illuminate\Support\Facades\DB::table('usuarios')->insert([
        'usuario_id' => $adminId,
        'rol_id' => $adminRole->rol_id,
        'email' => $adminEmail,
        'password_hash' => Hash::make('Secret123!'),
        'nombre' => 'Admin',
        'apellido' => 'Test',
        'ci' => '00000000',
        'telefono' => null,
        'activo' => true,
        'fecha_creacion' => now(),
    ]);
    echo "Inserted admin user: {$adminEmail} (id={$adminId})\n";
}

$admin = User::find($adminId);
// Assign 'administrador' role to admin (via assignRoles helper)
$assigned = $admin->assignRoles(['administrador']);
echo "Assigned roles to admin: " . implode(',', $assigned) . "\n";

// Create a teacher
// Create a teacher (skip if email already exists)
$teacherEmail = 'juan@uni.edu';
$existingTeacher = Illuminate\Support\Facades\DB::table('teachers')->where('email', $teacherEmail)->first();
if ($existingTeacher) {
    echo "Teacher exists: {$existingTeacher->id} - {$existingTeacher->name}\n";
} else {
    $teacher = Teacher::create([
        'name' => 'Juan Perez',
        'email' => $teacherEmail,
        'dni' => '12345678',
        'phone' => '555-1234',
        'department' => 'Matemáticas',
    ]);
    echo "Created teacher: {$teacher->id} - {$teacher->name}\n";
}

echo "CU05/CU06 test script completed.\n";
