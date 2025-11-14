<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Horario Semanal - FICCT SGA</title>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
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

<body class="bg-gray-50">
<div class="flex min-h-screen">
    @include('layouts.admin-sidebar')

    <main class="flex-1 ml-64 p-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Horario Semanal</h1>
                <p class="text-gray-500 mt-1">Visualiza los horarios de clases por docente, grupo o aula</p>
            </div>
            <div class="flex gap-3">
                <button onclick="exportSchedule('pdf')" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                    📄 Exportar PDF
                </button>
                <button onclick="exportSchedule('excel')" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors">
                    📊 Exportar Excel
                </button>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Vista</label>
                    <select id="viewType" onchange="changeViewType()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        <option value="teacher">Por Docente</option>
                        <option value="group">Por Grupo</option>
                        <option value="room">Por Aula</option>
                        <option value="general">Vista General</option>
                    </select>
                </div>
                
                <div id="teacherFilter">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Docente</label>
                    <select id="teacherSelect" onchange="loadSchedule()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        <option value="">Seleccionar docente</option>
                    </select>
                </div>
                
                <div id="groupFilter" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Grupo</label>
                    <select id="groupSelect" onchange="loadSchedule()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        <option value="">Seleccionar grupo</option>
                    </select>
                </div>
                
                <div id="roomFilter" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Aula</label>
                    <select id="roomSelect" onchange="loadSchedule()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        <option value="">Seleccionar aula</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Periodo Académico</label>
                    <select id="periodSelect" onchange="loadSchedule()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        <option value="">Periodo actual</option>
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button onclick="printSchedule()" class="w-full px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                        🖨️ Imprimir
                    </button>
                </div>
            </div>
        </div>

        <!-- Schedule Info Card -->
        <div id="scheduleInfo" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6 hidden">
            <div class="flex items-center justify-between">
                <div>
                    <h3 id="scheduleTitle" class="text-lg font-semibold text-gray-900"></h3>
                    <p id="scheduleSubtitle" class="text-sm text-gray-500 mt-1"></p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500">Total de Horas</div>
                    <div id="totalHours" class="text-2xl font-bold text-brand-primary">0</div>
                </div>
            </div>
        </div>

        <!-- Weekly Schedule Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Horario de la Semana</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700 w-32">Hora</th>
                            <th class="border border-gray-300 px-4 py-3 text-center text-sm font-medium text-gray-700">Lunes</th>
                            <th class="border border-gray-300 px-4 py-3 text-center text-sm font-medium text-gray-700">Martes</th>
                            <th class="border border-gray-300 px-4 py-3 text-center text-sm font-medium text-gray-700">Miércoles</th>
                            <th class="border border-gray-300 px-4 py-3 text-center text-sm font-medium text-gray-700">Jueves</th>
                            <th class="border border-gray-300 px-4 py-3 text-center text-sm font-medium text-gray-700">Viernes</th>
                            <th class="border border-gray-300 px-4 py-3 text-center text-sm font-medium text-gray-700">Sábado</th>
                        </tr>
                    </thead>
                    <tbody id="scheduleTable">
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-lg font-medium">Selecciona un filtro para ver el horario</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Legend -->
        <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Leyenda</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-blue-100 border border-blue-300 rounded"></div>
                    <span class="text-sm text-gray-700">Clase Teórica</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-green-100 border border-green-300 rounded"></div>
                    <span class="text-sm text-gray-700">Clase Práctica</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-yellow-100 border border-yellow-300 rounded"></div>
                    <span class="text-sm text-gray-700">Clase Virtual</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-red-100 border border-red-300 rounded"></div>
                    <span class="text-sm text-gray-700">Conflicto</span>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
const API_BASE = '/api';
let teachers = [];
let groups = [];
let rooms = [];
let periods = [];
let currentSchedule = [];

const timeSlots = [
    '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', 
    '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', 
    '19:00', '20:00', '21:00', '22:00'
];

const days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

