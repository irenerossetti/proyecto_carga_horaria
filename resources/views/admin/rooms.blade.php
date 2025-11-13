<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gestión de Aulas - FICCT SGA</title>
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
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Gestión de Aulas</h1>
                    <p class="text-gray-500 mt-1">Administra las aulas y espacios físicos</p>
                </div>
                <button onclick="openCreateModal()" class="flex items-center gap-2 bg-brand-primary hover:bg-brand-hover text-white px-6 py-3 rounded-lg font-semibold transition-colors shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nueva Aula
                </button>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Aulas</p>
                        <p id="stat-total" class="text-3xl font-bold text-gray-900 mt-1">0</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Disponibles</p>
                        <p id="stat-available" class="text-3xl font-bold text-green-600 mt-1">0</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Capacidad Total</p>
                        <p id="stat-capacity" class="text-3xl font-bold text-purple-600 mt-1">0</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Pisos</p>
                        <p class="text-3xl font-bold text-orange-600 mt-1">4</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Buscar por nombre..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <select id="floorFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                    <option value="">Todos los pisos</option>
                    <option value="Piso 1">Piso 1</option>
                    <option value="Piso 2">Piso 2</option>
                    <option value="Piso 3">Piso 3</option>
                    <option value="Piso 4">Piso 4</option>
                </select>
                <select id="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                    <option value="">Todos los estados</option>
                    <option value="available">Disponibles</option>
                    <option value="unavailable">No disponibles</option>
                </select>
            </div>
        </div>

        <!-- Rooms Grid -->
        <div id="roomsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Loading -->
            <div class="col-span-full flex items-center justify-center py-12">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-brand-primary"></div>
                <span class="ml-3 text-gray-500">Cargando aulas...</span>
            </div>
        </div>
    </main>
</div>

<!-- Modal -->
<div id="roomModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 id="modalTitle" class="text-xl font-bold text-gray-900">Nueva Aula</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <form id="roomForm" class="p-6 space-y-4">
            <input type="hidden" id="roomId">
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Aula *</label>
                    <input type="text" id="roomName" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent" placeholder="Aula 11">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ubicación *</label>
                    <select id="roomLocation" name="location" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        <option value="">Seleccionar piso</option>
                        <option value="Piso 1">Piso 1</option>
                        <option value="Piso 2">Piso 2</option>
                        <option value="Piso 3">Piso 3</option>
                        <option value="Piso 4">Piso 4</option>
                    </select>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Capacidad *</label>
                <input type="number" id="roomCapacity" name="capacity" required min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent" placeholder="40">
                <p class="text-xs text-gray-500 mt-1">Número de estudiantes que puede albergar (0 si no está disponible)</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Recursos/Equipamiento</label>
                <textarea id="roomResources" name="resources" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent" placeholder="Proyector, Pizarra, Computadoras..."></textarea>
                <p class="text-xs text-gray-500 mt-1">Separar con comas</p>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeModal()" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                    Cancelar
                </button>
                <button type="submit" class="px-6 py-2 bg-brand-primary hover:bg-brand-hover text-white rounded-lg font-medium transition-colors">
                    <span id="submitButtonText">Crear Aula</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
const API_BASE = '/api';
let allRooms = [];

