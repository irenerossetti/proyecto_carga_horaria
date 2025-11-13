<?php

/**
 * Script de Ayuda Rápida - Quick Start
 * 
 * Este script te muestra el estado del sistema y te guía sobre qué hacer.
 * 
 * Uso: php quick_check.php
 */

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║     SISTEMA DE CARGA HORARIA - VERIFICACIÓN RÁPIDA        ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

// Verificar conexión al servidor
echo "🔍 Verificando servidor Laravel...\n";
$ch = curl_init('http://127.0.0.1:8000');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 2);
curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode > 0) {
    echo "   ✅ Servidor corriendo en http://127.0.0.1:8000\n\n";
} else {
    echo "   ❌ Servidor NO está corriendo\n";
    echo "   💡 Ejecuta: php artisan serve\n\n";
    exit(1);
}

// Verificar rutas API
echo "🔍 Verificando rutas API...\n";
$endpoints = [
    '/api/periods' => 'Periodos Académicos',
    '/api/teachers' => 'Docentes',
    '/api/students' => 'Estudiantes',
    '/api/rooms' => 'Aulas',
    '/api/subjects' => 'Materias',
];

foreach ($endpoints as $endpoint => $name) {
    $ch = curl_init("http://127.0.0.1:8000$endpoint");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 2);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $namePadded = str_pad($name, 25, ' ');
    
    if ($httpCode == 200) {
        echo "   ✅ $namePadded  API funcionando\n";
    } elseif ($httpCode == 404) {
        echo "   ⚠️  $namePadded  Ruta no encontrada (404)\n";
    } elseif ($httpCode == 500) {
        echo "   ❌ $namePadded  Error del servidor (500)\n";
    } else {
        echo "   ⚠️  $namePadded  Status: $httpCode\n";
    }
}

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║                 COMANDOS DISPONIBLES                       ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

echo "📋 PROBAR UN MÓDULO ESPECÍFICO:\n";
echo "   php test_periods.php       # Probar Periodos\n";
echo "   php test_teachers.php      # Probar Docentes\n";
echo "   php test_students.php      # Probar Estudiantes\n";
echo "   php test_classrooms.php    # Probar Aulas\n";
echo "   php test_subjects.php      # Probar Materias\n";
echo "\n";

echo "🚀 PROBAR TODO:\n";
echo "   php run_all_tests.php      # Ejecutar todos los tests\n";
echo "\n";

echo "🔧 EJEMPLO CON cURL:\n";
echo "   php test_periods_curl.php  # Test sin dependencias de Laravel\n";
echo "\n";

echo "📖 VER DOCUMENTACIÓN:\n";
echo "   cat README.md              # Ver guía completa (Linux/Mac)\n";
echo "   type README.md             # Ver guía completa (Windows)\n";
echo "\n";

echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║              SOLUCIÓN DE PROBLEMAS COMUNES                 ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

echo "❓ Si ves error 404 (Ruta no encontrada):\n";
echo "   1. Verifica routes/api.php\n";
echo "   2. Ejecuta: php artisan route:list\n";
echo "   3. Limpia caché: php artisan optimize:clear\n";
echo "\n";

echo "❓ Si ves error 500 (Error del servidor):\n";
echo "   1. Revisa logs: storage/logs/laravel.log\n";
echo "   2. Verifica que la tabla existe en la BD\n";
echo "   3. Ejecuta migraciones: php artisan migrate\n";
echo "\n";

echo "❓ Si la tabla no existe:\n";
echo "   1. Crea migración: php artisan make:migration create_tabla_table\n";
echo "   2. Edita la migración en database/migrations/\n";
echo "   3. Ejecuta: php artisan migrate\n";
echo "\n";

echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║                  PRÓXIMOS PASOS                            ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

echo "1️⃣  Ejecutar test de Periodos (debería funcionar):\n";
echo "    php test_periods.php\n";
echo "\n";

echo "2️⃣  Ver qué módulos fallan:\n";
echo "    php run_all_tests.php\n";
echo "\n";

echo "3️⃣  Implementar APIs faltantes según los errores\n";
echo "\n";

echo "4️⃣  Crear migraciones para tablas faltantes\n";
echo "\n";

echo "5️⃣  Volver a ejecutar todos los tests\n";
echo "\n";

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "✨ ¡Todo listo! Ahora puedes ejecutar los tests.\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "\n";
