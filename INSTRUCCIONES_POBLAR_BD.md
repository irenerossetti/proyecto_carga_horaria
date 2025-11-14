# 🎯 INSTRUCCIONES PARA POBLAR LA BASE DE DATOS

## ⚡ COMANDO RÁPIDO

```bash
php artisan migrate:fresh --seed
```

**⚠️ ADVERTENCIA:** Este comando BORRARÁ todos los datos existentes y creará datos nuevos.

---

## 📊 DATOS QUE SE CREARÁN

### Usuarios (279 total):
- ✅ 1 Administrador
- ✅ 3 Coordinadores
- ✅ 25 Docentes
- ✅ 250 Estudiantes

### Estructura Académica:
- ✅ 3 Periodos académicos
- ✅ 45 Materias (10 semestres)
- ✅ 28+ Grupos
- ✅ 31 Aulas (3 edificios)
- ✅ 45+ Asignaciones de docentes

### Datos Operacionales:
- ✅ Horarios de clases
- ✅ Registros de asistencia
- ✅ Incidencias reportadas
- ✅ Anuncios del sistema
- ✅ Bitácora de actividades

---

## 👤 CREDENCIALES DE ACCESO

### Administrador:
```
Email: admin@ficct.edu.bo
Password: password
```

### Coordinadores:
```
Email: coord.sistemas@ficct.edu.bo
Email: coord.redes@ficct.edu.bo
Email: coord.industrial@ficct.edu.bo
Password: password (para todos)
```

### Docentes (25 docentes):
```
Email: perezgarcia@ficct.edu.bo
Email: lopezsilva@ficct.edu.bo
Email: rodriguezdiaz@ficct.edu.bo
... (22 más)
Password: password (para todos)
```

### Estudiantes (250 estudiantes):
```
Email: est001@ficct.edu.bo
Email: est002@ficct.edu.bo
... hasta est250@ficct.edu.bo
Password: password (para todos)
```

---

## 🏗️ ESTRUCTURA DE MATERIAS

### Semestre 1 (5 materias):
- INF-101: Introducción a la Programación
- MAT-101: Cálculo I
- MAT-102: Álgebra Lineal
- FIS-101: Física I
- QUI-101: Química General

### Semestre 2 (5 materias):
- INF-201: Programación Orientada a Objetos
- MAT-201: Cálculo II
- FIS-201: Física II
- MAT-203: Estructuras Discretas
- ING-101: Inglés Técnico I

### Semestre 3 (5 materias):
- INF-301: Estructura de Datos
- INF-302: Base de Datos I
- INF-303: Arquitectura de Computadoras
- MAT-301: Probabilidad y Estadística
- ING-201: Inglés Técnico II

### Semestre 4 (5 materias):
- INF-401: Algoritmos Avanzados
- INF-402: Base de Datos II
- INF-403: Sistemas Operativos
- INF-404: Redes de Computadoras I
- INF-405: Ingeniería de Software I

### Semestre 5 (5 materias):
- INF-501: Programación Web
- INF-502: Inteligencia Artificial
- INF-503: Redes de Computadoras II
- INF-504: Ingeniería de Software II
- ADM-301: Investigación Operativa

### Semestre 6 (5 materias):
- INF-601: Desarrollo de Aplicaciones Móviles
- INF-602: Seguridad Informática
- INF-603: Sistemas Distribuidos
- ADM-401: Gestión de Proyectos
- ADM-402: Emprendimiento

### Semestre 7 (5 materias):
- INF-701: Cloud Computing
- INF-702: Big Data
- INF-703: Internet de las Cosas
- ADM-501: Auditoría de Sistemas
- ETI-101: Ética Profesional

### Semestre 8 (5 materias):
- INF-801: Machine Learning
- INF-802: Blockchain
- INF-803: Computación Cuántica
- TES-101: Taller de Tesis I
- PRA-101: Práctica Profesional

### Semestre 9 (3 materias):
- INF-901: Deep Learning
- INF-902: Ciberseguridad Avanzada
- TES-201: Taller de Tesis II

### Semestre 10 (2 materias):
- TES-301: Proyecto de Grado
- SEM-101: Seminario de Actualización

**Total: 45 materias**

---

## 🏫 AULAS CREADAS

### Edificio A (10 aulas):
- A-101 a A-110
- Capacidad: 25-45 estudiantes
- Piso 1-3

### Edificio B (10 aulas):
- B-101 a B-110
- Capacidad: 25-45 estudiantes
- Piso 1-3

### Edificio C (11 aulas):
- C-101 a C-111
- Capacidad: 25-45 estudiantes
- Piso 1-3

**Total: 31 aulas**

---

## 📅 PERIODOS ACADÉMICOS

### Gestión 1-2024 (Cerrado):
- Código: 2024-1
- Inicio: 15 de Enero 2024
- Fin: 30 de Junio 2024
- Estado: Cerrado

