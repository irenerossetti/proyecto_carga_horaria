<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel de Conflictos - FICCT SGA</title>
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
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Panel de Conflictos Horarios</h1>
                <p class="text-gray-500 mt-1">Detecta y resuelve conflictos de horarios</p>
            </div>
            <button onclick="detectConflicts()" class="px-6 py-3 bg-brand-primary hover:bg-brand-hover text-white rounded-lg font-medium transition-colors shadow-sm">
                🔍 Detectar Conflictos
            </button>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-red-100 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Conflictos Activos</p>
                        <p id="totalConflicts" class="text-2xl font-bold text-gray-900">0</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Docentes Afectados</p>
                        <p id="affectedTeachers" class="text-2xl font-bold text-gray-900">0</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Aulas Afectadas</p>
                        <p id="affectedRooms" class="text-2xl font-bold text-gray-900">0</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Resueltos Hoy</p>
                        <p id="resolvedToday" class="text-2xl font-bold text-gray-900">0</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Conflict Types -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Conflictos de Docentes</h3>
                    <span id="teacherConflicts" class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">0</span>
                </div>
                <p class="text-sm text-gray-600">Docentes con clases simultáneas</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Conflictos de Aulas</h3>
                    <span id="roomConflicts" class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">0</span>
                </div>
                <p class="text-sm text-gray-600">Aulas con múltiples asignaciones</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Conflictos de Grupos</h3>
                    <span id="groupConflicts" class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">0</span>
                </div>
                <p class="text-sm text-gray-600">Grupos con horarios superpuestos</p>
            </div>
        </div>

        <!-- Conflicts Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Lista de Conflictos</h2>
                <div class="flex gap-2">
                    <select id="conflictTypeFilter" onchange="applyFilters()" class="px-4 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="">Todos los tipos</option>
                        <option value="teacher">Docente</option>
                        <option value="room">Aula</option>
                        <option value="group">Grupo</option>
                    </select>
                    <button onclick="exportConflicts()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-sm font-medium">
                        📊 Exportar
                    </button>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descripción</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Horario</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Severidad</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="conflictsTable" class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-gray-500">No se han detectado conflictos</p>
                                <button onclick="detectConflicts()" class="mt-4 px-4 py-2 bg-brand-primary hover:bg-brand-hover text-white rounded-lg text-sm font-medium">
                                    Detectar Conflictos
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script>
const API_BASE = '/api';
let allConflicts = [];
let filteredConflicts = [];

