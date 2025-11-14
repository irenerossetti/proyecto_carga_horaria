@extends('layouts.coordinator')

@section('title', 'Validar Carga Horaria')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Validar Carga Horaria</h1>
            <p class="text-gray-600 mt-1">Revisa y aprueba las asignaciones de carga horaria de los docentes</p>
        </div>
        <div class="flex gap-2">
            <button onclick="approveAll()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-check-double mr-2"></i>Aprobar Todas
            </button>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                <select id="filterStatus" onchange="filterAssignments()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary">
                    <option value="">Todos</option>
                    <option value="pending" selected>Pendientes</option>
                    <option value="approved">Aprobadas</option>
                    <option value="rejected">Rechazadas</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Carrera</label>
                <select id="filterCareer" onchange="filterAssignments()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary">
                    <option value="">Todas las carreras</option>
                    <option value="sistemas">Ing. Sistemas</option>
                    <option value="informatica">Ing. Informática</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Periodo</label>
                <select id="filterPeriod" onchange="filterAssignments()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary">
                    <option value="">Seleccionar periodo...</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                <input type="text" id="searchTeacher" onkeyup="filterAssignments()" placeholder="Nombre del docente..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary">
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Pendientes</p>
                    <p class="text-2xl sm:text-3xl font-bold text-yellow-600" id="pendingCount">0</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Aprobadas</p>
                    <p class="text-2xl sm:text-3xl font-bold text-green-600" id="approvedCount">0</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Rechazadas</p>
                    <p class="text-2xl sm:text-3xl font-bold text-red-600" id="rejectedCount">0</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Horas</p>
                    <p class="text-2xl sm:text-3xl font-bold text-brand-primary" id="totalHours">0</p>
                </div>
                <div class="w-12 h-12 bg-brand-primary/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-brand-primary text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Asignaciones -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Docente</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Materia</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">Grupo</th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Horas/Sem</th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Carrera</th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody id="assignmentsTable" class="divide-y divide-gray-200">
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal de Detalles -->
    <div id="detailsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-900">Detalles de Asignación</h3>
                <button onclick="closeDetails()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6" id="detailsContent">
            </div>
        </div>
    </div>

    <!-- Modal de Rechazo -->
    <div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-md w-full">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900">Rechazar Asignación</h3>
            </div>
            <div class="p-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Motivo del rechazo *</label>
                <textarea id="rejectReason" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary" placeholder="Explica por qué rechazas esta asignación..."></textarea>
                <div class="flex gap-3 mt-4">
                    <button onclick="confirmReject()" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Rechazar
                    </button>
                    <button onclick="closeReject()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let assignments = [];
let currentAssignmentId = null;

document.addEventListener('DOMContentLoaded', function() {
    loadPeriods();
    loadAssignments();
});

async function loadPeriods() {
    try {
        const response = await fetch('/api/periods');
        const data = await response.json();
        const periods = data.data || [];
        
        const select = document.getElementById('filterPeriod');
        select.innerHTML = '<option value="">Seleccionar periodo...</option>' +
            periods.map(p => `<option value="${p.id}" ${p.is_active ? 'selected' : ''}>${p.name}</option>`).join('');
    } catch (error) {
        console.error('Error:', error);
    }
}

async function loadAssignments() {
    // Datos simulados - reemplazar con API real
    assignments = [
        { id: 1, teacher: 'Dr. Juan Pérez', subject: 'Programación I', group: 'SC', hours: 6, career: 'Ing. Sistemas', status: 'pending' },
        { id: 2, teacher: 'Dra. María García', subject: 'Base de Datos', group: 'SA', hours: 4, career: 'Ing. Sistemas', status: 'pending' },
        { id: 3, teacher: 'Ing. Carlos López', subject: 'Redes I', group: 'SB', hours: 4, career: 'Ing. Sistemas', status: 'approved' },
        { id: 4, teacher: 'Lic. Ana Martínez', subject: 'Algoritmos', group: 'SC', hours: 5, career: 'Ing. Informática', status: 'pending' }
    ];
    
    updateStats();
    displayAssignments(assignments);
}

function updateStats() {
    const pending = assignments.filter(a => a.status === 'pending').length;
    const approved = assignments.filter(a => a.status === 'approved').length;
    const rejected = assignments.filter(a => a.status === 'rejected').length;
    const totalHours = assignments.reduce((sum, a) => sum + a.hours, 0);
    
    document.getElementById('pendingCount').textContent = pending;
    document.getElementById('approvedCount').textContent = approved;
    document.getElementById('rejectedCount').textContent = rejected;
    document.getElementById('totalHours').textContent = totalHours;
}

