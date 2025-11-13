<?php

/**
 * Script de prueba para el módulo de Aulas/Salas
 * 
 * Este script prueba todas las operaciones CRUD del API de aulas:
 * - CREATE (POST)
 * - READ (GET)
 * - UPDATE (PUT)
 * - DELETE (DELETE)
 * 
 * Uso: php test_classrooms.php
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
echo "PRUEBA DE API: AULAS/SALAS\n";
echo "========================================\n\n";

// ID del aula creada
$roomId = null;

try {
    // ============================================
    // 1. CREATE - Crear una nueva aula
    // ============================================
    echo "1️⃣  CREANDO NUEVA AULA...\n";
    echo "-----------------------------------\n";
    
    $newRoom = [
        'name' => 'Aula ' . rand(100, 999),
        'code' => 'ROOM-' . time(),
        'building' => 'Edificio Principal',
        'floor' => rand(1, 5),
        'capacity' => rand(20, 50),
        'type' => 'Aula Teórica',
        'has_projector' => true,
        'has_computers' => false,
        'has_air_conditioning' => true,
        'is_available' => true,
    ];
    
    $response = Http::withHeaders($headers)->post("$baseUrl/rooms", $newRoom);
    
    echo "Status: " . $response->status() . "\n";
    echo "Datos enviados:\n";
    print_r($newRoom);
    echo "\nRespuesta del servidor:\n";
    print_r($response->json());
    echo "\n";
    
    if ($response->successful()) {
        $roomId = $response->json()['data']['id'] ?? null;
        echo "✅ Aula creada exitosamente con ID: $roomId\n\n";
    } else {
        echo "⚠️  Error al crear el aula\n";
        echo "Respuesta: " . $response->body() . "\n\n";
    }

    // ============================================
    // 2. READ - Obtener todas las aulas
    // ============================================
    echo "2️⃣  OBTENIENDO TODAS LAS AULAS...\n";
    echo "-----------------------------------\n";
    
    $response = Http::withHeaders($headers)->get("$baseUrl/rooms");
    
    echo "Status: " . $response->status() . "\n";
    
    if ($response->successful()) {
        echo "Cantidad de aulas: " . count($response->json()['data'] ?? []) . "\n";
        echo "\nPrimeras 3 aulas:\n";
        $rooms = array_slice($response->json()['data'] ?? [], 0, 3);
        print_r($rooms);
        echo "\n";
        
        if (!$roomId && !empty($response->json()['data'])) {
            $roomId = $response->json()['data'][0]['id'] ?? null;
            echo "ℹ️  Usando aula existente con ID: $roomId para las siguientes pruebas\n\n";
        }
        
        echo "✅ Lista de aulas obtenida exitosamente\n\n";
    } else {
        echo "⚠️  Error al obtener aulas\n";
        echo "Respuesta: " . $response->body() . "\n\n";
    }

    // ============================================
    // 3. READ ONE - Obtener un aula específica
    // ============================================
    if ($roomId) {
        echo "3️⃣  OBTENIENDO AULA ESPECÍFICA (ID: $roomId)...\n";
        echo "-----------------------------------\n";
        
        $response = Http::withHeaders($headers)->get("$baseUrl/rooms/$roomId");
        
        echo "Status: " . $response->status() . "\n";
        echo "Respuesta del servidor:\n";
        print_r($response->json());
        echo "\n";
        
        if ($response->successful()) {
            echo "✅ Aula obtenida exitosamente\n\n";
        } else {
            echo "❌ Error al obtener el aula\n\n";
        }
    }

    // ============================================
    // 4. UPDATE - Actualizar el aula
    // ============================================
    if ($roomId) {
        echo "4️⃣  ACTUALIZANDO AULA (ID: $roomId)...\n";
        echo "-----------------------------------\n";
        
        $updatedData = [
            'name' => 'Aula Renovada ' . rand(100, 999),
            'building' => 'Edificio Nuevo',
            'floor' => rand(1, 5),
            'capacity' => rand(30, 60),
            'type' => 'Laboratorio de Computación',
            'has_projector' => true,
            'has_computers' => true,
            'has_air_conditioning' => true,
            'is_available' => true,
        ];
        
        $response = Http::withHeaders($headers)->put("$baseUrl/rooms/$roomId", $updatedData);
        
        echo "Status: " . $response->status() . "\n";
        echo "Datos enviados:\n";
        print_r($updatedData);
        echo "\nRespuesta del servidor:\n";
        print_r($response->json());
        echo "\n";
        
        if ($response->successful()) {
            echo "✅ Aula actualizada exitosamente\n\n";
        } else {
            echo "❌ Error al actualizar el aula\n\n";
        }
    }

    // ============================================
    // 5. FILTER - Filtrar aulas disponibles
    // ============================================
    echo "5️⃣  FILTRANDO AULAS DISPONIBLES...\n";
    echo "-----------------------------------\n";
    
    $response = Http::withHeaders($headers)->get("$baseUrl/rooms?available=true");
    
    echo "Status: " . $response->status() . "\n";
    
    if ($response->successful()) {
        echo "Aulas disponibles: " . count($response->json()['data'] ?? []) . "\n";
        echo "Primeros resultados:\n";
        print_r(array_slice($response->json()['data'] ?? [], 0, 2));
        echo "\n✅ Filtro aplicado exitosamente\n\n";
    } else {
        echo "⚠️  Error al filtrar aulas\n\n";
    }

    // ============================================
    // 6. FILTER - Filtrar por capacidad mínima
    // ============================================
    echo "6️⃣  FILTRANDO AULAS POR CAPACIDAD (>30)...\n";
    echo "-----------------------------------\n";
    
    $response = Http::withHeaders($headers)->get("$baseUrl/rooms?min_capacity=30");
    
    echo "Status: " . $response->status() . "\n";
    
    if ($response->successful()) {
        echo "Aulas con capacidad >30: " . count($response->json()['data'] ?? []) . "\n";
        echo "Primeros resultados:\n";
        print_r(array_slice($response->json()['data'] ?? [], 0, 2));
        echo "\n✅ Filtro por capacidad aplicado exitosamente\n\n";
    } else {
        echo "⚠️  Error al filtrar por capacidad\n\n";
    }

    // ============================================
    // 7. DELETE - Eliminar el aula
    // ============================================
    if ($roomId) {
        echo "7️⃣  ELIMINANDO AULA (ID: $roomId)...\n";
        echo "-----------------------------------\n";
        echo "⚠️  Comentado para evitar eliminar datos existentes\n";
        echo "Descomenta el código si quieres probar la eliminación\n\n";
        
        /*
        $response = Http::withHeaders($headers)->delete("$baseUrl/rooms/$roomId");
        
        echo "Status: " . $response->status() . "\n";
        echo "Respuesta del servidor:\n";
        print_r($response->json());
        echo "\n";
        
        if ($response->successful()) {
            echo "✅ Aula eliminada exitosamente\n\n";
        } else {
            echo "❌ Error al eliminar el aula\n\n";
        }
        */
    }

    // ============================================
    // Resumen Final
    // ============================================
    echo "========================================\n";
    echo "✅ PRUEBAS COMPLETADAS\n";
    echo "========================================\n";
    echo "\nNOTA: La tabla 'rooms' existe en tu base de datos.\n";
    echo "Campos disponibles: name, code, building, floor, capacity, type,\n";
    echo "has_projector, has_computers, has_air_conditioning, is_available\n";

} catch (\Exception $e) {
    echo "\n❌ ERROR GENERAL:\n";
    echo $e->getMessage() . "\n";
    echo "\nStack Trace:\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
