@extends('layouts.admin')

@section('title', 'Asistencia por Docente')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Asistencia por Docente</h1>
            <p class="text-gray-600 mt-1">Visualiza y analiza la asistencia de cada docente</p>
        </div>
        <button onclick="exportReport()" class="px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary-dark transition-colors">
            <i class="fas fa-file-export mr-2"></i>Exportar Reporte
        </button>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Docente</label>
                <select id="teacherId" onchange="loadTeacherAttendance()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary">
                    <option value="">Seleccionar docente...</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Periodo Académico</label>
                <select id="periodId" onchange="loadTeacherAttendance()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary">
                    <option value="">Seleccionar periodo...</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Desde</label>
                <input type="date" id="dateFrom" onchange="loadTeacherAttendance()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Hasta</label>
                <input type="date" id="dateTo" onchange="loadTeacherAttendance()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary">
            </div>
        </div>
    </div>

    <!-- Información del Docente -->
    <div id="teacherInfo" class="hidden bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-start gap-6">
            <div class="w-20 h-20 bg-brand-primary rounded-full flex items-center justify-center text-white text-2xl font-bold">
                <span id="teacherInitials"></span>
            </div>
            <div class="flex-1">
                <h2 class="text-xl font-semibold text-gray-900" id="teacherName"></h2>
                <p class="text-gray-600" id="teacherEmail"></p>
                <div class="grid grid-cols-4 gap-4 mt-4">
                    <div>
                        <p class="text-sm text-gray-600">Total Clases</p>
                        <p class="text-2xl font-bold text-gray-900" id="totalClasses">0</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Asistencias</p>
                        <p class="text-2xl font-bold text-green-600" id="presentCount">0</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Faltas</p>
                        <p class="text-2xl font-bold text-red-600" id="absentCount">0</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">% Asistencia</p>
                        <p class="text-2xl font-bold text-brand-primary" id="attendancePercent">0%</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico de Asistencia -->
    <div id="chartSection" class="hidden bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Tendencia de Asistencia</h3>
        <canvas id="attendanceChart" height="80"></canvas>
    </div>

    <!-- Detalle por Materia -->
    <div id="subjectsSection" class="hidden bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Asistencia por Materia</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Materia</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Grupo</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Total Clases</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Asistencias</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Faltas</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">% Asistencia</th>
                    </tr>
                </thead>
                <tbody id="subjectsTable" class="divide-y divide-gray-200">
                </tbody>
            </table>
        </div>
    </div>

    <!-- Historial Detallado -->
    <div id="historySection" class="hidden bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Historial Detallado</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Materia</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Grupo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Horario</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aula</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
                    </tr>
                </thead>
                <tbody id="historyTable" class="divide-y divide-gray-200">
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let teachers = [];
let periods = [];
let attendanceChart = null;

document.addEventListener('DOMContentLoaded', function() {
    loadTeachers();
    loadPeriods();
});

async function loadTeachers() {
    try {
        const response = await fetch('/api/teachers');
        const data = await response.json();
        teachers = data.data || [];
        
        const select = document.getElementById('teacherId');
        select.innerHTML = '<option value="">Seleccionar docente...</option>' +
            teachers.map(t => `<option value="${t.id}">${t.name}</option>`).join('');
    } catch (error) {
        console.error('Error:', error);
    }
}

async function loadPeriods() {
    try {
        const response = await fetch('/api/academic-periods');
        const data = await response.json();
        periods = data.data || [];
        
        const select = document.getElementById('periodId');
        select.innerHTML = '<option value="">Seleccionar periodo...</option>' +
            periods.map(p => `<option value="${p.id}">${p.name}</option>`).join('');
    } catch (error) {
        console.error('Error:', error);
    }
}

async function loadTeacherAttendance() {
    const teacherId = document.getElementById('teacherId').value;
    
    if (!teacherId) {
        hideAllSections();
        return;
    }
    
    const teacher = teachers.find(t => t.id == teacherId);
    if (!teacher) return;
    
    // Mostrar información del docente
    showTeacherInfo(teacher);
    
    // Cargar datos de asistencia (simulado)
    const attendanceData = generateMockData();
    
    displayStats(attendanceData);
    displayChart(attendanceData);
    displaySubjects(attendanceData);
    displayHistory(attendanceData);
}