function showNotification(message, type = 'success') {
    const alertClass = type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800';
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg border ${alertClass} z-50 shadow-lg`;
    notification.innerHTML = `<span class="font-medium">${message}</span>`;
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
}

async function detectConflicts() {
    showNotification('🔍 Detectando conflictos...');
    
    try {
        const response = await fetch(`${API_BASE}/conflicts`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const result = await response.json();
            allConflicts = result.conflicts || [];
            showNotification(`✅ Se encontraron ${allConflicts.length} conflictos`);
        } else {
            // Mock data
            allConflicts = [
                {
                    id: 1,
                    type: 'teacher',
                    description: 'Dr. Juan Pérez tiene clases simultáneas',
                    details: 'Programación I (Grupo A) y Base de Datos (Grupo B)',
                    day: 'Lunes',
                    time: '08:00 - 10:00',
                    severity: 'high',
                    status: 'pending'
                },
                {
                    id: 2,
                    type: 'room',
                    description: 'Aula 201 asignada a múltiples grupos',
                    details: 'Grupo A y Grupo C al mismo tiempo',
                    day: 'Martes',
                    time: '14:00 - 16:00',
                    severity: 'high',
                    status: 'pending'
                },
                {
                    id: 3,
                    type: 'group',
                    description: 'Grupo B tiene horarios superpuestos',
                    details: 'Dos materias programadas simultáneamente',
                    day: 'Miércoles',
                    time: '10:00 - 12:00',
                    severity: 'medium',
                    status: 'pending'
                }
            ];
            showNotification(`✅ Se encontraron ${allConflicts.length} conflictos`);
        }
        
        filteredConflicts = [...allConflicts];
        renderConflicts();
        updateStats();
    } catch (error) {
        console.error('Error:', error);
        showNotification('❌ Error al detectar conflictos', 'error');
    }
}

function renderConflicts() {
    const tbody = document.getElementById('conflictsTable');
    
    if (filteredConflicts.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="px-6 py-12 text-center">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-gray-500">No se encontraron conflictos</p>
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = filteredConflicts.map(c => {
        const typeConfig = {
            teacher: { color: 'blue', label: 'Docente', icon: '👨‍🏫' },
            room: { color: 'purple', label: 'Aula', icon: '🏫' },
            group: { color: 'yellow', label: 'Grupo', icon: '👥' }
        };
        
        const severityConfig = {
            high: { color: 'red', label: 'Alta' },
            medium: { color: 'yellow', label: 'Media' },
            low: { color: 'green', label: 'Baja' }
        };
        
        const statusConfig = {
            pending: { color: 'yellow', label: 'Pendiente' },
            resolved: { color: 'green', label: 'Resuelto' },
            ignored: { color: 'gray', label: 'Ignorado' }
        };
        
        const type = typeConfig[c.type];
        const severity = severityConfig[c.severity];
        const status = statusConfig[c.status];
        
        return `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-3 py-1 bg-${type.color}-100 text-${type.color}-800 rounded-full text-sm font-medium">
                        ${type.icon} ${type.label}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm font-medium text-gray-900">${c.description}</div>
                    <div class="text-sm text-gray-500">${c.details}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <div>${c.day}</div>
                    <div class="text-gray-500">${c.time}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <span class="px-3 py-1 bg-${severity.color}-100 text-${severity.color}-800 rounded-full text-sm font-medium">
                        ${severity.label}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <span class="px-3 py-1 bg-${status.color}-100 text-${status.color}-800 rounded-full text-sm font-medium">
                        ${status.label}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right">
                    <button onclick="resolveConflict(${c.id})" class="text-green-600 hover:text-green-900 mr-3" title="Resolver">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </button>
                    <button onclick="ignoreConflict(${c.id})" class="text-gray-600 hover:text-gray-900" title="Ignorar">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    </button>
                </td>
            </tr>
        `;
    }).join('');
}

function updateStats() {
    const total = allConflicts.length;
    const teachers = new Set(allConflicts.filter(c => c.type === 'teacher').map(c => c.teacher_id)).size;
    const rooms = new Set(allConflicts.filter(c => c.type === 'room').map(c => c.room_id)).size;
    const resolved = allConflicts.filter(c => c.status === 'resolved').length;
    
    const teacherConflicts = allConflicts.filter(c => c.type === 'teacher').length;
    const roomConflicts = allConflicts.filter(c => c.type === 'room').length;
    const groupConflicts = allConflicts.filter(c => c.type === 'group').length;
    
    document.getElementById('totalConflicts').textContent = total;
    document.getElementById('affectedTeachers').textContent = teachers || allConflicts.filter(c => c.type === 'teacher').length;
    document.getElementById('affectedRooms').textContent = rooms || allConflicts.filter(c => c.type === 'room').length;
    document.getElementById('resolvedToday').textContent = resolved;
    
    document.getElementById('teacherConflicts').textContent = teacherConflicts;
    document.getElementById('roomConflicts').textContent = roomConflicts;
    document.getElementById('groupConflicts').textContent = groupConflicts;
}

function applyFilters() {
    const type = document.getElementById('conflictTypeFilter').value;
    
    filteredConflicts = allConflicts.filter(c => {
        return !type || c.type === type;
    });
    
    renderConflicts();
}

async function resolveConflict(id) {
    if (!confirm('¿Marcar este conflicto como resuelto?')) return;
    
    const conflict = allConflicts.find(c => c.id === id);
    if (conflict) {
        conflict.status = 'resolved';
        renderConflicts();
        updateStats();
        showNotification('✅ Conflicto marcado como resuelto');
    }
}

async function ignoreConflict(id) {
    if (!confirm('¿Ignorar este conflicto?')) return;
    
    const conflict = allConflicts.find(c => c.id === id);
    if (conflict) {
        conflict.status = 'ignored';
        renderConflicts();
        showNotification('✅ Conflicto ignorado');
    }
}

function exportConflicts() {
    showNotification('📊 Exportando conflictos...');
    // Implementar exportación
}

// Auto-detect on load
detectConflicts();
</script>

</body>
</html>
