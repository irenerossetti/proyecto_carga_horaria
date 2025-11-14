<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Importar Datos - FICCT SGA</title>
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
            <h1 class="text-3xl font-bold text-gray-900">Importar Datos Masivos</h1>
            <p class="text-gray-500 mt-1">Carga docentes, materias y grupos desde archivos Excel/CSV</p>
        </div>

        <!-- Import Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Docentes -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Docentes</h3>
                        <p class="text-sm text-gray-500">Importar lista de docentes</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <button onclick="downloadTemplate('teachers')" class="w-full px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                        📥 Descargar Plantilla
                    </button>
                    <button onclick="openImportModal('teachers')" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                        📤 Importar Docentes
                    </button>
                </div>
                
                <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                    <p class="text-xs text-blue-800">
                        <strong>Campos requeridos:</strong> Nombre, Email, CI, Teléfono
                    </p>
                </div>
            </div>

            <!-- Materias -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Materias</h3>
                        <p class="text-sm text-gray-500">Importar plan de estudios</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <button onclick="downloadTemplate('subjects')" class="w-full px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                        📥 Descargar Plantilla
                    </button>
                    <button onclick="openImportModal('subjects')" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors">
                        📤 Importar Materias
                    </button>
                </div>
                
                <div class="mt-4 p-3 bg-green-50 rounded-lg">
                    <p class="text-xs text-green-800">
                        <strong>Campos requeridos:</strong> Código, Nombre, Semestre, Horas
                    </p>
                </div>
            </div>

            <!-- Grupos -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Grupos</h3>
                        <p class="text-sm text-gray-500">Importar grupos académicos</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <button onclick="downloadTemplate('groups')" class="w-full px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                        📥 Descargar Plantilla
                    </button>
                    <button onclick="openImportModal('groups')" class="w-full px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium transition-colors">
                        📤 Importar Grupos
                    </button>
                </div>
                
                <div class="mt-4 p-3 bg-purple-50 rounded-lg">
                    <p class="text-xs text-purple-800">
                        <strong>Campos requeridos:</strong> Nombre, Materia, Capacidad
                    </p>
                </div>
            </div>
        </div>

        <!-- Import History -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Historial de Importaciones</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Archivo</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Registros</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="importHistory" class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                No hay importaciones registradas
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Modal de Importación -->
<div id="importModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl mx-4">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 id="importModalTitle" class="text-xl font-bold text-gray-900">Importar Datos</h3>
                <button onclick="closeImportModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <form id="importForm" class="p-6 space-y-6">
            <input type="hidden" id="importType">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Seleccionar Archivo</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="importFile" class="relative cursor-pointer bg-white rounded-md font-medium text-brand-primary hover:text-brand-hover focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-brand-primary">
                                <span>Subir archivo</span>
                                <input id="importFile" name="file" type="file" accept=".xlsx,.xls,.csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/csv" class="sr-only" required>
                            </label>
                            <p class="pl-1">o arrastra y suelta</p>
                        </div>
                        <p class="text-xs text-gray-500">Excel (.xlsx, .xls) o CSV hasta 10MB</p>
                    </div>
                </div>
                <div id="selectedFile" class="mt-2 hidden">
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span id="fileName" class="text-sm text-gray-700"></span>
                        <button type="button" onclick="clearFile()" class="ml-auto text-red-500 hover:text-red-700">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Separador CSV</label>
                    <select id="csvSeparator" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        <option value=",">Coma (,)</option>
                        <option value=";">Punto y coma (;)</option>
                        <option value="\t">Tabulación</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Codificación</label>
                    <select id="encoding" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        <option value="utf-8">UTF-8</option>
                        <option value="iso-8859-1">ISO-8859-1</option>
                        <option value="windows-1252">Windows-1252</option>
                    </select>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <input type="checkbox" id="skipFirstRow" checked class="w-4 h-4 text-brand-primary border-gray-300 rounded focus:ring-brand-primary">
                <label for="skipFirstRow" class="text-sm font-medium text-gray-700">Omitir primera fila (encabezados)</label>
            </div>
            
            <div class="flex items-center gap-3">
                <input type="checkbox" id="validateData" checked class="w-4 h-4 text-brand-primary border-gray-300 rounded focus:ring-brand-primary">
                <label for="validateData" class="text-sm font-medium text-gray-700">Validar datos antes de importar</label>
            </div>
            
            <div id="importInstructions" class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h4 class="font-medium text-blue-900 mb-2">Instrucciones:</h4>
                <ul id="instructionsList" class="text-sm text-blue-800 space-y-1">
                    <!-- Se llenará dinámicamente -->
                </ul>
            </div>
        </form>

        <div class="p-6 pt-0">
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeImportModal()" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                    Cancelar
                </button>
                <button type="button" onclick="processImport()" class="px-6 py-2 bg-brand-primary hover:bg-brand-hover text-white rounded-lg font-medium transition-colors">
                    Importar Datos
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Progress Modal -->
<div id="progressModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4">
        <div class="p-6">
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-brand-primary mx-auto mb-4"></div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Procesando Importación</h3>
                <p id="progressText" class="text-sm text-gray-500">Validando archivo...</p>
                
                <div class="mt-4 bg-gray-200 rounded-full h-2">
                    <div id="progressBar" class="bg-brand-primary h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
                
                <div id="progressStats" class="mt-4 text-sm text-gray-600 hidden">
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <div class="font-medium text-green-600" id="successCount">0</div>
                            <div>Exitosos</div>
                        </div>
                        <div>
                            <div class="font-medium text-red-600" id="errorCount">0</div>
                            <div>Errores</div>
                        </div>
                        <div>
                            <div class="font-medium text-gray-600" id="totalCount">0</div>
                            <div>Total</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const API_BASE = '/api';
