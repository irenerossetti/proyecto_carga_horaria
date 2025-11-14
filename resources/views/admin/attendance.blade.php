<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registro de Asistencia - FICCT SGA</title>
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
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Registro de Asistencia</h1>
                <p class="text-gray-500 mt-1">Gestiona el registro de asistencia de docentes</p>
            </div>
            <button onclick="openAttendanceModal()" class="px-6 py-3 bg-brand-primary hover:bg-brand-hover text-white rounded-lg font-medium transition-colors shadow-sm">
                + Registrar Asistencia
            </button>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Asistencias Hoy</p>
                        <p id="todayAttendance" class="text-2xl font-bold text-gray-900">0</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-red-100 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Ausencias Hoy</p>
                        <p id="todayAbsences" class="text-2xl font-bold text-gray-900">0</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Tardanzas</p>
                        <p id="todayLate" class="text-2xl font-bold text-gray-900">0</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">% Asistencia</p>
                        <p id="attendanceRate" class="text-2xl font-bold text-gray-900">0%</p>
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
                        <option value="">Todos los docentes</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Materia</label>
                    <select id="subjectFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        <option value="">Todas las materias</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <select id="statusFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        <option value="">Todos</option>
                        <option value="present">Presente</option>
                        <option value="absent">Ausente</option>
                        <option value="late">Tardanza</option>
                        <option value="justified">Justificado</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button onclick="applyFilters()" class="w-full px-4 py-2 bg-brand-primary hover:bg-brand-hover text-white rounded-lg font-medium transition-colors">
                        Buscar
                    </button>
                </div>
            </div>
        </div>

        <!-- Attendance Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Registros de Asistencia</h2>
                <button onclick="exportAttendance()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                    📊 Exportar Excel
                </button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Docente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Materia</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grupo</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Hora</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Observaciones</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="attendanceTable" class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-brand-primary mx-auto"></div>
                                <p class="mt-4 text-gray-500">Cargando registros...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Modal para Registrar Asistencia -->
<div id="attendanceModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 id="attendanceModalTitle" class="text-xl font-bold text-gray-900">Registrar Asistencia</h3>
                <button onclick="closeAttendanceModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <form id="attendanceForm" class="p-6 space-y-6">
            <input type="hidden" id="attendanceId">
            
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Docente *</label>
                    <select id="attendanceTeacher" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        <option value="">Seleccionar docente</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha *</label>
                    <input type="date" id="attendanceDate" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Materia *</label>
                    <select id="attendanceSubject" required onchange="loadGroupsBySubject()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        <option value="">Seleccionar materia</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Grupo *</label>
                    <select id="attendanceGroup" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        <option value="">Seleccionar grupo</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Hora Inicio *</label>
                    <input type="time" id="attendanceStartTime" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Hora Fin *</label>
                    <input type="time" id="attendanceEndTime" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Hora Registro</label>
                    <input type="time" id="attendanceCheckTime" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent" readonly>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Estado *</label>
                <div class="grid grid-cols-4 gap-4">
                    <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-brand-primary transition-colors">
                        <input type="radio" name="attendanceStatus" value="present" required class="w-4 h-4 text-brand-primary border-gray-300 focus:ring-brand-primary">
                        <span class="ml-3 text-sm font-medium text-gray-700">Presente</span>
                    </label>
                    <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-brand-primary transition-colors">
                        <input type="radio" name="attendanceStatus" value="absent" class="w-4 h-4 text-brand-primary border-gray-300 focus:ring-brand-primary">
                        <span class="ml-3 text-sm font-medium text-gray-700">Ausente</span>
                    </label>
                    <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-brand-primary transition-colors">
                        <input type="radio" name="attendanceStatus" value="late" class="w-4 h-4 text-brand-primary border-gray-300 focus:ring-brand-primary">
                        <span class="ml-3 text-sm font-medium text-gray-700">Tardanza</span>
                    </label>
                    <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-brand-primary transition-colors">
                        <input type="radio" name="attendanceStatus" value="justified" class="w-4 h-4 text-brand-primary border-gray-300 focus:ring-brand-primary">
                        <span class="ml-3 text-sm font-medium text-gray-700">Justificado</span>
                    </label>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Observaciones</label>
                <textarea id="attendanceNotes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent" placeholder="Notas adicionales..."></textarea>
            </div>
            
            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-blue-900">Información</h4>
                        <p class="text-sm text-blue-700 mt-1">
                            El registro de asistencia se guardará automáticamente con la hora actual. 
                            Si el docente llega tarde, el sistema lo detectará automáticamente.
                        </p>
                    </div>
                </div>
            </div>
        </form>

        <div class="p-6 pt-0">
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeAttendanceModal()" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                    Cancelar
                </button>
                <button type="button" onclick="document.getElementById('attendanceForm').requestSubmit()" class="px-6 py-2 bg-brand-primary hover:bg-brand-hover text-white rounded-lg font-medium transition-colors">
                    Guardar Asistencia
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const API_BASE = '/api';
let allAttendances = [];
let filteredAttendances = [];
let teachers = [];
let subjects = [];
let groups = [];