function showNotification(message, type = 'success') {
    const alertClass = type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800';
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg border ${alertClass} z-50 shadow-lg`;
    notification.innerHTML = `<span class="font-medium">${message}</span>`;
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
}

async function loadTeachers() {
    try {
        const response = await fetch(`${API_BASE}/teachers`, {
            headers: { 'Accept': 'application/json' }
        });
        
        if (response.ok) {
            teachers = await response.json();
        } else {
            teachers = [
                { id: 1, name: 'Dr. Juan Pérez García' },
                { id: 2, name: 'Ing. María López Silva' },
                { id: 3, name: 'Lic. Carlos Rodríguez' }
            ];
        }
        
        const select = document.getElementById('teacherSelect');
        select.innerHTML = '<option value="">Seleccionar docente</option>' + 
            teachers.map(t => `<option value="${t.id}">${t.name}</option>`).join('');
    } catch (error) {
        console.error('Error loading teachers:', error);
    }
}

async function loadGroups() {
    try {
        const response = await fetch(`${API_BASE}/groups`, {
            headers: { 'Accept': 'application/json' }
        });
        
        if (response.ok) {
            groups = await response.json();
        } else {
            groups = [
                { id: 1, name: 'Grupo A', subject_name: 'Programación I' },
                { id: 2, name: 'Grupo B', subject_name: 'Programación I' },
                { id: 3, name: 'Grupo A', subject_name: 'Cálculo I' }
            ];
        }
        
        const select = document.getElementById('groupSelect');
        select.innerHTML = '<option value="">Seleccionar grupo</option>' + 
            groups.map(g => `<option value="${g.id}">${g.subject_name} - ${g.name}</option>`).join('');
    } catch (error) {
        console.error('Error loading groups:', error);
    }
}

async function loadRooms() {
    try {
        const response = await fetch(`${API_BASE}/rooms`, {
            headers: { 'Accept': 'application/json' }
        });
        
        if (response.ok) {
            rooms = await response.json();
        } else {
            rooms = [
                { id: 1, name: 'Aula 101', floor: 1 },
                { id: 2, name: 'Aula 102', floor: 1 },
                { id: 3, name: 'Aula 201', floor: 2 }
            ];
        }
        
        const select = document.getElementById('roomSelect');
        select.innerHTML = '<option value="">Seleccionar aula</option>' + 
            rooms.map(r => `<option value="${r.id}">${r.name} (Piso ${r.floor})</option>`).join('');
    } catch (error) {
        console.error('Error loading rooms:', error);
    }
}

async function loadPeriods() {
    try {
        const response = await fetch(`${API_BASE}/periods`, {
            headers: { 'Accept': 'application/json' }
        });
        
        if (response.ok) {
            periods = await response.json();
        } else {
            periods = [
                { id: 1, name: 'Gestión 2-2025', status: 'active' }
            ];
        }
        
        const select = document.getElementById('periodSelect');
        select.innerHTML = '<option value="">Periodo actual</option>' + 
            periods.map(p => `<option value="${p.id}" ${p.status === 'active' ? 'selected' : ''}>${p.name}</option>`).join('');
    } catch (error) {
        console.error('Error loading periods:', error);
    }
}

function changeViewType() {
    const viewType = document.getElementById('viewType').value;
    
    // Hide all filters
    document.getElementById('teacherFilter').classList.add('hidden');
    document.getElementById('groupFilter').classList.add('hidden');
    document.getElementById('roomFilter').classList.add('hidden');
    
    // Show selected filter
    if (viewType === 'teacher') {
        document.getElementById('teacherFilter').classList.remove('hidden');
    } else if (viewType === 'group') {
        document.getElementById('groupFilter').classList.remove('hidden');
    } else if (viewType === 'room') {
        document.getElementById('roomFilter').classList.remove('hidden');
    }
    
    // Clear schedule
    if (viewType !== 'general') {
        renderEmptySchedule();
    } else {
        loadGeneralSchedule();
    }
}

async function loadSchedule() {
    const viewType = document.getElementById('viewType').value;
    let entityId = null;
    let entityName = '';
    
    if (viewType === 'teacher') {
        entityId = document.getElementById('teacherSelect').value;
        const teacher = teachers.find(t => t.id == entityId);
        entityName = teacher ? teacher.name : '';
    } else if (viewType === 'group') {
        entityId = document.getElementById('groupSelect').value;
        const group = groups.find(g => g.id == entityId);
        entityName = group ? `${group.subject_name} - ${group.name}` : '';
    } else if (viewType === 'room') {
        entityId = document.getElementById('roomSelect').value;
        const room = rooms.find(r => r.id == entityId);
        entityName = room ? room.name : '';
    }
    
    if (!entityId && viewType !== 'general') {
        renderEmptySchedule();
        return;
    }
    
    try {
        // Simular datos de horario
        currentSchedule = generateMockSchedule(viewType, entityId);
        renderSchedule(currentSchedule, entityName, viewType);
    } catch (error) {
        console.error('Error loading schedule:', error);
        showNotification('❌ Error al cargar el horario', 'error');
    }
}

function generateMockSchedule(viewType, entityId) {
    // Generar horario de ejemplo
    return [
        { day: 'Lunes', start_time: '08:00', end_time: '10:00', subject: 'Programación I', group: 'Grupo A', room: 'Aula 101', type: 'theoretical', is_virtual: false },
        { day: 'Lunes', start_time: '14:00', end_time: '16:00', subject: 'Base de Datos', group: 'Grupo B', room: 'Aula 201', type: 'practical', is_virtual: false },
        { day: 'Martes', start_time: '10:00', end_time: '12:00', subject: 'Programación I', group: 'Grupo A', room: 'Aula 101', type: 'practical', is_virtual: false },
        { day: 'Miércoles', start_time: '08:00', end_time: '10:00', subject: 'Programación I', group: 'Grupo A', room: 'Aula 101', type: 'theoretical', is_virtual: false },
        { day: 'Miércoles', start_time: '16:00', end_time: '18:00', subject: 'Redes', group: 'Grupo C', room: 'Virtual', type: 'theoretical', is_virtual: true },
        { day: 'Jueves', start_time: '14:00', end_time: '16:00', subject: 'Base de Datos', group: 'Grupo B', room: 'Aula 201', type: 'theoretical', is_virtual: false },
        { day: 'Viernes', start_time: '08:00', end_time: '10:00', subject: 'Programación I', group: 'Grupo A', room: 'Aula 101', type: 'theoretical', is_virtual: false },
    ];
}

function renderSchedule(schedule, entityName, viewType) {
    // Show info card
    document.getElementById('scheduleInfo').classList.remove('hidden');
    document.getElementById('scheduleTitle').textContent = entityName;
    
    const viewTypeNames = {
        teacher: 'Horario del Docente',
        group: 'Horario del Grupo',
        room: 'Ocupación del Aula',
        general: 'Vista General'
    };
    document.getElementById('scheduleSubtitle').textContent = viewTypeNames[viewType];
    
    // Calculate total hours
    const totalHours = schedule.reduce((sum, item) => {
        const start = parseInt(item.start_time.split(':')[0]);
        const end = parseInt(item.end_time.split(':')[0]);
        return sum + (end - start);
    }, 0);
    document.getElementById('totalHours').textContent = totalHours + 'h';
    
    // Render table
    const tbody = document.getElementById('scheduleTable');
    tbody.innerHTML = timeSlots.map((time, index) => {
        const nextTime = timeSlots[index + 1] || '23:00';
        
        return `
            <tr>
                <td class="border border-gray-300 px-4 py-3 text-sm font-medium text-gray-700 bg-gray-50">
                    ${time} - ${nextTime}
                </td>
                ${days.map(day => {
                    const classItem = schedule.find(s => 
                        s.day === day && s.start_time <= time && s.end_time > time
                    );
                    
                    if (classItem) {
                        const bgColor = classItem.is_virtual ? 'bg-yellow-100 border-yellow-300' : 
                                      classItem.type === 'practical' ? 'bg-green-100 border-green-300' : 
                                      'bg-blue-100 border-blue-300';
                        const textColor = classItem.is_virtual ? 'text-yellow-800' : 
                                        classItem.type === 'practical' ? 'text-green-800' : 
                                        'text-blue-800';
                        
                        return `
                            <td class="border border-gray-300 px-2 py-2 ${bgColor} cursor-pointer hover:opacity-80" onclick="showClassDetails('${classItem.subject}', '${classItem.group}', '${classItem.room}', '${classItem.start_time}', '${classItem.end_time}', ${classItem.is_virtual})">
                                <div class="text-xs ${textColor}">
                                    <div class="font-bold">${classItem.subject}</div>
                                    <div class="mt-1">${classItem.group}</div>
                                    <div class="text-xs opacity-75">${classItem.room}</div>
                                    <div class="mt-1 text-xs">${classItem.start_time} - ${classItem.end_time}</div>
                                    ${classItem.is_virtual ? '<div class="mt-1 font-semibold">🌐 VIRTUAL</div>' : ''}
                                </div>
                            </td>
                        `;
                    } else {
                        return `
                            <td class="border border-gray-300 px-2 py-2 bg-white hover:bg-gray-50">
                                <div class="text-xs text-gray-400 text-center">-</div>
                            </td>
                        `;
                    }
                }).join('')}
            </tr>
        `;
    }).join('');
}

function renderEmptySchedule() {
    document.getElementById('scheduleInfo').classList.add('hidden');
    const tbody = document.getElementById('scheduleTable');
    tbody.innerHTML = `
        <tr>
            <td colspan="7" class="px-6 py-12 text-center">
                <div class="text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-lg font-medium">Selecciona un filtro para ver el horario</p>
                </div>
            </td>
        </tr>
    `;
}

function showClassDetails(subject, group, room, start, end, isVirtual) {
    const status = isVirtual ? 'VIRTUAL' : 'PRESENCIAL';
    alert(`📚 ${subject}\n👥 ${group}\n🏫 ${room}\n⏰ ${start} - ${end}\n📍 ${status}`);
}

async function loadGeneralSchedule() {
    // Cargar vista general con todos los horarios
    showNotification('⏳ Cargando vista general...');
    // Implementar lógica para vista general
}

function exportSchedule(format) {
    const viewType = document.getElementById('viewType').value;
    
    if (currentSchedule.length === 0 && viewType !== 'general') {
        showNotification('❌ No hay horario para exportar', 'error');
        return;
    }
    
    if (format === 'pdf') {
        showNotification('📄 Generando PDF...');
        // Implementar exportación PDF
        window.open(`${API_BASE}/schedules/export.pdf?type=${viewType}`, '_blank');
    } else if (format === 'excel') {
        showNotification('📊 Generando Excel...');
        // Implementar exportación Excel
        window.open(`${API_BASE}/schedules/export?type=${viewType}`, '_blank');
    }
}

function printSchedule() {
    if (currentSchedule.length === 0) {
        showNotification('❌ No hay horario para imprimir', 'error');
        return;
    }
    window.print();
}

// Load initial data when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('Cargando horario semanal...');
    Promise.all([loadTeachers(), loadGroups(), loadRooms(), loadPeriods()])
        .then(() => {
            console.log('Datos cargados correctamente');
        })
        .catch(error => {
            console.error('Error al cargar datos:', error);
        });
});
</script>

</body>
</html>
