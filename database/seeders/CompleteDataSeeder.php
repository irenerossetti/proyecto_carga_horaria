<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CompleteDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Desactivar verificación de claves foráneas temporalmente
        DB::statement('SET CONSTRAINTS ALL DEFERRED');

        echo "🌱 Iniciando población de base de datos...\n\n";

        // 1. ROLES
        echo "📋 Creando roles...\n";
        $roles = [
            ['name' => 'ADMIN', 'guard_name' => 'web'],
            ['name' => 'DOCENTE', 'guard_name' => 'web'],
            ['name' => 'ESTUDIANTE', 'guard_name' => 'web'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->insertOrIgnore($role);
        }
        $adminRoleId = DB::table('roles')->where('name', 'ADMIN')->first()->id;
        $docenteRoleId = DB::table('roles')->where('name', 'DOCENTE')->first()->id;
        $estudianteRoleId = DB::table('roles')->where('name', 'ESTUDIANTE')->first()->id;

        // 2. USUARIOS (10 de cada tipo)
        echo "👥 Creando usuarios...\n";
        $users = [];
        
        // 1 Admin
        $adminUser = [
            'name' => 'Administrador Principal',
            'email' => 'admin@universidad.edu',
            'registration_number' => '000000001',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('users')->insertOrIgnore($adminUser);
        $adminUserId = DB::table('users')->where('email', 'admin@universidad.edu')->first()->id;
        DB::table('role_user')->insertOrIgnore(['user_id' => $adminUserId, 'role_id' => $adminRoleId]);

        // 10 Docentes
        $teacherNames = [
            'Dr. Carlos Rodríguez',
            'Dra. María González',
            'Ing. Pedro Martínez',
            'Lic. Ana López',
            'Dr. José Fernández',
            'Dra. Laura Sánchez',
            'Ing. Roberto Díaz',
            'Lic. Carmen Torres',
            'Dr. Miguel Ramírez',
            'Dra. Patricia Morales'
        ];

        $teacherIds = [];
        foreach ($teacherNames as $index => $name) {
            $email = 'docente' . ($index + 1) . '@universidad.edu';
            $regNumber = str_pad(100 + $index, 9, '0', STR_PAD_LEFT);
            
            DB::table('users')->insertOrIgnore([
                'name' => $name,
                'email' => $email,
                'registration_number' => $regNumber,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $userId = DB::table('users')->where('email', $email)->first()->id;
            DB::table('role_user')->insertOrIgnore(['user_id' => $userId, 'role_id' => $docenteRoleId]);
            $teacherIds[] = $userId;
        }

        // 10 Estudiantes
        $studentNames = [
            'Juan Pérez',
            'María García',
            'Carlos López',
            'Ana Martínez',
            'Luis Hernández',
            'Sofia Romero',
            'Diego Silva',
            'Valentina Cruz',
            'Mateo Ruiz',
            'Camila Vargas'
        ];

        foreach ($studentNames as $index => $name) {
            $email = 'estudiante' . ($index + 1) . '@universidad.edu';
            $regNumber = str_pad(200 + $index, 9, '0', STR_PAD_LEFT);
            
            DB::table('users')->insertOrIgnore([
                'name' => $name,
                'email' => $email,
                'registration_number' => $regNumber,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $userId = DB::table('users')->where('email', $email)->first()->id;
            DB::table('role_user')->insertOrIgnore(['user_id' => $userId, 'role_id' => $estudianteRoleId]);
        }

        // 3. TEACHERS (relacionados con usuarios docentes)
        echo "👨‍🏫 Creando registros de docentes...\n";
        foreach ($teacherNames as $index => $name) {
            $email = 'docente' . ($index + 1) . '@universidad.edu';
            $userId = DB::table('users')->where('email', $email)->first()->id;
            
            DB::table('teachers')->insertOrIgnore([
                'user_id' => $userId,
                'name' => $name,
                'email' => $email,
                'phone' => '+591 7' . rand(1000000, 9999999),
            ]);
        }

        // 4. PERIODOS ACADÉMICOS
        echo "📅 Creando periodos académicos...\n";
        $periods = [
            ['name' => '2024-I', 'status' => 'closed', 'start_date' => '2024-01-15', 'end_date' => '2024-06-30'],
            ['name' => '2024-II', 'status' => 'closed', 'start_date' => '2024-07-15', 'end_date' => '2024-12-20'],
            ['name' => '2025-I', 'status' => 'active', 'start_date' => '2025-01-15', 'end_date' => '2025-06-30'],
            ['name' => '2025-II', 'status' => 'draft', 'start_date' => '2025-07-15', 'end_date' => '2025-12-20'],
        ];

        foreach ($periods as $period) {
            DB::table('academic_periods')->insertOrIgnore(array_merge($period, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // 5. MATERIAS
        echo "📚 Creando materias...\n";
        $subjects = [
            ['code' => 'INF-111', 'name' => 'Programación I', 'credits' => 4, 'description' => 'Introducción a la programación'],
            ['code' => 'INF-121', 'name' => 'Programación II', 'credits' => 4, 'description' => 'Programación orientada a objetos'],
            ['code' => 'MAT-101', 'name' => 'Cálculo I', 'credits' => 5, 'description' => 'Cálculo diferencial'],
            ['code' => 'MAT-102', 'name' => 'Cálculo II', 'credits' => 5, 'description' => 'Cálculo integral'],
            ['code' => 'INF-211', 'name' => 'Estructuras de Datos', 'credits' => 4, 'description' => 'Algoritmos y estructuras'],
            ['code' => 'INF-221', 'name' => 'Base de Datos I', 'credits' => 4, 'description' => 'Diseño de bases de datos'],
            ['code' => 'INF-231', 'name' => 'Ingeniería de Software', 'credits' => 4, 'description' => 'Metodologías de desarrollo'],
            ['code' => 'INF-241', 'name' => 'Redes de Computadoras', 'credits' => 4, 'description' => 'Arquitectura de redes'],
            ['code' => 'INF-311', 'name' => 'Inteligencia Artificial', 'credits' => 4, 'description' => 'Fundamentos de IA'],
            ['code' => 'INF-321', 'name' => 'Desarrollo Web', 'credits' => 4, 'description' => 'Aplicaciones web modernas'],
        ];

        foreach ($subjects as $subject) {
            DB::table('subjects')->insertOrIgnore(array_merge($subject, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // 6. AULAS
        echo "🏫 Creando aulas...\n";
        $buildings = ['A', 'B', 'C'];
        $roomId = 1;
        
        foreach ($buildings as $building) {
            for ($floor = 1; $floor <= 3; $floor++) {
                for ($room = 1; $room <= 3; $room++) {
                    if ($roomId > 10) break 3;
                    
                    $roomName = "Aula {$building}{$floor}0{$room}";
                    $capacity = rand(20, 50);
                    
                    DB::table('rooms')->insertOrIgnore([
                        'name' => $roomName,
                        'capacity' => $capacity,
                        'location' => "Edificio {$building}, Piso {$floor}",
                        'resources' => json_encode([
                            'projector' => rand(0, 1) == 1,
                            'computers' => rand(0, 1) == 1 ? rand(10, 30) : 0,
                            'whiteboard' => true,
                            'ac' => rand(0, 1) == 1,
                        ]),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    $roomId++;
                }
            }
        }

        // 7. GRUPOS
        echo "👥 Creando grupos...\n";
        $subjectIds = DB::table('subjects')->pluck('id')->toArray();
        
        foreach ($subjectIds as $index => $subjectId) {
            $subject = DB::table('subjects')->where('id', $subjectId)->first();
            
            DB::table('groups')->insertOrIgnore([
                'subject_id' => $subjectId,
                'code' => $subject->code . '-A',
                'name' => $subject->name . ' - Grupo A',
                'capacity' => 30,
                'schedule' => 'LMV 08:00-10:00',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 8. HORARIOS (SCHEDULES)
        echo "⏰ Creando horarios...\n";
        $days = ['lunes', 'martes', 'miércoles', 'jueves', 'viernes'];
        $times = [
            ['08:00:00', '10:00:00'],
            ['10:00:00', '12:00:00'],
            ['14:00:00', '16:00:00'],
            ['16:00:00', '18:00:00'],
        ];

        $groups = DB::table('groups')->get();
        $rooms = DB::table('rooms')->pluck('id')->toArray();
        $teachers = DB::table('teachers')->pluck('id')->toArray();

        $scheduleCount = 0;
        foreach ($groups as $group) {
            if ($scheduleCount >= 10) break;
            
            $day = $days[array_rand($days)];
            $time = $times[array_rand($times)];
            
            DB::table('schedules')->insertOrIgnore([
                'group_id' => $group->id,
                'room_id' => $rooms[array_rand($rooms)],
                'teacher_id' => $teachers[array_rand($teachers)],
                'day_of_week' => $day,
                'start_time' => $time[0],
                'end_time' => $time[1],
                'assigned_by' => $adminUserId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $scheduleCount++;
        }

        // 9. ASISTENCIAS
        echo "✅ Creando registros de asistencia...\n";
        $schedules = DB::table('schedules')->take(10)->get();
        
        foreach ($schedules as $schedule) {
            DB::table('attendances')->insertOrIgnore([
                'teacher_id' => $schedule->teacher_id,
                'schedule_id' => $schedule->id,
                'date' => now()->subDays(rand(1, 30))->format('Y-m-d'),
                'time' => now()->format('H:i:s'),
                'status' => ['present', 'late'][rand(0, 1)],
                'notes' => 'Registro de asistencia automático',
                'recorded_by' => $adminUserId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 10. SYSTEM PARAMETERS
        echo "⚙️ Creando parámetros del sistema...\n";
        $parameters = [
            ['key' => 'max_schedule_conflicts', 'value' => '5', 'type' => 'integer', 'description' => 'Máximo de conflictos permitidos', 'updated_by' => $adminUserId],
            ['key' => 'default_class_duration', 'value' => '120', 'type' => 'integer', 'description' => 'Duración por defecto en minutos', 'updated_by' => $adminUserId],
            ['key' => 'attendance_tolerance', 'value' => '15', 'type' => 'integer', 'description' => 'Tolerancia de asistencia en minutos', 'updated_by' => $adminUserId],
            ['key' => 'qr_expiration', 'value' => '300', 'type' => 'integer', 'description' => 'Expiración del QR en segundos', 'updated_by' => $adminUserId],
            ['key' => 'max_room_capacity', 'value' => '50', 'type' => 'integer', 'description' => 'Capacidad máxima de aulas', 'updated_by' => $adminUserId],
        ];

        foreach ($parameters as $param) {
            DB::table('system_parameters')->insertOrIgnore(array_merge($param, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // 11. ANNOUNCEMENTS
        echo "📢 Creando anuncios...\n";
        $announcements = [
            ['title' => 'Inicio de clases 2025-I', 'body' => 'Las clases del primer semestre inician el 15 de enero', 'published_at' => '2025-01-01', 'expires_at' => '2025-01-15', 'pinned' => 'true'],
            ['title' => 'Exámenes finales', 'body' => 'Los exámenes finales se realizarán del 15 al 25 de junio', 'published_at' => '2025-06-01', 'expires_at' => '2025-06-25', 'pinned' => 'true'],
            ['title' => 'Inscripciones abiertas', 'body' => 'Período de inscripciones para nuevas materias', 'published_at' => '2025-01-05', 'expires_at' => '2025-01-20', 'pinned' => 'false'],
            ['title' => 'Mantenimiento de sistemas', 'body' => 'Se realizará mantenimiento este sábado', 'published_at' => now()->format('Y-m-d'), 'expires_at' => now()->addDays(3)->format('Y-m-d'), 'pinned' => 'false'],
        ];

        foreach ($announcements as $announcement) {
            DB::table('announcements')->insertOrIgnore(array_merge($announcement, [
                'published_by' => $adminUserId,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // 12. ACTIVITY LOGS
        echo "📝 Creando logs de actividad...\n";
        $actions = ['login', 'logout', 'create', 'update', 'delete'];
        $modules = ['schedules', 'attendances', 'teachers', 'rooms', 'subjects'];
        
        for ($i = 0; $i < 10; $i++) {
            $userId = $teacherIds[array_rand($teacherIds)];
            $user = DB::table('users')->where('id', $userId)->first();
            
            DB::table('activity_logs')->insertOrIgnore([
                'user_id' => $userId,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'user_role' => 'DOCENTE',
                'action' => $actions[array_rand($actions)],
                'module' => $modules[array_rand($modules)],
                'description' => 'Acción realizada por el usuario',
                'ip_address' => '192.168.1.' . rand(1, 255),
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'url' => '/api/' . $modules[array_rand($modules)],
                'method' => ['GET', 'POST', 'PATCH', 'DELETE'][rand(0, 3)],
                'created_at' => now()->subDays(rand(1, 30)),
            ]);
        }

        echo "\n✅ ¡Base de datos poblada exitosamente!\n";
        echo "📊 Resumen:\n";
        echo "   - 3 Roles\n";
        echo "   - " . DB::table('users')->count() . " Usuarios (1 admin, 10 docentes, 10 estudiantes)\n";
        echo "   - " . DB::table('teachers')->count() . " Docentes\n";
        echo "   - " . DB::table('academic_periods')->count() . " Períodos académicos\n";
        echo "   - " . DB::table('subjects')->count() . " Materias\n";
        echo "   - " . DB::table('rooms')->count() . " Aulas\n";
        echo "   - " . DB::table('groups')->count() . " Grupos\n";
        echo "   - " . DB::table('schedules')->count() . " Horarios\n";
        echo "   - " . DB::table('attendances')->count() . " Asistencias\n";
        echo "   - " . DB::table('system_parameters')->count() . " Parámetros del sistema\n";
        echo "   - " . DB::table('announcements')->count() . " Anuncios\n";
        echo "   - " . DB::table('activity_logs')->count() . " Logs de actividad\n";
        echo "\n🔑 Credenciales:\n";
        echo "   Admin: admin@universidad.edu / password\n";
        echo "   Docente: docente1@universidad.edu / password\n";
        echo "   Estudiante: estudiante1@universidad.edu / password\n";
    }
}
