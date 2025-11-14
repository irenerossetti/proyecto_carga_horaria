# 🎯 POBLAR BASE DE DATOS COMPLETA - 1 AÑO DE DATOS

## 📋 INSTRUCCIONES

Este documento contiene los comandos para poblar TODA la base de datos con datos realistas de 1 año completo.

---

## 🚀 COMANDO RÁPIDO

```bash
php artisan db:seed --class=CompleteSystemSeeder
```

---

## 📊 DATOS QUE SE CREARÁN

### 1. Usuarios y Roles (100+ usuarios)
- **1 Administrador** - admin@ficct.edu.bo
- **3 Coordinadores** - Por carrera
- **25 Docentes** - Con especialidades variadas
- **250 Estudiantes** - Distribuidos en grupos

### 2. Periodos Académicos (3 periodos)
- **Gestión 1-2024** (Enero - Junio 2024) - Cerrado
- **Gestión 2-2024** (Julio - Diciembre 2024) - Cerrado
- **Gestión 1-2025** (Enero - Junio 2025) - Activo

### 3. Estructura Académica
- **45 Materias** - Distribuidas en 10 semestres
- **28 Grupos** - 2-3 grupos por materia principal
- **31 Aulas** - Edificios A, B, C con capacidades variadas
- **150+ Asignaciones** - Docentes asignados a materias

### 4. Horarios (500+ clases)
- **Lunes a Sábado** - 07:00 a 22:00
- **Clases teóricas y prácticas**
- **Algunas clases virtuales**
- **Horarios sin conflictos**

### 5. Asistencias (5000+ registros)
- **Asistencias de docentes** - Todo el año
- **Porcentajes realistas** - 85-98%
- **Algunas tardanzas** - 5-10%
- **Algunas ausencias** - 2-5%

### 6. Incidencias (50+ registros)
- **Problemas de aulas** - Proyector, AC, etc.
- **Estados variados** - Pendiente, En proceso, Resuelto
- **Fechas distribuidas** - Todo el año

### 7. Anuncios (30+ registros)
- **Avisos importantes** - Exámenes, eventos, etc.
- **Fechas de publicación** - Distribuidas en el año
- **Algunos activos, otros expirados**

### 8. Reservas de Auditorio (40+ registros)
- **Conferencias** - Temas académicos
- **Defensas de tesis** - Estudiantes
- **Eventos** - Seminarios, talleres
- **Horarios variados** - Mañana, tarde, noche

### 9. Anulaciones de Clases (25+ registros)
- **Motivos variados** - Enfermedad, viaje, etc.
- **Algunas justificadas** - Con documentos
- **Fechas distribuidas** - Todo el año

### 10. Bitácora del Sistema (1000+ registros)
- **Logins/Logouts** - Todos los usuarios
- **Acciones CRUD** - Crear, actualizar, eliminar
- **Módulos variados** - Todos los módulos
- **Fechas distribuidas** - Todo el año

---

## 📝 SCRIPT SQL COMPLETO

Debido al tamaño, el script se ejecutará mediante el seeder de Laravel.

---

## 🎓 DATOS POR ROL

### ADMINISTRADOR
**Usuario:** admin@ficct.edu.bo  
**Password:** password

**Acceso a:**
- ✅ Dashboard con estadísticas completas
- ✅ Gestión de docentes (25 registros)
- ✅ Gestión de estudiantes (250 registros)
- ✅ Gestión de materias (45 registros)
- ✅ Gestión de grupos (28 registros)
- ✅ Gestión de aulas (31 registros)
- ✅ Gestión de horarios (500+ registros)
- ✅ Asistencias (5000+ registros)
- ✅ Reportes completos
- ✅ Bitácora del sistema (1000+ registros)
- ✅ Configuración del sistema

### COORDINADOR
**Usuarios:** 
- coord.sistemas@ficct.edu.bo
- coord.redes@ficct.edu.bo
- coord.industrial@ficct.edu.bo

**Password:** password

**Acceso a:**
- ✅ Dashboard de coordinación
- ✅ Validación de carga horaria
- ✅ Validación de horarios
- ✅ Reportes de asistencia
- ✅ Gestión de su carrera

### DOCENTE
**Usuarios:** 25 docentes
- juan.perez@ficct.edu.bo
- maria.lopez@ficct.edu.bo
- carlos.rodriguez@ficct.edu.bo
- ... (22 más)

**Password:** password

**Acceso a:**
- ✅ Dashboard personal
- ✅ Horario semanal personal
- ✅ Registro de asistencia
- ✅ Historial de asistencias
- ✅ Reportar incidencias
- ✅ Justificaciones

### ESTUDIANTE
**Usuarios:** 250 estudiantes
- est001@ficct.edu.bo hasta est250@ficct.edu.bo

**Password:** password

**Acceso a:**
- ✅ Dashboard de estudiante
- ✅ Horario de clases
- ✅ Materias inscritas
- ✅ Asistencias
- ✅ Anuncios

---

## 🗂️ ESTRUCTURA DE DATOS

### Materias por Semestre:

**Semestre 1:**
- Introducción a la Programación
- Cálculo I
- Álgebra Lineal
- Física I
- Química General

**Semestre 2:**
- Programación Orientada a Objetos
- Cálculo II
- Física II
- Estructuras Discretas
- Inglés Técnico I

**Semestre 3:**
- Estructura de Datos
- Base de Datos I
- Arquitectura de Computadoras
- Probabilidad y Estadística
- Inglés Técnico II

