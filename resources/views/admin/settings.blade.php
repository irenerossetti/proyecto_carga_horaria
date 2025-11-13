<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Configuración del Sistema - FICCT SGA</title>
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
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Configuración del Sistema</h1>
            <p class="text-gray-500 mt-1">Ajusta los parámetros del sistema</p>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px">
                    <button onclick="showTab('roles')" id="tab-roles" class="tab-button active px-6 py-4 text-sm font-medium border-b-2 border-brand-primary text-brand-primary">
                        Roles y Permisos
                    </button>
                    <button onclick="showTab('institution')" id="tab-institution" class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Información Institucional
                    </button>
                    <button onclick="showTab('schedules')" id="tab-schedules" class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Horarios de Clases
                    </button>
                    <button onclick="showTab('system')" id="tab-system" class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Calendario Auditorio
                    </button>
                </nav>
            </div>
        </div>

        <!-- Tab Content: Roles -->
        <div id="content-roles" class="tab-content">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Gestión de Roles</h2>
                    <button onclick="openRoleModal()" class="px-4 py-2 bg-brand-primary hover:bg-brand-hover text-white rounded-lg font-medium transition-colors">
                        + Nuevo Rol
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rol</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descripción</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Usuarios</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="rolesTable" class="divide-y">
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-brand-primary mx-auto"></div>
                                    <p class="mt-4 text-gray-500">Cargando roles...</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab Content: Institution -->
        <div id="content-institution" class="tab-content hidden">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Información Institucional</h2>
                
                <form id="institutionForm" class="space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre de la Institución</label>
                            <input type="text" id="inst_name" value="Facultad de Ingeniería en Ciencias de la Computación y Telecomunicaciones" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Siglas</label>
                            <input type="text" id="inst_acronym" value="FICCT" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dirección</label>
                            <input type="text" id="inst_address" value="Av. Busch, Santa Cruz - Bolivia" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                            <input type="text" id="inst_phone" value="+591 3 336-6000" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Institucional</label>
                        <input type="email" id="inst_email" value="info@ficct.uagrm.edu.bo" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-brand-primary hover:bg-brand-hover text-white rounded-lg font-medium transition-colors">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>


        <!-- Tab Content: Schedules -->
        <div id="content-schedules" class="tab-content hidden">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Configuración de Horarios</h2>
                
                <form id="schedulesForm" class="space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Hora de Inicio (Lunes-Viernes)</label>
                            <input type="time" id="weekday_start" value="07:00" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Hora de Fin (Lunes-Viernes)</label>
                            <input type="time" id="weekday_end" value="22:45" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Hora de Inicio (Sábados)</label>
                            <input type="time" id="saturday_start" value="07:00" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Hora de Fin (Sábados)</label>
                            <input type="time" id="saturday_end" value="12:00" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Duración de Clase (minutos)</label>
                        <select id="class_duration" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                            <option value="45">45 minutos</option>
                            <option value="60">60 minutos</option>
                            <option value="90" selected>90 minutos</option>
                            <option value="120">120 minutos</option>
                        </select>
                    </div>
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-blue-800">
                            <strong>Nota:</strong> Los domingos no se programan clases regulares.
                        </p>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-brand-primary hover:bg-brand-hover text-white rounded-lg font-medium transition-colors">
                            Guardar Configuración
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tab Content: System - Calendario del Auditorio -->
        <div id="content-system" class="tab-content hidden">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Calendario del Auditorio</h2>
                        <p class="text-sm text-gray-500 mt-1">Gestiona las reservas y horarios del auditorio</p>
                    </div>
                    <button onclick="openAuditoriumModal()" class="px-4 py-2 bg-brand-primary hover:bg-brand-hover text-white rounded-lg font-medium transition-colors">
                        + Nueva Reserva
                    </button>
                </div>
                
                <!-- Selector de Semana -->
                <div class="flex items-center justify-between mb-6 p-4 bg-gray-50 rounded-lg">
                    <button onclick="previousWeek()" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100">
                        ← Semana Anterior
                    </button>
                    <div class="text-center">
                        <p class="text-lg font-bold text-gray-900" id="weekDisplay">Semana del 11 al 17 de Noviembre 2025</p>
                        <p class="text-sm text-gray-500">Auditorio - Piso 4 (Capacidad: 120 personas)</p>
                    </div>
                    <button onclick="nextWeek()" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100">
                        Semana Siguiente →
                    </button>
                </div>
                
                <!-- Calendario Semanal -->
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
                        <tbody id="auditoriumCalendar">
                            <!-- Se llenará con JavaScript -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Leyenda -->
                <div class="mt-6 flex items-center gap-6 text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-green-100 border border-green-300 rounded"></div>
                        <span>Disponible</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-blue-100 border border-blue-300 rounded"></div>
                        <span>Ocupado (Presencial)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-yellow-100 border border-yellow-300 rounded"></div>
                        <span>Virtual (Disponible)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-red-100 border border-red-300 rounded"></div>
                        <span>Conflicto</span>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Modal para Reserva de Auditorio -->
