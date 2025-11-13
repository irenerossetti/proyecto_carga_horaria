<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Estudiantes - FICCT SGA</title>
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
                    <h1 class="text-3xl font-bold text-gray-900">Gestión de Estudiantes</h1>
                    <p class="text-gray-500 mt-1">Administra los estudiantes del sistema</p>
                </div>
                <button onclick="openCreateModal()" class="flex items-center gap-2 bg-brand-primary hover:bg-brand-hover text-white px-6 py-3 rounded-lg font-semibold transition-colors shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nuevo Estudiante
                </button>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Estudiantes</p>
                        <p id="stat-total" class="text-3xl font-bold text-gray-900 mt-1">0</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Activos</p>
                        <p id="stat-active" class="text-3xl font-bold text-green-600 mt-1">0</p>
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
                        <p class="text-sm font-medium text-gray-500">Nuevos (Este mes)</p>
                        <p id="stat-careers" class="text-3xl font-bold text-purple-600 mt-1">0</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Último Registro</p>
                        <p id="stat-last" class="text-lg font-bold text-gray-900 mt-1">-</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Buscar por nombre, email o número de registro..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Lista de Estudiantes</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estudiante</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nº Registro</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="studentsTable" class="bg-white divide-y divide-gray-200">
                        <tr id="loadingRow">
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex items-center justify-center">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-brand-primary"></div>
                                    <span class="ml-3 text-gray-500">Cargando estudiantes...</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Modal -->
<div id="studentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 id="modalTitle" class="text-xl font-bold text-gray-900">Nuevo Estudiante</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <form id="studentForm" class="p-6 space-y-4">
            <input type="hidden" id="studentId">
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo *</label>
                    <input type="text" id="studentName" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent" placeholder="Juan Pérez García">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                    <input type="email" id="studentEmail" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent" placeholder="estudiante@ficct.edu.bo">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Número de Registro *</label>
                    <input type="text" id="studentRegistration" name="registration_number" required maxlength="9" pattern="[0-9]{9}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent" placeholder="123456789">
                    <p class="text-xs text-gray-500 mt-1">Debe ser exactamente 9 dígitos numéricos</p>
                </div>
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-800">
                        <strong>Nota:</strong> La contraseña por defecto será <code class="bg-blue-100 px-2 py-1 rounded">password123</code>
                    </p>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeModal()" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                    Cancelar
                </button>
                <button type="submit" class="px-6 py-2 bg-brand-primary hover:bg-brand-hover text-white rounded-lg font-medium transition-colors">
                    <span id="submitButtonText">Crear Estudiante</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
const API_BASE = '/api';
let allStudents = [];

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
    document.getElementById('modalTitle').textContent = 'Nuevo Estudiante';
    document.getElementById('submitButtonText').textContent = 'Crear Estudiante';
    document.getElementById('studentForm').reset();
    document.getElementById('studentId').value = '';
    // Hacer el campo de registro obligatorio para crear
    document.getElementById('studentRegistration').required = true;
    document.getElementById('studentModal').classList.remove('hidden');
    document.getElementById('studentModal').classList.add('flex');
}

function closeModal() {
    document.getElementById('studentModal').classList.add('hidden');
    document.getElementById('studentModal').classList.remove('flex');
}

function editStudentById(id) {
    const student = allStudents.find(s => s.id === id);
    if (!student) {
        showNotification('❌ Error: Estudiante no encontrado', 'error');
        return;
    }
    
    document.getElementById('modalTitle').textContent = 'Editar Estudiante';
    document.getElementById('submitButtonText').textContent = 'Guardar Cambios';
    document.getElementById('studentId').value = student.id;
    document.getElementById('studentName').value = student.name || '';
    document.getElementById('studentEmail').value = student.email || '';
    document.getElementById('studentRegistration').value = student.registration_number || '';
    // Hacer el campo de registro opcional para editar (por si hay estudiantes antiguos sin registro)
    document.getElementById('studentRegistration').required = false;
    document.getElementById('studentModal').classList.remove('hidden');
    document.getElementById('studentModal').classList.add('flex');
}

async function loadStudents() {
    try {
        const response = await fetch(`${API_BASE}/students`, {
            headers: { 'Accept': 'application/json' }
        });
        
        if (!response.ok) throw new Error('Error al cargar estudiantes');
        
        const data = await response.json();
        allStudents = data.data || data || [];
        
        updateStats();
        renderStudents();
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('loadingRow').innerHTML = `
            <td colspan="5" class="px-6 py-8 text-center text-red-600">
                Error al cargar los estudiantes. Por favor, recarga la página.
            </td>
        `;
    }
}

