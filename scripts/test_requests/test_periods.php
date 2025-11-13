<?php

/**
 * Script de prueba para el módulo de Periodos Académicos
 * 
 * Este script prueba todas las operaciones CRUD del API de periodos:
 * - CREATE (POST)
 * - READ (GET)
 * - UPDATE (PUT)
 * - DELETE (DELETE)
 * 
 * Uso: php test_periods.php
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
echo "PRUEBA DE API: PERIODOS ACADÉMICOS\n";
echo "========================================\n\n";

// ID del periodo creado (se guardará para las siguientes operaciones)
$periodId = null;

try {
    // ============================================
    // 1. CREATE - Crear un nuevo periodo
    // ============================================
    echo "1️⃣  CREANDO NUEVO PERIODO...\n";
    echo "-----------------------------------\n";
    
    $newPeriod = [
        'name' => 'Periodo de Prueba ' . date('Y-m-d H:i:s'),
        'code' => 'TEST-' . time(),
        'start_date' => '2025-02-01',
        'end_date' => '2025-07-31',
    ];
    
    $response = Http::withHeaders($headers)->post("$baseUrl/periods", $newPeriod);
    
    echo "Status: " . $response->status() . "\n";
    echo "Datos enviados:\n";
    print_r($newPeriod);
    echo "\nRespuesta del servidor:\n";
    print_r($response->json());
    echo "\n";
    
    if ($response->successful()) {
        $periodId = $response->json()['data']['id'] ?? null;
        echo "✅ Periodo creado exitosamente con ID: $periodId\n\n";
    } else {
        echo "❌ Error al crear el periodo\n\n";
        exit(1);
    }

    // ============================================
    // 2. READ - Obtener todos los periodos
    // ============================================
    echo "2️⃣  OBTENIENDO TODOS LOS PERIODOS...\n";
    echo "-----------------------------------\n";
    
    $response = Http::withHeaders($headers)->get("$baseUrl/periods");
    
    echo "Status: " . $response->status() . "\n";
    echo "Cantidad de periodos: " . count($response->json()['data'] ?? []) . "\n";
    echo "\nPrimeros 3 periodos:\n";
    $periods = array_slice($response->json()['data'] ?? [], 0, 3);
    print_r($periods);
    echo "\n";
    
    if ($response->successful()) {
        echo "✅ Lista de periodos obtenida exitosamente\n\n";
    } else {
        echo "❌ Error al obtener periodos\n\n";
    }

    // ============================================
    // 3. READ ONE - Obtener un periodo específico
    // ============================================
    if ($periodId) {
        echo "3️⃣  OBTENIENDO PERIODO ESPECÍFICO (ID: $periodId)...\n";
        echo "-----------------------------------\n";
        
        $response = Http::withHeaders($headers)->get("$baseUrl/periods/$periodId");
        
        echo "Status: " . $response->status() . "\n";
        echo "Respuesta del servidor:\n";
        print_r($response->json());
        echo "\n";
        
        if ($response->successful()) {
            echo "✅ Periodo obtenido exitosamente\n\n";
        } else {
            echo "❌ Error al obtener el periodo\n\n";
        }
    }

    // ============================================
    // 4. UPDATE - Actualizar el periodo
    // ============================================
    if ($periodId) {
        echo "4️⃣  ACTUALIZANDO PERIODO (ID: $periodId)...\n";
        echo "-----------------------------------\n";
        
        $updatedData = [
            'name' => 'Periodo de Prueba ACTUALIZADO ' . date('Y-m-d H:i:s'),
            'code' => 'TEST-UPD-' . time(),
            'start_date' => '2025-03-01',
            'end_date' => '2025-08-31',
        ];
        
        $response = Http::withHeaders($headers)->put("$baseUrl/periods/$periodId", $updatedData);
        
        echo "Status: " . $response->status() . "\n";
        echo "Datos enviados:\n";
        print_r($updatedData);
        echo "\nRespuesta del servidor:\n";
        print_r($response->json());
        echo "\n";
        
        if ($response->successful()) {
            echo "✅ Periodo actualizado exitosamente\n\n";
        } else {
            echo "❌ Error al actualizar el periodo\n\n";
        }
    }

    // ============================================
    // 5. ACTIVATE - Activar el periodo
    // ============================================
    if ($periodId) {
        echo "5️⃣  ACTIVANDO PERIODO (ID: $periodId)...\n";
        echo "-----------------------------------\n";
        
        $response = Http::withHeaders($headers)->post("$baseUrl/periods/$periodId/activate");
        
        echo "Status: " . $response->status() . "\n";
        echo "Respuesta del servidor:\n";
        print_r($response->json());
        echo "\n";
        
        if ($response->successful()) {
            echo "✅ Periodo activado exitosamente\n\n";
        } else {
            echo "❌ Error al activar el periodo\n\n";
        }
    }

    // ============================================
    // 6. CLOSE - Cerrar el periodo
    // ============================================
    if ($periodId) {
        echo "6️⃣  CERRANDO PERIODO (ID: $periodId)...\n";
        echo "-----------------------------------\n";
        
        $response = Http::withHeaders($headers)->post("$baseUrl/periods/$periodId/close");
        
        echo "Status: " . $response->status() . "\n";
        echo "Respuesta del servidor:\n";
        print_r($response->json());
        echo "\n";
        
        if ($response->successful()) {
            echo "✅ Periodo cerrado exitosamente\n\n";
        } else {
            echo "❌ Error al cerrar el periodo\n\n";
        }
    }

    // ============================================
    // 7. DELETE - Eliminar el periodo
    // ============================================
    if ($periodId) {
        echo "7️⃣  ELIMINANDO PERIODO (ID: $periodId)...\n";
        echo "-----------------------------------\n";
        
        $response = Http::withHeaders($headers)->delete("$baseUrl/periods/$periodId");
        
        echo "Status: " . $response->status() . "\n";
        echo "Respuesta del servidor:\n";
        print_r($response->json());
        echo "\n";
        
        if ($response->successful()) {
            echo "✅ Periodo eliminado exitosamente\n\n";
        } else {
            echo "❌ Error al eliminar el periodo\n\n";
        }
    }

    // ============================================
    // Resumen Final
    // ============================================
    echo "========================================\n";
    echo "✅ PRUEBAS COMPLETADAS EXITOSAMENTE\n";
    echo "========================================\n";

} catch (\Exception $e) {
    echo "\n❌ ERROR GENERAL:\n";
    echo $e->getMessage() . "\n";
    echo "\nStack Trace:\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