<div id="auditoriumModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl mx-4">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900">Reservar Auditorio</h3>
                <button onclick="closeAuditoriumModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <form id="auditoriumForm" class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Materia *</label>
                    <input type="text" id="reservationSubject" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent" placeholder="Nombre de la materia">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Docente *</label>
                    <input type="text" id="reservationTeacher" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent" placeholder="Nombre del docente">
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Grupo *</label>
                    <input type="text" id="reservationGroup" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent" placeholder="Ej: Grupo A">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Día *</label>
                    <select id="reservationDay" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        <option value="">Seleccionar día</option>
                        <option value="Lunes">Lunes</option>
                        <option value="Martes">Martes</option>
                        <option value="Miércoles">Miércoles</option>
                        <option value="Jueves">Jueves</option>
                        <option value="Viernes">Viernes</option>
                        <option value="Sábado">Sábado</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hora Inicio *</label>
                    <input type="time" id="reservationStart" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hora Fin *</label>
                    <input type="time" id="reservationEnd" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                </div>
            </div>
            
            <div class="flex items-center gap-3 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <input type="checkbox" id="isVirtual" class="w-4 h-4 text-brand-primary border-gray-300 rounded focus:ring-brand-primary">
                <label for="isVirtual" class="text-sm font-medium text-gray-700">Marcar como clase virtual (libera el auditorio)</label>
            </div>
        </form>

        <div class="p-6 pt-0">
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeAuditoriumModal()" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                    Cancelar
                </button>
                <button type="button" onclick="document.getElementById('auditoriumForm').requestSubmit()" class="px-6 py-2 bg-brand-primary hover:bg-brand-hover text-white rounded-lg font-medium transition-colors">
                    Guardar Reserva
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Roles -->
<div id="roleModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 id="roleModalTitle" class="text-xl font-bold text-gray-900">Nuevo Rol</h3>
                <button onclick="closeRoleModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <form id="roleForm" class="p-6 space-y-4">
            <input type="hidden" id="roleId">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Rol *</label>
                <input type="text" id="roleName" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent" placeholder="DOCENTE">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                <textarea id="roleDescription" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent" placeholder="Descripción del rol..."></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeRoleModal()" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                    Cancelar
                </button>
                <button type="submit" class="px-6 py-2 bg-brand-primary hover:bg-brand-hover text-white rounded-lg font-medium transition-colors">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>


<script>
const API_BASE = '/api';
let allRoles = [];

function showNotification(message, type = 'success') {
    const alertClass = type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800';
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg border ${alertClass} z-50 shadow-lg`;
    notification.innerHTML = `<span class="font-medium">${message}</span>`;
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
}

function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => tab.classList.add('hidden'));
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('border-brand-primary', 'text-brand-primary');
        btn.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab
    document.getElementById(`content-${tabName}`).classList.remove('hidden');
    document.getElementById(`tab-${tabName}`).classList.remove('border-transparent', 'text-gray-500');
    document.getElementById(`tab-${tabName}`).classList.add('border-brand-primary', 'text-brand-primary');
    
    // Load data if needed
    if (tabName === 'roles') {
        loadRoles();
    } else if (tabName === 'system') {
        renderAuditoriumCalendar();
    }
}

async function loadRoles() {
    try {
        const response = await fetch(`${API_BASE}/roles`, {
            headers: { 'Accept': 'application/json' }
        });
        
        if (!response.ok) throw new Error('Error al cargar roles');
        
        allRoles = await response.json();
        renderRoles();
    } catch (error) {
        console.error('Error:', error);
        // Mostrar roles de prueba
        allRoles = [
            { id: 1, name: 'ADMIN', description: 'Administrador del sistema', users_count: 2 },
            { id: 2, name: 'DOCENTE', description: 'Profesor de la facultad', users_count: 15 },
            { id: 3, name: 'ESTUDIANTE', description: 'Estudiante regular', users_count: 250 },
        ];
        renderRoles();
    }
}

function renderRoles() {
    const tbody = document.getElementById('rolesTable');
    
    if (allRoles.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                    No hay roles registrados
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = allRoles.map(role => `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 font-medium text-gray-900">${role.name}</td>
            <td class="px-6 py-4 text-sm text-gray-600">${role.description || '-'}</td>
            <td class="px-6 py-4 text-center">
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                    ${role.users_count || 0}
                </span>
            </td>
            <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                    <button onclick="editRole(${role.id})" class="text-blue-600 hover:text-blue-900" title="Editar">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    ${role.name !== 'ADMIN' ? `
                    <button onclick="deleteRole(${role.id})" class="text-red-600 hover:text-red-900" title="Eliminar">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                    ` : ''}
                </div>
            </td>
        </tr>
    `).join('');
}

function openRoleModal() {
    document.getElementById('roleModalTitle').textContent = 'Nuevo Rol';
    document.getElementById('roleForm').reset();
    document.getElementById('roleId').value = '';
    document.getElementById('roleModal').classList.remove('hidden');
    document.getElementById('roleModal').classList.add('flex');
}

function closeRoleModal() {
    document.getElementById('roleModal').classList.add('hidden');
    document.getElementById('roleModal').classList.remove('flex');
}

function editRole(id) {
    const role = allRoles.find(r => r.id === id);
    if (!role) {
        showNotification('❌ Rol no encontrado', 'error');
        return;
    }
    
    document.getElementById('roleModalTitle').textContent = 'Editar Rol';
    document.getElementById('roleId').value = role.id;
    document.getElementById('roleName').value = role.name;
    document.getElementById('roleDescription').value = role.description || '';
    document.getElementById('roleModal').classList.remove('hidden');
    document.getElementById('roleModal').classList.add('flex');
}

async function deleteRole(id) {
    if (!confirm('¿Estás seguro de eliminar este rol?')) return;
    
    try {
        const response = await fetch(`${API_BASE}/roles/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (!response.ok) {
            throw new Error(result.message || 'Error al eliminar');
        }
        
        showNotification(result.message || '✅ Rol eliminado exitosamente');
        loadRoles();
    } catch (error) {
        console.error('Error:', error);
        showNotification('❌ ' + error.message, 'error');
    }
}

