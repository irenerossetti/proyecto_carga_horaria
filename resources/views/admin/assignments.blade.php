<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Asignación de Carga Horaria - FICCT SGA</title>
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
                <h1 class="text-3xl font-bold text-gray-900">Asignación de Carga Horaria</h1>
                <p class="text-gray-500 mt-1">Asigna materias y grupos a los docentes para el período activo</p>
            </div>
            <button onclick="openAssignmentModal()" class="px-6 py-3 bg-brand-primary hover:bg-brand-hover text-white rounded-lg font-medium transition-colors shadow-sm">
                + Nueva Asignación
            </button>
        </div>

        <!-- Period Info -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-blue-900">Período Activo</h3>
                        <p id="activePeriod" class="text-blue-700">Gestión 2-2025</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-blue-600">Total Asignaciones</p>
                    <p id="totalAssignments" class="text-2xl font-bold text-blue-900">0</p>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Docentes Asignados</p>
                        <p id="assignedTeachers" class="text-2xl font-bold text-gray-900">0</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Materias Asignadas</p>
                        <p id="assignedSubjects" class="text-2xl font-bold text-gray-900">0</p>
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
                        <p class="text-sm font-medium text-gray-500">Horas Totales</p>
                        <p id="totalHours" class="text-2xl font-bold text-gray-900">0</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Promedio Horas/Docente</p>
                        <p id="avgHours" class="text-2xl font-bold text-gray-900">0</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Buscar Docente</label>
                    <input type="text" id="searchInput" placeholder="Nombre del docente..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Materia</label>
                    <select id="subjectFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        <option value="">Todas las materias</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Carga Horaria</label>
                    <select id="workloadFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        <option value="">Todas</option>
                        <option value="low">Baja (1-10 hrs)</option>
                        <option value="medium">Media (11-20 hrs)</option>
                        <option value="high">Alta (21+ hrs)</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button onclick="clearFilters()" class="w-full px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                        Limpiar Filtros
                    </button>
                </div>
            </div>
        </div>

        <!-- Assignments Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Asignaciones de Carga Horaria</h2>
                    <div class="flex gap-2">
                        <button onclick="exportAssignments()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                            📊 Exportar
                        </button>
                        <button onclick="bulkAssign()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                            ⚡ Asignación Masiva
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Docente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Materia</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grupo</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Horas</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="assignmentsTable" class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-brand-primary mx-auto"></div>
                                <p class="mt-4 text-gray-500">Cargando asignaciones...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Modal para Asignación -->
<div id="assignmentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 id="assignmentModalTitle" class="text-xl font-bold text-gray-900">Nueva Asignación</h3>
                <button onclick="closeAssignmentModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <form id="assignmentForm" class="p-6 space-y-6">
            <input type="hidden" id="assignmentId">
            
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Docente *</label>
                    <select id="teacherSelect" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        <option value="">Seleccionar docente</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Materia *</label>
                    <select id="subjectSelect" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        <option value="">Seleccionar materia</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Grupo *</label>
                    <select id="groupSelect" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        <option value="">Seleccionar grupo</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Asignación</label>
                    <select id="assignmentType" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        <option value="theory">Teoría</option>
                        <option value="practice">Práctica</option>
                        <option value="both">Teoría y Práctica</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Horas Semanales *</label>
                    <input type="number" id="weeklyHours" required min="1" max="20" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent" placeholder="6">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Inicio</label>
                    <input type="date" id="startDate" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Fin</label>
                    <input type="date" id="endDate" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Observaciones</label>
                <textarea id="assignmentNotes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent" placeholder="Observaciones adicionales..."></textarea>
            </div>
            
            <!-- Teacher Workload Summary -->
            <div id="workloadSummary" class="p-4 bg-gray-50 rounded-lg hidden">
                <h4 class="font-medium text-gray-900 mb-2">Resumen de Carga Horaria del Docente</h4>
                <div class="grid grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Horas Actuales:</span>
                        <span id="currentHours" class="font-medium ml-2">0</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Nuevas Horas:</span>
                        <span id="newHours" class="font-medium ml-2">0</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Total:</span>
                        <span id="totalTeacherHours" class="font-medium ml-2">0</span>
                    </div>
                </div>
                <div id="workloadWarning" class="mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded text-sm text-yellow-800 hidden">
                    ⚠️ El docente excederá las 20 horas semanales recomendadas
                </div>
            </div>
        </form>

        <div class="p-6 pt-0">
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeAssignmentModal()" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                    Cancelar
                </button>
                <button type="button" onclick="document.getElementById('assignmentForm').requestSubmit()" class="px-6 py-2 bg-brand-primary hover:bg-brand-hover text-white rounded-lg font-medium transition-colors">
                    Guardar Asignación
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const API_BASE = '/api';
let allAssignments = [];
let filteredAssignments = [];
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

