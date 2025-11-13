# 🧪 Scripts de Prueba para API - Sistema de Carga Horaria

Este directorio contiene scripts PHP para probar todas las funcionalidades del backend del proyecto Laravel.

## 📋 Contenido

```
scripts/test_requests/
├── test_periods.php        # Pruebas para Periodos Académicos
├── test_teachers.php       # Pruebas para Docentes
├── test_students.php       # Pruebas para Estudiantes
├── test_classrooms.php     # Pruebas para Aulas/Salas
├── test_subjects.php       # Pruebas para Materias/Asignaturas
├── run_all_tests.php       # Script maestro que ejecuta todos los tests
└── README.md              # Este archivo
```

## 🚀 Requisitos Previos

1. **Servidor Laravel corriendo:**
   ```bash
   php artisan serve
   ```
   El servidor debe estar en: `http://127.0.0.1:8000`

2. **Base de datos configurada:**
   - PostgreSQL en Neon (o local)
   - Migraciones ejecutadas: `php artisan migrate`

3. **Rutas API configuradas:**
   - Las rutas deben estar definidas en `routes/api.php`
   - Prefijo por defecto: `/api`

## 📖 Uso de los Scripts

### Ejecutar un test individual:

```bash
# Desde el directorio del proyecto
cd scripts/test_requests

# Probar Periodos Académicos
php test_periods.php

# Probar Docentes
php test_teachers.php

# Probar Estudiantes
php test_students.php

# Probar Aulas
php test_classrooms.php

# Probar Materias
php test_subjects.php
```

### Ejecutar todos los tests a la vez:

```bash
cd scripts/test_requests
php run_all_tests.php
```

Este script ejecutará todos los tests secuencialmente y mostrará un resumen final.

## 📝 Qué hace cada script

Cada script realiza las siguientes operaciones:

1. **CREATE (POST)** - Crea un nuevo registro con datos de ejemplo
2. **READ ALL (GET)** - Obtiene todos los registros
3. **READ ONE (GET)** - Obtiene un registro específico por ID
4. **UPDATE (PUT)** - Modifica el registro creado
5. **DELETE (DELETE)** - Elimina el registro (comentado por defecto)

### Operaciones adicionales específicas:

- **test_periods.php**: Incluye operaciones de activar/cerrar periodo
- **test_teachers.php**: Incluye búsqueda por nombre
- **test_students.php**: Incluye filtrado por carrera
- **test_classrooms.php**: Incluye filtros por disponibilidad y capacidad
- **test_subjects.php**: Incluye búsqueda y filtros múltiples

## 🔍 Interpretando los Resultados

### ✅ Respuesta exitosa:
```
Status: 200
✅ Periodo creado exitosamente con ID: 5
```

### ❌ Error en la respuesta:
```
Status: 404
❌ Error al crear el periodo
Respuesta: {"message": "Route not found"}
```

### ⚠️ Tabla no existe:
```
⚠️  Error al crear el estudiante (posiblemente la tabla no existe aún)
Este módulo requiere migración previa
```

## 🛠️ Solución de Problemas

### Error: "Connection refused"
**Causa:** El servidor Laravel no está corriendo.
**Solución:** 
```bash
php artisan serve
```

### Error: "Route not found"
**Causa:** Las rutas API no están definidas.
**Solución:** Verifica `routes/api.php` y asegúrate de tener:
```php
Route::apiResource('periods', AcademicPeriodController::class);
Route::apiResource('teachers', TeacherController::class);
// etc...
```

### Error: "Table doesn't exist"
**Causa:** La tabla no existe en la base de datos.
**Solución:** 
```bash
# Ver migraciones pendientes
php artisan migrate:status

# Ejecutar migraciones
php artisan migrate
```

### Error: "Undefined method"
**Causa:** El controlador no tiene el método necesario.
**Solución:** Implementa los métodos CRUD en el controlador:
```php
public function index() { }
public function store(Request $request) { }
public function show($id) { }
public function update(Request $request, $id) { }
public function destroy($id) { }
```

## 📊 Estado Actual de los Módulos

| Módulo | Tabla Existe | API Implementada | Status |
|--------|--------------|------------------|--------|
| **Periodos** | ✅ Sí | ✅ Sí | 🟢 Funcional |
| **Docentes (Teachers)** | ✅ Sí | ⚠️ Parcial | 🟡 Necesita API |
| **Estudiantes** | ❌ No | ❌ No | 🔴 Requiere migración |
| **Aulas (Rooms)** | ✅ Sí | ⚠️ Parcial | 🟡 Necesita API |
| **Materias (Subjects)** | ❌ No | ❌ No | 🔴 Requiere migración |

## 🔧 Modificar los Scripts

### Cambiar datos de prueba:

Edita las variables `$newRecord` en cada script:

```php
$newTeacher = [
    'name' => 'Tu Nombre',
    'email' => 'tu.email@example.com',
    // ...
];
```

### Habilitar eliminación:

Por defecto, la operación DELETE está comentada para evitar eliminar datos existentes. Para habilitarla, descomenta el código:

```php
// Busca esto en el script:
/*
$response = Http::withHeaders($headers)->delete("$baseUrl/items/$itemId");
*/

// Y elimina los comentarios:
$response = Http::withHeaders($headers)->delete("$baseUrl/items/$itemId");
```

### Cambiar URL del servidor:

Si tu servidor está en otro puerto o URL, modifica:

```php
$baseUrl = 'http://127.0.0.1:8000/api';  // Cambiar aquí
```

## 📚 Recursos Adicionales

### Crear un nuevo módulo de prueba:

1. Copia uno de los scripts existentes
2. Modifica las variables según tu modelo
3. Actualiza las rutas API
4. Ajusta los campos del payload

### Ejemplo de estructura básica:

```php
<?php
require __DIR__ . '/../../vendor/autoload.php';
use Illuminate\Support\Facades\Http;

$app = require_once __DIR__ . '/../../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$baseUrl = 'http://127.0.0.1:8000/api';
$headers = ['Accept' => 'application/json', 'Content-Type' => 'application/json'];

// CREATE
$response = Http::withHeaders($headers)->post("$baseUrl/items", $data);

// READ
$response = Http::withHeaders($headers)->get("$baseUrl/items");

// UPDATE
$response = Http::withHeaders($headers)->put("$baseUrl/items/$id", $data);

// DELETE
$response = Http::withHeaders($headers)->delete("$baseUrl/items/$id");
```

## 🎯 Siguientes Pasos

1. ✅ Ejecutar `test_periods.php` (debería funcionar)
2. 🔧 Implementar API para Teachers y Rooms
3. 🗃️ Crear migraciones para Students y Subjects
4. 🚀 Ejecutar `run_all_tests.php` para verificar todo

## 📞 Soporte

Si encuentras problemas:
1. Revisa los logs de Laravel: `storage/logs/laravel.log`
2. Verifica la consola del servidor: `php artisan serve`
3. Usa `php artisan route:list` para ver las rutas disponibles
4. Ejecuta `php artisan migrate:status` para verificar migraciones

---

**Creado para:** Sistema de Carga Horaria FICCT  
**Versión:** 1.0  
**Fecha:** Noviembre 2025
