@extends('layouts.admin')

@section('title', 'Carga Horaria por Materia')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Carga Horaria por Materia</h1>
            <p class="text-gray-600 mt-1">Analiza la distribución de carga horaria por materia</p>
        </div>
        <button onclick="exportReport()" class="px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary-dark transition-colors">
            <i class="fas fa-file-export mr-2"></i>Exportar
        </button>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Periodo Académico</label>
                <select id="periodId" onchange="loadWorkload()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary">
                    <option value="">Seleccionar periodo...</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Carrera</label>
                <select id="careerId" onchange="loadWorkload()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary">
                    <option value="">Todas las carreras</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Semestre</label>
                <select id="semester" onchange="loadWorkload()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary">
                    <option value="">Todos los semestres</option>
                    <option value="1">1er Semestre</option>
                    <option value="2">2do Semestre</option>
                    <option value="3">3er Semestre</option>
                    <option value="4">4to Semestre</option>
                    <option value="5">5to Semestre</option>
                    <option value="6">6to Semestre</option>
                    <option value="7">7mo Semestre</option>
                    <option value="8">8vo Semestre</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                <input type="text" id="searchSubject" onkeyup="filterSubjects()" placeholder="Nombre de materia..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary">
            </div>
        </div>
    </div>

    <!-- Estadísticas Generales -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Materias</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900" id="totalSubjects">0</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-book text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Horas Totales</p>
                    <p class="text-2xl sm:text-3xl font-bold text-green-600" id="totalHours">0</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Docentes Asignados</p>
                    <p class="text-2xl sm:text-3xl font-bold text-purple-600" id="totalTeachers">0</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Promedio Horas</p>
                    <p class="text-2xl sm:text-3xl font-bold text-brand-primary" id="avgHours">0</p>
                </div>
                <div class="w-12 h-12 bg-brand-primary/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-bar text-brand-primary text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Top 10 Materias por Horas</h3>
            <canvas id="topSubjectsChart" height="250"></canvas>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Distribución por Semestre</h3>
            <canvas id="semesterDistChart" height="250"></canvas>
        </div>
    </div>

    <!-- Tabla Detallada -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Detalle por Materia</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Materia</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Código</th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Grupos</th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Docentes</th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Horas/Sem</th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">Total Horas</th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody id="subjectsTable" class="divide-y divide-gray-200">
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let subjects = [];
let periods = [];
let topChart = null;
let distChart = null;

document.addEventListener('DOMContentLoaded', function() {
    loadPeriods();
    loadWorkload();
});

async function loadPeriods() {
    try {
        const response = await fetch('/api/periods');
        const data = await response.json();
        periods = data.data || [];
        
        const select = document.getElementById('periodId');
        select.innerHTML = '<option value="">Seleccionar periodo...</option>' +
            periods.map(p => `<option value="${p.id}" ${p.is_active ? 'selected' : ''}>${p.name}</option>`).join('');
    } catch (error) {
        console.error('Error:', error);
    }
}

async function loadWorkload() {
    // Datos simulados - reemplazar con API real
    subjects = [
        { id: 1, name: 'Programación I', code: 'INF-111', groups: 3, teachers: 2, hoursPerWeek: 6, totalHours: 96, semester: 1 },
        { id: 2, name: 'Cálculo I', code: 'MAT-101', groups: 4, teachers: 3, hoursPerWeek: 5, totalHours: 80, semester: 1 },
        { id: 3, name: 'Base de Datos', code: 'INF-211', groups: 2, teachers: 2, hoursPerWeek: 4, totalHours: 64, semester: 3 },
        { id: 4, name: 'Algoritmos', code: 'INF-121', groups: 3, teachers: 2, hoursPerWeek: 5, totalHours: 80, semester: 2 },
        { id: 5, name: 'Redes I', code: 'INF-311', groups: 2, teachers: 1, hoursPerWeek: 4, totalHours: 64, semester: 5 },
        { id: 6, name: 'Ingeniería Software', code: 'INF-321', groups: 2, teachers: 2, hoursPerWeek: 6, totalHours: 96, semester: 5 },
        { id: 7, name: 'Sistemas Operativos', code: 'INF-221', groups: 2, teachers: 1, hoursPerWeek: 4, totalHours: 64, semester: 4 },
        { id: 8, name: 'Inteligencia Artificial', code: 'INF-411', groups: 1, teachers: 1, hoursPerWeek: 4, totalHours: 64, semester: 7 }
    ];
    
    updateStats();
    displayCharts();
    displayTable(subjects);
}

