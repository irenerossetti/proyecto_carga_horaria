# Base de Datos Poblada - Resumen

## ✅ Estado: Base de datos completamente poblada

Fecha: 14 de Noviembre de 2025

## 📊 Resumen de Datos

| Tabla | Cantidad | Descripción |
|-------|----------|-------------|
| **Roles** | 3 | ADMIN, DOCENTE, ESTUDIANTE |
| **Usuarios** | 32 | 1 admin + 10 docentes + 10 estudiantes (+ 11 adicionales) |
| **Docentes** | 15 | Registros de profesores con información completa |
| **Períodos Académicos** | 20 | Incluye 2024-I, 2024-II, 2025-I (activo), 2025-II |
| **Materias** | 10 | Diversas materias de Ingeniería Informática |
| **Aulas** | 55 | Distribuidas en edificios A, B y C |
| **Grupos** | 50 | Grupos organizados por materia |
| **Horarios** | 50 | Asignaciones de horarios a grupos |
| **Asistencias** | 50 | Registros de asistencia de docentes |
| **Parámetros del Sistema** | 5 | Configuraciones del sistema |
| **Anuncios** | 8 | Comunicados importantes |
| **Logs de Actividad** | 10 | Registro de acciones de usuarios |

## 🔑 Credenciales de Acceso

### Administrador
- **Email:** admin@universidad.edu
- **Password:** password
- **Rol:** ADMIN
- **Matrícula:** 000000001

### Docentes (10 disponibles)
- **Email:** docente1@universidad.edu
- **Password:** password
- **Rol:** DOCENTE
- **Matrícula:** 000000100

*También disponibles: docente2@universidad.edu hasta docente10@universidad.edu*

### Estudiantes (10 disponibles)
- **Email:** estudiante1@universidad.edu
- **Password:** password
- **Rol:** ESTUDIANTE
- **Matrícula:** 000000200

*También disponibles: estudiante2@universidad.edu hasta estudiante10@universidad.edu*

## 📚 Materias Pobladas

1. **INF-111** - Programación I (4 créditos)
2. **INF-121** - Programación II (4 créditos)
3. **MAT-101** - Cálculo I (5 créditos)
4. **MAT-102** - Cálculo II (5 créditos)
5. **INF-211** - Estructuras de Datos (4 créditos)
6. **INF-221** - Base de Datos I (4 créditos)
7. **INF-231** - Ingeniería de Software (4 créditos)
8. **INF-241** - Redes de Computadoras (4 créditos)
9. **INF-311** - Inteligencia Artificial (4 créditos)
10. **INF-321** - Desarrollo Web (4 créditos)

## 🏫 Aulas Disponibles

- **Edificio A:** A101, A102, A103, A201, A202, A203, A301, A302, A303
- **Edificio B:** B101, B102, B103, B201, B202, B203, B301, B302, B303
- **Edificio C:** C101

Cada aula tiene:
- Capacidad entre 20-50 estudiantes
- Recursos (proyector, computadoras, pizarra, aire acondicionado)
- Ubicación específica

## 📅 Períodos Académicos

- **2024-I:** Cerrado (Enero - Junio 2024)
- **2024-II:** Cerrado (Julio - Diciembre 2024)
- **2025-I:** 🟢 **ACTIVO** (Enero - Junio 2025)
- **2025-II:** Borrador (Julio - Diciembre 2025)

## ⏰ Horarios

Los horarios están distribuidos en:
- **Días:** Lunes a Viernes
- **Bloques horarios:**
  - 08:00 - 10:00
  - 10:00 - 12:00
  - 14:00 - 16:00
  - 16:00 - 18:00

## 🎯 Próximos Pasos

1. ✅ Base de datos poblada
2. ⏭️ Verificar el dashboard administrativo
3. ⏭️ Probar funcionalidades de docentes
4. ⏭️ Probar funcionalidades de estudiantes
5. ⏭️ Validar reportes y exportaciones

## 🔧 Comando para Repoblar

Si necesitas volver a poblar la base de datos:

```bash
php artisan db:seed --class=CompleteDataSeeder
```

**Nota:** Este comando utiliza `insertOrIgnore` por lo que no duplicará datos existentes.

## 📝 Notas Importantes

- Todas las contraseñas son **"password"** (sin comillas)
- Los datos son ficticios y generados para desarrollo
- Las asistencias tienen fechas retroactivas (últimos 30 días)
- Los logs de actividad también tienen fechas retroactivas

## 🐛 Solución de Problemas

### Error: "cached plan must not change result type"
✅ **Solucionado:** Se agregó `PDO::ATTR_EMULATE_PREPARES => true` en la configuración de PostgreSQL

### Error: "operator does not exist: character varying = integer"
✅ **Solucionado:** Se corrigió el mapeo de días de la semana de números a strings en español

### Tablas no existen
✅ **Solucionado:** Se ejecutaron todas las migraciones y se verificaron las estructuras

## 🎓 Información del Proyecto

- **Framework:** Laravel 12.36.1
- **PHP:** 8.3.26
- **Base de Datos:** PostgreSQL
- **Entorno:** Neon (Cloud PostgreSQL)

---

✨ **¡La base de datos está lista para usar!**
