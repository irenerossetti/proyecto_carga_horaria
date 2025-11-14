@extends('layouts.coordinator')

@section('title', 'Validar Horarios')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Validar Horarios</h1>
            <p class="text-gray-600 mt-1">Revisa y aprueba los horarios generados automáticamente</p>
        </div>
        <button onclick="approveAll()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
            <i class="fas fa-check-double mr-2"></i>Aprobar Todos
        </button>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                <select id="filterStatus" onchange="filterSchedules()" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="pending" selected>Pendientes</option>
                    <option value="approved">Aprobados</option>
                    <option value="rejected">Rechazados</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Carrera</label>
                <select id="filterCareer" onchange="filterSchedules()" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="">Todas</option>
                    <option value="sistemas">Ing. Sistemas</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                <input type="text" id="searchSchedule" onkeyup="filterSchedules()" placeholder="Grupo o materia..." class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
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
                    <p class="text-sm text-gray-600">Aprobados</p>
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
                    <p class="text-sm text-gray-600">Con Conflictos</p>
                    <p class="text-2xl sm:text-3xl font-bold text-red-600" id="conflictsCount">0</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Grupos</p>
                    <p class="text-2xl sm:text-3xl font-bold text-brand-primary" id="totalGroups">0</p>
                </div>
                <div class="w-12 h-12 bg-brand-primary/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-brand-primary text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Horarios -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Grupo</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Materia</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">Docente</th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Horarios</th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody id="schedulesTable" class="divide-y divide-gray-200">
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
let schedules = [];

document.addEventListener('DOMContentLoaded', function() {
    loadSchedules();
});

async function loadSchedules() {
    schedules = [
        { id: 1, group: 'SC', subject: 'Programación I', teacher: 'Dr. Juan Pérez', schedules: 3, status: 'pending', conflicts: 0 },
        { id: 2, group: 'SA', subject: 'Base de Datos', teacher: 'Dra. María García', schedules: 2, status: 'pending', conflicts: 1 },
        { id: 3, group: 'SB', subject: 'Redes I', teacher: 'Ing. Carlos López', schedules: 2, status: 'approved', conflicts: 0 }
    ];
    
    updateStats();
    displaySchedules(schedules);
}

function updateStats() {
    const pending = schedules.filter(s => s.status === 'pending').length;
    const approved = schedules.filter(s => s.status === 'approved').length;
    const conflicts = schedules.reduce((sum, s) => sum + s.conflicts, 0);
    
    document.getElementById('pendingCount').textContent = pending;
    document.getElementById('approvedCount').textContent = approved;
    document.getElementById('conflictsCount').textContent = conflicts;
    document.getElementById('totalGroups').textContent = schedules.length;
}

function displaySchedules(data) {
    const tbody = document.getElementById('schedulesTable');
    
    tbody.innerHTML = data.map(schedule => `
        <tr class="hover:bg-gray-50">
            <td class="px-4 sm:px-6 py-4">
                <div class="font-medium text-gray-900">${schedule.group}</div>
                <div class="text-sm text-gray-500 sm:hidden">${schedule.subject}</div>
            </td>
            <td class="px-4 sm:px-6 py-4 text-sm text-gray-600 hidden sm:table-cell">${schedule.subject}</td>
            <td class="px-4 sm:px-6 py-4 text-sm text-gray-600 hidden lg:table-cell">${schedule.teacher}</td>
            <td class="px-4 sm:px-6 py-4 text-center">
                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded">${schedule.schedules} clases</span>
                ${schedule.conflicts > 0 ? `<span class="ml-2 px-2 py-1 bg-red-100 text-red-700 text-xs font-medium rounded">${schedule.conflicts} conflictos</span>` : ''}
            </td>
            <td class="px-4 sm:px-6 py-4 text-center">
                ${getStatusBadge(schedule.status)}
            </td>
            <td class="px-4 sm:px-6 py-4">
                <div class="flex justify-center gap-2">
                    <button onclick="viewSchedule(${schedule.id})" class="text-blue-600 hover:text-blue-700" title="Ver">
                        <i class="fas fa-eye"></i>
                    </button>
                    ${schedule.status === 'pending' ? `
                        <button onclick="approveSchedule(${schedule.id})" class="text-green-600 hover:text-green-700" title="Aprobar">
                            <i class="fas fa-check"></i>
                        </button>
                        <button onclick="rejectSchedule(${schedule.id})" class="text-red-600 hover:text-red-700" title="Rechazar">
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
        approved: '<span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded">Aprobado</span>',
        rejected: '<span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-medium rounded">Rechazado</span>'
    };
    return badges[status] || status;
}

function viewSchedule(id) {
    alert('Ver detalles del horario #' + id);
}

function approveSchedule(id) {
    const schedule = schedules.find(s => s.id === id);
    if (schedule) {
        schedule.status = 'approved';
        updateStats();
        displaySchedules(schedules);
        alert('Horario aprobado');
    }
}

function rejectSchedule(id) {
    if (confirm('¿Rechazar este horario?')) {
        const schedule = schedules.find(s => s.id === id);
        if (schedule) {
            schedule.status = 'rejected';
            updateStats();
            displaySchedules(schedules);
        }
    }
}

function approveAll() {
    if (confirm('¿Aprobar todos los horarios pendientes?')) {
        schedules.forEach(s => {
            if (s.status === 'pending') s.status = 'approved';
        });
        updateStats();
        displaySchedules(schedules);
        alert('Todos los horarios aprobados');
    }
}

function filterSchedules() {
    const status = document.getElementById('filterStatus').value;
    const search = document.getElementById('searchSchedule').value.toLowerCase();
    
    let filtered = schedules;
    
    if (status) {
        filtered = filtered.filter(s => s.status === status);
    }
    
    if (search) {
        filtered = filtered.filter(s => 
            s.group.toLowerCase().includes(search) ||
            s.subject.toLowerCase().includes(search)
        );
    }
    
    displaySchedules(filtered);
}
</script>
@endsection
