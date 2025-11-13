<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Docentes - FICCT SGA</title>
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
                    <h1 class="text-3xl font-bold text-gray-900">Gestión de Docentes</h1>
                    <p class="text-gray-500 mt-1">Administra los docentes del sistema</p>
                </div>
                <button onclick="openCreateModal()" class="flex items-center gap-2 bg-brand-primary hover:bg-brand-hover text-white px-6 py-3 rounded-lg font-semibold transition-colors shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nuevo Docente
                </button>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Docentes</p>
                        <p id="stat-total" class="text-3xl font-bold text-gray-900 mt-1">0</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
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
                        <p class="text-sm font-medium text-gray-500">Departamentos</p>
                        <p id="stat-departments" class="text-3xl font-bold text-purple-600 mt-1">0</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
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

        <!-- Search and Filter -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
            <div class="flex gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" id="searchInput" placeholder="Buscar por nombre, email o código..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
                <select id="departmentFilter" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                    <option value="">Todos los departamentos</option>
                </select>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Lista de Docentes</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Docente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contacto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Departamento</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="teachersTable" class="bg-white divide-y divide-gray-200">
                        <tr id="loadingRow">
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex items-center justify-center">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-brand-primary"></div>
                                    <span class="ml-3 text-gray-500">Cargando docentes...</span>
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
<div id="teacherModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 id="modalTitle" class="text-xl font-bold text-gray-900">Nuevo Docente</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <form id="teacherForm" class="p-6 space-y-4">
            <input type="hidden" id="teacherId">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo *</label>
                    <input type="text" id="teacherName" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent" placeholder="Prof. Juan Pérez">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                    <input type="email" id="teacherEmail" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent" placeholder="docente@ficct.edu.bo">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                    <input type="text" id="teacherPhone" name="phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent" placeholder="+591 7XXXXXXX">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">DNI/CI</label>
                    <input type="text" id="teacherCode" name="code" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent" placeholder="12345678">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Departamento</label>
                    <input type="text" id="teacherDepartment" name="department" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent" placeholder="Sistemas">
                </div>
                
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Especialización</label>
                    <input type="text" id="teacherSpecialization" name="specialization" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent" placeholder="Ingeniería de Software">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeModal()" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                    Cancelar
                </button>
                <button type="submit" class="px-6 py-2 bg-brand-primary hover:bg-brand-hover text-white rounded-lg font-medium transition-colors">
                    <span id="submitButtonText">Crear Docente</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
const API_BASE = '/api';
let allTeachers = [];

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
    document.getElementById('modalTitle').textContent = 'Nuevo Docente';
    document.getElementById('submitButtonText').textContent = 'Crear Docente';
    document.getElementById('teacherForm').reset();
    document.getElementById('teacherId').value = '';
    document.getElementById('teacherModal').classList.remove('hidden');
    document.getElementById('teacherModal').classList.add('flex');
}

function closeModal() {
    document.getElementById('teacherModal').classList.add('hidden');
    document.getElementById('teacherModal').classList.remove('flex');
}

function editTeacherById(id) {
    const teacher = allTeachers.find(t => t.id === id);
    if (!teacher) {
        console.error('Docente no encontrado:', id);
        alert('Error: Docente no encontrado');
        return;
    }
    
    console.log('Editando docente:', teacher);
    
    document.getElementById('modalTitle').textContent = 'Editar Docente';
    document.getElementById('submitButtonText').textContent = 'Guardar Cambios';
    document.getElementById('teacherId').value = teacher.id;
    document.getElementById('teacherName').value = teacher.name || '';
    document.getElementById('teacherEmail').value = teacher.email || '';
    document.getElementById('teacherPhone').value = teacher.phone || '';
    document.getElementById('teacherCode').value = teacher.dni || '';
    document.getElementById('teacherDepartment').value = teacher.department || '';
    document.getElementById('teacherSpecialization').value = teacher.specialization || '';
    document.getElementById('teacherModal').classList.remove('hidden');
    document.getElementById('teacherModal').classList.add('flex');
}