function updateStats() {
    const totalSubjects = subjects.length;
    const totalHours = subjects.reduce((sum, s) => sum + s.totalHours, 0);
    const uniqueTeachers = new Set(subjects.flatMap(s => Array(s.teachers).fill(s.id))).size;
    const avgHours = totalSubjects > 0 ? Math.round(totalHours / totalSubjects) : 0;
    
    document.getElementById('totalSubjects').textContent = totalSubjects;
    document.getElementById('totalHours').textContent = totalHours;
    document.getElementById('totalTeachers').textContent = uniqueTeachers;
    document.getElementById('avgHours').textContent = avgHours;
}

function displayCharts() {
    // Top 10 Materias
    const topSubjects = [...subjects].sort((a, b) => b.totalHours - a.totalHours).slice(0, 10);
    const topCtx = document.getElementById('topSubjectsChart').getContext('2d');
    
    if (topChart) topChart.destroy();
    
    topChart = new Chart(topCtx, {
        type: 'bar',
        data: {
            labels: topSubjects.map(s => s.name),
            datasets: [{
                label: 'Horas Totales',
                data: topSubjects.map(s => s.totalHours),
                backgroundColor: '#881F34',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
    
    // Distribución por Semestre
    const semesterData = {};
    subjects.forEach(s => {
        semesterData[s.semester] = (semesterData[s.semester] || 0) + s.totalHours;
    });
    
    const distCtx = document.getElementById('semesterDistChart').getContext('2d');
    
    if (distChart) distChart.destroy();
    
    distChart = new Chart(distCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(semesterData).map(s => `Semestre ${s}`),
            datasets: [{
                data: Object.values(semesterData),
                backgroundColor: [
                    '#3b82f6', '#10b981', '#f59e0b', '#ef4444',
                    '#8b5cf6', '#ec4899', '#14b8a6', '#f97316'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
}

function displayTable(data) {
    const tbody = document.getElementById('subjectsTable');
    
    if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-8 text-center text-gray-500">No hay datos disponibles</td></tr>';
        return;
    }
    
    tbody.innerHTML = data.map(subject => `
        <tr class="hover:bg-gray-50">
            <td class="px-4 sm:px-6 py-4">
                <div class="font-medium text-gray-900">${subject.name}</div>
                <div class="text-sm text-gray-500 sm:hidden">${subject.code}</div>
            </td>
            <td class="px-4 sm:px-6 py-4 text-sm text-gray-600 hidden sm:table-cell">${subject.code}</td>
            <td class="px-4 sm:px-6 py-4 text-center">
                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded">${subject.groups}</span>
            </td>
            <td class="px-4 sm:px-6 py-4 text-center">
                <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs font-medium rounded">${subject.teachers}</span>
            </td>
            <td class="px-4 sm:px-6 py-4 text-center font-semibold text-gray-900">${subject.hoursPerWeek}h</td>
            <td class="px-4 sm:px-6 py-4 text-center font-semibold text-brand-primary hidden lg:table-cell">${subject.totalHours}h</td>
            <td class="px-4 sm:px-6 py-4 text-center">
                <button onclick="viewDetails(${subject.id})" class="text-brand-primary hover:text-brand-primary-dark" title="Ver detalles">
                    <i class="fas fa-eye"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

function filterSubjects() {
    const search = document.getElementById('searchSubject').value.toLowerCase();
    const semester = document.getElementById('semester').value;
    
    let filtered = subjects;
    
    if (search) {
        filtered = filtered.filter(s => 
            s.name.toLowerCase().includes(search) ||
            s.code.toLowerCase().includes(search)
        );
    }
    
    if (semester) {
        filtered = filtered.filter(s => s.semester == semester);
    }
    
    displayTable(filtered);
}

function viewDetails(id) {
    const subject = subjects.find(s => s.id === id);
    if (subject) {
        alert(`Detalles de ${subject.name}:\n\nCódigo: ${subject.code}\nGrupos: ${subject.groups}\nDocentes: ${subject.teachers}\nHoras/Semana: ${subject.hoursPerWeek}\nTotal Horas: ${subject.totalHours}`);
    }
}

function exportReport() {
    alert('Exportando reporte de carga horaria por materia...');
}
</script>
@endsection
