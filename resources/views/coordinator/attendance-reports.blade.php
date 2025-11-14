@extends('layouts.coordinator')

@section('title', 'Reportes de Asistencia')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Reportes de Asistencia</h1>
            <p class="text-gray-600 mt-1">Supervisa la asistencia de docentes y estudiantes de tu carrera</p>
        </div>
        <button onclick="exportReport()" class="px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary-dark">
            <i class="fas fa-file-export mr-2"></i>Exportar
        </button>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button onclick="showTab('teachers')" id="tab-teachers" class="tab-button active px-4 sm:px-6 py-3 text-sm font-medium border-b-2 border-brand-primary text-brand-primary">
                    <i class="fas fa-chalkboard-teacher mr-2"></i>
                    <span class="hidden sm:inline">Asistencia </span>Docentes
                </button>
                <button onclick="showTab('students')" id="tab-students" class="tab-button px-4 sm:px-6 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    <i class="fas fa-user-graduate mr-2"></i>
                    <span class="hidden sm:inline">Asistencia </span>Estudiantes
                </button>
            </nav>
        </div>

        <!-- Filtros -->
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Carrera</label>
                    <select id="filterCareer" onchange="loadReports()" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="sistemas">Ing. Sistemas</option>
                        <option value="informatica">Ing. Informática</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Periodo</label>
                    <select id="filterPeriod" onchange="loadReports()" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">Seleccionar...</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Desde</label>
                    <input type="date" id="dateFrom" onchange="loadReports()" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Hasta</label>
                    <input type="date" id="dateTo" onchange="loadReports()" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
            </div>
        </div>

        <!-- Contenido Docentes -->
        <div id="content-teachers" class="tab-content p-4 sm:p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600">Total Docentes</p>
                    <p class="text-2xl font-bold text-gray-900">12</p>
                </div>
                <div class="bg-green-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600">Asistencia Promedio</p>
                    <p class="text-2xl font-bold text-green-600">94%</p>
                </div>
                <div class="bg-yellow-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600">Con Alertas</p>
                    <p class="text-2xl font-bold text-yellow-600">2</p>
                </div>
                <div class="bg-red-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600">Faltas Totales</p>
                    <p class="text-2xl font-bold text-red-600">8</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Docente</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Clases</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Asistencias</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">Faltas</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">%</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">Dr. Juan Pérez</td>
                            <td class="px-4 py-3 text-center text-sm text-gray-600 hidden sm:table-cell">32</td>
                            <td class="px-4 py-3 text-center text-sm text-green-600 font-medium">30</td>
                            <td class="px-4 py-3 text-center text-sm text-red-600 font-medium hidden lg:table-cell">2</td>
                            <td class="px-4 py-3 text-center text-sm font-bold text-green-600">94%</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded">Excelente</span>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">Dra. María García</td>
                            <td class="px-4 py-3 text-center text-sm text-gray-600 hidden sm:table-cell">28</td>
                            <td class="px-4 py-3 text-center text-sm text-green-600 font-medium">26</td>
                            <td class="px-4 py-3 text-center text-sm text-red-600 font-medium hidden lg:table-cell">2</td>
                            <td class="px-4 py-3 text-center text-sm font-bold text-green-600">93%</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded">Excelente</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Contenido Estudiantes -->
        <div id="content-students" class="tab-content hidden p-4 sm:p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600">Total Estudiantes</p>
                    <p class="text-2xl font-bold text-gray-900">245</p>
                </div>
                <div class="bg-green-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600">Asistencia Promedio</p>
                    <p class="text-2xl font-bold text-green-600">88%</p>
                </div>
                <div class="bg-yellow-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600">En Riesgo</p>
                    <p class="text-2xl font-bold text-yellow-600">15</p>
                </div>
                <div class="bg-red-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600">Críticos</p>
                    <p class="text-2xl font-bold text-red-600">5</p>
                </div>
            </div>

            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-chart-bar text-4xl mb-4"></i>
                <p>Selecciona un grupo específico para ver el detalle de asistencia de estudiantes</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadPeriods();
    loadReports();
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

function loadReports() {
    // Cargar datos según filtros
    console.log('Cargando reportes...');
}

function showTab(tab) {
    // Ocultar todos los contenidos
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remover clase active de todos los botones
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-brand-primary', 'text-brand-primary');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Mostrar contenido seleccionado
    document.getElementById('content-' + tab).classList.remove('hidden');
    
    // Activar botón seleccionado
    const activeButton = document.getElementById('tab-' + tab);
    activeButton.classList.add('active', 'border-brand-primary', 'text-brand-primary');
    activeButton.classList.remove('border-transparent', 'text-gray-500');
}

function exportReport() {
    alert('Exportando reporte...');
}
</script>
@endsection
