<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Periodos Académicos - FICCT SGA</title>
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

    <!-- Main Content -->
    <main class="flex-1 ml-64 p-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Periodos Académicos</h1>
                    <p class="text-gray-500 mt-1">Gestiona los períodos académicos del sistema</p>
                </div>
                <button onclick="openCreateModal()" class="flex items-center gap-2 bg-brand-primary hover:bg-brand-hover text-white px-6 py-3 rounded-lg font-semibold transition-colors shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nuevo Periodo
                </button>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Periodos</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalPeriods }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Activos</p>
                        <p class="text-3xl font-bold text-green-600 mt-1">{{ $activePeriods }}</p>
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
                        <p class="text-sm font-medium text-gray-500">Planificación</p>
                        <p class="text-3xl font-bold text-amber-600 mt-1">{{ $plannedPeriods }}</p>
                    </div>
                    <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Finalizados</p>
                        <p class="text-3xl font-bold text-gray-600 mt-1">{{ $closedPeriods }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
            <div class="flex gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" id="searchInput" placeholder="Buscar por nombre o código..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
                <select id="statusFilter" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                    <option value="">Todos los estados</option>
                    <option value="active">Activos</option>
                    <option value="planned">Planificación</option>
                    <option value="closed">Finalizados</option>
                </select>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Lista de Periodos</h2>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fechas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="periodsTable">
                        @forelse($periods as $period)
                        @php
                            $periodData = is_array($period) ? $period : (array)$period;
                            $status = $periodData['status'] ?? 'planned';
                            $code = $periodData['code'] ?? 'N/A';
                            $name = $periodData['name'] ?? 'Sin nombre';
                            $description = $periodData['description'] ?? 'Sin descripción';
                            $startDate = $periodData['start_date'] ?? null;
                            $endDate = $periodData['end_date'] ?? null;
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors" data-status="{{ $status }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $code }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $name }}</div>
                                <div class="text-sm text-gray-500">{{ $description }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($startDate && $endDate)
                                <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}</div>
                                <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</div>
                                @else
                                <div class="text-sm text-gray-500">Sin fechas</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusConfig = [
                                        'active' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Activo'],
                                        'planned' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-800', 'label' => 'Planificación'],
                                        'closed' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => 'Finalizado']
                                    ];
                                    $config = $statusConfig[$status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => ucfirst($status)];
                                @endphp
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $config['bg'] }} {{ $config['text'] }}">
                                    {{ $config['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @php
                                    $periodId = $periodData['id'] ?? 0;
                                @endphp
                                <div class="flex items-center justify-end gap-2">
                                    @if($status === 'planned')
                                    <button onclick="activatePeriod({{ $periodId }})" class="text-green-600 hover:text-green-900" title="Activar">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </button>
                                    @endif
                                    @if($status === 'active')
                                    <button onclick="closePeriod({{ $periodId }})" class="text-red-600 hover:text-red-900" title="Cerrar">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </button>
                                    @endif
                                    <button onclick="editPeriod({{ json_encode($periodData) }})" class="text-blue-600 hover:text-blue-900" title="Editar">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    @if($status !== 'active')
                                    <button onclick="deletePeriod({{ $periodId }})" class="text-red-600 hover:text-red-900" title="Eliminar">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="text-gray-400">
                                    <svg class="mx-auto h-12 w-12 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-lg font-medium">No hay periodos académicos</p>
                                    <p class="text-sm mt-1">Crea tu primer periodo para comenzar</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Modal -->
<div id="periodModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 id="modalTitle" class="text-xl font-bold text-gray-900">Nuevo Periodo Académico</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <form id="periodForm" class="p-6 space-y-4">
            <input type="hidden" id="periodId" name="id">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Código *</label>
                    <input type="text" id="code" name="code" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent" placeholder="Ej: 2025-1">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                    <input type="text" id="name" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent" placeholder="Ej: Primer Semestre 2025">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                <textarea id="description" name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent" placeholder="Descripción opcional del periodo"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Inicio *</label>
                    <input type="date" id="start_date" name="start_date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Fin *</label>
                    <input type="date" id="end_date" name="end_date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeModal()" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                    Cancelar
                </button>
                <button type="submit" class="px-6 py-2 bg-brand-primary hover:bg-brand-hover text-white rounded-lg font-medium transition-colors">
                    Guardar Periodo
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

if (!csrfToken) {
    console.error('⚠️ CSRF Token no encontrado. Recarga la página.');
    alert('⚠️ Error de seguridad: Token CSRF no encontrado. Por favor recarga la página.');
}

function openCreateModal() {
    document.getElementById('modalTitle').textContent = 'Nuevo Periodo Académico';
    document.getElementById('periodForm').reset();
    document.getElementById('periodId').value = '';
    document.getElementById('periodModal').classList.remove('hidden');
    document.getElementById('periodModal').classList.add('flex');
}

function closeModal() {
    document.getElementById('periodModal').classList.add('hidden');
    document.getElementById('periodModal').classList.remove('flex');
}

function editPeriod(period) {
    document.getElementById('modalTitle').textContent = 'Editar Periodo Académico';
    document.getElementById('periodId').value = period.id;
    document.getElementById('code').value = period.code;
    document.getElementById('name').value = period.name;
    document.getElementById('description').value = period.description || '';
    document.getElementById('start_date').value = period.start_date;
    document.getElementById('end_date').value = period.end_date;
    document.getElementById('periodModal').classList.remove('hidden');
    document.getElementById('periodModal').classList.add('flex');
}

document.getElementById('periodForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const submitButton = e.target.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;
    submitButton.disabled = true;
    submitButton.textContent = 'Guardando...';
    
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());
    const periodId = data.id;
    delete data.id;

    console.log('Enviando datos:', data);
    console.log('URL:', periodId ? `/api/periods/${periodId}` : '/api/periods');
    console.log('Método:', periodId ? 'PATCH' : 'POST');
    console.log('CSRF Token:', csrfToken);

    try {
        const response = await fetch(periodId ? `/api/periods/${periodId}` : '/api/periods', {
            method: periodId ? 'PATCH' : 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data),
            credentials: 'same-origin'
        });

        console.log('Response status:', response.status);
        console.log('Response headers:', [...response.headers.entries()]);
        
        if (response.ok) {
            const result = await response.json();
            console.log('Resultado:', result);
            alert('✅ Periodo guardado exitosamente');
            closeModal();
            // Forzar recarga completa sin caché
            window.location.href = window.location.href.split('?')[0] + '?t=' + Date.now();
        } else {
            let errorMessage = 'Error desconocido';
            try {
                const error = await response.json();
                console.error('Error del servidor:', error);
                errorMessage = error.message || JSON.stringify(error.errors || error);
            } catch (e) {
                const errorText = await response.text();
                console.error('Error text:', errorText);
                errorMessage = `Error ${response.status}: ${response.statusText}`;
            }
            alert('❌ Error: ' + errorMessage);
        }
    } catch (error) {
        console.error('Error de conexión:', error);
        alert('❌ Error de conexión: ' + error.message + '\n\n⚠️ Asegúrate de que el servidor Laravel esté corriendo:\nphp artisan serve');
    } finally {
        submitButton.disabled = false;
        submitButton.textContent = originalText;
    }
});

