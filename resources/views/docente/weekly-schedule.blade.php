<!DOCTYPE html>
<html lang="es" class="h-full bg-[#F5F5F5]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Horario Semanal - FICCT SGA</title>
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
                        <i class="fas fa-calendar-week text-white"></i>
                    </div>
                    <span class="font-bold text-lg tracking-tight">Mi Horario Semanal</span>
                </div>
                <button onclick="exportPDF()" class="px-4 py-2 bg-white/10 hover:bg-white/20 rounded-lg transition-colors text-sm font-medium">
                    <i class="fas fa-file-pdf mr-2"></i><span class="hidden sm:inline">Exportar PDF</span>
                </button>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 sm:py-8 px-4 sm:px-6 lg:px-8">
        
        <!-- Resumen -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <p class="text-sm text-gray-600">Total Clases</p>
                <p class="text-2xl font-bold text-gray-900" id="totalClasses">0</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <p class="text-sm text-gray-600">Horas/Semana</p>
                <p class="text-2xl font-bold text-brand-primary" id="totalHours">0</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <p class="text-sm text-gray-600">Materias</p>
                <p class="text-2xl font-bold text-blue-600" id="totalSubjects">0</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <p class="text-sm text-gray-600">Grupos</p>
                <p class="text-2xl font-bold text-green-600" id="totalGroups">0</p>
            </div>
        </div>

        <!-- Horario -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[800px]">
                    <thead class="bg-brand-primary text-white">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold">Hora</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold">Lunes</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold">Martes</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold">Miércoles</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold">Jueves</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold">Viernes</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold">Sábado</th>
                        </tr>
                    </thead>
                    <tbody id="scheduleTable" class="divide-y divide-gray-200">
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Leyenda -->
        <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">Leyenda</h3>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-sm">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-blue-100 border border-blue-300 rounded"></div>
                    <span>Teórica</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-green-100 border border-green-300 rounded"></div>
                    <span>Práctica</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-purple-100 border border-purple-300 rounded"></div>
                    <span>Laboratorio</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-orange-100 border border-orange-300 rounded"></div>
                    <span>Virtual</span>
                </div>
            </div>
        </div>
    </main>

    <script>
    const timeSlots = [
        '07:00 - 08:30', '08:30 - 10:00', '10:00 - 11:30', '11:30 - 13:00',
        '14:00 - 15:30', '15:30 - 17:00', '17:00 - 18:30', '18:30 - 20:00'
    ];

    const schedule = {
        'Lunes': {
            '07:00 - 08:30': { subject: 'Programación I', group: 'SC', room: 'Aula 301', type: 'teorica' },
            '10:00 - 11:30': { subject: 'Base de Datos', group: 'SA', room: 'Lab 102', type: 'laboratorio' }
        },
        'Martes': {
            '14:00 - 15:30': { subject: 'Programación I', group: 'SC', room: 'Aula 301', type: 'practica' }
        },
        'Miércoles': {
            '07:00 - 08:30': { subject: 'Programación I', group: 'SC', room: 'Aula 301', type: 'teorica' },
            '10:00 - 11:30': { subject: 'Base de Datos', group: 'SA', room: 'Lab 102', type: 'laboratorio' }
        },
        'Jueves': {
            '14:00 - 15:30': { subject: 'Programación I', group: 'SC', room: 'Virtual', type: 'virtual' }
        },
        'Viernes': {
            '07:00 - 08:30': { subject: 'Programación I', group: 'SC', room: 'Aula 301', type: 'teorica' }
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        displaySchedule();
        calculateStats();
    });

    function displaySchedule() {
        const tbody = document.getElementById('scheduleTable');
        const days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        
        tbody.innerHTML = timeSlots.map(time => {
            return `
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm font-medium text-gray-900 bg-gray-50">${time}</td>
                    ${days.map(day => {
                        const classInfo = schedule[day]?.[time];
                        if (classInfo) {
                            return `
                                <td class="px-2 py-2">
                                    <div class="p-2 rounded-lg border ${getClassColor(classInfo.type)} text-xs">
                                        <div class="font-semibold text-gray-900">${classInfo.subject}</div>
                                        <div class="text-gray-600 mt-1">Grupo ${classInfo.group}</div>
                                        <div class="text-gray-500 mt-1 flex items-center gap-1">
                                            <i class="fas fa-door-open"></i>
                                            ${classInfo.room}
                                        </div>
                                    </div>
                                </td>
                            `;
                        }
                        return '<td class="px-2 py-2 bg-gray-50/50"></td>';
                    }).join('')}
                </tr>
            `;
        }).join('');
    }

    function getClassColor(type) {
        const colors = {
            teorica: 'bg-blue-50 border-blue-200',
            practica: 'bg-green-50 border-green-200',
            laboratorio: 'bg-purple-50 border-purple-200',
            virtual: 'bg-orange-50 border-orange-200'
        };
        return colors[type] || 'bg-gray-50 border-gray-200';
    }

    function calculateStats() {
        let totalClasses = 0;
        let subjects = new Set();
        let groups = new Set();
        
        Object.values(schedule).forEach(day => {
            Object.values(day).forEach(classInfo => {
                totalClasses++;
                subjects.add(classInfo.subject);
                groups.add(classInfo.group);
            });
        });
        
        document.getElementById('totalClasses').textContent = totalClasses;
        document.getElementById('totalHours').textContent = totalClasses * 1.5 + 'h';
        document.getElementById('totalSubjects').textContent = subjects.size;
        document.getElementById('totalGroups').textContent = groups.size;
    }

    function exportPDF() {
        alert('Exportando horario a PDF...');
    }
    </script>
</body>
</html>