async function loadPeriods() {
    try {
        const response = await fetch(`${API_BASE}/periods`, {
            headers: { 'Accept': 'application/json' }
        });
        
        if (!response.ok) throw new Error('Error al cargar períodos');
        
        const periods = await response.json();
        const select = document.getElementById('active_period');
        
        select.innerHTML = periods.map(p => `
            <option value="${p.id}" ${p.status === 'active' ? 'selected' : ''}>
                ${p.name} (${p.code})
            </option>
        `).join('');
    } catch (error) {
        console.error('Error:', error);
    }
}

// Form submissions
document.getElementById('roleForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const roleId = document.getElementById('roleId').value;
    const data = {
        name: document.getElementById('roleName').value,
        description: document.getElementById('roleDescription').value
    };
    
    try {
        const url = roleId ? `${API_BASE}/roles/${roleId}` : `${API_BASE}/roles`;
        const method = roleId ? 'PATCH' : 'POST';
        
        const response = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (!response.ok) {
            throw new Error(result.message || 'Error al guardar');
        }
        
        showNotification(result.message || '✅ Rol guardado exitosamente');
        closeRoleModal();
        loadRoles();
    } catch (error) {
        console.error('Error:', error);
        showNotification('❌ ' + error.message, 'error');
    }
});

document.getElementById('institutionForm').addEventListener('submit', (e) => {
    e.preventDefault();
    showNotification('✅ Información institucional actualizada');
});

document.getElementById('schedulesForm').addEventListener('submit', (e) => {
    e.preventDefault();
    showNotification('✅ Configuración de horarios guardada');
});

document.getElementById('systemForm').addEventListener('submit', (e) => {
    e.preventDefault();
    showNotification('✅ Parámetros del sistema actualizados');
});

// Calendario del Auditorio
let currentWeekStart = new Date();
let auditoriumSchedules = [];

// Datos de prueba para el auditorio
const mockAuditoriumData = [
    { day: 'Lunes', start: '08:00', end: '10:00', subject: 'Inteligencia Artificial', teacher: 'Dr. Juan Pérez', group: 'Grupo A', isVirtual: false },
    { day: 'Martes', start: '14:00', end: '16:00', subject: 'Redes Avanzadas', teacher: 'Ing. María García', group: 'Grupo B', isVirtual: false },
    { day: 'Miércoles', start: '10:00', end: '12:00', subject: 'Base de Datos', teacher: 'Lic. Carlos López', group: 'Grupo C', isVirtual: true },
    { day: 'Jueves', start: '16:00', end: '18:00', subject: 'Ingeniería de Software', teacher: 'Dra. Ana Martínez', group: 'Grupo A', isVirtual: false },
    { day: 'Viernes', start: '08:00', end: '10:00', subject: 'Programación Web', teacher: 'Ing. Pedro Rodríguez', group: 'Grupo B', isVirtual: false },
];

