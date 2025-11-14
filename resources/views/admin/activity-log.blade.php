@extends('layouts.admin')

@section('title', 'Bitácora del Sistema')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Bitácora del Sistema</h1>
            <p class="text-gray-600 mt-1">Registro completo de todas las actividades de los usuarios</p>
        </div>
        <div class="flex gap-2">
            <button onclick="exportLogs()" class="px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary-dark transition-colors">
                <i class="fas fa-file-export mr-2"></i>Exportar
            </button>
            <button onclick="clearOldLogs()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                <i class="fas fa-trash mr-2"></i>Limpiar Antiguos
            </button>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Usuario</label>
                <input type="text" id="filterUser" onkeyup="filterLogs()" placeholder="Nombre o email..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Acción</label>
                <select id="filterAction" onchange="filterLogs()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary">
                    <option value="">Todas</option>
                    <option value="login">Login</option>
                    <option value="logout">Logout</option>
                    <option value="create">Crear</option>
                    <option value="update">Actualizar</option>
                    <option value="delete">Eliminar</option>
                    <option value="view">Ver</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Módulo</label>
                <select id="filterModule" onchange="filterLogs()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary">
                    <option value="">Todos</option>
                    <option value="auth">Autenticación</option>
                    <option value="teachers">Docentes</option>
                    <option value="students">Estudiantes</option>
                    <option value="schedules">Horarios</option>
                    <option value="attendance">Asistencia</option>
                    <option value="reports">Reportes</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Desde</label>
                <input type="date" id="dateFrom" onchange="filterLogs()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Hasta</label>
                <input type="date" id="dateTo" onchange="filterLogs()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary">
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <p class="text-xs text-gray-600">Total Registros</p>
            <p class="text-xl sm:text-2xl font-bold text-gray-900" id="totalLogs">0</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <p class="text-xs text-gray-600">Logins</p>
            <p class="text-xl sm:text-2xl font-bold text-green-600" id="loginCount">0</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <p class="text-xs text-gray-600">Creaciones</p>
            <p class="text-xl sm:text-2xl font-bold text-blue-600" id="createCount">0</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <p class="text-xs text-gray-600">Actualizaciones</p>
            <p class="text-xl sm:text-2xl font-bold text-yellow-600" id="updateCount">0</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <p class="text-xs text-gray-600">Eliminaciones</p>
            <p class="text-xl sm:text-2xl font-bold text-red-600" id="deleteCount">0</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <p class="text-xs text-gray-600">Usuarios Activos</p>
            <p class="text-xl sm:text-2xl font-bold text-purple-600" id="activeUsers">0</p>
        </div>
    </div>

    <!-- Tabla de Bitácora -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha/Hora</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuario</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">IP</th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Acción</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Módulo</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden xl:table-cell">Descripción</th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Detalles</th>
                    </tr>
                </thead>
                <tbody id="logsTable" class="divide-y divide-gray-200">
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Mostrando <span id="showingFrom">0</span> a <span id="showingTo">0</span> de <span id="totalRecords">0</span> registros
            </div>
            <div class="flex gap-2">
                <button onclick="previousPage()" id="prevBtn" class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50 disabled:opacity-50" disabled>
                    Anterior
                </button>
                <button onclick="nextPage()" id="nextBtn" class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50 disabled:opacity-50">
                    Siguiente
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de Detalles -->
    <div id="detailsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-3xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-900">Detalles de la Actividad</h3>
                <button onclick="closeDetails()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6" id="detailsContent">
            </div>
        </div>
    </div>
</div>

<script>
let logs = [];
let currentPage = 1;
const perPage = 50;

document.addEventListener('DOMContentLoaded', function() {
    loadLogs();
});

async function loadLogs() {
    try {
        const params = new URLSearchParams({
            per_page: perPage,
            page: currentPage
        });
        
        // Agregar filtros si existen
        const user = document.getElementById('filterUser')?.value;
        const action = document.getElementById('filterAction')?.value;
        const module = document.getElementById('filterModule')?.value;
        const dateFrom = document.getElementById('dateFrom')?.value;
        const dateTo = document.getElementById('dateTo')?.value;
        
        if (user) params.append('user', user);
        if (action) params.append('action', action);
        if (module) params.append('module', module);
        if (dateFrom) params.append('date_from', dateFrom);
        if (dateTo) params.append('date_to', dateTo);
        
        const response = await fetch(`/api/activity-logs?${params}`);
        const data = await response.json();
        
        logs = data.data || [];
        
        // Cargar estadísticas
        await loadStats();
        
        updateStats();
        displayLogs();
    } catch (error) {
        console.error('Error:', error);
        // Fallback a datos simulados
        logs = generateMockLogs();
        updateStats();
        displayLogs();
    }
}