function showNotification(message, type = 'success') {
    const alertClass = type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800';
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg border ${alertClass} z-50 shadow-lg`;
    notification.innerHTML = `<span class="font-medium">${message}</span>`;
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
}

async function loadAttendances() {
    try {
        const response = await fetch(`${API_BASE}/attendances`, {
            headers: { 'Accept': 'application/json' }
        });
        
        if (response.ok) {
            allAttendances = await response.json();
        } else {
            // Datos de prueba
            allAttendances = [
                {
                    id: 1,
                    date: '2025-11-14',
                    teacher_name: 'Dr. Juan Pérez García',
                    subject_name: 'Programación I',
                    group_name: 'Grupo A',
                    start_time: '08:00',
                    end_time: '10:00',
                    check_time: '08:05',
                    status: 'late',
                    notes: 'Llegó 5 minutos tarde'
                },
                {
                    id: 2,
                    date: '2025-11-14',
                    teacher_name: 'Ing. María López Silva',
                    subject_name: 'Base de Datos',
                    group_name: 'Grupo B',
                    start_time: '10:00',
                    end_time: '12:00',
                    check_time: '09:55',
                    status: 'present',
                    notes: ''
                },
                {
                    id: 3,
                    date: '2025-11-14',
                    teacher_name: 'Lic. Carlos Rodríguez',
                    subject_name: 'Cálculo I',
                    group_name: 'Grupo A',
                    start_time: '14:00',
                    end_time: '16:00',
                    check_time: null,
                    status: 'absent',
                    notes: 'No se presentó'
                }
            ];
        }
        
        filteredAttendances = [...allAttendances];
        renderAttendances();
        updateStats();
    } catch (error) {
        console.error('Error:', error);
        allAttendances = [];
        filteredAttendances = [];
        renderAttendances();
    }
}

async function loadTeachers() {
    try {
        const response = await fetch(`${API_BASE}/teachers`, {
            headers: { 'Accept': 'application/json' }
        });
        
        if (response.ok) {
            teachers = await response.json();
        } else {
            teachers = [
                { id: 1, name: 'Dr. Juan Pérez García' },
                { id: 2, name: 'Ing. María López Silva' },
                { id: 3, name: 'Lic. Carlos Rodríguez' }
            ];
        }
        
        populateTeacherSelects();
    } catch (error) {
        console.error('Error loading teachers:', error);
    }
}

async function loadSubjects() {
    try {
        const response = await fetch(`${API_BASE}/subjects`, {
            headers: { 'Accept': 'application/json' }
        });
        
        if (response.ok) {
            subjects = await response.json();
        } else {
            subjects = [
                { id: 1, name: 'Programación I', code: 'INF-101' },
                { id: 2, name: 'Base de Datos', code: 'INF-201' },
                { id: 3, name: 'Cálculo I', code: 'MAT-101' }
            ];
        }
        
        populateSubjectSelects();
    } catch (error) {
        console.error('Error loading subjects:', error);
    }
}

function populateTeacherSelects() {
    const teacherFilterSelect = document.getElementById('teacherFilter');
    const attendanceTeacherSelect = document.getElementById('attendanceTeacher');
    
    const teacherOptions = teachers.map(t => 
        `<option value="${t.id}">${t.name}</option>`
    ).join('');
    
    teacherFilterSelect.innerHTML = '<option value="">Todos los docentes</option>' + teacherOptions;
    attendanceTeacherSelect.innerHTML = '<option value="">Seleccionar docente</option>' + teacherOptions;
}

function populateSubjectSelects() {
    const subjectFilterSelect = document.getElementById('subjectFilter');
    const attendanceSubjectSelect = document.getElementById('attendanceSubject');
    
    const subjectOptions = subjects.map(s => 
        `<option value="${s.id}">${s.code} - ${s.name}</option>`
    ).join('');
    
    subjectFilterSelect.innerHTML = '<option value="">Todas las materias</option>' + subjectOptions;
    attendanceSubjectSelect.innerHTML = '<option value="">Seleccionar materia</option>' + subjectOptions;
}

async function loadGroupsBySubject() {
    const subjectId = document.getElementById('attendanceSubject').value;
    
    if (!subjectId) {
        document.getElementById('attendanceGroup').innerHTML = '<option value="">Seleccionar grupo</option>';
        return;
    }
    
    try {
        const response = await fetch(`${API_BASE}/groups?subject_id=${subjectId}`, {
            headers: { 'Accept': 'application/json' }
        });
        
        if (response.ok) {
            groups = await response.json();
        } else {
            groups = [
                { id: 1, name: 'Grupo A' },
                { id: 2, name: 'Grupo B' }
            ];
        }
        
        const groupSelect = document.getElementById('attendanceGroup');
        groupSelect.innerHTML = '<option value="">Seleccionar grupo</option>' + 
            groups.map(g => `<option value="${g.id}">${g.name}</option>`).join('');
    } catch (error) {
        console.error('Error loading groups:', error);
    }
}

function renderAttendances() {
    const tbody = document.getElementById('attendanceTable');
    
    if (filteredAttendances.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                    No se encontraron registros de asistencia
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = filteredAttendances.map(attendance => {
        const statusConfig = {
            present: { color: 'bg-green-100 text-green-800', label: 'Presente', icon: '✓' },
            absent: { color: 'bg-red-100 text-red-800', label: 'Ausente', icon: '✗' },
            late: { color: 'bg-yellow-100 text-yellow-800', label: 'Tardanza', icon: '⏰' },
            justified: { color: 'bg-blue-100 text-blue-800', label: 'Justificado', icon: '📝' }
        };
        
        const status = statusConfig[attendance.status] || statusConfig.absent;
        
        return `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${new Date(attendance.date).toLocaleDateString('es-ES')}
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">
                    ${attendance.teacher_name}
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">
                    ${attendance.subject_name}
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">
                    ${attendance.group_name}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                    <div>${attendance.start_time} - ${attendance.end_time}</div>
                    ${attendance.check_time ? `<div class="text-xs text-gray-500">Registro: ${attendance.check_time}</div>` : ''}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <span class="px-3 py-1 rounded-full text-sm font-medium ${status.color}">
                        ${status.icon} ${status.label}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    ${attendance.notes || '-'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right">
                    <div class="flex items-center justify-end gap-2">
                        <button onclick="editAttendance(${attendance.id})" class="text-blue-600 hover:text-blue-900" title="Editar">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button onclick="deleteAttendance(${attendance.id})" class="text-red-600 hover:text-red-900" title="Eliminar">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

function updateStats() {
    const today = new Date().toISOString().split('T')[0];
    const todayAttendances = allAttendances.filter(a => a.date === today);
    
    const present = todayAttendances.filter(a => a.status === 'present').length;
    const absent = todayAttendances.filter(a => a.status === 'absent').length;
    const late = todayAttendances.filter(a => a.status === 'late').length;
    const total = todayAttendances.length;
    
    document.getElementById('todayAttendance').textContent = present;
    document.getElementById('todayAbsences').textContent = absent;
    document.getElementById('todayLate').textContent = late;
    
    const rate = total > 0 ? Math.round(((present + late) / total) * 100) : 0;
    document.getElementById('attendanceRate').textContent = rate + '%';
}

function openAttendanceModal() {
    document.getElementById('attendanceModalTitle').textContent = 'Registrar Asistencia';
    document.getElementById('attendanceForm').reset();
    document.getElementById('attendanceId').value = '';
    
    // Set current date and time
    const now = new Date();
    document.getElementById('attendanceDate').valueAsDate = now;
    document.getElementById('attendanceCheckTime').value = now.toTimeString().slice(0, 5);
    
    // Set default status to present
    document.querySelector('input[name="attendanceStatus"][value="present"]').checked = true;
    
    document.getElementById('attendanceModal').classList.remove('hidden');
    document.getElementById('attendanceModal').classList.add('flex');
}

function closeAttendanceModal() {
    document.getElementById('attendanceModal').classList.add('hidden');
    document.getElementById('attendanceModal').classList.remove('flex');
}

function editAttendance(id) {
    const attendance = allAttendances.find(a => a.id === id);
    if (!attendance) {
        showNotification('❌ Registro no encontrado', 'error');
        return;
    }
    
    document.getElementById('attendanceModalTitle').textContent = 'Editar Asistencia';
    document.getElementById('attendanceId').value = attendance.id;
    document.getElementById('attendanceTeacher').value = attendance.teacher_id;
    document.getElementById('attendanceDate').value = attendance.date;
    document.getElementById('attendanceSubject').value = attendance.subject_id;
    document.getElementById('attendanceGroup').value = attendance.group_id;
    document.getElementById('attendanceStartTime').value = attendance.start_time;
    document.getElementById('attendanceEndTime').value = attendance.end_time;
    document.getElementById('attendanceCheckTime').value = attendance.check_time || '';
    document.querySelector(`input[name="attendanceStatus"][value="${attendance.status}"]`).checked = true;
    document.getElementById('attendanceNotes').value = attendance.notes || '';
    
    document.getElementById('attendanceModal').classList.remove('hidden');
    document.getElementById('attendanceModal').classList.add('flex');
}

async function deleteAttendance(id) {
    if (!confirm('¿Estás seguro de eliminar este registro de asistencia?')) return;
    
    try {
        const response = await fetch(`${API_BASE}/attendances/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (!response.ok) {
            throw new Error(result.message || 'Error al eliminar');
        }
        
        showNotification(result.message || '✅ Registro eliminado exitosamente');
        loadAttendances();
    } catch (error) {
        console.error('Error:', error);
        showNotification('❌ ' + error.message, 'error');
    }
}

// Form submission
document.getElementById('attendanceForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const attendanceId = document.getElementById('attendanceId').value;
    const data = {
        teacher_id: parseInt(document.getElementById('attendanceTeacher').value),
        date: document.getElementById('attendanceDate').value,
        subject_id: parseInt(document.getElementById('attendanceSubject').value),
        group_id: parseInt(document.getElementById('attendanceGroup').value),
        start_time: document.getElementById('attendanceStartTime').value,
        end_time: document.getElementById('attendanceEndTime').value,
        check_time: document.getElementById('attendanceCheckTime').value,
        status: document.querySelector('input[name="attendanceStatus"]:checked').value,
        notes: document.getElementById('attendanceNotes').value
    };
    
    try {
        const url = attendanceId ? `${API_BASE}/attendances/${attendanceId}` : `${API_BASE}/attendances`;
        const method = attendanceId ? 'PATCH' : 'POST';
        
        const response = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (!response.ok) {
            throw new Error(result.message || 'Error al guardar');
        }
        
        showNotification(result.message || '✅ Asistencia registrada exitosamente');
        closeAttendanceModal();
        loadAttendances();
    } catch (error) {
        console.error('Error:', error);
        showNotification('❌ ' + error.message, 'error');
    }
});

function applyFilters() {
    const date = document.getElementById('dateFilter').value;
    const teacherId = document.getElementById('teacherFilter').value;
    const subjectId = document.getElementById('subjectFilter').value;
    const status = document.getElementById('statusFilter').value;
    
    filteredAttendances = allAttendances.filter(attendance => {
        const matchesDate = !date || attendance.date === date;
        const matchesTeacher = !teacherId || attendance.teacher_id == teacherId;
        const matchesSubject = !subjectId || attendance.subject_id == subjectId;
        const matchesStatus = !status || attendance.status === status;
        
        return matchesDate && matchesTeacher && matchesSubject && matchesStatus;
    });
    
    renderAttendances();
}

function exportAttendance() {
    showNotification('📊 Generando reporte de asistencia...');
    window.open(`${API_BASE}/reports/attendances?format=excel`, '_blank');
}

// Set today's date as default
document.getElementById('dateFilter').valueAsDate = new Date();

// Load initial data
Promise.all([loadAttendances(), loadTeachers(), loadSubjects()]);
</script>

</body>
</html>