let currentImportType = '';

const importInstructions = {
    teachers: [
        'Columna A: Nombre completo (requerido)',
        'Columna B: Email institucional (requerido)',
        'Columna C: Cédula de identidad (requerido)',
        'Columna D: Teléfono (requerido)',
        'Columna E: Especialidad (opcional)',
        'Columna F: Grado académico (opcional)'
    ],
    subjects: [
        'Columna A: Código de materia (requerido)',
        'Columna B: Nombre de la materia (requerido)',
        'Columna C: Semestre (1-10, requerido)',
        'Columna D: Horas teóricas (requerido)',
        'Columna E: Horas prácticas (requerido)',
        'Columna F: Prerrequisitos (opcional)',
        'Columna G: Descripción (opcional)'
    ],
    groups: [
        'Columna A: Nombre del grupo (requerido)',
        'Columna B: Código de materia (requerido)',
        'Columna C: Capacidad máxima (requerido)',
        'Columna D: Horario (opcional)',
        'Columna E: Descripción (opcional)'
    ]
};

function showNotification(message, type = 'success') {
    const alertClass = type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800';
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg border ${alertClass} z-50 shadow-lg`;
    notification.innerHTML = `<span class="font-medium">${message}</span>`;
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 5000);
}

function downloadTemplate(type) {
    // Crear datos de ejemplo para la plantilla
    let templateData = [];
    let filename = '';
    
    switch(type) {
        case 'teachers':
            templateData = [
                ['Nombre', 'Email', 'CI', 'Teléfono', 'Especialidad', 'Grado'],
                ['Juan Pérez García', 'juan.perez@ficct.edu.bo', '12345678', '70123456', 'Programación', 'Licenciado'],
                ['María López Silva', 'maria.lopez@ficct.edu.bo', '87654321', '70654321', 'Matemáticas', 'Magister']
            ];
            filename = 'plantilla_docentes.csv';
            break;
        case 'subjects':
            templateData = [
                ['Código', 'Nombre', 'Semestre', 'Horas Teóricas', 'Horas Prácticas', 'Prerrequisitos', 'Descripción'],
                ['INF-101', 'Introducción a la Programación', '1', '4', '2', '', 'Fundamentos de programación'],
                ['MAT-101', 'Cálculo I', '1', '4', '0', '', 'Cálculo diferencial']
            ];
            filename = 'plantilla_materias.csv';
            break;
        case 'groups':
            templateData = [
                ['Nombre', 'Código Materia', 'Capacidad', 'Horario', 'Descripción'],
                ['Grupo A', 'INF-101', '30', 'Lun-Mie-Vie 08:00-10:00', 'Grupo matutino'],
                ['Grupo B', 'INF-101', '30', 'Lun-Mie-Vie 14:00-16:00', 'Grupo vespertino']
            ];
            filename = 'plantilla_grupos.csv';
            break;
    }
    
    // Convertir a CSV y descargar
    const csvContent = templateData.map(row => row.join(',')).join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = filename;
    link.click();
    
    showNotification(`✅ Plantilla de ${type === 'teachers' ? 'docentes' : type === 'subjects' ? 'materias' : 'grupos'} descargada`);
}

function openImportModal(type) {
    currentImportType = type;
    console.log('Abriendo modal de importación para:', type);
    
    const titles = {
        teachers: 'Importar Docentes',
        subjects: 'Importar Materias',
        groups: 'Importar Grupos'
    };
    
    document.getElementById('importModalTitle').textContent = titles[type];
    document.getElementById('importType').value = type;
    
    // Resetear el formulario
    document.getElementById('importForm').reset();
    clearFile();
    
    // Mostrar instrucciones específicas
    const instructionsList = document.getElementById('instructionsList');
    instructionsList.innerHTML = importInstructions[type].map(instruction => 
        `<li>• ${instruction}</li>`
    ).join('');
    
    document.getElementById('importModal').classList.remove('hidden');
    document.getElementById('importModal').classList.add('flex');
}

function closeImportModal() {
    document.getElementById('importModal').classList.add('hidden');
    document.getElementById('importModal').classList.remove('flex');
    document.getElementById('importForm').reset();
    clearFile();
}

function clearFile() {
    document.getElementById('importFile').value = '';
    document.getElementById('selectedFile').classList.add('hidden');
}

// File selection handler
document.addEventListener('DOMContentLoaded', function() {
    const importFileInput = document.getElementById('importFile');
    if (importFileInput) {
        importFileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                console.log('Archivo seleccionado:', file.name);
                document.getElementById('fileName').textContent = file.name;
                document.getElementById('selectedFile').classList.remove('hidden');
                
                // Show/hide CSV options based on file type
                const csvOptions = document.getElementById('csvSeparator').closest('.grid');
                if (file.name.toLowerCase().endsWith('.csv')) {
                    csvOptions.style.display = 'grid';
                } else {
                    csvOptions.style.display = 'none';
                }
            }
        });
    }
});

async function processImport() {
    const fileInput = document.getElementById('importFile');
    
    if (!fileInput) {
        console.error('Input de archivo no encontrado');
        showNotification('❌ Error: Input de archivo no encontrado', 'error');
        return;
    }
    
    const file = fileInput.files[0];
    
    if (!file) {
        console.log('No hay archivo seleccionado');
        showNotification('❌ Por favor selecciona un archivo', 'error');
        return;
    }
    
    console.log('Procesando archivo:', file.name, 'Tipo:', currentImportType);
    
    // Show progress modal
    closeImportModal();
    document.getElementById('progressModal').classList.remove('hidden');
    document.getElementById('progressModal').classList.add('flex');
    
    const formData = new FormData();
    formData.append('file', file);
    formData.append('type', currentImportType);
    formData.append('separator', document.getElementById('csvSeparator').value);
    formData.append('encoding', document.getElementById('encoding').value);
    formData.append('skip_first_row', document.getElementById('skipFirstRow').checked);
    formData.append('validate_data', document.getElementById('validateData').checked);
    
    try {
        updateProgress(10, 'Subiendo archivo...');
        
        const response = await fetch(`${API_BASE}/imports`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json'
            },
            body: formData
        });
        
        updateProgress(50, 'Procesando datos...');
        
        const result = await response.json();
        
        if (!response.ok) {
            throw new Error(result.message || 'Error en la importación');
        }
        
        updateProgress(100, 'Importación completada');
        
        // Show results
        document.getElementById('progressStats').classList.remove('hidden');
        document.getElementById('successCount').textContent = result.success_count || 0;
        document.getElementById('errorCount').textContent = result.error_count || 0;
        document.getElementById('totalCount').textContent = result.total_count || 0;
        
        setTimeout(() => {
            document.getElementById('progressModal').classList.add('hidden');
            document.getElementById('progressModal').classList.remove('flex');
            
            if (result.error_count > 0) {
                showNotification(`⚠️ Importación completada con ${result.error_count} errores de ${result.total_count} registros`, 'error');
            } else {
                showNotification(`✅ ${result.success_count} registros importados exitosamente`);
            }
            
            loadImportHistory();
        }, 2000);
        
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('progressModal').classList.add('hidden');
        document.getElementById('progressModal').classList.remove('flex');
        showNotification('❌ ' + error.message, 'error');
    }
}

function updateProgress(percentage, text) {
    document.getElementById('progressBar').style.width = percentage + '%';
    document.getElementById('progressText').textContent = text;
}

function loadImportHistory() {
    // Simular historial de importaciones
    const mockHistory = [
        {
            id: 1,
            date: '2025-11-13 10:30:00',
            type: 'teachers',
            filename: 'docentes_2025.xlsx',
            total_records: 25,
            success_count: 23,
            error_count: 2,
            status: 'completed'
        },
        {
            id: 2,
            date: '2025-11-12 15:45:00',
            type: 'subjects',
            filename: 'materias_ficct.csv',
            total_records: 45,
            success_count: 45,
            error_count: 0,
            status: 'completed'
        }
    ];
    
    const tbody = document.getElementById('importHistory');
    
    if (mockHistory.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                    No hay importaciones registradas
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = mockHistory.map(item => {
        const typeNames = {
            teachers: 'Docentes',
            subjects: 'Materias',
            groups: 'Grupos'
        };
        
        const statusColors = {
            completed: 'bg-green-100 text-green-800',
            failed: 'bg-red-100 text-red-800',
            processing: 'bg-yellow-100 text-yellow-800'
        };
        
        return `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${new Date(item.date).toLocaleString('es-ES')}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                        ${typeNames[item.type]}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">
                    ${item.filename}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                    <div class="text-green-600 font-medium">${item.success_count}</div>
                    ${item.error_count > 0 ? `<div class="text-red-600 text-xs">${item.error_count} errores</div>` : ''}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <span class="px-3 py-1 rounded-full text-sm font-medium ${statusColors[item.status]}">
                        ${item.status === 'completed' ? 'Completado' : item.status === 'failed' ? 'Fallido' : 'Procesando'}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button class="text-blue-600 hover:text-blue-900 mr-3" title="Ver detalles">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                    <button class="text-red-600 hover:text-red-900" title="Eliminar">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </td>
            </tr>
        `;
    }).join('');
}

// Load initial data
loadImportHistory();
</script>

</body>
</html>