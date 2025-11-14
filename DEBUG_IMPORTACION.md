# 🐛 DEBUG - IMPORTACIÓN DE DATOS

## 🔍 PASOS PARA DIAGNOSTICAR EL PROBLEMA

### 1. Abrir Consola del Navegador

**Chrome/Edge:**
- Presionar `F12` o `Ctrl+Shift+I`
- Ir a la pestaña "Console"

**Firefox:**
- Presionar `F12` o `Ctrl+Shift+K`
- Ir a la pestaña "Consola"

### 2. Intentar Importar

1. Ir a `/importar`
2. Clic en "Importar Docentes" (o cualquier tipo)
3. Seleccionar un archivo
4. Clic en "Importar Datos"

### 3. Revisar Mensajes en Consola

Deberías ver mensajes como:
```
Abriendo modal de importación para: teachers
Archivo seleccionado: docentes.xlsx
Procesando archivo: docentes.xlsx Tipo: teachers
```

Si NO ves estos mensajes, hay un problema con el JavaScript.

### 4. Revisar Pestaña "Network"

1. Ir a pestaña "Network" o "Red"
2. Intentar importar
3. Buscar la petición a `/api/imports`
4. Ver el estado de la respuesta

**Estados posibles:**
- `200 OK` - Éxito
- `403 Forbidden` - Sin permisos
- `422 Unprocessable Entity` - Error de validación
- `500 Internal Server Error` - Error del servidor

### 5. Ver Detalles de la Petición

Clic en la petición `/api/imports` y revisar:

**Request (Petición):**
- Method: POST
- Content-Type: multipart/form-data
- Form Data:
  - type: teachers/subjects/groups
  - file: [archivo]

**Response (Respuesta):**
- Ver el JSON de respuesta
- Buscar mensajes de error

---

## 🧪 PRUEBA SIMPLE

### Opción 1: Usar archivo de prueba HTML

1. Abrir en navegador:
   ```
   http://localhost:8000/test-import.html
   ```

2. Seleccionar tipo de importación
3. Seleccionar archivo
4. Clic en "Importar Datos"
5. Ver resultado

### Opción 2: Usar cURL

```bash
# Crear archivo CSV de prueba
echo "name,email,dni,phone,department
Juan Pérez,juan.perez@ficct.edu.bo,12345678,70123456,Sistemas" > test_teachers.csv

# Importar con cURL
curl -X POST http://localhost:8000/api/imports \
  -F "type=teachers" \
  -F "file=@test_teachers.csv" \
  -H "Accept: application/json"
```

### Opción 3: Usar Postman/Insomnia

1. Crear nueva petición POST
2. URL: `http://localhost:8000/api/imports`
3. Body: form-data
   - type: teachers
   - file: [seleccionar archivo]
4. Headers:
   - Accept: application/json
5. Send

---

## 🔧 PROBLEMAS COMUNES Y SOLUCIONES

### Problema 1: "Por favor selecciona un archivo"

**Causa:** El input de archivo no está detectando el archivo seleccionado.

**Solución:**
1. Abrir consola del navegador
2. Ejecutar:
   ```javascript
   const input = document.getElementById('importFile');
   console.log('Input existe:', !!input);
   console.log('Archivos:', input?.files);
   ```
3. Si input es null, el ID está mal
4. Si files está vacío, el archivo no se seleccionó

### Problema 2: Error 403 Forbidden

**Causa:** Usuario no tiene permisos de ADMIN.

**Solución:**
1. Verificar que el usuario tenga rol ADMIN
2. Ejecutar en tinker:
   ```php
   $user = auth()->user();
   $user->roles->pluck('name');
   ```

### Problema 3: Error 422 Validation Error

**Causa:** Datos del formulario inválidos.

**Solución:**
1. Verificar que el campo `type` sea: teachers, subjects, o groups
2. Verificar que el archivo sea .xlsx, .xls, o .csv
3. Ver mensaje de error específico en la respuesta

### Problema 4: Error 500 Internal Server Error

**Causa:** Error en el servidor (PHP).

**Solución:**
1. Revisar logs de Laravel:
   ```bash
   tail -f storage/logs/laravel.log
   ```