function showNotification(message, type = 'success') {
    const alertClass = type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800';
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg border ${alertClass} z-50 shadow-lg`;
    notification.innerHTML = `
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${type === 'success' ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' : 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'}" />
            </svg>
            <span class="font-medium">${message}</span>
        </div>
    `;
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
}

function openCreateModal() {
    document.getElementById('modalTitle').textContent = 'Nueva Aula';
    document.getElementById('submitButtonText').textContent = 'Crear Aula';
    document.getElementById('roomForm').reset();
    document.getElementById('roomId').value = '';
    document.getElementById('roomModal').classList.remove('hidden');
    document.getElementById('roomModal').classList.add('flex');
}

function closeModal() {
    document.getElementById('roomModal').classList.add('hidden');
    document.getElementById('roomModal').classList.remove('flex');
}

function editRoomById(id) {
    const room = allRooms.find(r => r.id === id);
    if (!room) {
        showNotification('❌ Error: Aula no encontrada', 'error');
        return;
    }
    
    document.getElementById('modalTitle').textContent = 'Editar Aula';
    document.getElementById('submitButtonText').textContent = 'Guardar Cambios';
    document.getElementById('roomId').value = room.id;
    document.getElementById('roomName').value = room.name || '';
    document.getElementById('roomLocation').value = room.location || '';
    document.getElementById('roomCapacity').value = room.capacity || 0;
    
    // Parse resources
    let resources = '';
    try {
        const resourcesArray = JSON.parse(room.resources || '[]');
        resources = Array.isArray(resourcesArray) ? resourcesArray.join(', ') : '';
    } catch (e) {
        resources = room.resources || '';
    }
    document.getElementById('roomResources').value = resources;
    
    document.getElementById('roomModal').classList.remove('hidden');
    document.getElementById('roomModal').classList.add('flex');
}

async function loadRooms() {
    try {
        const response = await fetch(`${API_BASE}/rooms`, {
            headers: { 'Accept': 'application/json' }
        });
        
        if (!response.ok) throw new Error('Error al cargar aulas');
        
        allRooms = await response.json();
        updateStats();
        renderRooms();
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('roomsGrid').innerHTML = `
            <div class="col-span-full text-center text-red-600 py-8">
                Error al cargar las aulas. Por favor, recarga la página.
            </div>
        `;
    }
}

function updateStats() {
    const available = allRooms.filter(r => r.capacity > 0).length;
    const totalCapacity = allRooms.reduce((sum, r) => sum + (r.capacity || 0), 0);
    
    document.getElementById('stat-total').textContent = allRooms.length;
    document.getElementById('stat-available').textContent = available;
    document.getElementById('stat-capacity').textContent = totalCapacity;
}

function renderRooms(filtered = null) {
    const rooms = filtered || allRooms;
    const grid = document.getElementById('roomsGrid');
    
    if (rooms.length === 0) {
        grid.innerHTML = `
            <div class="col-span-full text-center py-12">
                <div class="text-gray-400">
                    <svg class="mx-auto h-12 w-12 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <p class="text-lg font-medium">No hay aulas registradas</p>
                    <button onclick="openCreateModal()" class="mt-4 text-brand-primary hover:text-brand-hover font-medium">
                        + Crear la primera aula
                    </button>
                </div>
            </div>
        `;
        return;
    }
    
    grid.innerHTML = rooms.map(room => {
        const isAvailable = room.capacity > 0;
        const statusColor = isAvailable ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800';
        const statusText = isAvailable ? 'Disponible' : 'No disponible';
        
        let resources = [];
        try {
            resources = JSON.parse(room.resources || '[]');
            if (!Array.isArray(resources)) resources = [];
        } catch (e) {
            resources = [];
        }
        
        // Verificar si requiere reserva (Piso 4)
        const requiresReservation = room.location === 'Piso 4' && isAvailable;
        
        return `
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow ${requiresReservation ? 'border-orange-300' : ''}">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">${room.name}</h3>
                        <p class="text-sm text-gray-500">${room.location || 'Sin ubicación'}</p>
                        ${requiresReservation ? '<p class="text-xs text-orange-600 font-medium mt-1">⚠️ Requiere reserva previa</p>' : ''}
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-medium ${statusColor}">
                        ${statusText}
                    </span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span>Capacidad: ${room.capacity || 0} personas</span>
                    </div>
                    ${resources.length > 0 ? `
                        <div class="flex items-start gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                            </svg>
                            <span>${resources.slice(0, 2).join(', ')}${resources.length > 2 ? '...' : ''}</span>
                        </div>
                    ` : ''}
                </div>
                
                <div class="flex items-center gap-2 pt-4 border-t border-gray-200">
                    <button onclick="editRoomById(${room.id})" class="flex-1 px-4 py-2 text-blue-600 hover:bg-blue-50 rounded-lg font-medium transition-colors">
                        Editar
                    </button>
                    <button onclick="deleteRoom(${room.id})" class="px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg font-medium transition-colors">
                        Eliminar
                    </button>
                </div>
            </div>
        `;
    }).join('');
}

document.getElementById('roomForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const submitButton = e.target.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;
    submitButton.disabled = true;
    submitButton.textContent = 'Guardando...';
    
    const formData = new FormData(e.target);
    const data = {};
    
    for (const [key, value] of formData.entries()) {
        if (value && value.trim() !== '') {
            if (key === 'resources') {
                // Convert comma-separated string to JSON array
                const resourcesArray = value.split(',').map(r => r.trim()).filter(r => r);
                data[key] = JSON.stringify(resourcesArray);
            } else {
                data[key] = value.trim();
            }
        }
    }
    
    const roomId = document.getElementById('roomId').value;
    
    try {
        const url = roomId ? `${API_BASE}/rooms/${roomId}` : `${API_BASE}/rooms`;
        const method = roomId ? 'PATCH' : 'POST';
        
        const response = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        if (!response.ok) {
            const error = await response.json();
            throw new Error(error.message || 'Error al guardar');
        }
        
        showNotification(roomId ? '✅ Aula actualizada exitosamente' : '✅ Aula creada exitosamente');
        closeModal();
        loadRooms();
    } catch (error) {
        console.error('Error:', error);
        showNotification('❌ ' + error.message, 'error');
    } finally {
        submitButton.disabled = false;
        submitButton.textContent = originalText;
    }
});

async function deleteRoom(id) {
    if (!confirm('¿Estás seguro de eliminar esta aula? Esta acción no se puede deshacer.')) return;
    
    try {
        const response = await fetch(`${API_BASE}/rooms/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) throw new Error('Error al eliminar');
        
        showNotification('✅ Aula eliminada exitosamente');
        loadRooms();
    } catch (error) {
        console.error('Error:', error);
        showNotification('❌ ' + error.message, 'error');
    }
}

// Filters
document.getElementById('searchInput').addEventListener('input', applyFilters);
document.getElementById('floorFilter').addEventListener('change', applyFilters);
document.getElementById('statusFilter').addEventListener('change', applyFilters);

function applyFilters() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const floor = document.getElementById('floorFilter').value;
    const status = document.getElementById('statusFilter').value;
    
    let filtered = allRooms;
    
    if (searchTerm) {
        filtered = filtered.filter(r => 
            r.name.toLowerCase().includes(searchTerm) ||
            (r.location && r.location.toLowerCase().includes(searchTerm))
        );
    }
    
    if (floor) {
        filtered = filtered.filter(r => r.location === floor);
    }
    
    if (status === 'available') {
        filtered = filtered.filter(r => r.capacity > 0);
    } else if (status === 'unavailable') {
        filtered = filtered.filter(r => r.capacity === 0);
    }
    
    renderRooms(filtered);
}

// Load rooms on init
loadRooms();
</script>

</body>
</html>
