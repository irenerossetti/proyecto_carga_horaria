<?php

/**
 * Script de prueba para el módulo de Estudiantes
 * 
 * Este script prueba todas las operaciones CRUD del API de estudiantes:
 * - CREATE (POST)
 * - READ (GET)
 * - UPDATE (PUT)
 * - DELETE (DELETE)
 * 
 * Uso: php test_students.php
 */

require __DIR__ . '/../../vendor/autoload.php';

use Illuminate\Support\Facades\Http;

// Cargar la aplicación Laravel
$app = require_once __DIR__ . '/../../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Configuración
$baseUrl = 'http://127.0.0.1:8000/api';
$headers = [
    'Accept' => 'application/json',
    'Content-Type' => 'application/json',
];

echo "========================================\n";
echo "PRUEBA DE API: ESTUDIANTES\n";
echo "========================================\n\n";

// ID del estudiante creado
$studentId = null;

try {
    // ============================================
    // 1. CREATE - Crear un nuevo estudiante
    // ============================================
    echo "1️⃣  CREANDO NUEVO ESTUDIANTE...\n";
    echo "-----------------------------------\n";
    
    $newStudent = [
        'name' => 'María Fernanda López García',
        'email' => 'maria.lopez.test' . time() . '@est.ficct.edu.bo',
        'phone' => '+591 7' . rand(1000000, 9999999),
        'registration_code' => 'EST-' . time(),
        'career' => 'Ingeniería de Sistemas',
        'semester' => rand(1, 10),
        'enrollment_year' => 2024,
    ];
    
    $response = Http::withHeaders($headers)->post("$baseUrl/students", $newStudent);
    
    echo "Status: " . $response->status() . "\n";
    echo "Datos enviados:\n";
    print_r($newStudent);
    echo "\nRespuesta del servidor:\n";
    print_r($response->json());
    echo "\n";
    
    if ($response->successful()) {
        $studentId = $response->json()['data']['id'] ?? null;
        echo "✅ Estudiante creado exitosamente con ID: $studentId\n\n";
    } else {
        echo "⚠️  Error al crear el estudiante (posiblemente la tabla no existe aún)\n";
        echo "Este módulo requiere migración previa\n\n";
    }

    // ============================================
    // 2. READ - Obtener todos los estudiantes
    // ============================================
    echo "2️⃣  OBTENIENDO TODOS LOS ESTUDIANTES...\n";
    echo "-----------------------------------\n";
    
    $response = Http::withHeaders($headers)->get("$baseUrl/students");
    
    echo "Status: " . $response->status() . "\n";
    
    if ($response->successful()) {
        echo "Cantidad de estudiantes: " . count($response->json()['data'] ?? []) . "\n";
        echo "\nPrimeros 3 estudiantes:\n";
        $students = array_slice($response->json()['data'] ?? [], 0, 3);
        print_r($students);
        echo "\n";
        
        if (!$studentId && !empty($response->json()['data'])) {
            $studentId = $response->json()['data'][0]['id'] ?? null;
            echo "ℹ️  Usando estudiante existente con ID: $studentId para las siguientes pruebas\n\n";
        }
        
        echo "✅ Lista de estudiantes obtenida exitosamente\n\n";
    } else {
        echo "⚠️  Error al obtener estudiantes\n";
        echo "Respuesta: " . $response->body() . "\n\n";
    }

    // ============================================
    // 3. READ ONE - Obtener un estudiante específico
    // ============================================
    if ($studentId) {
        echo "3️⃣  OBTENIENDO ESTUDIANTE ESPECÍFICO (ID: $studentId)...\n";
        echo "-----------------------------------\n";
        
        $response = Http::withHeaders($headers)->get("$baseUrl/students/$studentId");
        
        echo "Status: " . $response->status() . "\n";
        echo "Respuesta del servidor:\n";
        print_r($response->json());
        echo "\n";
        
        if ($response->successful()) {
            echo "✅ Estudiante obtenido exitosamente\n\n";
        } else {
            echo "❌ Error al obtener el estudiante\n\n";
        }
    }

    // ============================================
    // 4. UPDATE - Actualizar el estudiante
    // ============================================
    if ($studentId) {
        echo "4️⃣  ACTUALIZANDO ESTUDIANTE (ID: $studentId)...\n";
        echo "-----------------------------------\n";
        
        $updatedData = [
            'name' => 'María Fernanda López García ACTUALIZADA',
            'email' => 'maria.lopez.updated' . time() . '@est.ficct.edu.bo',
            'phone' => '+591 7' . rand(1000000, 9999999),
            'career' => 'Ingeniería de Sistemas e Informática',
            'semester' => rand(1, 10),
        ];
        
        $response = Http::withHeaders($headers)->put("$baseUrl/students/$studentId", $updatedData);
        
        echo "Status: " . $response->status() . "\n";
        echo "Datos enviados:\n";
        print_r($updatedData);
        echo "\nRespuesta del servidor:\n";
        print_r($response->json());
        echo "\n";
        
        if ($response->successful()) {
            echo "✅ Estudiante actualizado exitosamente\n\n";
        } else {
            echo "❌ Error al actualizar el estudiante\n\n";
        }
    }

    // ============================================
    // 5. FILTER - Filtrar estudiantes por carrera
    // ============================================
    echo "5️⃣  FILTRANDO ESTUDIANTES POR CARRERA...\n";
    echo "-----------------------------------\n";
    
    $response = Http::withHeaders($headers)->get("$baseUrl/students?career=Sistemas");
    
    echo "Status: " . $response->status() . "\n";
    
    if ($response->successful()) {
        echo "Resultados encontrados: " . count($response->json()['data'] ?? []) . "\n";
        echo "Primeros resultados:\n";
        print_r(array_slice($response->json()['data'] ?? [], 0, 2));
        echo "\n✅ Filtro aplicado exitosamente\n\n";
    } else {
        echo "⚠️  Error al filtrar estudiantes\n\n";
    }

    // ============================================
    // 6. DELETE - Eliminar el estudiante
    // ============================================
    if ($studentId) {
        echo "6️⃣  ELIMINANDO ESTUDIANTE (ID: $studentId)...\n";
        echo "-----------------------------------\n";
        echo "⚠️  Comentado para evitar eliminar datos existentes\n";
        echo "Descomenta el código si quieres probar la eliminación\n\n";
        
        /*
        $response = Http::withHeaders($headers)->delete("$baseUrl/students/$studentId");
        
        echo "Status: " . $response->status() . "\n";
        echo "Respuesta del servidor:\n";
        print_r($response->json());
        echo "\n";
        
        if ($response->successful()) {
            echo "✅ Estudiante eliminado exitosamente\n\n";
        } else {
            echo "❌ Error al eliminar el estudiante\n\n";
        }
        */
    }

    // ============================================
    // Resumen Final
    // ============================================
    echo "========================================\n";
    echo "✅ PRUEBAS COMPLETADAS\n";
    echo "========================================\n";
    echo "\nNOTA: Si la tabla 'students' no existe, necesitas:\n";
    echo "1. Crear la migración: php artisan make:migration create_students_table\n";
    echo "2. Ejecutar la migración: php artisan migrate\n";
    echo "3. Crear el controlador API: AcademicPeriodController (ya existe como ejemplo)\n";
    echo "4. Definir las rutas en routes/api.php\n";

} catch (\Exception $e) {
    echo "\n❌ ERROR GENERAL:\n";
    echo $e->getMessage() . "\n";
    echo "\nStack Trace:\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
