<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Anulación de Clases - FICCT SGA</title>
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
                <h1 class="text-3xl font-bold text-gray-900">Anulación de Clases</h1>
                <p class="text-gray-500 mt-1">Gestiona cancelaciones y cambios a modalidad virtual</p>
            </div>
            <button onclick="openCancellationModal()" class="px-6 py-3 bg-brand-primary hover:bg-brand-hover text-white rounded-lg font-medium transition-colors shadow-sm">
                + Anular Clase
            </button>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-red-100 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Canceladas</p>
                        <p id="totalCancelled" class="text-2xl font-bold text-gray-900">0</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Virtuales</p>
                        <p id="totalVirtual" class="text-2xl font-bold text-gray-900">0</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Esta Semana</p>
                        <p id="thisWeek" class="text-2xl font-bold text-gray-900">0</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Aulas Liberadas</p>
                        <p id="roomsFreed" class="text-2xl font-bold text-gray-900">0</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha</label>
                    <input type="date" id="dateFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Docente</label>
                    <select id="teacherFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        <option value="">Todos</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                    <select id="typeFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        <option value="">Todos</option>
                        <option value="cancelled">Cancelada</option>
                        <option value="virtual">Virtual</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <select id="statusFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        <option value="">Todos</option>
                        <option value="pending">Pendiente</option>
                        <option value="approved">Aprobada</option>
                        <option value="rejected">Rechazada</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button onclick="applyFilters()" class="w-full px-4 py-2 bg-brand-primary hover:bg-brand-hover text-white rounded-lg font-medium transition-colors">
                        Buscar
                    </button>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Clases Anuladas</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Docente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Materia</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Horario</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Tipo</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="cancellationsTable" class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-brand-primary mx-auto"></div>
                                <p class="mt-4 text-gray-500">Cargando...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Modal -->
<div id="cancellationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900">Anular Clase</h3>
                <button onclick="closeCancellationModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <form id="cancellationForm" class="p-6 space-y-6">
            <input type="hidden" id="cancellationId">
            
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Horario *</label>
                    <select id="scheduleSelect" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        <option value="">Seleccionar horario</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha *</label>
                    <input type="date" id="cancellationDate" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Anulación *</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-brand-primary transition-colors">
                        <input type="radio" name="cancellationType" value="cancelled" required class="w-4 h-4 text-brand-primary">
                        <div class="ml-3">
                            <span class="text-sm font-medium text-gray-900">Cancelar Clase</span>
                            <p class="text-xs text-gray-500">La clase no se realizará</p>
                        </div>
                    </label>
                    <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-brand-primary transition-colors">
                        <input type="radio" name="cancellationType" value="virtual" class="w-4 h-4 text-brand-primary">
                        <div class="ml-3">
                            <span class="text-sm font-medium text-gray-900">Cambiar a Virtual</span>
                            <p class="text-xs text-gray-500">Libera el aula</p>
                        </div>
                    </label>
                </div>
            </div>
            
            <div id="virtualLinkDiv" class="hidden">
                <label class="block text-sm font-medium text-gray-700 mb-2">Enlace de Clase Virtual</label>
                <input type="url" id="virtualLink" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent" placeholder="https://meet.google.com/...">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Motivo *</label>
                <textarea id="cancellationReason" required rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent" placeholder="Explica el motivo de la anulación..."></textarea>
            </div>
            
            <div class="flex items-center gap-3 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <input type="checkbox" id="notifyStudents" checked class="w-4 h-4 text-brand-primary">
                <label for="notifyStudents" class="text-sm font-medium text-gray-700">Notificar a los estudiantes</label>
            </div>
        </form>

        <div class="p-6 pt-0">
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeCancellationModal()" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                    Cancelar
                </button>
                <button type="button" onclick="document.getElementById('cancellationForm').requestSubmit()" class="px-6 py-2 bg-brand-primary hover:bg-brand-hover text-white rounded-lg font-medium transition-colors">
                    Confirmar Anulación
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const API_BASE = '/api';
let allCancellations = [];
let filteredCancellations = [];