function displayAssignments(data) {
    const tbody = document.getElementById('assignmentsTable');
    
    if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-8 text-center text-gray-500">No hay asignaciones para mostrar</td></tr>';
        return;
    }
    
    tbody.innerHTML = data.map(assignment => `
        <tr class="hover:bg-gray-50">
            <td class="px-4 sm:px-6 py-4">
                <div class="font-medium text-gray-900">${assignment.teacher}</div>
                <div class="text-sm text-gray-500 sm:hidden">${assignment.subject} - ${assignment.group}</div>
            </td>
            <td class="px-4 sm:px-6 py-4 text-sm text-gray-600 hidden sm:table-cell">${assignment.subject}</td>
            <td class="px-4 sm:px-6 py-4 text-sm text-gray-600 hidden lg:table-cell">${assignment.group}</td>
            <td class="px-4 sm:px-6 py-4 text-center">
                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded">${assignment.hours}h</span>
            </td>
            <td class="px-4 sm:px-6 py-4 text-sm text-gray-600 text-center hidden md:table-cell">${assignment.career}</td>
            <td class="px-4 sm:px-6 py-4 text-center">
                ${getStatusBadge(assignment.status)}
            </td>
            <td class="px-4 sm:px-6 py-4">
                <div class="flex justify-center gap-2">
                    <button onclick="viewDetails(${assignment.id})" class="text-blue-600 hover:text-blue-700" title="Ver detalles">
                        <i class="fas fa-eye"></i>
                    </button>
                    ${assignment.status === 'pending' ? `
                        <button onclick="approveAssignment(${assignment.id})" class="text-green-600 hover:text-green-700" title="Aprobar">
                            <i class="fas fa-check"></i>
                        </button>
                        <button onclick="showRejectModal(${assignment.id})" class="text-red-600 hover:text-red-700" title="Rechazar">
                            <i class="fas fa-times"></i>
                        </button>
                    ` : ''}
                </div>
            </td>
        </tr>
    `).join('');
}

function getStatusBadge(status) {
    const badges = {
        pending: '<span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-medium rounded">Pendiente</span>',
        approved: '<span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded">Aprobada</span>',
        rejected: '<span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-medium rounded">Rechazada</span>'
    };
    return badges[status] || status;
}

function viewDetails(id) {
    const assignment = assignments.find(a => a.id === id);
    if (!assignment) return;
    
    const content = document.getElementById('detailsContent');
    content.innerHTML = `
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Docente</p>
                    <p class="font-semibold">${assignment.teacher}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Materia</p>
                    <p class="font-semibold">${assignment.subject}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Grupo</p>
                    <p class="font-semibold">${assignment.group}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Horas/Semana</p>
                    <p class="font-semibold">${assignment.hours}h</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Carrera</p>
                    <p class="font-semibold">${assignment.career}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Estado</p>
                    ${getStatusBadge(assignment.status)}
                </div>
            </div>
            ${assignment.status === 'pending' ? `
                <div class="pt-4 border-t border-gray-200 flex gap-3">
                    <button onclick="approveAssignment(${assignment.id}); closeDetails();" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        <i class="fas fa-check mr-2"></i>Aprobar
                    </button>
                    <button onclick="showRejectModal(${assignment.id}); closeDetails();" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        <i class="fas fa-times mr-2"></i>Rechazar
                    </button>
                </div>
            ` : ''}
        </div>
    `;
    
    document.getElementById('detailsModal').classList.remove('hidden');
}

function closeDetails() {
    document.getElementById('detailsModal').classList.add('hidden');
}

function approveAssignment(id) {
    const assignment = assignments.find(a => a.id === id);
    if (assignment) {
        assignment.status = 'approved';
        updateStats();
        displayAssignments(assignments);
        alert('Asignación aprobada exitosamente');
    }
}

function showRejectModal(id) {
    currentAssignmentId = id;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeReject() {
    currentAssignmentId = null;
    document.getElementById('rejectReason').value = '';
    document.getElementById('rejectModal').classList.add('hidden');
}

function confirmReject() {
    const reason = document.getElementById('rejectReason').value.trim();
    if (!reason) {
        alert('Debes proporcionar un motivo para el rechazo');
        return;
    }
    
    const assignment = assignments.find(a => a.id === currentAssignmentId);
    if (assignment) {
        assignment.status = 'rejected';
        assignment.rejectReason = reason;
        updateStats();
        displayAssignments(assignments);
        closeReject();
        alert('Asignación rechazada');
    }
}

function approveAll() {
    if (confirm('¿Aprobar todas las asignaciones pendientes?')) {
        assignments.forEach(a => {
            if (a.status === 'pending') {
                a.status = 'approved';
            }
        });
        updateStats();
        displayAssignments(assignments);
        alert('Todas las asignaciones han sido aprobadas');
    }
}

function filterAssignments() {
    const status = document.getElementById('filterStatus').value;
    const career = document.getElementById('filterCareer').value;
    const search = document.getElementById('searchTeacher').value.toLowerCase();
    
    let filtered = assignments;
    
    if (status) {
        filtered = filtered.filter(a => a.status === status);
    }
    
    if (career) {
        filtered = filtered.filter(a => a.career.toLowerCase().includes(career));
    }
    
    if (search) {
        filtered = filtered.filter(a => 
            a.teacher.toLowerCase().includes(search) ||
            a.subject.toLowerCase().includes(search)
        );
    }
    
    displayAssignments(filtered);
}
</script>
@endsection