async function loadAssignments() {
    try {
        const response = await fetch(`${API_BASE}/assignments`, {
            headers: { 'Accept': 'application/json' }
        });
        
        if (!response.ok) throw new Error('Error al cargar asignaciones');
        
        allAssignments = await response.json();
        filteredAssignments = [...allAssignments];
        renderAssignments();
        updateStats();
    } catch (error) {
        console.error('Error:', error);
        // Datos de prueba
        allAssignments = [
            { 
                id: 1, 
                teacher_name: 'Dr. Juan Pérez', 
                subject_name: 'Introducción a la Programación', 
                group_name: 'Grupo A', 
                weekly_hours: 6, 
                assignment_type: 'both',
                status: 'active',
                start_date: '2025-02-01',
                end_date: '2025-06-30'
            },
            { 
                id: 2, 
                teacher_name: 'Ing. María García', 
                subject_name: 'Base de Datos', 
                group_name: 'Grupo B', 
                weekly_hours: 4, 
                assignment_type: 'theory',
                status: 'active',
                start_date: '2025-02-01',
                end_date: '2025-06-30'
            },
        ];
        filteredAssignments = [...allAssignments];
        renderAssignments();
        updateStats();
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
                { id: 1, name: 'Dr. Juan Pérez', email: 'juan.perez@ficct.edu.bo' },
                { id: 2, name: 'Ing. María García', email: 'maria.garcia@ficct.edu.bo' },
                { id: 3, name: 'Lic. Carlos López', email: 'carlos.lopez@ficct.edu.bo' },
            ];
        }
        
        populateTeacherSelect();
    } catch (error) {
        console.error('Error loading teachers:', error);
        teachers = [
            { id: 1, name: 'Dr. Juan Pérez', email: 'juan.perez@ficct.edu.bo' },
            { id: 2, name: 'Ing. María García', email: 'maria.garcia@ficct.edu.bo' },
            { id: 3, name: 'Lic. Carlos López', email: 'carlos.lopez@ficct.edu.bo' },
        ];
        populateTeacherSelect();
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
                { id: 1, name: 'Introducción a la Programación', code: 'INF-101', theoretical_hours: 4, practical_hours: 2 },
                { id: 2, name: 'Base de Datos', code: 'INF-201', theoretical_hours: 3, practical_hours: 3 },
                { id: 3, name: 'Cálculo I', code: 'MAT-101', theoretical_hours: 4, practical_hours: 0 },
            ];
        }
        
        populateSubjectSelects();
    } catch (error) {
        console.error('Error loading subjects:', error);
        subjects = [
            { id: 1, name: 'Introducción a la Programación', code: 'INF-101', theoretical_hours: 4, practical_hours: 2 },
            { id: 2, name: 'Base de Datos', code: 'INF-201', theoretical_hours: 3, practical_hours: 3 },
            { id: 3, name: 'Cálculo I', code: 'MAT-101', theoretical_hours: 4, practical_hours: 0 },
        ];
        populateSubjectSelects();
    }
}

async function loadGroups() {
    try {
        const response = await fetch(`${API_BASE}/groups`, {
            headers: { 'Accept': 'application/json' }
        });
        
        if (response.ok) {
            groups = await response.json();
        } else {
            groups = [
                { id: 1, name: 'Grupo A', subject_id: 1, subject_name: 'Introducción a la Programación' },
                { id: 2, name: 'Grupo B', subject_id: 1, subject_name: 'Introducción a la Programación' },
                { id: 3, name: 'Grupo A', subject_id: 2, subject_name: 'Base de Datos' },
            ];
        }
    } catch (error) {
        console.error('Error loading groups:', error);
        groups = [
            { id: 1, name: 'Grupo A', subject_id: 1, subject_name: 'Introducción a la Programación' },
            { id: 2, name: 'Grupo B', subject_id: 1, subject_name: 'Introducción a la Programación' },
            { id: 3, name: 'Grupo A', subject_id: 2, subject_name: 'Base de Datos' },
        ];
    }
}

function populateTeacherSelect() {
    const teacherSelect = document.getElementById('teacherSelect');
    
    const teacherOptions = teachers.map(teacher => 
        `<option value="${teacher.id}">${teacher.name}</option>`
    ).join('');
    
    teacherSelect.innerHTML = '<option value="">Seleccionar docente</option>' + teacherOptions;
}