function showTeacherInfo(teacher) {
    document.getElementById('teacherInfo').classList.remove('hidden');
    document.getElementById('teacherName').textContent = teacher.name;
    document.getElementById('teacherEmail').textContent = teacher.email || 'Sin email';
    
    const initials = teacher.name.split(' ').map(n => n[0]).join('').substring(0, 2);
    document.getElementById('teacherInitials').textContent = initials;
}

function displayStats(data) {
    document.getElementById('totalClasses').textContent = data.total;
    document.getElementById('presentCount').textContent = data.present;
    document.getElementById('absentCount').textContent = data.absent;
    document.getElementById('attendancePercent').textContent = 
        Math.round((data.present / data.total) * 100) + '%';
}

function displayChart(data) {
    document.getElementById('chartSection').classList.remove('hidden');
    
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    
    if (attendanceChart) {
        attendanceChart.destroy();
    }
    
    attendanceChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4', 'Sem 5', 'Sem 6', 'Sem 7', 'Sem 8'],
            datasets: [{
                label: 'Asistencias',
                data: [95, 92, 88, 94, 90, 96, 93, 91],
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4
            }, {
                label: 'Faltas',
                data: [5, 8, 12, 6, 10, 4, 7, 9],
                borderColor: '#ef4444',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
}

function displaySubjects(data) {
    document.getElementById('subjectsSection').classList.remove('hidden');
    
    const tbody = document.getElementById('subjectsTable');
    tbody.innerHTML = `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 font-medium text-gray-900">Programación I</td>
            <td class="px-6 py-4 text-sm text-gray-600">Grupo A</td>
            <td class="px-6 py-4 text-center text-sm text-gray-600">32</td>
            <td class="px-6 py-4 text-center text-sm text-green-600 font-medium">30</td>
            <td class="px-6 py-4 text-center text-sm text-red-600 font-medium">2</td>
            <td class="px-6 py-4 text-center">
                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded">94%</span>
            </td>
        </tr>
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 font-medium text-gray-900">Base de Datos</td>
            <td class="px-6 py-4 text-sm text-gray-600">Grupo B</td>
            <td class="px-6 py-4 text-center text-sm text-gray-600">28</td>
            <td class="px-6 py-4 text-center text-sm text-green-600 font-medium">25</td>
            <td class="px-6 py-4 text-center text-sm text-red-600 font-medium">3</td>
            <td class="px-6 py-4 text-center">
                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-medium rounded">89%</span>
            </td>
        </tr>
    `;
}

function displayHistory(data) {
    document.getElementById('historySection').classList.remove('hidden');
    
    const tbody = document.getElementById('historyTable');
    tbody.innerHTML = `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 text-sm text-gray-600">14/11/2025</td>
            <td class="px-6 py-4 text-sm text-gray-900">Programación I</td>
            <td class="px-6 py-4 text-sm text-gray-600">Grupo A</td>
            <td class="px-6 py-4 text-sm text-gray-600">08:00 - 10:00</td>
            <td class="px-6 py-4 text-sm text-gray-600">Aula 301</td>
            <td class="px-6 py-4 text-center">
                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded">Presente</span>
            </td>
        </tr>
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 text-sm text-gray-600">13/11/2025</td>
            <td class="px-6 py-4 text-sm text-gray-900">Base de Datos</td>
            <td class="px-6 py-4 text-sm text-gray-600">Grupo B</td>
            <td class="px-6 py-4 text-sm text-gray-600">10:00 - 12:00</td>
            <td class="px-6 py-4 text-sm text-gray-600">Lab 102</td>
            <td class="px-6 py-4 text-center">
                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded">Presente</span>
            </td>
        </tr>
    `;
}

function generateMockData() {
    return {
        total: 60,
        present: 55,
        absent: 5
    };
}

function hideAllSections() {
    document.getElementById('teacherInfo').classList.add('hidden');
    document.getElementById('chartSection').classList.add('hidden');
    document.getElementById('subjectsSection').classList.add('hidden');
    document.getElementById('historySection').classList.add('hidden');
}

function exportReport() {
    const teacherId = document.getElementById('teacherId').value;
    if (!teacherId) {
        alert('Selecciona un docente primero');
        return;
    }
    alert('Exportando reporte...');
}
</script>
@endsection