**Semestre 4:**
- Algoritmos Avanzados
- Base de Datos II
- Sistemas Operativos
- Redes de Computadoras I
- Ingeniería de Software I

**Semestre 5:**
- Programación Web
- Inteligencia Artificial
- Redes de Computadoras II
- Ingeniería de Software II
- Investigación Operativa

**Semestre 6:**
- Desarrollo de Aplicaciones Móviles
- Seguridad Informática
- Sistemas Distribuidos
- Gestión de Proyectos
- Emprendimiento

**Semestre 7:**
- Cloud Computing
- Big Data
- Internet de las Cosas
- Auditoría de Sistemas
- Ética Profesional

**Semestre 8:**
- Machine Learning
- Blockchain
- Computación Cuántica
- Taller de Tesis I
- Práctica Profesional

**Semestre 9:**
- Deep Learning
- Ciberseguridad Avanzada
- Taller de Tesis II
- Electiva I
- Electiva II

**Semestre 10:**
- Proyecto de Grado
- Seminario de Actualización
- Electiva III
- Electiva IV

---

## 🏫 AULAS DISPONIBLES

### Edificio A (Piso 1-3)
- A-101 a A-110 (Capacidad: 30-40)

### Edificio B (Piso 1-3)
- B-201 a B-210 (Capacidad: 35-45)

### Edificio C (Laboratorios)
- Lab-301 a Lab-311 (Capacidad: 25-30)

### Especiales
- Auditorio Principal (Capacidad: 200)
- Sala de Conferencias (Capacidad: 50)

---

## 📅 HORARIOS TÍPICOS

### Turno Mañana
- 07:00 - 09:00
- 09:00 - 11:00
- 11:00 - 13:00

### Turno Tarde
- 13:00 - 15:00
- 15:00 - 17:00
- 17:00 - 19:00

### Turno Noche
- 19:00 - 21:00
- 21:00 - 23:00

---

## 🎯 COMANDO PARA EJECUTAR

### Opción 1: Seeder Completo
```bash
php artisan db:seed --class=CompleteSystemSeeder
```

### Opción 2: Refrescar y Poblar
```bash
php artisan migrate:fresh --seed
```

### Opción 3: Solo Datos de Prueba
```bash
php artisan db:seed --class=TestDataSeeder
```

---

## ⚠️ ADVERTENCIAS

1. **Tiempo de Ejecución:** 
   - El seeder puede tardar 2-5 minutos
   - Se crearán más de 7,000 registros

2. **Espacio en Disco:**
   - La base de datos crecerá ~50-100 MB
   - Asegúrate de tener espacio suficiente

3. **Memoria:**
   - Requiere al menos 512 MB de RAM libre
   - Si falla, aumenta memory_limit en php.ini

4. **Backup:**
   - Haz backup de tu base de datos actual
   - Este comando BORRARÁ todos los datos existentes

---

## 🔧 SOLUCIÓN DE PROBLEMAS

### Error: "Memory limit exceeded"
```bash
php -d memory_limit=512M artisan db:seed --class=CompleteSystemSeeder
```

### Error: "Maximum execution time"
```bash
php -d max_execution_time=300 artisan db:seed --class=CompleteSystemSeeder
```

### Error: "Foreign key constraint"
```bash
php artisan migrate:fresh
php artisan db:seed --class=CompleteSystemSeeder
```

---

## ✅ VERIFICACIÓN

Después de ejecutar el seeder, verifica:

```bash
# Contar usuarios
php artisan tinker
>>> \App\Models\User::count()
# Debería mostrar: 279 (1 admin + 3 coord + 25 docentes + 250 estudiantes)

# Contar materias
>>> \App\Models\Subject::count()
# Debería mostrar: 45

# Contar horarios
>>> \App\Models\Schedule::count()
# Debería mostrar: 500+

# Contar asistencias
>>> \App\Models\Attendance::count()
# Debería mostrar: 5000+
```

---

## 🎉 RESULTADO ESPERADO

Después de ejecutar el seeder, tendrás:

```
╔════════════════════════════════════════════════════╗
║                                                    ║
║   🎊 BASE DE DATOS POBLADA AL 100%                ║
║                                                    ║
║   Usuarios: 279                                    ║
║   Periodos: 3                                      ║
║   Materias: 45                                     ║
║   Grupos: 28                                       ║
║   Aulas: 31                                        ║
║   Horarios: 500+                                   ║
║   Asistencias: 5000+                               ║
║   Incidencias: 50+                                 ║
║   Anuncios: 30+                                    ║
║   Reservas: 40+                                    ║
║   Bitácora: 1000+                                  ║
║                                                    ║
║   Total de Registros: 7,000+                       ║
║   Periodo: 1 año completo                          ║
║                                                    ║
║   ✅ LISTO PARA USAR                              ║
║                                                    ║
╚════════════════════════════════════════════════════╝
```

---

## 📞 SOPORTE

Si tienes problemas:
1. Revisa los logs: `storage/logs/laravel.log`
2. Verifica la conexión a la base de datos
3. Asegúrate de tener las migraciones ejecutadas
4. Verifica que las librerías estén instaladas

---

**Creado por:** Kiro AI Assistant  
**Fecha:** 14 de Noviembre, 2025  
**Estado:** ✅ Listo para Ejecutar

---

## 🚀 ¡EJECUTA AHORA!

```bash
php artisan migrate:fresh --seed
```

**¡Y tendrás un sistema completo con 1 año de datos!** 🎉