### Gestión 2-2024 (Cerrado):
- Código: 2024-2
- Inicio: 15 de Julio 2024
- Fin: 20 de Diciembre 2024
- Estado: Cerrado

### Gestión 1-2025 (Activo):
- Código: 2025-1
- Inicio: 20 de Enero 2025
- Fin: 30 de Junio 2025
- Estado: Activo ✅

---

## 🚀 PASOS PARA EJECUTAR

### Paso 1: Backup (Opcional pero Recomendado)
```bash
# Exportar base de datos actual
mysqldump -u root -p nombre_bd > backup_$(date +%Y%m%d).sql
```

### Paso 2: Ejecutar Seeder
```bash
php artisan migrate:fresh --seed
```

### Paso 3: Verificar
```bash
php artisan tinker
```

```php
// Verificar usuarios
\App\Models\User::count()
// Debería mostrar: 279

// Verificar materias
DB::table('subjects')->count()
// Debería mostrar: 45

// Verificar aulas
DB::table('rooms')->count()
// Debería mostrar: 31

// Verificar grupos
DB::table('groups')->count()
// Debería mostrar: 28+
```

---

## ⏱️ TIEMPO ESTIMADO

- **Migración:** 10-20 segundos
- **Seeding:** 30-60 segundos
- **Total:** ~1-2 minutos

---

## 💾 ESPACIO REQUERIDO

- **Base de datos:** ~50-100 MB
- **Memoria RAM:** 512 MB mínimo
- **Espacio en disco:** 200 MB libre

---

## ✅ VERIFICACIÓN POST-SEEDING

### 1. Verificar Login
```
http://localhost:8000/login
Email: admin@ficct.edu.bo
Password: password
```

### 2. Verificar Dashboard
- Debería mostrar números reales
- Gráficos con datos
- Tarjetas clicables

### 3. Verificar Módulos
- **Docentes:** 25 registros
- **Estudiantes:** 250 registros
- **Materias:** 45 registros
- **Grupos:** 28+ registros
- **Aulas:** 31 registros

### 4. Verificar Roles
```bash
php artisan tinker
```

```php
// Ver usuarios por rol
DB::table('role_user')->select('role_id', DB::raw('count(*) as total'))
    ->groupBy('role_id')->get();

// Debería mostrar:
// role_id: 1 (ADMIN) -> 1 usuario
// role_id: 2 (COORDINADOR) -> 3 usuarios
// role_id: 3 (DOCENTE) -> 25 usuarios
// role_id: 4 (ESTUDIANTE) -> 250 usuarios
```

---

## 🐛 SOLUCIÓN DE PROBLEMAS

### Error: "SQLSTATE[42S02]: Base table or view not found"
**Solución:**
```bash
php artisan migrate
php artisan db:seed
```

### Error: "Class 'DatabaseSeeder' not found"
**Solución:**
```bash
composer dump-autoload
php artisan db:seed
```

### Error: "Memory limit exceeded"
**Solución:**
```bash
php -d memory_limit=512M artisan db:seed
```

### Error: "Maximum execution time"
**Solución:**
```bash
php -d max_execution_time=300 artisan db:seed
```

---

## 📝 NOTAS IMPORTANTES

1. **Passwords:**
   - Todos los usuarios tienen password: `password`
   - Cambiar en producción

2. **Emails:**
   - Todos terminan en `@ficct.edu.bo`
   - Son ficticios para pruebas

3. **Datos:**
   - Son realistas pero ficticios
   - Útiles para demos y pruebas
   - No usar en producción real

4. **Relaciones:**
   - Todas las relaciones están correctamente configuradas
   - Foreign keys respetadas
   - Sin datos huérfanos

---

## 🎉 RESULTADO ESPERADO

Después de ejecutar el seeder:

```
╔════════════════════════════════════════════════════╗
║                                                    ║
║   🎊 BASE DE DATOS POBLADA COMPLETAMENTE          ║
║                                                    ║
║   Usuarios:                                        ║
║   ✅ 1 Administrador                              ║
║   ✅ 3 Coordinadores                              ║
║   ✅ 25 Docentes                                  ║
║   ✅ 250 Estudiantes                              ║
║                                                    ║
║   Estructura:                                      ║
║   ✅ 3 Periodos académicos                        ║
║   ✅ 45 Materias                                  ║
║   ✅ 28+ Grupos                                   ║
║   ✅ 31 Aulas                                     ║
║   ✅ 45+ Asignaciones                             ║
║                                                    ║
║   Estado: LISTO PARA USAR                          ║
║                                                    ║
╚════════════════════════════════════════════════════╝
```

---

## 🚀 ¡EJECUTA AHORA!

```bash
php artisan migrate:fresh --seed
```

**¡Y tendrás un sistema completo con datos de prueba!** 🎉

---

**Creado por:** Kiro AI Assistant  
**Fecha:** 14 de Noviembre, 2025  
**Estado:** ✅ Listo para Ejecutar