function showNotification(message, type = 'success') {
    const alertClass = type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800';
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg border ${alertClass} z-50 shadow-lg`;
    notification.innerHTML = `<span class="font-medium">${message}</span>`;
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
}

async function loadCancellations() {
    try {
        const response = await fetch(`${API_BASE}/cancellations`, {
            headers: { 'Accept': 'application/json' }
        });
        
        if (response.ok) {
            allCancellations = await response.json();
        } else {
            // Mock data
            allCancellations = [
                {
                    id: 1,
                    date: '2025-11-15',
                    teacher_name: 'Dr. Juan Pérez',
                    subject_name: 'Programación I',
                    group_name: 'Grupo A',
                    start_time: '08:00',
                    end_time: '10:00',
                    type: 'cancelled',
                    reason: 'Enfermedad del docente',
                    status: 'approved'
                },
                {
                    id: 2,
                    date: '2025-11-16',
                    teacher_name: 'Ing. María García',
                    subject_name: 'Base de Datos',
                    group_name: 'Grupo B',
                    start_time: '14:00',
                    end_time: '16:00',
                    type: 'virtual',
                    reason: 'Reunión administrativa',
                    virtual_link: 'https://meet.google.com/abc-defg-hij',
                    status: 'approved'
                }
            ];
        }
        
        filteredCancellations = [...allCancellations];
        renderCancellations();
        updateStats();
    } catch (error) {
        console.error('Error:', error);
        allCancellations = [];
        renderCancellations();
    }
}

function renderCancellations() {
    const tbody = document.getElementById('cancellationsTable');
    
    if (filteredCancellations.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                    No se encontraron anulaciones
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = filteredCancellations.map(c => {
        const typeConfig = {
            cancelled: { color: 'red', label: 'Cancelada', icon: '✗' },
            virtual: { color: 'yellow', label: 'Virtual', icon: '🌐' }
        };
        
        const statusConfig = {
            pending: { color: 'yellow', label: 'Pendiente' },
            approved: { color: 'green', label: 'Aprobada' },
            rejected: { color: 'red', label: 'Rechazada' }
        };
        
        const type = typeConfig[c.type];
        const status = statusConfig[c.status];
        
        return `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${new Date(c.date).toLocaleDateString('es-ES')}
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">${c.teacher_name}</td>
                <td class="px-6 py-4">
                    <div class="text-sm font-medium text-gray-900">${c.subject_name}</div>
                    <div class="text-sm text-gray-500">${c.group_name}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                    ${c.start_time} - ${c.end_time}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <span class="px-3 py-1 bg-${type.color}-100 text-${type.color}-800 rounded-full text-sm font-medium">
                        ${type.icon} ${type.label}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <span class="px-3 py-1 bg-${status.color}-100 text-${status.color}-800 rounded-full text-sm font-medium">
                        ${status.label}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right">
                    <button onclick="viewDetails(${c.id})" class="text-blue-600 hover:text-blue-900 mr-3" title="Ver detalles">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                    ${c.status === 'pending' ? `
                    <button onclick="deleteCancellation(${c.id})" class="text-red-600 hover:text-red-900" title="Eliminar">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                    ` : ''}
                </td>
            </tr>
        `;
    }).join('');
}

function updateStats() {
    const cancelled = allCancellations.filter(c => c.type === 'cancelled').length;
    const virtual = allCancellations.filter(c => c.type === 'virtual').length;
    
    const today = new Date();
    const weekStart = new Date(today.setDate(today.getDate() - today.getDay()));
    const thisWeek = allCancellations.filter(c => new Date(c.date) >= weekStart).length;
    
    document.getElementById('totalCancelled').textContent = cancelled;
    document.getElementById('totalVirtual').textContent = virtual;
    document.getElementById('thisWeek').textContent = thisWeek;
    document.getElementById('roomsFreed').textContent = cancelled + virtual;
}

function openCancellationModal() {
    document.getElementById('cancellationForm').reset();
    document.getElementById('cancellationId').value = '';
    document.getElementById('cancellationDate').valueAsDate = new Date();
    document.getElementById('cancellationModal').classList.remove('hidden');
    document.getElementById('cancellationModal').classList.add('flex');
    loadSchedules();
}

function closeCancellationModal() {
    document.getElementById('cancellationModal').classList.add('hidden');
    document.getElementById('cancellationModal').classList.remove('flex');
}

async function loadSchedules() {
    try {
        const response = await fetch(`${API_BASE}/schedules`, {
            headers: { 'Accept': 'application/json' }
        });
        
        if (response.ok) {
            const schedules = await response.json();
            const select = document.getElementById('scheduleSelect');
            select.innerHTML = '<option value="">Seleccionar horario</option>' + 
                schedules.map(s => `
                    <option value="${s.id}">
                        ${s.subject_name} - ${s.group_name} (${s.day} ${s.start_time}-${s.end_time})
                    </option>
                `).join('');
        }
    } catch (error) {
        console.error('Error loading schedules:', error);
    }
}

// Show/hide virtual link field
document.querySelectorAll('input[name="cancellationType"]').forEach(radio => {
    radio.addEventListener('change', (e) => {
        const virtualDiv = document.getElementById('virtualLinkDiv');
        if (e.target.value === 'virtual') {
            virtualDiv.classList.remove('hidden');
            document.getElementById('virtualLink').required = true;
        } else {
            virtualDiv.classList.add('hidden');
            document.getElementById('virtualLink').required = false;
        }
    });
});

document.getElementById('cancellationForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const data = {
        schedule_id: parseInt(document.getElementById('scheduleSelect').value),
        date: document.getElementById('cancellationDate').value,
        type: document.querySelector('input[name="cancellationType"]:checked').value,
        reason: document.getElementById('cancellationReason').value,
        virtual_link: document.getElementById('virtualLink').value,
        notify_students: document.getElementById('notifyStudents').checked
    };
    
    try {
        const response = await fetch(`${API_BASE}/schedules/${data.schedule_id}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (!response.ok) {
            throw new Error(result.message || 'Error al anular clase');
        }
        
        showNotification(result.message || '✅ Clase anulada exitosamente');
        closeCancellationModal();
        loadCancellations();
    } catch (error) {
        console.error('Error:', error);
        showNotification('❌ ' + error.message, 'error');
    }
});