function updateStats() {
    const active = allStudents.length; // Todos son activos por defecto
    const last = allStudents[allStudents.length - 1];
    
    document.getElementById('stat-total').textContent = allStudents.length;
    document.getElementById('stat-active').textContent = active;
    document.getElementById('stat-careers').textContent = '-';
    document.getElementById('stat-last').textContent = last ? last.name.split(' ')[0] : '-';
}

function renderStudents(filtered = null) {
    const students = filtered || allStudents;
    const tbody = document.getElementById('studentsTable');
    
    if (students.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="5" class="px-6 py-12 text-center">
                    <div class="text-gray-400">
                        <svg class="mx-auto h-12 w-12 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <p class="text-lg font-medium">No hay estudiantes registrados</p>
                        <button onclick="openCreateModal()" class="mt-4 text-brand-primary hover:text-brand-hover font-medium">
                            + Crear el primer estudiante
                        </button>
                    </div>
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = students.map(student => `
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-brand-primary rounded-full flex items-center justify-center text-white font-semibold">
                        ${student.name.charAt(0).toUpperCase()}
                    </div>
                    <div>
                        <div class="font-medium text-gray-900">${student.name}</div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4">
                <span class="font-mono text-lg font-bold text-brand-primary">${student.registration_number || '-'}</span>
            </td>
            <td class="px-6 py-4 text-sm">
                <div class="text-gray-900">${student.email}</div>
            </td>
            <td class="px-6 py-4">
                <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Activo</span>
            </td>
            <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                    <button onclick="editStudentById(${student.id})" class="text-blue-600 hover:text-blue-900" title="Editar">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button onclick="deleteStudent(${student.id})" class="text-red-600 hover:text-red-900" title="Eliminar">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

document.getElementById('studentForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const submitButton = e.target.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;
    submitButton.disabled = true;
    submitButton.textContent = 'Guardando...';
    
    const formData = new FormData(e.target);
    const data = {};
    
    // Solo incluir campos con valor
    for (const [key, value] of formData.entries()) {
        if (value && value.trim() !== '') {
            data[key] = value.trim();
        }
    }
    
    const studentId = document.getElementById('studentId').value;
    
    // Debug: mostrar datos que se enviarán
    console.log('Enviando datos:', data);
    console.log('Student ID:', studentId);
    
    try {
        const url = studentId ? `${API_BASE}/students/${studentId}` : `${API_BASE}/students`;
        const method = studentId ? 'PATCH' : 'POST';
        
        console.log('URL:', url);
        console.log('Method:', method);
        
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
            let errorMessage = error.message || 'Error al guardar';
            
            // Si hay errores de validación, mostrarlos
            if (error.errors) {
                const errorList = Object.values(error.errors).flat();
                errorMessage = errorList.join(', ');
            }
            
            throw new Error(errorMessage);
        }
        
        showNotification(studentId ? '✅ Estudiante actualizado exitosamente' : '✅ Estudiante creado exitosamente');
        closeModal();
        loadStudents();
    } catch (error) {
        console.error('Error:', error);
        showNotification('❌ ' + error.message, 'error');
    } finally {
        submitButton.disabled = false;
        submitButton.textContent = originalText;
    }
});

async function deleteStudent(id) {
    if (!confirm('¿Estás seguro de eliminar este estudiante? Esta acción no se puede deshacer.')) return;
    
    try {
        const response = await fetch(`${API_BASE}/students/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) throw new Error('Error al eliminar');
        
        showNotification('✅ Estudiante eliminado exitosamente');
        loadStudents();
    } catch (error) {
        console.error('Error:', error);
        showNotification('❌ ' + error.message, 'error');
    }
}

// Búsqueda
document.getElementById('searchInput').addEventListener('input', (e) => {
    const searchTerm = e.target.value.toLowerCase();
    
    let filtered = allStudents;
    
    if (searchTerm) {
        filtered = filtered.filter(s => 
            s.name.toLowerCase().includes(searchTerm) ||
            s.email.toLowerCase().includes(searchTerm) ||
            (s.registration_number && s.registration_number.includes(searchTerm))
        );
    }
    
    renderStudents(filtered);
});

// Cargar estudiantes al iniciar
loadStudents();
</script>

</body>
</html>
