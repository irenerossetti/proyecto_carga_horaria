<?php

/**
 * Script de prueba para el módulo de Docentes
 * 
 * Este script prueba todas las operaciones CRUD del API de docentes:
 * - CREATE (POST)
 * - READ (GET)
 * - UPDATE (PUT)
 * - DELETE (DELETE)
 * 
 * Uso: php test_teachers.php
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
echo "PRUEBA DE API: DOCENTES\n";
echo "========================================\n\n";

// ID del docente creado
$teacherId = null;

try {
    // ============================================
    // 1. CREATE - Crear un nuevo docente
    // ============================================
    echo "1️⃣  CREANDO NUEVO DOCENTE...\n";
    echo "-----------------------------------\n";
    
    $newTeacher = [
        'name' => 'Prof. Juan Carlos Pérez',
        'email' => 'juan.perez.test' . time() . '@ficct.edu.bo',
        'phone' => '+591 7' . rand(1000000, 9999999),
        'code' => 'DOC-' . time(),
        'specialization' => 'Ingeniería de Software',
        'department' => 'Sistemas',
    ];
    
    $response = Http::withHeaders($headers)->post("$baseUrl/teachers", $newTeacher);
    
    echo "Status: " . $response->status() . "\n";
    echo "Datos enviados:\n";
    print_r($newTeacher);
    echo "\nRespuesta del servidor:\n";
    print_r($response->json());
    echo "\n";
    
    if ($response->successful()) {
        $teacherId = $response->json()['data']['id'] ?? null;
        echo "✅ Docente creado exitosamente con ID: $teacherId\n\n";
    } else {
        echo "❌ Error al crear el docente\n\n";
        // No salimos, intentamos con datos existentes
    }

    // ============================================
    // 2. READ - Obtener todos los docentes
    // ============================================
    echo "2️⃣  OBTENIENDO TODOS LOS DOCENTES...\n";
    echo "-----------------------------------\n";
    
    $response = Http::withHeaders($headers)->get("$baseUrl/teachers");
    
    echo "Status: " . $response->status() . "\n";
    echo "Cantidad de docentes: " . count($response->json()['data'] ?? []) . "\n";
    echo "\nPrimeros 3 docentes:\n";
    $teachers = array_slice($response->json()['data'] ?? [], 0, 3);
    print_r($teachers);
    echo "\n";
    
    if ($response->successful() && !$teacherId && !empty($response->json()['data'])) {
        // Si no pudimos crear uno, usamos el primero existente para las pruebas
        $teacherId = $response->json()['data'][0]['id'] ?? null;
        echo "ℹ️  Usando docente existente con ID: $teacherId para las siguientes pruebas\n\n";
    }
    
    if ($response->successful()) {
        echo "✅ Lista de docentes obtenida exitosamente\n\n";
    } else {
        echo "❌ Error al obtener docentes\n\n";
    }

    // ============================================
    // 3. READ ONE - Obtener un docente específico
    // ============================================
    if ($teacherId) {
        echo "3️⃣  OBTENIENDO DOCENTE ESPECÍFICO (ID: $teacherId)...\n";
        echo "-----------------------------------\n";
        
        $response = Http::withHeaders($headers)->get("$baseUrl/teachers/$teacherId");
        
        echo "Status: " . $response->status() . "\n";
        echo "Respuesta del servidor:\n";
        print_r($response->json());
        echo "\n";
        
        if ($response->successful()) {
            echo "✅ Docente obtenido exitosamente\n\n";
        } else {
            echo "❌ Error al obtener el docente\n\n";
        }
    }

    // ============================================
    // 4. UPDATE - Actualizar el docente
    // ============================================
    if ($teacherId) {
        echo "4️⃣  ACTUALIZANDO DOCENTE (ID: $teacherId)...\n";
        echo "-----------------------------------\n";
        
        $updatedData = [
            'name' => 'Prof. Juan Carlos Pérez ACTUALIZADO',
            'email' => 'juan.perez.updated' . time() . '@ficct.edu.bo',
            'phone' => '+591 7' . rand(1000000, 9999999),
            'specialization' => 'Arquitectura de Software',
            'department' => 'Sistemas y Computación',
        ];
        
        $response = Http::withHeaders($headers)->put("$baseUrl/teachers/$teacherId", $updatedData);
        
        echo "Status: " . $response->status() . "\n";
        echo "Datos enviados:\n";
        print_r($updatedData);
        echo "\nRespuesta del servidor:\n";
        print_r($response->json());
        echo "\n";
        
        if ($response->successful()) {
            echo "✅ Docente actualizado exitosamente\n\n";
        } else {
            echo "❌ Error al actualizar el docente\n\n";
        }
    }

    // ============================================
    // 5. SEARCH - Buscar docentes
    // ============================================
    echo "5️⃣  BUSCANDO DOCENTES POR NOMBRE...\n";
    echo "-----------------------------------\n";
    
    $response = Http::withHeaders($headers)->get("$baseUrl/teachers?search=Prof");
    
    echo "Status: " . $response->status() . "\n";
    echo "Resultados encontrados: " . count($response->json()['data'] ?? []) . "\n";
    echo "Primeros resultados:\n";
    print_r(array_slice($response->json()['data'] ?? [], 0, 2));
    echo "\n";
    
    if ($response->successful()) {
        echo "✅ Búsqueda realizada exitosamente\n\n";
    } else {
        echo "❌ Error en la búsqueda\n\n";
    }

    // ============================================
    // 6. DELETE - Eliminar el docente
    // ============================================
    if ($teacherId) {
        echo "6️⃣  ELIMINANDO DOCENTE (ID: $teacherId)...\n";
        echo "-----------------------------------\n";
        echo "⚠️  Comentado para evitar eliminar datos existentes\n";
        echo "Descomenta el código si quieres probar la eliminación\n\n";
        
        /*
        $response = Http::withHeaders($headers)->delete("$baseUrl/teachers/$teacherId");
        
        echo "Status: " . $response->status() . "\n";
        echo "Respuesta del servidor:\n";
        print_r($response->json());
        echo "\n";
        
        if ($response->successful()) {
            echo "✅ Docente eliminado exitosamente\n\n";
        } else {
            echo "❌ Error al eliminar el docente\n\n";
        }
        */
    }

    // ============================================
    // Resumen Final
    // ============================================
    echo "========================================\n";
    echo "✅ PRUEBAS COMPLETADAS\n";
    echo "========================================\n";

} catch (\Exception $e) {
    echo "\n❌ ERROR GENERAL:\n";
    echo $e->getMessage() . "\n";
    echo "\nStack Trace:\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
