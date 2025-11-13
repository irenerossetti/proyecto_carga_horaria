# Instrucciones para usar la gestión de Periodos Académicos

## Problema Resuelto

El archivo `resources/views/periods/index.blade.php` estaba completamente corrupto con código mezclado. Se ha recreado completamente.

## Requisitos

Para que la funcionalidad de crear y editar períodos funcione correctamente, necesitas:

### 1. Servidor Laravel corriendo

El servidor debe estar activo para que las peticiones API funcionen:

```bash
php artisan serve
```

O si ya está corriendo en otro puerto:

```bash
php artisan serve --port=8000
```

### 2. Base de datos configurada

Asegúrate de que las migraciones estén ejecutadas:

```bash
php artisan migrate
```

### 3. Sesión activa

Debes estar autenticado como administrador para acceder a `/periodos`

## Verificación

### Paso 1: Verificar que el servidor esté corriendo

Abre tu navegador y ve a: `http://127.0.0.1:8000/periodos`

### Paso 2: Abrir la consola del navegador

Presiona `F12` y ve a la pestaña "Console"

### Paso 3: Intentar crear un periodo

1. Haz clic en "Nuevo Periodo"
2. Llena el formulario
3. Haz clic en "Guardar Periodo"
4. Revisa la consola para ver los mensajes de depuración

## Mensajes de Error Comunes

### "Error de conexión"

**Causa**: El servidor Laravel no está corriendo

**Solución**: Ejecuta `php artisan serve` en una terminal

### "CSRF Token no encontrado"

**Causa**: La página no se cargó correctamente

**Solución**: Recarga la página (F5)

### "No tienes permisos de administrador"

**Causa**: Tu usuario no tiene el rol ADMIN

**Solución**: Verifica tu rol en la base de datos

## Depuración Avanzada

Si sigues teniendo problemas, abre la consola del navegador (F12) y verás:

- URL de la petición
- Método HTTP (POST/PATCH)
- Datos enviados
- Respuesta del servidor
- Errores detallados

## Cambios Realizados

1. ✅ Archivo de vista completamente recreado
2. ✅ Manejo de errores mejorado en el controlador
3. ✅ JavaScript con logs de depuración detallados
4. ✅ Validación de CSRF token
5. ✅ Indicador visual si el servidor no está disponible
6. ✅ Mensajes de error más claros

## Rutas API Disponibles

- `GET /api/periods` - Listar períodos
- `POST /api/periods` - Crear período
- `PATCH /api/periods/{id}` - Actualizar período
- `POST /api/periods/{id}/activate` - Activar período
- `POST /api/periods/{id}/close` - Cerrar período
- `DELETE /api/periods/{id}` - Eliminar período

Todas las rutas requieren autenticación y rol de administrador.