2. Buscar el error específico
3. Verificar que PhpSpreadsheet esté instalado:
   ```bash
   composer show phpoffice/phpspreadsheet
   ```

### Problema 5: Archivo no se procesa

**Causa:** Formato de archivo incorrecto o corrupto.

**Solución:**
1. Verificar que el archivo tenga datos
2. Abrir el archivo en Excel/LibreOffice
3. Guardar como nuevo archivo
4. Intentar con formato diferente (.csv en lugar de .xlsx)

### Problema 6: Caracteres raros (�, ?, etc.)

**Causa:** Problema de codificación.

**Solución:**
1. Guardar archivo como UTF-8
2. En Excel: Guardar como → CSV UTF-8
3. El controlador ahora convierte automáticamente

---

## 📝 LOGS ÚTILES

### Ver logs de Laravel en tiempo real:

```bash
# Windows PowerShell
Get-Content storage/logs/laravel.log -Wait -Tail 50

# Windows CMD
tail -f storage/logs/laravel.log
```

### Ver logs del navegador:

1. Abrir consola (F12)
2. Pestaña "Console"
3. Intentar importar
4. Copiar todos los mensajes

---

## 🧪 CREAR ARCHIVO DE PRUEBA

### Docentes (CSV UTF-8):

```csv
name,email,dni,phone,department
Juan Pérez García,juan.perez@ficct.edu.bo,12345678,70123456,Sistemas
María López Silva,maria.lopez@ficct.edu.bo,87654321,70654321,Matemáticas
Carlos Ruiz Díaz,carlos.ruiz@ficct.edu.bo,11223344,70112233,Redes
```

Guardar como: `test_docentes.csv` con codificación UTF-8

### Materias (CSV UTF-8):

```csv
code,name,credits,description
INF-101,Introducción a la Programación,4,Fundamentos de programación
MAT-101,Cálculo I,4,Cálculo diferencial
INF-201,Estructura de Datos,4,Estructuras de datos y algoritmos
```

Guardar como: `test_materias.csv` con codificación UTF-8

### Grupos (CSV UTF-8):

```csv
code,name,subject_id,capacity,schedule
INF-101-A,Grupo A,1,30,Lun-Mie-Vie 08:00-10:00
INF-101-B,Grupo B,1,30,Lun-Mie-Vie 14:00-16:00
MAT-101-A,Grupo A,2,35,Mar-Jue 10:00-12:00
```

Guardar como: `test_grupos.csv` con codificación UTF-8

---

## 🔍 VERIFICAR CONFIGURACIÓN

### 1. Verificar que PhpSpreadsheet esté instalado:

```bash
composer show phpoffice/phpspreadsheet
```

Debería mostrar:
```
name     : phpoffice/phpspreadsheet
versions : * 5.2.x
```

### 2. Verificar ruta de importación:

```bash
php artisan route:list --path=imports
```

Debería mostrar:
```
POST | api/imports | ImportController@import
```

### 3. Verificar permisos del usuario:

```bash
php artisan tinker
```

```php
$user = \App\Models\User::find(1); // Cambiar ID según tu usuario
$user->roles->pluck('name');
// Debería incluir 'ADMIN'
```

### 4. Verificar que el controlador existe:

```bash
php artisan route:list --name=imports
```

---

## 📞 INFORMACIÓN PARA REPORTAR

Si el problema persiste, proporciona:

1. **Mensaje de error exacto** (captura de pantalla)
2. **Logs de consola del navegador** (copiar todo)
3. **Logs de Laravel** (últimas 50 líneas)
4. **Tipo de archivo** (.xlsx, .xls, .csv)
5. **Tamaño del archivo**
6. **Navegador y versión**
7. **Sistema operativo**

---

## ✅ CHECKLIST DE VERIFICACIÓN

Antes de reportar un problema, verificar:

- [ ] PhpSpreadsheet está instalado
- [ ] Usuario tiene rol ADMIN
- [ ] Ruta `/api/imports` existe
- [ ] Archivo tiene formato correcto
- [ ] Archivo tiene datos
- [ ] Consola del navegador no muestra errores JavaScript
- [ ] Logs de Laravel no muestran errores PHP
- [ ] Archivo de prueba HTML funciona

---

**Fecha:** 14 de Noviembre, 2025  
**Versión:** 1.0.0
