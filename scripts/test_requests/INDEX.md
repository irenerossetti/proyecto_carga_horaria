# 🧪 Sistema de Pruebas API - Laravel

## 🎯 Inicio Rápido

```bash
cd scripts/test_requests
php quick_check.php        # Ver estado del sistema
php test_periods.php       # Probar módulo de periodos
php run_all_tests.php      # Ejecutar todos los tests
```

---

## 📁 Archivos en esta Carpeta

### 🚀 Scripts de Prueba (5)
1. **test_periods.php** - Prueba CRUD de Periodos Académicos
2. **test_teachers.php** - Prueba CRUD de Docentes
3. **test_students.php** - Prueba CRUD de Estudiantes  
4. **test_classrooms.php** - Prueba CRUD de Aulas/Salas
5. **test_subjects.php** - Prueba CRUD de Materias

### 🔧 Utilidades (3)
6. **run_all_tests.php** - Ejecuta todos los tests y muestra resumen
7. **test_periods_curl.php** - Ejemplo usando cURL puro (sin Laravel)
8. **quick_check.php** - Verificación rápida del sistema

### 📚 Documentación (4)
9. **INDEX.md** - Este archivo (punto de entrada)
10. **README.md** - Guía completa de uso y solución de problemas
11. **RESUMEN.md** - Resumen ejecutivo del proyecto
12. **RUTAS_API.md** - Mapa completo de las 90 rutas API disponibles

---

## 📊 Estado del Proyecto

### ✅ APIs Funcionando (con tabla en BD):
- **Periodos Académicos** - Totalmente funcional
- **Docentes** - API completa implementada
- **Aulas** - API completa implementada
- **Horarios** - API completa implementada
- **Roles** - API completa implementada

### ⚠️ APIs Implementadas (falta crear tabla en BD):
- **Materias/Subjects** - API lista, tabla no existe
- **Grupos** - API lista, tabla no existe
- **Asistencia** - API lista, tabla no existe
- **Reservas** - API lista, tabla no existe
- **Conflictos** - API lista, tabla no existe

### ❌ No Implementado:
- **Estudiantes** - Ni API ni tabla (pero hay script de prueba listo)

---

## 🗺️ Navegación Rápida

### Para empezar:
1. Lee: **README.md** → Guía completa con ejemplos
2. Ejecuta: **quick_check.php** → Ver estado actual
3. Prueba: **test_periods.php** → Debe funcionar de inmediato

### Para conocer el sistema:
- **RUTAS_API.md** → Ver todas las 90 rutas disponibles
- **RESUMEN.md** → Resumen ejecutivo del estado

### Para probar:
- **test_*.php** → Scripts individuales por módulo
- **run_all_tests.php** → Ejecutar todos a la vez

---

## 💡 Tips

### ¿Servidor no corre?
```bash
php artisan serve
```

### ¿Error 404 en las rutas?
```bash
php artisan route:list --path=api
php artisan optimize:clear
```

### ¿Tabla no existe?
```bash
php artisan migrate:status
php artisan migrate
```

### ¿Ver documentación API?
```
http://127.0.0.1:8000/api/documentation
```

---

## 📈 Estadísticas del Proyecto

- **Scripts de prueba:** 8 archivos
- **Documentación:** 4 archivos  
- **Rutas API:** 90 endpoints
- **Controladores:** 16+ implementados
- **Tablas en BD:** 8+ existentes
- **Frameworks:** Laravel 11, TailwindCSS 4
- **Base de datos:** PostgreSQL (Neon)

---

## 🎯 Siguiente Paso Recomendado

1. **Ejecutar quick_check.php** para ver estado
2. **Probar test_periods.php** (debería funcionar)
3. **Ejecutar migraciones pendientes** si hay tablas faltantes
4. **Ejecutar run_all_tests.php** para ver qué más falta

---

## 📞 Ayuda

Si algo no funciona:
1. Revisa **README.md** sección "Solución de Problemas"
2. Verifica logs: `storage/logs/laravel.log`
3. Comprueba rutas: `php artisan route:list`

---

**Creado:** Noviembre 2025  
**Proyecto:** Sistema de Carga Horaria FICCT  
**Laravel:** 12.36.1 | **PHP:** 8.2+ | **PostgreSQL:** Neon