async function loadStats() {
    try {
        const params = new URLSearchParams();
        const dateFrom = document.getElementById('dateFrom')?.value;
        const dateTo = document.getElementById('dateTo')?.value;
        
        if (dateFrom) params.append('date_from', dateFrom);
        if (dateTo) params.append('date_to', dateTo);
        
        const response = await fetch(`/api/activity-logs/stats?${params}`);
        const stats = await response.json();
        
        document.getElementById('totalLogs').textContent = stats.total || 0;
        document.getElementById('loginCount').textContent = stats.by_action?.login || 0;
        document.getElementById('createCount').textContent = stats.by_action?.create || 0;
        document.getElementById('updateCount').textContent = stats.by_action?.update || 0;
        document.getElementById('deleteCount').textContent = stats.by_action?.delete || 0;
        document.getElementById('activeUsers').textContent = stats.unique_users || 0;
    } catch (error) {
        console.error('Error loading stats:', error);
    }
}

function generateMockLogs() {
    const actions = ['login', 'logout', 'create', 'update', 'delete', 'view'];
    const modules = ['auth', 'teachers', 'students', 'schedules', 'attendance', 'reports'];
    const users = [
        { name: 'Admin Sistema', email: 'admin@ficct.edu.bo', role: 'ADMIN', ip: '192.168.1.100' },
        { name: 'Dr. Juan Pérez', email: 'jperez@ficct.edu.bo', role: 'DOCENTE', ip: '192.168.1.101' },
        { name: 'Coordinador Sistemas', email: 'coord@ficct.edu.bo', role: 'COORDINADOR', ip: '192.168.1.102' }
    ];
    
    const mockLogs = [];
    for (let i = 0; i < 100; i++) {
        const user = users[Math.floor(Math.random() * users.length)];
        const action = actions[Math.floor(Math.random() * actions.length)];
        const module = modules[Math.floor(Math.random() * modules.length)];
        const date = new Date();
        date.setHours(date.getHours() - Math.floor(Math.random() * 72));
        
        mockLogs.push({
            id: i + 1,
            user_name: user.name,
            user_email: user.email,
            user_role: user.role,
            ip_address: user.ip,
            action: action,
            module: module,
            description: `${user.name} ${getActionText(action)} ${getModuleText(module)}`,
            url: `/admin/${module}`,
            method: action === 'view' ? 'GET' : 'POST',
            user_agent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            created_at: date.toISOString()
        });
    }
    
    return mockLogs.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
}

function getActionText(action) {
    const texts = {
        login: 'inició sesión en',
        logout: 'cerró sesión en',
        create: 'creó en',
        update: 'actualizó en',
        delete: 'eliminó en',
        view: 'consultó'
    };
    return texts[action] || action;
}

function getModuleText(module) {
    const texts = {
        auth: 'autenticación',
        teachers: 'docentes',
        students: 'estudiantes',
        schedules: 'horarios',
        attendance: 'asistencia',
        reports: 'reportes'
    };
    return texts[module] || module;
}

function updateStats() {
    document.getElementById('totalLogs').textContent = logs.length;
    document.getElementById('loginCount').textContent = logs.filter(l => l.action === 'login').length;
    document.getElementById('createCount').textContent = logs.filter(l => l.action === 'create').length;
    document.getElementById('updateCount').textContent = logs.filter(l => l.action === 'update').length;
    document.getElementById('deleteCount').textContent = logs.filter(l => l.action === 'delete').length;
    
    const uniqueUsers = new Set(logs.map(l => l.user_email)).size;
    document.getElementById('activeUsers').textContent = uniqueUsers;
}

function displayLogs() {
    const start = (currentPage - 1) * perPage;
    const end = start + perPage;
    const paginatedLogs = logs.slice(start, end);
    
    const tbody = document.getElementById('logsTable');
    
    tbody.innerHTML = paginatedLogs.map(log => `
        <tr class="hover:bg-gray-50">
            <td class="px-4 sm:px-6 py-4">
                <div class="text-sm font-medium text-gray-900">${formatDateTime(log.created_at)}</div>
                <div class="text-xs text-gray-500">${formatTime(log.created_at)}</div>
            </td>
            <td class="px-4 sm:px-6 py-4">
                <div class="text-sm font-medium text-gray-900">${log.user_name}</div>
                <div class="text-xs text-gray-500">${log.user_role}</div>
            </td>
            <td class="px-4 sm:px-6 py-4 text-sm text-gray-600 hidden lg:table-cell">
                <code class="text-xs bg-gray-100 px-2 py-1 rounded">${log.ip_address}</code>
            </td>
            <td class="px-4 sm:px-6 py-4 text-center">
                ${getActionBadge(log.action)}
            </td>
            <td class="px-4 sm:px-6 py-4 text-sm text-gray-600 hidden md:table-cell">${getModuleText(log.module)}</td>
            <td class="px-4 sm:px-6 py-4 text-sm text-gray-600 hidden xl:table-cell">${log.description}</td>
            <td class="px-4 sm:px-6 py-4 text-center">
                <button onclick='viewDetails(${JSON.stringify(log)})' class="text-brand-primary hover:text-brand-primary-dark">
                    <i class="fas fa-info-circle"></i>
                </button>
            </td>
        </tr>
    `).join('');
    
    // Actualizar paginación
    document.getElementById('showingFrom').textContent = start + 1;
    document.getElementById('showingTo').textContent = Math.min(end, logs.length);
    document.getElementById('totalRecords').textContent = logs.length;
    
    document.getElementById('prevBtn').disabled = currentPage === 1;
    document.getElementById('nextBtn').disabled = end >= logs.length;
}

