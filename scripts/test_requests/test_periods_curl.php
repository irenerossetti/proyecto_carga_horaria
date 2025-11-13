<?php

/**
 * Script de prueba usando cURL puro (sin dependencias de Laravel)
 * 
 * Este script demuestra cómo hacer las mismas pruebas usando cURL directo,
 * lo cual es útil si necesitas ejecutarlo sin cargar Laravel completo.
 * 
 * Uso: php test_periods_curl.php
 */

// Configuración
$baseUrl = 'http://127.0.0.1:8000/api';

echo "========================================\n";
echo "PRUEBA DE API CON cURL: PERIODOS\n";
echo "========================================\n\n";

$periodId = null;

/**
 * Función helper para hacer requests HTTP con cURL
 */
function makeRequest($method, $url, $data = null) {
    $ch = curl_init();
    
    // Headers comunes
    $headers = [
        'Accept: application/json',
        'Content-Type: application/json',
    ];
    
    // Configuración básica
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    
    // Configurar método y datos
    switch(strtoupper($method)) {
        case 'POST':
            curl_setopt($ch, CURLOPT_POST, true);
            if ($data) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
            break;
            
        case 'PUT':
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            if ($data) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
            break;
            
        case 'DELETE':
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            break;
            
        case 'GET':
        default:
            // GET es el método por defecto
            break;
    }
    
    // Ejecutar request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    
    curl_close($ch);
    
    return [
        'status' => $httpCode,
        'body' => $response ? json_decode($response, true) : null,
        'error' => $error,
    ];
}

try {
    // ============================================
    // 1. CREATE - Crear un nuevo periodo
    // ============================================
    echo "1️⃣  CREANDO NUEVO PERIODO...\n";
    echo "-----------------------------------\n";
    
    $newPeriod = [
        'name' => 'Periodo cURL Test ' . date('Y-m-d H:i:s'),
        'code' => 'CURL-' . time(),
        'start_date' => '2025-02-01',
        'end_date' => '2025-07-31',
    ];
    
    $response = makeRequest('POST', "$baseUrl/periods", $newPeriod);
    
    echo "Status: " . $response['status'] . "\n";
    echo "Datos enviados:\n";
    print_r($newPeriod);
    echo "\nRespuesta del servidor:\n";
    print_r($response['body']);
    echo "\n";
    
    if ($response['status'] == 201 || $response['status'] == 200) {
        $periodId = $response['body']['data']['id'] ?? null;
        echo "✅ Periodo creado exitosamente con ID: $periodId\n\n";
    } else {
        echo "❌ Error al crear el periodo\n\n";
        if ($response['error']) {
            echo "Error cURL: " . $response['error'] . "\n\n";
        }
    }

    // ============================================
    // 2. READ - Obtener todos los periodos
    // ============================================
    echo "2️⃣  OBTENIENDO TODOS LOS PERIODOS...\n";
    echo "-----------------------------------\n";
    
    $response = makeRequest('GET', "$baseUrl/periods");
    
    echo "Status: " . $response['status'] . "\n";
    
    if ($response['status'] == 200) {
        $periods = $response['body']['data'] ?? [];
        echo "Cantidad de periodos: " . count($periods) . "\n";
        echo "\nPrimeros 3 periodos:\n";
        print_r(array_slice($periods, 0, 3));
        echo "\n✅ Lista obtenida exitosamente\n\n";
    } else {
        echo "❌ Error al obtener periodos\n\n";
    }

    // ============================================
    // 3. READ ONE - Obtener periodo específico
    // ============================================
    if ($periodId) {
        echo "3️⃣  OBTENIENDO PERIODO (ID: $periodId)...\n";
        echo "-----------------------------------\n";
        
        $response = makeRequest('GET', "$baseUrl/periods/$periodId");
        
        echo "Status: " . $response['status'] . "\n";
        echo "Respuesta:\n";
        print_r($response['body']);
        echo "\n";
        
        if ($response['status'] == 200) {
            echo "✅ Periodo obtenido exitosamente\n\n";
        } else {
            echo "❌ Error al obtener el periodo\n\n";
        }
    }

    // ============================================
    // 4. UPDATE - Actualizar periodo
    // ============================================
    if ($periodId) {
        echo "4️⃣  ACTUALIZANDO PERIODO (ID: $periodId)...\n";
        echo "-----------------------------------\n";
        
        $updatedData = [
            'name' => 'Periodo cURL ACTUALIZADO ' . date('Y-m-d H:i:s'),
            'code' => 'CURL-UPD-' . time(),
            'start_date' => '2025-03-01',
            'end_date' => '2025-08-31',
        ];
        
        $response = makeRequest('PUT', "$baseUrl/periods/$periodId", $updatedData);
        
        echo "Status: " . $response['status'] . "\n";
        echo "Datos enviados:\n";
        print_r($updatedData);
        echo "\nRespuesta:\n";
        print_r($response['body']);
        echo "\n";
        
        if ($response['status'] == 200) {
            echo "✅ Periodo actualizado exitosamente\n\n";
        } else {
            echo "❌ Error al actualizar\n\n";
        }
    }

    // ============================================
    // 5. DELETE - Eliminar periodo
    // ============================================
    if ($periodId) {
        echo "5️⃣  ELIMINANDO PERIODO (ID: $periodId)...\n";
        echo "-----------------------------------\n";
        
        $response = makeRequest('DELETE', "$baseUrl/periods/$periodId");
        
        echo "Status: " . $response['status'] . "\n";
        echo "Respuesta:\n";
        print_r($response['body']);
        echo "\n";
        
        if ($response['status'] == 200 || $response['status'] == 204) {
            echo "✅ Periodo eliminado exitosamente\n\n";
        } else {
            echo "❌ Error al eliminar\n\n";
        }
    }

    // ============================================
    // Resumen
    // ============================================
    echo "========================================\n";
    echo "✅ PRUEBAS CON cURL COMPLETADAS\n";
    echo "========================================\n";
    echo "\nEste script usa cURL puro, sin dependencias de Laravel.\n";
    echo "Es útil para pruebas rápidas o scripts externos.\n";

} catch (\Exception $e) {
    echo "\n❌ ERROR:\n";
    echo $e->getMessage() . "\n";
    exit(1);
}
