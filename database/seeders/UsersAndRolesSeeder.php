<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersAndRolesSeeder extends Seeder
{
    /**
     * Crear usuarios de prueba para todos los roles.
     */
    public function run(): void
    {
        // Verificar que existen los roles
        $adminRole = Role::where('name', 'ADMIN')->first();
        $coordinadorRole = Role::where('name', 'COORDINADOR')->first();
        $docenteRole = Role::where('name', 'DOCENTE')->first();
        $estudianteRole = Role::where('name', 'ESTUDIANTE')->first();

        // Si no existen los roles, crearlos (solo name, sin description)
        if (!$adminRole) {
            $adminRole = Role::create(['name' => 'ADMIN']);
        }
        if (!$coordinadorRole) {
            $coordinadorRole = Role::create(['name' => 'COORDINADOR']);
        }
        if (!$docenteRole) {
            $docenteRole = Role::create(['name' => 'DOCENTE']);
        }
        if (!$estudianteRole) {
            $estudianteRole = Role::create(['name' => 'ESTUDIANTE']);
        }

        // Crear usuarios de prueba
        $users = [
            [
                'name' => 'Administrador Sistema',
                'email' => 'admin@ficct.edu.bo',
                'password' => 'admin123',
                'role' => $adminRole
            ],
            [
                'name' => 'Coordinador Académico',
                'email' => 'coordinador@ficct.edu.bo',
                'password' => 'coord123',
                'role' => $coordinadorRole
            ],
            [
                'name' => 'Docente Juan Pérez',
                'email' => 'docente@ficct.edu.bo',
                'password' => 'docente123',
                'role' => $docenteRole
            ],
            [
                'name' => 'María González',
                'email' => 'docente2@ficct.edu.bo',
                'password' => 'docente123',
                'role' => $docenteRole
            ],
            [
                'name' => 'Estudiante Carlos López',
                'email' => 'estudiante@ficct.edu.bo',
                'password' => 'estudiante123',
                'role' => $estudianteRole
            ],
            [
                'name' => 'Estudiante Ana Martínez',
                'email' => 'estudiante2@ficct.edu.bo',
                'password' => 'estudiante123',
                'role' => $estudianteRole
            ]
        ];

        foreach ($users as $userData) {
            // Verificar si el usuario ya existe
            $user = User::where('email', $userData['email'])->first();
            
            if (!$user) {
                // Crear el usuario
                $user = User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make($userData['password']),
                    'email_verified_at' => now(),
                ]);

                echo "✓ Usuario creado: {$userData['email']}\n";
            } else {
                echo "✓ Usuario ya existe: {$userData['email']}\n";
            }

            // Asignar el rol si no lo tiene
            if (!$user->roles()->where('role_id', $userData['role']->id)->exists()) {
                $user->roles()->attach($userData['role']->id);
                echo "  → Rol asignado: {$userData['role']->name}\n";
            } else {
                echo "  → Rol ya asignado: {$userData['role']->name}\n";
            }
        }

        echo "\n=== RESUMEN DE USUARIOS ===\n\n";
        echo "ADMIN:\n";
        echo "  📧 admin@ficct.edu.bo\n";
        echo "  🔑 admin123\n\n";
        
        echo "COORDINADOR:\n";
        echo "  📧 coordinador@ficct.edu.bo\n";
        echo "  🔑 coord123\n\n";
        
        echo "DOCENTES:\n";
        echo "  📧 docente@ficct.edu.bo\n";
        echo "  🔑 docente123\n";
        echo "  📧 docente2@ficct.edu.bo\n";
        echo "  🔑 docente123\n\n";
        
        echo "ESTUDIANTES:\n";
        echo "  📧 estudiante@ficct.edu.bo\n";
        echo "  🔑 estudiante123\n";
        echo "  📧 estudiante2@ficct.edu.bo\n";
        echo "  🔑 estudiante123\n\n";
    }
}
