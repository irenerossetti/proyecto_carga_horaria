@extends('layouts.admin')

@section('title', 'Consultar Aulas Disponibles')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Consultar Aulas Disponibles</h1>
            <p class="text-gray-600 mt-1">Busca y consulta la disponibilidad de aulas en tiempo real</p>
        </div>
    </div>

    <!-- Filtros de Búsqueda -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Filtros de Búsqueda</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha</label>
                <input type="date" id="searchDate" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Hora Inicio</label>
                <input type="time" id="startTime" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Hora Fin</label>
                <input type="time" id="endTime" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Capacidad Mínima</label>
                <input type="number" id="minCapacity" placeholder="Ej: 30" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
            </div>
        </div>
        <div class="mt-4 flex gap-3">
            <button onclick="searchAvailableRooms()" class="px-6 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary-dark transition-colors">
                <i class="fas fa-search mr-2"></i>Buscar Disponibilidad
            </button>
            <button onclick="clearFilters()" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-times mr-2"></i>Limpiar
            </button>
        </div>
    </div>

    <!-- Estadísticas Rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Aulas</p>
                    <p class="text-2xl font-bold text-gray-900" id="totalRooms">0</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-door-open text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Disponibles</p>
                    <p class="text-2xl font-bold text-green-600" id="availableRooms">0</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Ocupadas</p>
                    <p class="text-2xl font-bold text-red-600" id="occupiedRooms">0</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">% Disponibilidad</p>
                    <p class="text-2xl font-bold text-brand-primary" id="availabilityPercent">0%</p>
                </div>
                <div class="w-12 h-12 bg-brand-primary/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-pie text-brand-primary text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Resultados de Búsqueda -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Aulas Disponibles</h2>
        </div>
        <div class="p-6">
            <div id="roomsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Las tarjetas se cargarán dinámicamente -->
            </div>
            <div id="noResults" class="text-center py-12 hidden">
                <i class="fas fa-search text-gray-300 text-5xl mb-4"></i>
                <p class="text-gray-500">No se encontraron aulas disponibles con los criterios seleccionados</p>
            </div>
        </div>
    </div>

    <!-- Modal de Detalles del Aula -->
    <div id="roomDetailsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-900" id="modalRoomName">Detalles del Aula</h3>
                <button onclick="closeRoomDetails()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6" id="roomDetailsContent">
                <!-- Contenido dinámico -->
            </div>
        </div>
    </div>
</div>

<script>
let allRooms = [];

// Inicializar con fecha y hora actual
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('searchDate').value = today;
    document.getElementById('startTime').value = '07:00';
    document.getElementById('endTime').value = '21:00';
    
    loadRooms();
});

async function loadRooms() {
    try {
        const response = await fetch('/api/rooms');
        const data = await response.json();
        allRooms = data.data || [];
        updateStats();
        searchAvailableRooms();
    } catch (error) {
        console.error('Error:', error);
    }
}

function updateStats() {
    document.getElementById('totalRooms').textContent = allRooms.length;
}

async function searchAvailableRooms() {
    const date = document.getElementById('searchDate').value;
    const startTime = document.getElementById('startTime').value;
    const endTime = document.getElementById('endTime').value;
    const minCapacity = document.getElementById('minCapacity').value;

    if (!date || !startTime || !endTime) {
        alert('Por favor completa fecha y horarios');
        return;
    }

    try {
        const response = await fetch(`/api/rooms/available?date=${date}&start_time=${startTime}&end_time=${endTime}`);
        const data = await response.json();
        
        let availableRooms = data.data || [];
        
        // Filtrar por capacidad si se especificó
        if (minCapacity) {
            availableRooms = availableRooms.filter(room => room.capacity >= parseInt(minCapacity));
        }
        
        displayRooms(availableRooms);
        updateSearchStats(availableRooms.length);
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('noResults').classList.remove('hidden');
        document.getElementById('roomsGrid').innerHTML = '';
    }
}

function updateSearchStats(available) {
    const total = allRooms.length;
    const occupied = total - available;
    const percent = total > 0 ? Math.round((available / total) * 100) : 0;
    
    document.getElementById('availableRooms').textContent = available;
    document.getElementById('occupiedRooms').textContent = occupied;
    document.getElementById('availabilityPercent').textContent = percent + '%';
}

function displayRooms(rooms) {
    const grid = document.getElementById('roomsGrid');
    const noResults = document.getElementById('noResults');
    
    if (rooms.length === 0) {
        grid.innerHTML = '';
        noResults.classList.remove('hidden');
        return;
    }
    
    noResults.classList.add('hidden');
    grid.innerHTML = rooms.map(room => `
        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <h3 class="font-semibold text-gray-900">${room.name}</h3>
                    <p class="text-sm text-gray-600">${room.building || 'Edificio Principal'}</p>
                </div>
                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded">
                    Disponible
                </span>
            </div>
            <div class="space-y-2 mb-4">
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-users w-5"></i>
                    <span>Capacidad: ${room.capacity} personas</span>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-map-marker-alt w-5"></i>
                    <span>${room.location || 'Ubicación no especificada'}</span>
                </div>
                ${room.equipment ? `
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-desktop w-5"></i>
                    <span>${room.equipment}</span>
                </div>
                ` : ''}
            </div>
            <div class="flex gap-2">
                <button onclick='showRoomDetails(${JSON.stringify(room)})' class="flex-1 px-3 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors text-sm">
                    <i class="fas fa-info-circle mr-1"></i>Ver Detalles
                </button>
                <button onclick="reserveRoom(${room.id})" class="flex-1 px-3 py-2 bg-brand-primary text-white rounded hover:bg-brand-primary-dark transition-colors text-sm">
                    <i class="fas fa-calendar-check mr-1"></i>Reservar
                </button>
            </div>
        </div>
    `).join('');
}

function showRoomDetails(room) {
    const modal = document.getElementById('roomDetailsModal');
    const content = document.getElementById('roomDetailsContent');
    
    document.getElementById('modalRoomName').textContent = room.name;
    
    content.innerHTML = `
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Capacidad</p>
                    <p class="font-semibold">${room.capacity} personas</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Edificio</p>
                    <p class="font-semibold">${room.building || 'Principal'}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Ubicación</p>
                    <p class="font-semibold">${room.location || 'N/A'}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Estado</p>
                    <p class="font-semibold text-green-600">Disponible</p>
                </div>
            </div>
            ${room.equipment ? `
            <div>
                <p class="text-sm text-gray-600 mb-2">Equipamiento</p>
                <p class="font-semibold">${room.equipment}</p>
            </div>
            ` : ''}
            <div class="pt-4 border-t border-gray-200">
                <button onclick="reserveRoom(${room.id})" class="w-full px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary-dark transition-colors">
                    <i class="fas fa-calendar-check mr-2"></i>Reservar Aula
                </button>
            </div>
        </div>
    `;
    
    modal.classList.remove('hidden');
}

function closeRoomDetails() {
    document.getElementById('roomDetailsModal').classList.add('hidden');
}

function reserveRoom(roomId) {
    // Redirigir a la página de reservas con el aula preseleccionada
    window.location.href = `/reservas?room_id=${roomId}&date=${document.getElementById('searchDate').value}&start_time=${document.getElementById('startTime').value}&end_time=${document.getElementById('endTime').value}`;
}

function clearFilters() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('searchDate').value = today;
    document.getElementById('startTime').value = '07:00';
    document.getElementById('endTime').value = '21:00';
    document.getElementById('minCapacity').value = '';
    searchAvailableRooms();
}
</script>
@endsection