async function activatePeriod(id) {
    if (!confirm('¿Estás seguro de activar este periodo? Esto desactivará otros periodos activos.')) return;
    
    try {
        const response = await fetch(`/api/periods/${id}/activate`, {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            alert('✅ Periodo activado exitosamente');
            window.location.href = window.location.href.split('?')[0] + '?t=' + Date.now();
        } else {
            const error = await response.json();
            alert('❌ Error: ' + (error.message || 'No se pudo activar el periodo'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('❌ Error al activar el periodo: ' + error.message);
    }
}

async function closePeriod(id) {
    if (!confirm('¿Estás seguro de cerrar este periodo? Esta acción no se puede deshacer.')) return;
    
    try {
        const response = await fetch(`/api/periods/${id}/close`, {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            alert('✅ Periodo cerrado exitosamente');
            window.location.href = window.location.href.split('?')[0] + '?t=' + Date.now();
        } else {
            const error = await response.json();
            alert('❌ Error: ' + (error.message || 'No se pudo cerrar el periodo'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('❌ Error al cerrar el periodo: ' + error.message);
    }
}

async function deletePeriod(id) {
    if (!confirm('¿Estás seguro de eliminar este periodo? Esta acción no se puede deshacer.')) return;
    
    try {
        const response = await fetch(`/api/periods/${id}`, {
            method: 'DELETE',
            headers: { 
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            alert('✅ Periodo eliminado exitosamente');
            window.location.href = window.location.href.split('?')[0] + '?t=' + Date.now();
        } else {
            const error = await response.json();
            alert('❌ Error: ' + (error.message || 'No se pudo eliminar el periodo'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('❌ Error al eliminar el periodo: ' + error.message);
    }
}

// Búsqueda en tiempo real - MEJORADO
document.getElementById('searchInput').addEventListener('input', (e) => {
    const searchTerm = e.target.value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
    const rows = document.querySelectorAll('#periodsTable tr[data-status]');
    
    let visibleCount = 0;
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const rowStatus = row.getAttribute('data-status');
        
        // Aplicar ambos filtros: búsqueda Y estado
        const matchesSearch = !searchTerm || text.includes(searchTerm);
        const matchesStatus = !statusFilter || rowStatus === statusFilter;
        
        if (matchesSearch && matchesStatus) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    console.log(`Búsqueda: "${searchTerm}" - Mostrando ${visibleCount} períodos`);
});

// Filtro por estado - MEJORADO
document.getElementById('statusFilter').addEventListener('change', (e) => {
    const filterValue = e.target.value.toLowerCase();
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#periodsTable tr[data-status]');
    
    console.log('Filtrando por estado:', filterValue || 'todos');
    
    let visibleCount = 0;
    rows.forEach(row => {
        const rowStatus = row.getAttribute('data-status');
        const text = row.textContent.toLowerCase();
        
        // Aplicar ambos filtros: estado Y búsqueda
        const matchesStatus = !filterValue || rowStatus === filterValue;
        const matchesSearch = !searchTerm || text.includes(searchTerm);
        
        if (matchesStatus && matchesSearch) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    console.log(`Mostrando ${visibleCount} de ${rows.length} períodos`);
});

// Verificar estado del servidor al cargar
async function checkServerStatus() {
    try {
        const response = await fetch('/api/periods', {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok && response.status >= 500) {
            document.getElementById('serverStatus').classList.remove('hidden');
        }
    } catch (error) {
        console.error('Error al verificar servidor:', error);
        document.getElementById('serverStatus').classList.remove('hidden');
    }
}

// Ejecutar verificación al cargar
checkServerStatus();
</script>

</body>
</html>