async function loadTeachers() {
    try {
        const response = await fetch(`${API_BASE}/teachers`, {
            headers: {
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) throw new Error('Error al cargar docentes');
        
        const data = await response.json();
        allTeachers = data.data || data || [];
        
        updateStats();
        renderTeachers();
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('loadingRow').innerHTML = `
            <td colspan="5" class="px-6 py-8 text-center text-red-600">
                Error al cargar los docentes. Por favor, recarga la página.
            </td>
        `;
    }
}

function updateStats() {
    const departments = [...new Set(allTeachers.map(t => t.department).filter(Boolean))];
    const active = allTeachers.filter(t => t.is_active !== false);
    const last = allTeachers[allTeachers.length - 1];
    
    document.getElementById('stat-total').textContent = allTeachers.length;
    document.getElementById('stat-active').textContent = active.length;
    document.getElementById('stat-departments').textContent = departments.length;
    document.getElementById('stat-last').textContent = last ? last.name.split(' ')[0] : '-';
    
    // Actualizar filtro de departamentos
    const select = document.getElementById('departmentFilter');
    select.innerHTML = '<option value="">Todos los departamentos</option>';
    departments.forEach(dept => {
        select.innerHTML += `<option value="${dept}">${dept}</option>`;
    });
}

function renderTeachers(filtered = null) {
    const teachers = filtered || allTeachers;
    const tbody = document.getElementById('teachersTable');
    
    if (teachers.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="5" class="px-6 py-12 text-center">
                    <div class="text-gray-400">
                        <svg class="mx-auto h-12 w-12 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <p class="text-lg font-medium">No hay docentes registrados</p>
                        <button onclick="openCreateModal()" class="mt-4 text-brand-primary hover:text-brand-hover font-medium">
                            + Crear el primer docente
                        </button>
                    </div>
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = teachers.map(teacher => `
        <tr class="hover:bg-gray-50 transition-colors" data-department="${teacher.department || ''}">
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-brand-primary rounded-full flex items-center justify-center text-white font-semibold">
                        ${teacher.name.charAt(0).toUpperCase()}
                    </div>
                    <div>
                        <div class="font-medium text-gray-900">${teacher.name}</div>
                        ${teacher.specialization ? `<div class="text-sm text-gray-500">${teacher.specialization}</div>` : ''}
                    </div>
                </div>
            </td>
            <td class="px-6 py-4">
                <span class="font-mono text-sm text-gray-600">${teacher.dni || '-'}</span>
            </td>
            <td class="px-6 py-4 text-sm">
                <div class="text-gray-900">${teacher.email}</div>
                ${teacher.phone ? `<div class="text-gray-500">${teacher.phone}</div>` : ''}
            </td>
            <td class="px-6 py-4">
                ${teacher.department ? `<span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">${teacher.department}</span>` : '-'}
            </td>
            <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                    <button onclick="editTeacherById(${teacher.id})" class="text-blue-600 hover:text-blue-900" title="Editar">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button onclick="deleteTeacher(${teacher.id})" class="text-red-600 hover:text-red-900" title="Eliminar">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

document.getElementById('teacherForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const submitButton = e.target.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;
    submitButton.disabled = true;
    submitButton.textContent = 'Guardando...';
    
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());
    const teacherId = document.getElementById('teacherId').value;
    
    try {
        const url = teacherId ? `${API_BASE}/teachers/${teacherId}` : `${API_BASE}/teachers`;
        const method = teacherId ? 'PATCH' : 'POST';
        
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
        
        showNotification(teacherId ? '✅ Docente actualizado exitosamente' : '✅ Docente creado exitosamente');
        closeModal();
        loadTeachers();
    } catch (error) {
        console.error('Error:', error);
        showNotification('❌ ' + error.message, 'error');
    } finally {
        submitButton.disabled = false;
        submitButton.textContent = originalText;
    }
});

async function deleteTeacher(id) {
    if (!confirm('¿Estás seguro de eliminar este docente? Esta acción no se puede deshacer.')) return;
    
    try {
        const response = await fetch(`${API_BASE}/teachers/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) throw new Error('Error al eliminar');
        
        showNotification('✅ Docente eliminado exitosamente');
        loadTeachers();
    } catch (error) {
        console.error('Error:', error);
        showNotification('❌ ' + error.message, 'error');
    }
}

// Búsqueda - MEJORADO
document.getElementById('searchInput').addEventListener('input', (e) => {
    const searchTerm = e.target.value.toLowerCase();
    const department = document.getElementById('departmentFilter').value;
    const rows = document.querySelectorAll('#teachersTable tr[data-department]');
    
    let visibleCount = 0;
    rows.forEach(row => {
        const rowDept = row.getAttribute('data-department');
        const text = row.textContent.toLowerCase();
        
        // Aplicar ambos filtros: búsqueda Y departamento
        const matchesSearch = !searchTerm || text.includes(searchTerm);
        const matchesDept = !department || rowDept === department;
        
        if (matchesSearch && matchesDept) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    console.log(`Búsqueda: "${searchTerm}" - Mostrando ${visibleCount} docentes`);
});

// Filtro por departamento - MEJORADO
document.getElementById('departmentFilter').addEventListener('change', (e) => {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const department = e.target.value;
    const rows = document.querySelectorAll('#teachersTable tr[data-department]');
    
    console.log('Filtrando por departamento:', department || 'todos');
    
    let visibleCount = 0;
    rows.forEach(row => {
        const rowDept = row.getAttribute('data-department');
        const text = row.textContent.toLowerCase();
        
        // Aplicar ambos filtros: departamento Y búsqueda
        const matchesDept = !department || rowDept === department;
        const matchesSearch = !searchTerm || text.includes(searchTerm);
        
        if (matchesDept && matchesSearch) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    console.log(`Mostrando ${visibleCount} docentes`);
});

// Cargar docentes al iniciar
loadTeachers();
</script>

</body>
</html>
