<!DOCTYPE html>
<html lang="es" class="h-full bg-[#F5F5F5]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Historial de Asistencias - FICCT SGA</title>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Instrument Sans', 'sans-serif'] },
                    colors: { brand: { primary: '#881F34', hover: '#6d1829' } }
                }
            }
        }
    </script>
</head>

<body class="min-h-full font-sans antialiased text-neutral-900 bg-[#F5F5F5]">
    <nav class="bg-brand-primary text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-3">
                    <a href="{{ route('docente.dashboard') }}" class="text-white/70 hover:text-white">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center">
                        <i class="fas fa-history text-white"></i>
                    </div>
                    <span class="font-bold text-lg tracking-tight">Historial de Asistencias</span>
                </div>
                <button onclick="exportReport()" class="px-4 py-2 bg-white/10 hover:bg-white/20 rounded-lg transition-colors text-sm font-medium">
                    <i class="fas fa-file-export mr-2"></i><span class="hidden sm:inline">Exportar</span>
                </button>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 sm:py-8 px-4 sm:px-6 lg:px-8">
        
        <!-- Filtros -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Materia</label>
                    <select id="filterSubject" onchange="filterAttendance()" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">Todas las materias</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Desde</label>
                    <input type="date" id="dateFrom" onchange="filterAttendance()" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Hasta</label>
                    <input type="date" id="dateTo" onchange="filterAttendance()" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <select id="filterStatus" onchange="filterAttendance()" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">Todos</option>
                        <option value="presente">Presente</option>
                        <option value="ausente">Ausente</option>
                        <option value="justificado">Justificado</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <p class="text-sm text-gray-600">Total Clases</p>
                <p class="text-2xl font-bold text-gray-900" id="totalClasses">0</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <p class="text-sm text-gray-600">Asistencias</p>
                <p class="text-2xl font-bold text-green-600" id="presentCount">0</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <p class="text-sm text-gray-600">Ausencias</p>
                <p class="text-2xl font-bold text-red-600" id="absentCount">0</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <p class="text-sm text-gray-600">% Asistencia</p>
                <p class="text-2xl font-bold text-brand-primary" id="attendancePercent">0%</p>
            </div>
        </div>

        <!-- Tabla de Historial -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Registro de Asistencias</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Materia</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">Grupo</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Horario</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">Aula</th>
                            <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
                        </tr>
                    </thead>
                    <tbody id="attendanceTable" class="divide-y divide-gray-200">
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
    let attendances = [];

    document.addEventListener('DOMContentLoaded', function() {
        loadAttendances();
    });

    async function loadAttendances() {
        // Datos simulados
        attendances = [
            { id: 1, date: '2025-11-14', subject: 'Programación I', group: 'SC', time: '07:00-08:30', room: 'Aula 301', status: 'presente' },
            { id: 2, date: '2025-11-13', subject: 'Base de Datos', group: 'SA', time: '10:00-11:30', room: 'Lab 102', status: 'presente' },
            { id: 3, date: '2025-11-12', subject: 'Programación I', group: 'SC', time: '07:00-08:30', room: 'Aula 301', status: 'presente' },
            { id: 4, date: '2025-11-11', subject: 'Base de Datos', group: 'SA', time: '10:00-11:30', room: 'Lab 102', status: 'ausente' },
            { id: 5, date: '2025-11-10', subject: 'Programación I', group: 'SC', time: '07:00-08:30', room: 'Aula 301', status: 'justificado' }
        ];
        
        updateStats();
        displayAttendances(attendances);
    }

    function updateStats() {
        const total = attendances.length;
        const present = attendances.filter(a => a.status === 'presente').length;
        const absent = attendances.filter(a => a.status === 'ausente').length;
        const percent = total > 0 ? Math.round((present / total) * 100) : 0;
        
        document.getElementById('totalClasses').textContent = total;
        document.getElementById('presentCount').textContent = present;
        document.getElementById('absentCount').textContent = absent;
        document.getElementById('attendancePercent').textContent = percent + '%';
    }

    function displayAttendances(data) {
        const tbody = document.getElementById('attendanceTable');
        
        if (data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">No hay registros de asistencia</td></tr>';
            return;
        }
        
        tbody.innerHTML = data.map(att => `
            <tr class="hover:bg-gray-50">
                <td class="px-4 sm:px-6 py-4">
                    <div class="font-medium text-gray-900">${formatDate(att.date)}</div>
                    <div class="text-sm text-gray-500 sm:hidden">${att.subject}</div>
                </td>
                <td class="px-4 sm:px-6 py-4 text-sm text-gray-600 hidden sm:table-cell">${att.subject}</td>
                <td class="px-4 sm:px-6 py-4 text-sm text-gray-600 hidden lg:table-cell">${att.group}</td>
                <td class="px-4 sm:px-6 py-4 text-sm text-gray-600 hidden md:table-cell">${att.time}</td>
                <td class="px-4 sm:px-6 py-4 text-sm text-gray-600 hidden lg:table-cell">${att.room}</td>
                <td class="px-4 sm:px-6 py-4 text-center">
                    ${getStatusBadge(att.status)}
                </td>
            </tr>
        `).join('');
    }

    function getStatusBadge(status) {
        const badges = {
            presente: '<span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded">Presente</span>',
            ausente: '<span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-medium rounded">Ausente</span>',
            justificado: '<span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded">Justificado</span>'
        };
        return badges[status] || status;
    }

    function formatDate(dateStr) {
        const date = new Date(dateStr + 'T00:00:00');
        return date.toLocaleDateString('es-ES', { weekday: 'short', day: '2-digit', month: '2-digit', year: 'numeric' });
    }

    function filterAttendance() {
        const status = document.getElementById('filterStatus').value;
        const dateFrom = document.getElementById('dateFrom').value;
        const dateTo = document.getElementById('dateTo').value;
        
        let filtered = attendances;
        
        if (status) {
            filtered = filtered.filter(a => a.status === status);
        }
        
        if (dateFrom) {
            filtered = filtered.filter(a => a.date >= dateFrom);
        }
        
        if (dateTo) {
            filtered = filtered.filter(a => a.date <= dateTo);
        }
        
        displayAttendances(filtered);
    }

    function exportReport() {
        alert('Exportando reporte de asistencias...');
    }
    </script>
</body>
</html>
