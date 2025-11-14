<!DOCTYPE html>
<html lang="es" class="h-full bg-[#F5F5F5]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Justificaciones - FICCT SGA</title>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

<body class="min-h-full font-sans antialiased text-neutral-900 bg-[#F5F5F5]">
    <nav class="bg-brand-primary text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-3">
                    <a href="{{ route('docente.dashboard') }}" class="text-white/70 hover:text-white">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center">
                        <i class="fas fa-file-medical text-white"></i>
                    </div>
                    <span class="font-bold text-lg tracking-tight">Justificaciones</span>
                </div>
                <span class="text-sm opacity-90 hidden sm:inline">{{ Auth::user()->name ?? 'Docente' }}</span>
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto py-6 sm:py-8 px-4 sm:px-6 lg:px-8">
        
        <!-- Botón Nueva Justificación -->
        <div class="mb-6">
            <button onclick="showNewJustification()" class="px-6 py-3 bg-brand-primary text-white rounded-lg hover:bg-brand-hover transition-colors font-semibold shadow-sm">
                <i class="fas fa-plus mr-2"></i>Nueva Justificación
            </button>
        </div>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Pendientes</p>
                        <p class="text-2xl font-bold text-yellow-600" id="pendingCount">0</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Aprobadas</p>
                        <p class="text-2xl font-bold text-green-600" id="approvedCount">0</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Rechazadas</p>
                        <p class="text-2xl font-bold text-red-600" id="rejectedCount">0</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-times-circle text-red-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total</p>
                        <p class="text-2xl font-bold text-gray-900" id="totalCount">0</p>
                    </div>
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-list text-gray-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Justificaciones -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Mis Justificaciones</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha Ausencia</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Motivo</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">Clase</th>
                            <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="justificationsTable" class="divide-y divide-gray-200">
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Nueva Justificación -->
        <div id="newJustificationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-900">Nueva Justificación</h3>
                    <button onclick="closeNewJustification()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <form id="justificationForm" class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Ausencia *</label>
                            <input type="date" id="absenceDate" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Clase Afectada *</label>
                            <select id="scheduleId" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary">
                                <option value="">Seleccionar clase...</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Justificación *</label>
                        <select id="justificationType" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary">
                            <option value="">Seleccionar tipo...</option>
                            <option value="medica">Médica</option>
                            <option value="personal">Personal</option>
                            <option value="academica">Académica</option>
                            <option value="otra">Otra</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Motivo Detallado *</label>
                        <textarea id="reason" required rows="4" placeholder="Explica el motivo de tu ausencia..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Adjuntar Documento (Opcional)</label>
                        <input type="file" id="document" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary">
                        <p class="text-xs text-gray-500 mt-1">PDF, JPG o PNG hasta 5MB</p>
                    </div>
                    <div class="flex gap-3 pt-4">
                        <button type="submit" class="flex-1 px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-hover transition-colors">
                            <i class="fas fa-paper-plane mr-2"></i>Enviar Justificación
                        </button>
                        <button type="button" onclick="closeNewJustification()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
    let justifications = [];

    document.addEventListener('DOMContentLoaded', function() {
        loadJustifications();
        document.getElementById('justificationForm').addEventListener('submit', handleSubmit);
    });

    async function loadJustifications() {
        // Datos simulados
        justifications = [
            {
                id: 1,
                absenceDate: '2025-11-10',
                type: 'medica',
                reason: 'Consulta médica de emergencia',
                class: 'Programación I - SC',
                status: 'pending',
                submittedDate: '2025-11-10'
            },
            {
                id: 2,
                absenceDate: '2025-11-05',
                type: 'personal',
                reason: 'Trámite personal urgente',
                class: 'Base de Datos - SA',
                status: 'approved',
                submittedDate: '2025-11-05',
                reviewedBy: 'Coordinador'
            }
        ];
        
        updateStats();
        displayJustifications();
    }

    function updateStats() {
        const pending = justifications.filter(j => j.status === 'pending').length;
        const approved = justifications.filter(j => j.status === 'approved').length;
        const rejected = justifications.filter(j => j.status === 'rejected').length;
        
        document.getElementById('pendingCount').textContent = pending;
        document.getElementById('approvedCount').textContent = approved;
        document.getElementById('rejectedCount').textContent = rejected;
        document.getElementById('totalCount').textContent = justifications.length;
    }

    function displayJustifications() {
        const tbody = document.getElementById('justificationsTable');
        
        if (justifications.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">No has enviado justificaciones aún</td></tr>';
            return;
        }
        
        tbody.innerHTML = justifications.map(j => `
            <tr class="hover:bg-gray-50">
                <td class="px-4 sm:px-6 py-4">
                    <div class="font-medium text-gray-900">${formatDate(j.absenceDate)}</div>
                    <div class="text-sm text-gray-500 sm:hidden">${j.type}</div>
                </td>
                <td class="px-4 sm:px-6 py-4 text-sm text-gray-600 hidden sm:table-cell">${getTypeText(j.type)}</td>
                <td class="px-4 sm:px-6 py-4 text-sm text-gray-600 hidden lg:table-cell">${j.class}</td>
                <td class="px-4 sm:px-6 py-4 text-center">
                    ${getStatusBadge(j.status)}
                </td>
                <td class="px-4 sm:px-6 py-4">
                    <div class="flex justify-center gap-2">
                        <button onclick="viewJustification(${j.id})" class="text-blue-600 hover:text-blue-700" title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </button>
                        ${j.status === 'pending' ? `
                            <button onclick="deleteJustification(${j.id})" class="text-red-600 hover:text-red-700" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        ` : ''}
                    </div>
                </td>
            </tr>
        `).join('');
    }

    function getStatusBadge(status) {
        const badges = {
            pending: '<span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-medium rounded">Pendiente</span>',
            approved: '<span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded">Aprobada</span>',
            rejected: '<span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-medium rounded">Rechazada</span>'
        };
        return badges[status] || status;
    }

    function getTypeText(type) {
        const types = {
            medica: 'Médica',
            personal: 'Personal',
            academica: 'Académica',
            otra: 'Otra'
        };
        return types[type] || type;
    }

    function formatDate(dateStr) {
        const date = new Date(dateStr + 'T00:00:00');
        return date.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });
    }

    function showNewJustification() {
        document.getElementById('newJustificationModal').classList.remove('hidden');
    }

    function closeNewJustification() {
        document.getElementById('newJustificationModal').classList.add('hidden');
        document.getElementById('justificationForm').reset();
    }

    async function handleSubmit(e) {
        e.preventDefault();
        
        const formData = {
            absence_date: document.getElementById('absenceDate').value,
            schedule_id: document.getElementById('scheduleId').value,
            type: document.getElementById('justificationType').value,
            reason: document.getElementById('reason').value
        };
        
        try {
            console.log('Enviando justificación:', formData);
            alert('✅ Justificación enviada exitosamente');
            closeNewJustification();
            loadJustifications();
        } catch (error) {
            console.error('Error:', error);
            alert('❌ Error al enviar la justificación');
        }
    }

    function viewJustification(id) {
        const j = justifications.find(just => just.id === id);
        if (j) {
            alert(`Detalles:\n\nFecha: ${formatDate(j.absenceDate)}\nTipo: ${getTypeText(j.type)}\nMotivo: ${j.reason}\nEstado: ${j.status}`);
        }
    }

    function deleteJustification(id) {
        if (confirm('¿Eliminar esta justificación?')) {
            justifications = justifications.filter(j => j.id !== id);
            updateStats();
            displayJustifications();
        }
    }
    </script>
</body>
</html>