function populateSubjectSelects() {
    const subjectSelect = document.getElementById('subjectSelect');
    const subjectFilter = document.getElementById('subjectFilter');
    
    const subjectOptions = subjects.map(subject => 
        `<option value="${subject.id}">${subject.code} - ${subject.name}</option>`
    ).join('');
    
    subjectSelect.innerHTML = '<option value="">Seleccionar materia</option>' + subjectOptions;
    subjectFilter.innerHTML = '<option value="">Todas las materias</option>' + subjectOptions;
}

function updateGroupSelect() {
    const subjectId = document.getElementById('subjectSelect').value;
    const groupSelect = document.getElementById('groupSelect');
    
    if (!subjectId) {
        groupSelect.innerHTML = '<option value="">Seleccionar grupo</option>';
        return;
    }
    
    const filteredGroups = groups.filter(group => group.subject_id == subjectId);
    const groupOptions = filteredGroups.map(group => 
        `<option value="${group.id}">${group.name}</option>`
    ).join('');
    
    groupSelect.innerHTML = '<option value="">Seleccionar grupo</option>' + groupOptions;
}

function renderAssignments() {
    const tbody = document.getElementById('assignmentsTable');
    
    if (filteredAssignments.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                    No se encontraron asignaciones
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = filteredAssignments.map(assignment => {
        const typeLabels = {
            theory: 'Teoría',
            practice: 'Práctica',
            both: 'Teoría y Práctica'
        };
        
        return `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <div class="text-sm font-medium text-gray-900">${assignment.teacher_name}</div>
                    <div class="text-sm text-gray-500">${typeLabels[assignment.assignment_type]}</div>
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm font-medium text-gray-900">${assignment.subject_name}</div>
                    <div class="text-sm text-gray-500">${assignment.start_date} - ${assignment.end_date}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                        ${assignment.group_name}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <div class="text-sm font-medium text-gray-900">${assignment.weekly_hours}h</div>
                    <div class="text-xs text-gray-500">semanales</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <span class="px-3 py-1 rounded-full text-sm font-medium ${assignment.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                        ${assignment.status === 'active' ? 'Activa' : 'Inactiva'}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right">
                    <div class="flex items-center justify-end gap-2">
                        <button onclick="editAssignment(${assignment.id})" class="text-blue-600 hover:text-blue-900" title="Editar">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button onclick="deleteAssignment(${assignment.id})" class="text-red-600 hover:text-red-900" title="Eliminar">
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
    const uniqueTeachers = new Set(allAssignments.map(a => a.teacher_name)).size;
    const uniqueSubjects = new Set(allAssignments.map(a => a.subject_name)).size;
    const totalHours = allAssignments.reduce((sum, a) => sum + (a.weekly_hours || 0), 0);
    const avgHours = uniqueTeachers > 0 ? Math.round(totalHours / uniqueTeachers) : 0;
    
    document.getElementById('totalAssignments').textContent = allAssignments.length;
    document.getElementById('assignedTeachers').textContent = uniqueTeachers;
    document.getElementById('assignedSubjects').textContent = uniqueSubjects;
    document.getElementById('totalHours').textContent = totalHours;
    document.getElementById('avgHours').textContent = avgHours;
}

function openAssignmentModal() {
    document.getElementById('assignmentModalTitle').textContent = 'Nueva Asignación';
    document.getElementById('assignmentForm').reset();
    document.getElementById('assignmentId').value = '';
    document.getElementById('workloadSummary').classList.add('hidden');
    document.getElementById('assignmentModal').classList.remove('hidden');
    document.getElementById('assignmentModal').classList.add('flex');
}

function closeAssignmentModal() {
    document.getElementById('assignmentModal').classList.add('hidden');
    document.getElementById('assignmentModal').classList.remove('flex');
}

function editAssignment(id) {
    const assignment = allAssignments.find(a => a.id === id);
    if (!assignment) {
        showNotification('❌ Asignación no encontrada', 'error');
        return;
    }
    
    document.getElementById('assignmentModalTitle').textContent = 'Editar Asignación';
    document.getElementById('assignmentId').value = assignment.id;
    document.getElementById('teacherSelect').value = assignment.teacher_id;
    document.getElementById('subjectSelect').value = assignment.subject_id;
    document.getElementById('weeklyHours').value = assignment.weekly_hours;
    document.getElementById('assignmentType').value = assignment.assignment_type;
    document.getElementById('startDate').value = assignment.start_date;
    document.getElementById('endDate').value = assignment.end_date;
    document.getElementById('assignmentNotes').value = assignment.notes || '';
    
    updateGroupSelect();
    setTimeout(() => {
        document.getElementById('groupSelect').value = assignment.group_id;
    }, 100);
    
    document.getElementById('assignmentModal').classList.remove('hidden');
    document.getElementById('assignmentModal').classList.add('flex');
}

async function deleteAssignment(id) {
    if (!confirm('¿Estás seguro de eliminar esta asignación?')) return;
    
    try {
        const response = await fetch(`${API_BASE}/assignments/${id}`, {
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
        
        showNotification(result.message || '✅ Asignación eliminada exitosamente');
        loadAssignments();
    } catch (error) {
        console.error('Error:', error);
        showNotification('❌ ' + error.message, 'error');
    }
}

// Form submission
document.getElementById('assignmentForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const assignmentId = document.getElementById('assignmentId').value;
    const data = {
        teacher_id: parseInt(document.getElementById('teacherSelect').value),
        subject_id: parseInt(document.getElementById('subjectSelect').value),
        group_id: parseInt(document.getElementById('groupSelect').value),
        weekly_hours: parseInt(document.getElementById('weeklyHours').value),
        assignment_type: document.getElementById('assignmentType').value,
        start_date: document.getElementById('startDate').value,
        end_date: document.getElementById('endDate').value,
        notes: document.getElementById('assignmentNotes').value
    };
    
    try {
        const url = assignmentId ? `${API_BASE}/assignments/${assignmentId}` : `${API_BASE}/assignments`;
        const method = assignmentId ? 'PATCH' : 'POST';
        
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
        
        showNotification(result.message || '✅ Asignación guardada exitosamente');
        closeAssignmentModal();
        loadAssignments();
    } catch (error) {
        console.error('Error:', error);
        showNotification('❌ ' + error.message, 'error');
    }
});

// Event listeners
document.getElementById('subjectSelect').addEventListener('change', updateGroupSelect);
document.getElementById('teacherSelect').addEventListener('change', updateWorkloadSummary);
document.getElementById('weeklyHours').addEventListener('input', updateWorkloadSummary);

function updateWorkloadSummary() {
    const teacherId = document.getElementById('teacherSelect').value;
    const newHours = parseInt(document.getElementById('weeklyHours').value) || 0;
    
    if (!teacherId || !newHours) {
        document.getElementById('workloadSummary').classList.add('hidden');
        return;
    }
    
    // Calculate current hours for this teacher
    const currentHours = allAssignments
        .filter(a => a.teacher_id == teacherId)
        .reduce((sum, a) => sum + (a.weekly_hours || 0), 0);
    
    const totalHours = currentHours + newHours;
    
    document.getElementById('currentHours').textContent = currentHours;
    document.getElementById('newHours').textContent = newHours;
    document.getElementById('totalTeacherHours').textContent = totalHours;
    
    const warningDiv = document.getElementById('workloadWarning');
    if (totalHours > 20) {
        warningDiv.classList.remove('hidden');
    } else {
        warningDiv.classList.add('hidden');
    }
    
    document.getElementById('workloadSummary').classList.remove('hidden');
}

// Filters
function applyFilters() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const subjectId = document.getElementById('subjectFilter').value;
    const workload = document.getElementById('workloadFilter').value;
    
    filteredAssignments = allAssignments.filter(assignment => {
        const matchesSearch = !search || assignment.teacher_name.toLowerCase().includes(search);
        const matchesSubject = !subjectId || assignment.subject_id == subjectId;
        
        let matchesWorkload = true;
        if (workload) {
            const hours = assignment.weekly_hours || 0;
            switch(workload) {
                case 'low': matchesWorkload = hours <= 10; break;
                case 'medium': matchesWorkload = hours > 10 && hours <= 20; break;
                case 'high': matchesWorkload = hours > 20; break;
            }
        }
        
        return matchesSearch && matchesSubject && matchesWorkload;
    });
    
    renderAssignments();
}

function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('subjectFilter').value = '';
    document.getElementById('workloadFilter').value = '';
    filteredAssignments = [...allAssignments];
    renderAssignments();
}

function exportAssignments() {
    showNotification('📊 Exportando asignaciones...');
    // Implementar exportación
}

function bulkAssign() {
    showNotification('⚡ Función de asignación masiva en desarrollo');
    // Implementar asignación masiva
}

// Event listeners for filters
document.getElementById('searchInput').addEventListener('input', applyFilters);
document.getElementById('subjectFilter').addEventListener('change', applyFilters);
document.getElementById('workloadFilter').addEventListener('change', applyFilters);

// Load initial data
Promise.all([loadAssignments(), loadTeachers(), loadSubjects(), loadGroups()]);
</script>

</body>
</html>