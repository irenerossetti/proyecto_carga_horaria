# 🚀 GUÍA RÁPIDA - EXPORTACIÓN DE BITÁCORA

## ⚡ Uso Inmediato

### 1️⃣ Acceder a la Bitácora

```
http://localhost:8000/bitacora
```

**Requisito:** Usuario con rol ADMIN

---

### 2️⃣ Exportar a Excel

1. **Aplicar filtros** (opcional):
   - Usuario: Buscar por nombre o email
   - Acción: Seleccionar tipo (login, create, update, etc.)
   - Módulo: Seleccionar módulo (teachers, students, etc.)
   - Fechas: Rango de fechas

2. **Clic en botón "Exportar"**

3. **Escribir "1"** para Excel

4. **Resultado:**
   - Se descarga: `bitacora_2025-11-14_153045.xlsx`
   - Formato profesional con colores corporativos
   - Todas las columnas incluidas

---

### 3️⃣ Exportar a PDF

1. **Aplicar filtros** (opcional)

2. **Clic en botón "Exportar"**

3. **Escribir "2"** para PDF

4. **Resultado:**
   - Se descarga: `bitacora_2025-11-14_153045.pdf`
   - Formato landscape (horizontal)
   - Diseño corporativo con logo
   - Máximo 1000 registros

---

## 📊 Columnas Exportadas

### Excel y PDF incluyen:

- **Fecha** - dd/mm/yyyy
- **Hora** - HH:mm:ss
- **Usuario** - Nombre completo
- **Email** - Correo electrónico
- **Rol** - ADMIN, DOCENTE, COORDINADOR
- **IP** - Dirección IP
- **Acción** - login, logout, create, update, delete, view
- **Módulo** - auth, teachers, students, schedules, etc.
- **Descripción** - Detalle de la acción
- **URL** - Ruta completa
- **Método** - GET, POST, PUT, DELETE

---

## 🔍 Filtros Disponibles

### Usuario
```
Ejemplo: "admin", "juan", "@ficct.edu.bo"
```

### Acción
- `login` - Inicio de Sesión
- `logout` - Cierre de Sesión
- `create` - Crear
- `update` - Actualizar
- `delete` - Eliminar
- `view` - Consultar

### Módulo
- `auth` - Autenticación
- `teachers` - Docentes
- `students` - Estudiantes
- `schedules` - Horarios
- `attendance` - Asistencia
- `reports` - Reportes

### Fechas
```
Formato: YYYY-MM-DD
Ejemplo: 2025-01-01 hasta 2025-12-31
```

---

## 💡 Ejemplos de Uso

### Ejemplo 1: Exportar todos los logins del mes

1. Filtro Acción: `login`
2. Fecha Desde: `2025-11-01`
3. Fecha Hasta: `2025-11-30`
4. Exportar → Excel

**Resultado:** Archivo con todos los inicios de sesión de noviembre

---

### Ejemplo 2: Exportar actividad de un usuario

1. Filtro Usuario: `admin@ficct.edu.bo`
2. Fecha Desde: `2025-11-01`
3. Fecha Hasta: `2025-11-14`
4. Exportar → PDF

**Resultado:** PDF con toda la actividad del usuario admin

---

### Ejemplo 3: Exportar cambios en docentes

1. Filtro Módulo: `teachers`
2. Filtro Acción: `update`
3. Exportar → Excel

**Resultado:** Excel con todas las modificaciones a docentes

---

## 🎯 Casos de Uso Comunes

### Auditoría de Seguridad
```
Filtros:
- Acción: login
- Fecha: Último mes
Exportar: Excel
```

### Seguimiento de Cambios
```
Filtros:
- Acción: update, delete
- Módulo: schedules
Exportar: PDF
```

### Reporte Mensual
```
Filtros:
- Fecha: Mes completo
Exportar: Excel
```

### Actividad de Usuario Específico
```
Filtros:
- Usuario: email@ficct.edu.bo
- Fecha: Rango específico
Exportar: PDF
```

---

## 🧹 Limpiar Logs Antiguos

### Cuándo usar:
- Tabla muy grande (>100,000 registros)
- Logs de más de 90 días
- Antes de exportaciones grandes

### Cómo usar:
1. **Exportar primero** (recomendado)
2. Clic en "Limpiar Antiguos"
3. Confirmar acción
4. Se eliminan registros >90 días

⚠️ **Advertencia:** Esta acción es irreversible

---

## 📱 Interfaz Responsive

### Móvil
- Filtros apilados verticalmente
- Tabla con scroll horizontal
- Botones adaptados

### Tablet
- Filtros en 2 columnas
- Tabla completa visible
- Estadísticas en 3 columnas

### Desktop
- Filtros en 5 columnas
- Tabla completa con todas las columnas
- Estadísticas en 6 columnas

---

## 🔒 Seguridad

### Acceso Restringido
- ✅ Solo usuarios con rol ADMIN
- ✅ Rutas protegidas con middleware
- ✅ Validación de permisos

### Información Registrada
- ✅ IP del usuario
- ✅ User Agent (navegador)
- ✅ Timestamp preciso
- ✅ Acción realizada

---

## ⚡ Atajos de Teclado

### En la vista web:
- `Enter` en filtros → Aplicar filtro
- `Esc` en modal → Cerrar detalles

---

## 📊 Estadísticas Visibles

La vista muestra en tiempo real:

1. **Total Registros** - Contador total
2. **Logins** - Total de inicios de sesión
3. **Creaciones** - Total de registros creados
4. **Actualizaciones** - Total de modificaciones
5. **Eliminaciones** - Total de eliminaciones
6. **Usuarios Activos** - Usuarios únicos

---

## 🎨 Formato de Archivos

### Excel (.xlsx)
- **Tamaño:** Variable según registros
- **Columnas:** Auto-ajustadas
- **Estilos:** Encabezado con color brand
- **Límite:** Sin límite (puede ser lento)

### PDF
- **Tamaño:** ~100KB por 100 registros
- **Orientación:** Landscape (horizontal)
- **Páginas:** Automáticas
- **Límite:** 1000 registros máximo

---

## 🚨 Solución de Problemas

### No se descarga el archivo
```bash
# Verificar permisos
chmod -R 775 storage/
```

### Error al exportar
```bash
# Limpiar caché
php artisan config:clear
php artisan cache:clear
```

### No se ven registros
```bash
# Verificar tabla
php artisan migrate
```

---

## 📞 Soporte Rápido

### Verificar instalación:
```bash
composer show | findstr /i "excel dompdf"
```

### Verificar rutas:
```bash
php artisan route:list --path=activity-logs
```

### Probar registro:
```bash
php artisan tinker
\App\Models\ActivityLog::log('test', 'system', 'Prueba');
```

---

## ✅ Checklist de Uso

Antes de exportar:

- [ ] Aplicar filtros necesarios
- [ ] Verificar rango de fechas
- [ ] Revisar estadísticas
- [ ] Elegir formato (Excel o PDF)

Después de exportar:

- [ ] Verificar descarga
- [ ] Abrir archivo
- [ ] Validar datos
- [ ] Archivar si es necesario

---

## 🎉 ¡Listo!

Con esta guía puedes exportar la bitácora en segundos.

**Recuerda:**
- Excel para análisis detallado
- PDF para reportes formales
- Filtros para datos específicos
- Limpiar logs antiguos periódicamente

---

**Desarrollado por:** Kiro AI Assistant  
**Fecha:** 14 de Noviembre, 2025  
**Versión:** 1.0.0