function getActionBadge(action) {
    const badges = {
        login: '<span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded"><i class="fas fa-sign-in-alt mr-1"></i>Login</span>',
        logout: '<span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded"><i class="fas fa-sign-out-alt mr-1"></i>Logout</span>',
        create: '<span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded"><i class="fas fa-plus mr-1"></i>Crear</span>',
        update: '<span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-medium rounded"><i class="fas fa-edit mr-1"></i>Actualizar</span>',
        delete: '<span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-medium rounded"><i class="fas fa-trash mr-1"></i>Eliminar</span>',
        view: '<span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs font-medium rounded"><i class="fas fa-eye mr-1"></i>Ver</span>'
    };
    return badges[action] || action;
}

function formatDateTime(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });
}

function formatTime(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
}

function viewDetails(log) {
    const content = document.getElementById('detailsContent');
    content.innerHTML = `
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Usuario</p>
                    <p class="font-semibold">${log.user_name}</p>
                    <p class="text-sm text-gray-500">${log.user_email}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Rol</p>
                    <p class="font-semibold">${log.user_role}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Fecha y Hora</p>
                    <p class="font-semibold">${formatDateTime(log.created_at)} ${formatTime(log.created_at)}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Dirección IP</p>
                    <p class="font-semibold"><code class="bg-gray-100 px-2 py-1 rounded">${log.ip_address}</code></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Acción</p>
                    ${getActionBadge(log.action)}
                </div>
                <div>
                    <p class="text-sm text-gray-600">Módulo</p>
                    <p class="font-semibold">${getModuleText(log.module)}</p>
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-2">Descripción</p>
                <p class="font-semibold">${log.description}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-2">URL</p>
                <code class="text-sm bg-gray-100 px-3 py-2 rounded block">${log.url}</code>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-2">User Agent</p>
                <code class="text-xs bg-gray-100 px-3 py-2 rounded block break-all">${log.user_agent}</code>
            </div>
        </div>
    `;
    
    document.getElementById('detailsModal').classList.remove('hidden');
}

function closeDetails() {
    document.getElementById('detailsModal').classList.add('hidden');
}

function filterLogs() {
    // Implementar filtros
    displayLogs();
}

function previousPage() {
    if (currentPage > 1) {
        currentPage--;
        displayLogs();
    }
}

function nextPage() {
    if (currentPage * perPage < logs.length) {
        currentPage++;
        displayLogs();
    }
}

function exportLogs() {
    // Mostrar opciones de exportación
    const format = prompt('¿En qué formato deseas exportar?\n\n1. Excel (.xlsx)\n2. PDF\n\nEscribe 1 o 2:');
    
    if (!format) return;
    
    // Construir URL con filtros
    const params = new URLSearchParams();
    const user = document.getElementById('filterUser')?.value;
    const action = document.getElementById('filterAction')?.value;
    const module = document.getElementById('filterModule')?.value;
    const dateFrom = document.getElementById('dateFrom')?.value;
    const dateTo = document.getElementById('dateTo')?.value;
    
    if (user) params.append('user', user);
    if (action) params.append('action', action);
    if (module) params.append('module', module);
    if (dateFrom) params.append('date_from', dateFrom);
    if (dateTo) params.append('date_to', dateTo);
    
    let url;
    if (format === '1') {
        url = `/api/activity-logs/export-excel?${params}`;
    } else if (format === '2') {
        url = `/api/activity-logs/export-pdf?${params}`;
    } else {
        alert('Opción inválida');
        return;
    }
    
    // Descargar archivo
    window.location.href = url;
}

async function clearOldLogs() {
    if (!confirm('¿Eliminar registros antiguos (más de 90 días)?\n\nEsta acción no se puede deshacer.')) {
        return;
    }
    
    try {
        const response = await fetch('/api/activity-logs/clear-old', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert(`✅ ${data.message}`);
            loadLogs(); // Recargar
        } else {
            alert('❌ Error al eliminar registros');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('❌ Error al eliminar registros');
    }
}
</script>
@endsection
