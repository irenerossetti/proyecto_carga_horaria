# 🔐 Credenciales de Usuarios de Prueba

## Sistema de Gestión de Carga Horaria - FICCT

---

### 🔴 ADMINISTRADOR (Acceso completo)
**Email:** `admin@ficct.edu.bo`  
**Contraseña:** `admin123`

**Permisos:**
- ✅ Gestión completa de periodos académicos
- ✅ CRUD de docentes, estudiantes, materias, aulas
- ✅ Generación automática de horarios
- ✅ Resolución de conflictos
- ✅ Reportes y estadísticas
- ✅ Configuración del sistema

---

### 🟡 COORDINADOR (Gestión limitada)
**Email:** `coordinador@ficct.edu.bo`  
**Contraseña:** `coord123`

**Permisos:**
- ✅ Ver dashboard con estadísticas
- ✅ Gestión de docentes
- ✅ Gestión de aulas
- ✅ Gestión de materias
- ❌ No puede eliminar usuarios
- ❌ No puede cambiar configuración del sistema

---

### 🔵 DOCENTES (Asistencia y clases)
**Email:** `docente@ficct.edu.bo`  
**Contraseña:** `docente123`  
**Nombre:** Juan Pérez

**Email:** `docente2@ficct.edu.bo`  
**Contraseña:** `docente123`  
**Nombre:** María González

**Permisos:**
- ✅ Ver su horario semanal
- ✅ Marcar asistencia a clases
- ✅ Cambiar clases a modalidad virtual
- ✅ Ver sus grupos asignados
- ✅ Reportar incidencias en aulas
- ❌ No puede ver información de otros docentes

---

### 🟢 ESTUDIANTES (Solo lectura)
**Email:** `estudiante@ficct.edu.bo`  
**Contraseña:** `estudiante123`  
**Nombre:** Carlos López

**Email:** `estudiante2@ficct.edu.bo`  
**Contraseña:** `estudiante123`  
**Nombre:** Ana Martínez

**Permisos:**
- ✅ Ver sus materias inscritas
- ✅ Ver horarios de sus clases
- ✅ Ver estado de clases (Normal, Virtual, Cambio de aula)
- ✅ Ver anuncios y notificaciones
- ❌ No puede modificar ningún dato

---

## 🗄️ Estado de la Base de Datos (Neon)

### ✅ Tablas Existentes:
- `users` (1 registro: admin)
- `roles` (3 registros: ADMIN, COORDINADOR, DOCENTE)
- `role_user` (relación usuarios-roles)
- `teachers` (0 registros)
- `rooms` (0 registros)
- `schedules` (0 registros)
- `academic_periods` (verifica cuántos hay)
- `teacher_assignments` (0 registros)

### ❌ Tablas Faltantes (Necesitan migración):
- `subjects` (materias)
- `groups` (grupos de estudiantes)
- `attendances` (asistencias)
- `conflicts` (conflictos horarios)
- `reservations` (reservas de aulas)
- `class_cancellations` (clases anuladas/virtuales)
- `incidents` (incidencias de aulas)
- `announcements` (anuncios)
- `students` (datos de estudiantes)

---

## 🚀 Para empezar a usar el sistema:

1. **Acceder al login:**
   ```
   http://127.0.0.1:8000/login
   ```

2. **Probar cada rol:**
   - Inicia sesión con cada usuario
   - Verifica que el dashboard muestre la información correcta
   - Cierra sesión y prueba otro rol

3. **Siguiente paso:**
   - Crear las migraciones faltantes para las tablas que no existen
   - Agregar datos de prueba (aulas, materias, horarios)
   - Implementar las vistas CRUD para el administrador

---

## 📝 Notas Importantes:

- **Contraseñas simples:** Solo para desarrollo. En producción usar contraseñas seguras.
- **Base de datos limpia:** Solo tiene el admin original. Los demás usuarios fueron agregados.
- **Middleware activo:** Las rutas están protegidas por roles. Si intentas acceder a una ruta sin el rol correcto, serás redirigido.

---

**Fecha de creación:** 11 de noviembre de 2025  
**Proyecto:** Sistema de Gestión de Carga Horaria - FICCT  
**Framework:** Laravel 12.36.1 + PostgreSQL (Neon)
