@extends('layouts.admin')

@section('title', 'Reservar Aulas Liberadas')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Reservar Aulas Liberadas</h1>
            <p class="text-gray-600 mt-1">Gestiona y reserva aulas que han sido liberadas por cancelaciones</p>
        </div>
        <button onclick="showNewReservation()" class="px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary-dark transition-colors">
            <i class="fas fa-plus mr-2"></i>Nueva Reserva
        </button>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Reservas Activas</p>
                    <p class="text-2xl font-bold text-gray-900" id="activeReservations">0</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Pendientes</p>
                    <p class="text-2xl font-bold text-yellow-600" id="pendingReservations">0</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Aulas Liberadas</p>
                    <p class="text-2xl font-bold text-green-600" id="freedRooms">0</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-door-open text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Completadas</p>
                    <p class="text-2xl font-bold text-brand-primary" id="completedReservations">0</p>
                </div>
                <div class="w-12 h-12 bg-brand-primary/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-double text-brand-primary text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                <select id="filterStatus" onchange="filterReservations()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary">
                    <option value="">Todos</option>
                    <option value="pending">Pendiente</option>
                    <option value="approved">Aprobada</option>
                    <option value="rejected">Rechazada</option>
                    <option value="completed">Completada</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Desde</label>
                <input type="date" id="filterDateFrom" onchange="filterReservations()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Hasta</label>
                <input type="date" id="filterDateTo" onchange="filterReservations()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                <input type="text" id="searchReservation" onkeyup="filterReservations()" placeholder="Aula, docente..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary">
            </div>
        </div>
    </div>

    <!-- Tabla de Reservas -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aula</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Solicitante</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Horario</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Motivo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody id="reservationsTable" class="divide-y divide-gray-200">
                    <!-- Contenido dinámico -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Nueva Reserva -->
    <div id="newReservationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-900">Nueva Reserva de Aula</h3>
                <button onclick="closeNewReservation()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="reservationForm" class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Aula *</label>
                        <select id="roomId" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary">
                            <option value="">Seleccionar aula...</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha *</label>
                        <input type="date" id="reservationDate" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hora Inicio *</label>
                        <input type="time" id="startTime" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hora Fin *</label>
                        <input type="time" id="endTime" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Motivo de la Reserva *</label>
                    <textarea id="reason" required rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary" placeholder="Describe el motivo de la reserva..."></textarea>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="submit" class="flex-1 px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary-dark transition-colors">
                        <i class="fas fa-save mr-2"></i>Crear Reserva
                    </button>
                    <button type="button" onclick="closeNewReservation()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let reservations = [];
let rooms = [];

document.addEventListener('DOMContentLoaded', function() {
    loadReservations();
    loadRooms();
    
    // Pre-llenar si viene de consulta de aulas
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('room_id')) {
        setTimeout(() => {
            showNewReservation();
            document.getElementById('roomId').value = urlParams.get('room_id');
            document.getElementById('reservationDate').value = urlParams.get('date');
            document.getElementById('startTime').value = urlParams.get('start_time');
            document.getElementById('endTime').value = urlParams.get('end_time');
        }, 500);
    }
    
    document.getElementById('reservationForm').addEventListener('submit', handleSubmit);
});

async function loadReservations() {
    try {
        // Simulación - reemplazar con API real
        reservations = [
            {
                id: 1,
                room: 'Aula 301',
                requester: 'Dr. Juan Pérez',
                date: '2025-11-15',
                start_time: '14:00',
                end_time: '16:00',
                reason: 'Clase de recuperación',
                status: 'pending'
            },
            {
                id: 2,
                room: 'Lab 102',
                requester: 'Dra. María García',
                date: '2025-11-16',
                start_time: '10:00',
                end_time: '12:00',
                reason: 'Práctica adicional',
                status: 'approved'
            }
        ];
        
        updateStats();
        displayReservations(reservations);
    } catch (error) {
        console.error('Error:', error);
    }
}

async function loadRooms() {
    try {
        const response = await fetch('/api/rooms');
        const data = await response.json();
        rooms = data.data || [];
        
        const select = document.getElementById('roomId');
        select.innerHTML = '<option value="">Seleccionar aula...</option>' +
            rooms.map(room => `<option value="${room.id}">${room.name} (Cap: ${room.capacity})</option>`).join('');
    } catch (error) {
        console.error('Error:', error);
    }
}

