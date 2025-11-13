<?php

/**
 * Script de prueba para el módulo de Materias/Asignaturas
 * 
 * Este script prueba todas las operaciones CRUD del API de materias:
 * - CREATE (POST)
 * - READ (GET)
 * - UPDATE (PUT)
 * - DELETE (DELETE)
 * 
 * Uso: php test_subjects.php
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
echo "PRUEBA DE API: MATERIAS/ASIGNATURAS\n";
echo "========================================\n\n";

// ID de la materia creada
$subjectId = null;

try {
    // ============================================
    // 1. CREATE - Crear una nueva materia
    // ============================================
    echo "1️⃣  CREANDO NUEVA MATERIA...\n";
    echo "-----------------------------------\n";
    
    $newSubject = [
        'name' => 'Desarrollo de Software ' . rand(1, 999),
        'code' => 'MAT-' . time(),
        'credits' => rand(3, 6),
        'semester' => rand(1, 10),
        'department' => 'Ingeniería de Sistemas',
        'theory_hours' => rand(2, 4),
        'practice_hours' => rand(2, 4),
        'is_active' => true,
        'description' => 'Materia de prueba para el sistema de carga horaria',
    ];
    
    $response = Http::withHeaders($headers)->post("$baseUrl/subjects", $newSubject);
    
    echo "Status: " . $response->status() . "\n";
    echo "Datos enviados:\n";
    print_r($newSubject);
    echo "\nRespuesta del servidor:\n";
    print_r($response->json());
    echo "\n";
    
    if ($response->successful()) {
        $subjectId = $response->json()['data']['id'] ?? null;
        echo "✅ Materia creada exitosamente con ID: $subjectId\n\n";
    } else {
        echo "⚠️  Error al crear la materia (posiblemente la tabla no existe aún)\n";
        echo "Este módulo requiere migración previa\n\n";
    }

    // ============================================
    // 2. READ - Obtener todas las materias
    // ============================================
    echo "2️⃣  OBTENIENDO TODAS LAS MATERIAS...\n";
    echo "-----------------------------------\n";
    
    $response = Http::withHeaders($headers)->get("$baseUrl/subjects");
    
    echo "Status: " . $response->status() . "\n";
    
    if ($response->successful()) {
        echo "Cantidad de materias: " . count($response->json()['data'] ?? []) . "\n";
        echo "\nPrimeras 3 materias:\n";
        $subjects = array_slice($response->json()['data'] ?? [], 0, 3);
        print_r($subjects);
        echo "\n";
        
        if (!$subjectId && !empty($response->json()['data'])) {
            $subjectId = $response->json()['data'][0]['id'] ?? null;
            echo "ℹ️  Usando materia existente con ID: $subjectId para las siguientes pruebas\n\n";
        }
        
        echo "✅ Lista de materias obtenida exitosamente\n\n";
    } else {
        echo "⚠️  Error al obtener materias\n";
        echo "Respuesta: " . $response->body() . "\n\n";
    }

    // ============================================
    // 3. READ ONE - Obtener una materia específica
    // ============================================
    if ($subjectId) {
        echo "3️⃣  OBTENIENDO MATERIA ESPECÍFICA (ID: $subjectId)...\n";
        echo "-----------------------------------\n";
        
        $response = Http::withHeaders($headers)->get("$baseUrl/subjects/$subjectId");
        
        echo "Status: " . $response->status() . "\n";
        echo "Respuesta del servidor:\n";
        print_r($response->json());
        echo "\n";
        
        if ($response->successful()) {
            echo "✅ Materia obtenida exitosamente\n\n";
        } else {
            echo "❌ Error al obtener la materia\n\n";
        }
    }

    // ============================================
    // 4. UPDATE - Actualizar la materia
    // ============================================
    if ($subjectId) {
        echo "4️⃣  ACTUALIZANDO MATERIA (ID: $subjectId)...\n";
        echo "-----------------------------------\n";
        
        $updatedData = [
            'name' => 'Desarrollo de Software Avanzado ' . rand(1, 999),
            'credits' => rand(4, 6),
            'semester' => rand(5, 10),
            'department' => 'Ingeniería de Sistemas y Computación',
            'theory_hours' => 4,
            'practice_hours' => 4,
            'is_active' => true,
            'description' => 'Materia actualizada - contenido avanzado',
        ];
        
        $response = Http::withHeaders($headers)->put("$baseUrl/subjects/$subjectId", $updatedData);
        
        echo "Status: " . $response->status() . "\n";
        echo "Datos enviados:\n";
        print_r($updatedData);
        echo "\nRespuesta del servidor:\n";
        print_r($response->json());
        echo "\n";
        
        if ($response->successful()) {
            echo "✅ Materia actualizada exitosamente\n\n";
        } else {
            echo "❌ Error al actualizar la materia\n\n";
        }
    }

    // ============================================
    // 5. FILTER - Filtrar materias por semestre
    // ============================================
    echo "5️⃣  FILTRANDO MATERIAS POR SEMESTRE (5to)...\n";
    echo "-----------------------------------\n";
    
    $response = Http::withHeaders($headers)->get("$baseUrl/subjects?semester=5");
    
    echo "Status: " . $response->status() . "\n";
    
    if ($response->successful()) {
        echo "Materias del 5to semestre: " . count($response->json()['data'] ?? []) . "\n";
        echo "Primeros resultados:\n";
        print_r(array_slice($response->json()['data'] ?? [], 0, 2));
        echo "\n✅ Filtro aplicado exitosamente\n\n";
    } else {
        echo "⚠️  Error al filtrar materias\n\n";
    }

    // ============================================
    // 6. FILTER - Filtrar materias activas
    // ============================================
    echo "6️⃣  FILTRANDO MATERIAS ACTIVAS...\n";
    echo "-----------------------------------\n";
    
    $response = Http::withHeaders($headers)->get("$baseUrl/subjects?active=true");
    
    echo "Status: " . $response->status() . "\n";
    
    if ($response->successful()) {
        echo "Materias activas: " . count($response->json()['data'] ?? []) . "\n";
        echo "Primeros resultados:\n";
        print_r(array_slice($response->json()['data'] ?? [], 0, 2));
        echo "\n✅ Filtro aplicado exitosamente\n\n";
    } else {
        echo "⚠️  Error al filtrar materias activas\n\n";
    }

    // ============================================
    // 7. SEARCH - Buscar materias por nombre
    // ============================================
    echo "7️⃣  BUSCANDO MATERIAS POR NOMBRE (Software)...\n";
    echo "-----------------------------------\n";
    
    $response = Http::withHeaders($headers)->get("$baseUrl/subjects?search=Software");
    
    echo "Status: " . $response->status() . "\n";
    
    if ($response->successful()) {
        echo "Resultados encontrados: " . count($response->json()['data'] ?? []) . "\n";
        echo "Primeros resultados:\n";
        print_r(array_slice($response->json()['data'] ?? [], 0, 2));
        echo "\n✅ Búsqueda realizada exitosamente\n\n";
    } else {
        echo "⚠️  Error en la búsqueda\n\n";
    }

    // ============================================
    // 8. DELETE - Eliminar la materia
    // ============================================
    if ($subjectId) {
        echo "8️⃣  ELIMINANDO MATERIA (ID: $subjectId)...\n";
        echo "-----------------------------------\n";
        echo "⚠️  Comentado para evitar eliminar datos existentes\n";
        echo "Descomenta el código si quieres probar la eliminación\n\n";
        
        /*
        $response = Http::withHeaders($headers)->delete("$baseUrl/subjects/$subjectId");
        
        echo "Status: " . $response->status() . "\n";
        echo "Respuesta del servidor:\n";
        print_r($response->json());
        echo "\n";
        
        if ($response->successful()) {
            echo "✅ Materia eliminada exitosamente\n\n";
        } else {
            echo "❌ Error al eliminar la materia\n\n";
        }
        */
    }

    // ============================================
    // Resumen Final
    // ============================================
    echo "========================================\n";
    echo "✅ PRUEBAS COMPLETADAS\n";
    echo "========================================\n";
    echo "\nNOTA: Si la tabla 'subjects' no existe, necesitas:\n";
    echo "1. Crear la migración: php artisan make:migration create_subjects_table\n";
    echo "2. Ejecutar la migración: php artisan migrate\n";
    echo "3. Crear el controlador API con métodos CRUD\n";
    echo "4. Definir las rutas en routes/api.php\n";
    echo "\nCampos sugeridos para subjects:\n";
    echo "- name, code, credits, semester, department\n";
    echo "- theory_hours, practice_hours, is_active, description\n";

} catch (\Exception $e) {
    echo "\n❌ ERROR GENERAL:\n";
    echo $e->getMessage() . "\n";
    echo "\nStack Trace:\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