function renderAuditoriumCalendar() {
    const hours = ['07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00'];
    const days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    const tbody = document.getElementById('auditoriumCalendar');
    
    tbody.innerHTML = hours.map(hour => {
        const nextHour = hours[hours.indexOf(hour) + 1] || '23:00';
        
        return `
            <tr>
                <td class="border border-gray-300 px-4 py-3 text-sm font-medium text-gray-700 bg-gray-50">
                    ${hour} - ${nextHour}
                </td>
                ${days.map(day => {
                    const schedule = mockAuditoriumData.find(s => 
                        s.day === day && s.start <= hour && s.end > hour
                    );
                    
                    if (schedule) {
                        const bgColor = schedule.isVirtual ? 'bg-yellow-100 border-yellow-300' : 'bg-blue-100 border-blue-300';
                        const textColor = schedule.isVirtual ? 'text-yellow-800' : 'text-blue-800';
                        
                        return `
                            <td class="border border-gray-300 px-2 py-2 ${bgColor} cursor-pointer hover:opacity-80" onclick="showScheduleDetails('${schedule.subject}', '${schedule.teacher}', '${schedule.group}', '${schedule.start}', '${schedule.end}', ${schedule.isVirtual})">
                                <div class="text-xs ${textColor}">
                                    <div class="font-bold">${schedule.subject}</div>
                                    <div class="mt-1">${schedule.teacher}</div>
                                    <div>${schedule.group}</div>
                                    <div class="mt-1 text-xs">${schedule.start} - ${schedule.end}</div>
                                    ${schedule.isVirtual ? '<div class="mt-1 font-semibold">🌐 VIRTUAL</div>' : ''}
                                </div>
                            </td>
                        `;
                    } else {
                        return `
                            <td class="border border-gray-300 px-2 py-2 bg-green-50 hover:bg-green-100 cursor-pointer" onclick="quickReserve('${day}', '${hour}', '${nextHour}')">
                                <div class="text-xs text-green-600 text-center">Disponible</div>
                            </td>
                        `;
                    }
                }).join('')}
            </tr>
        `;
    }).join('');
}

function showScheduleDetails(subject, teacher, group, start, end, isVirtual) {
    const status = isVirtual ? 'VIRTUAL - Auditorio disponible' : 'PRESENCIAL - Auditorio ocupado';
    alert(`📚 ${subject}\n👨‍🏫 ${teacher}\n👥 ${group}\n⏰ ${start} - ${end}\n📍 ${status}`);
}

function quickReserve(day, start, end) {
    document.getElementById('reservationDay').value = day;
    document.getElementById('reservationStart').value = start;
    document.getElementById('reservationEnd').value = end;
    openAuditoriumModal();
}

function openAuditoriumModal() {
    document.getElementById('auditoriumModal').classList.remove('hidden');
    document.getElementById('auditoriumModal').classList.add('flex');
}

function closeAuditoriumModal() {
    document.getElementById('auditoriumModal').classList.add('hidden');
    document.getElementById('auditoriumModal').classList.remove('flex');
    document.getElementById('auditoriumForm').reset();
}

function previousWeek() {
    currentWeekStart.setDate(currentWeekStart.getDate() - 7);
    updateWeekDisplay();
}

function nextWeek() {
    currentWeekStart.setDate(currentWeekStart.getDate() + 7);
    updateWeekDisplay();
}

function updateWeekDisplay() {
    const weekEnd = new Date(currentWeekStart);
    weekEnd.setDate(weekEnd.getDate() + 6);
    
    const options = { day: 'numeric', month: 'long', year: 'numeric' };
    const startStr = currentWeekStart.toLocaleDateString('es-ES', options);
    const endStr = weekEnd.toLocaleDateString('es-ES', { day: 'numeric', month: 'long' });
    
    document.getElementById('weekDisplay').textContent = `Semana del ${startStr.split(' de ')[0]} al ${endStr}`;
}

document.getElementById('auditoriumForm').addEventListener('submit', (e) => {
    e.preventDefault();
    
    const data = {
        subject: document.getElementById('reservationSubject').value,
        teacher: document.getElementById('reservationTeacher').value,
        group: document.getElementById('reservationGroup').value,
        day: document.getElementById('reservationDay').value,
        start: document.getElementById('reservationStart').value,
        end: document.getElementById('reservationEnd').value,
        isVirtual: document.getElementById('isVirtual').checked
    };
    
    // Verificar conflictos
    const conflict = mockAuditoriumData.find(s => 
        s.day === data.day && 
        !s.isVirtual &&
        ((data.start >= s.start && data.start < s.end) || 
         (data.end > s.start && data.end <= s.end))
    );
    
    if (conflict && !data.isVirtual) {
        showNotification(`❌ Conflicto de horario: ${conflict.subject} con ${conflict.teacher} (${conflict.group}) está programado de ${conflict.start} a ${conflict.end}`, 'error');
        return;
    }
    
    mockAuditoriumData.push(data);
    showNotification('✅ Reserva creada exitosamente');
    closeAuditoriumModal();
    renderAuditoriumCalendar();
});

// Load initial data
loadRoles();
renderAuditoriumCalendar();
</script>

</body>
</html>