function updateStats() {
    const active = reservations.filter(r => r.status === 'approved').length;
    const pending = reservations.filter(r => r.status === 'pending').length;
    const completed = reservations.filter(r => r.status === 'completed').length;
    
    document.getElementById('activeReservations').textContent = active;
    document.getElementById('pendingReservations').textContent = pending;
    document.getElementById('completedReservations').textContent = completed;
    document.getElementById('freedRooms').textContent = '5'; // Simulado
}

function displayReservations(data) {
    const tbody = document.getElementById('reservationsTable');
    
    if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-8 text-center text-gray-500">No hay reservas registradas</td></tr>';
        return;
    }
    
    tbody.innerHTML = data.map(res => `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4">
                <div class="font-medium text-gray-900">${res.room}</div>
            </td>
            <td class="px-6 py-4 text-sm text-gray-600">${res.requester}</td>
            <td class="px-6 py-4 text-sm text-gray-600">${formatDate(res.date)}</td>
            <td class="px-6 py-4 text-sm text-gray-600">${res.start_time} - ${res.end_time}</td>
            <td class="px-6 py-4 text-sm text-gray-600">${res.reason}</td>
            <td class="px-6 py-4">
                ${getStatusBadge(res.status)}
            </td>
            <td class="px-6 py-4">
                <div class="flex gap-2">
                    ${res.status === 'pending' ? `
                        <button onclick="approveReservation(${res.id})" class="text-green-600 hover:text-green-700" title="Aprobar">
                            <i class="fas fa-check"></i>
                        </button>
                        <button onclick="rejectReservation(${res.id})" class="text-red-600 hover:text-red-700" title="Rechazar">
                            <i class="fas fa-times"></i>
                        </button>
                    ` : ''}
                    <button onclick="deleteReservation(${res.id})" class="text-gray-600 hover:text-gray-700" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

function getStatusBadge(status) {
    const badges = {
        pending: '<span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-medium rounded">Pendiente</span>',
        approved: '<span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded">Aprobada</span>',
        rejected: '<span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-medium rounded">Rechazada</span>',
        completed: '<span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded">Completada</span>'
    };
    return badges[status] || status;
}

function formatDate(dateStr) {
    const date = new Date(dateStr + 'T00:00:00');
    return date.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });
}

function showNewReservation() {
    document.getElementById('newReservationModal').classList.remove('hidden');
}

function closeNewReservation() {
    document.getElementById('newReservationModal').classList.add('hidden');
    document.getElementById('reservationForm').reset();
}

async function handleSubmit(e) {
    e.preventDefault();
    
    const formData = {
        room_id: document.getElementById('roomId').value,
        date: document.getElementById('reservationDate').value,
        start_time: document.getElementById('startTime').value,
        end_time: document.getElementById('endTime').value,
        reason: document.getElementById('reason').value
    };
    
    try {
        // Simulación - reemplazar con API real
        console.log('Creando reserva:', formData);
        alert('Reserva creada exitosamente');
        closeNewReservation();
        loadReservations();
    } catch (error) {
        console.error('Error:', error);
        alert('Error al crear la reserva');
    }
}

async function approveReservation(id) {
    if (confirm('¿Aprobar esta reserva?')) {
        const reservation = reservations.find(r => r.id === id);
        if (reservation) {
            reservation.status = 'approved';
            updateStats();
            displayReservations(reservations);
        }
    }
}

async function rejectReservation(id) {
    if (confirm('¿Rechazar esta reserva?')) {
        const reservation = reservations.find(r => r.id === id);
        if (reservation) {
            reservation.status = 'rejected';
            updateStats();
            displayReservations(reservations);
        }
    }
}

async function deleteReservation(id) {
    if (confirm('¿Eliminar esta reserva?')) {
        reservations = reservations.filter(r => r.id !== id);
        updateStats();
        displayReservations(reservations);
    }
}

function filterReservations() {
    const status = document.getElementById('filterStatus').value;
    const dateFrom = document.getElementById('filterDateFrom').value;
    const dateTo = document.getElementById('filterDateTo').value;
    const search = document.getElementById('searchReservation').value.toLowerCase();
    
    let filtered = reservations;
    
    if (status) {
        filtered = filtered.filter(r => r.status === status);
    }
    
    if (dateFrom) {
        filtered = filtered.filter(r => r.date >= dateFrom);
    }
    
    if (dateTo) {
        filtered = filtered.filter(r => r.date <= dateTo);
    }
    
    if (search) {
        filtered = filtered.filter(r => 
            r.room.toLowerCase().includes(search) ||
            r.requester.toLowerCase().includes(search) ||
            r.reason.toLowerCase().includes(search)
        );
    }
    
    displayReservations(filtered);
}
</script>
@endsection
