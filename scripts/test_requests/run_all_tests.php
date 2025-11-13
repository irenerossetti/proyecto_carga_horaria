<?php

/**
 * Script Maestro - Ejecuta todas las pruebas de API
 * 
 * Este script ejecuta todos los tests de los diferentes módulos
 * del sistema de carga horaria.
 * 
 * Uso: php run_all_tests.php
 */

$scriptsDir = __DIR__;
$testFiles = [
    'test_periods.php' => 'Periodos Académicos',
    'test_teachers.php' => 'Docentes',
    'test_students.php' => 'Estudiantes',
    'test_classrooms.php' => 'Aulas/Salas',
    'test_subjects.php' => 'Materias/Asignaturas',
];

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║     SISTEMA DE PRUEBAS - CARGA HORARIA FICCT              ║\n";
echo "║     Ejecutando todos los tests del API                    ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

$startTime = microtime(true);
$results = [];

foreach ($testFiles as $file => $moduleName) {
    $filePath = $scriptsDir . '/' . $file;
    
    if (!file_exists($filePath)) {
        echo "⚠️  Archivo no encontrado: $file\n\n";
        continue;
    }
    
    echo "┌─────────────────────────────────────────────────────────┐\n";
    echo "│ Ejecutando: $moduleName\n";
    echo "│ Archivo: $file\n";
    echo "└─────────────────────────────────────────────────────────┘\n";
    
    $testStartTime = microtime(true);
    
    // Ejecutar el script
    ob_start();
    $exitCode = 0;
    try {
        include $filePath;
    } catch (\Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
        $exitCode = 1;
    }
    $output = ob_get_clean();
    
    $testEndTime = microtime(true);
    $testDuration = round($testEndTime - $testStartTime, 2);
    
    // Guardar resultado
    $results[$moduleName] = [
        'file' => $file,
        'duration' => $testDuration,
        'success' => $exitCode === 0,
        'output' => $output,
    ];
    
    // Mostrar output
    echo $output;
    
    echo "\n";
    echo "Tiempo de ejecución: {$testDuration}s\n";
    echo "Estado: " . ($exitCode === 0 ? "✅ COMPLETADO" : "❌ ERROR") . "\n";
    echo "\n";
    echo str_repeat("═", 60) . "\n\n";
    
    // Pausa pequeña entre tests
    sleep(1);
}

$endTime = microtime(true);
$totalDuration = round($endTime - $startTime, 2);

// Resumen final
echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║                    RESUMEN DE PRUEBAS                      ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

$successCount = 0;
$failCount = 0;

foreach ($results as $moduleName => $result) {
    $status = $result['success'] ? '✅' : '❌';
    $duration = str_pad($result['duration'] . 's', 8, ' ', STR_PAD_LEFT);
    $name = str_pad($moduleName, 30, ' ', STR_PAD_RIGHT);
    
    echo "$status $name  $duration\n";
    
    if ($result['success']) {
        $successCount++;
    } else {
        $failCount++;
    }
}

echo "\n";
echo "────────────────────────────────────────────────────────────\n";
echo "Total de tests ejecutados: " . count($results) . "\n";
echo "Exitosos: $successCount\n";
echo "Fallidos: $failCount\n";
echo "Tiempo total: {$totalDuration}s\n";
echo "────────────────────────────────────────────────────────────\n";

if ($failCount > 0) {
    echo "\n⚠️  Algunos tests fallaron. Revisa los detalles arriba.\n";
    exit(1);
} else {
    echo "\n✅ Todos los tests completados exitosamente!\n";
    exit(0);
}