function viewDetails(id) {
    const cancellation = allCancellations.find(c => c.id === id);
    if (cancellation) {
        alert(`Detalles:\n\nMotivo: ${cancellation.reason}\n${cancellation.virtual_link ? `Enlace: ${cancellation.virtual_link}` : ''}`);
    }
}

async function deleteCancellation(id) {
    if (!confirm('¿Estás seguro de eliminar esta anulación?')) return;
    
    try {
        const response = await fetch(`${API_BASE}/cancellations/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (!response.ok) {
            throw new Error(result.message || 'Error al eliminar');
        }
        
        showNotification(result.message || '✅ Anulación eliminada');
        loadCancellations();
    } catch (error) {
        console.error('Error:', error);
        showNotification('❌ ' + error.message, 'error');
    }
}

function applyFilters() {
    const date = document.getElementById('dateFilter').value;
    const teacher = document.getElementById('teacherFilter').value;
    const type = document.getElementById('typeFilter').value;
    const status = document.getElementById('statusFilter').value;
    
    filteredCancellations = allCancellations.filter(c => {
        return (!date || c.date === date) &&
               (!teacher || c.teacher_id == teacher) &&
               (!type || c.type === type) &&
               (!status || c.status === status);
    });
    
    renderCancellations();
}

loadCancellations();
</script>

</body>
</html>
