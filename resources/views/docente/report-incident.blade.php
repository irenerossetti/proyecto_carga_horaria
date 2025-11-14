<!DOCTYPE html>
<html lang="es" class="h-full bg-[#F5F5F5]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reportar Incidencia - FICCT SGA</title>
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
                        <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                    <span class="font-bold text-lg tracking-tight">Reportar Incidencia</span>
                </div>
                <span class="text-sm opacity-90">{{ Auth::user()->name ?? 'Docente' }}</span>
            </div>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto py-6 sm:py-8 px-4 sm:px-6 lg:px-8">
        
        <!-- Formulario de Nueva Incidencia -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
            <div class="bg-brand-primary text-white px-6 py-4">
                <h2 class="text-xl font-bold">Nueva Incidencia</h2>
                <p class="text-sm text-white/80 mt-1">Reporta problemas en aulas o equipamiento</p>
            </div>

            <form id="incidentForm" class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tipo de Incidencia *
                        </label>
                        <select id="incidentType" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                            <option value="">Seleccionar tipo...</option>
                            <option value="equipamiento">Equipamiento</option>
                            <option value="infraestructura">Infraestructura</option>
                            <option value="limpieza">Limpieza</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Prioridad *
                        </label>
                        <select id="priority" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                            <option value="baja">Baja</option>
                            <option value="media" selected>Media</option>
                            <option value="alta">Alta</option>
                            <option value="urgente">Urgente</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Aula *
                        </label>
                        <select id="roomId" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                            <option value="">Seleccionar aula...</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha del Incidente *
                        </label>
                        <input type="date" id="incidentDate" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Título del Problema *
                    </label>
                    <input type="text" id="title" required placeholder="Ej: Proyector no funciona" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Descripción Detallada *
                    </label>
                    <textarea id="description" required rows="5" placeholder="Describe el problema con el mayor detalle posible..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Adjuntar Foto (Opcional)
                    </label>
                    <div class="flex items-center justify-center w-full">
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-500"><span class="font-semibold">Click para subir</span> o arrastra aquí</p>
                                <p class="text-xs text-gray-500 mt-1">PNG, JPG hasta 5MB</p>
                            </div>
                            <input id="photo" type="file" accept="image/*" class="hidden" />
                        </label>
                    </div>
                    <div id="photoPreview" class="mt-3 hidden">
                        <img id="previewImage" class="h-32 rounded-lg border border-gray-200" />
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit" class="flex-1 px-6 py-3 bg-brand-primary text-white rounded-lg hover:bg-brand-hover transition-colors font-semibold">
                        <i class="fas fa-paper-plane mr-2"></i>Enviar Reporte
                    </button>
                    <a href="{{ route('docente.dashboard') }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-semibold">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>

        <!-- Mis Incidencias Reportadas -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Mis Incidencias Reportadas</h3>
                <span class="text-sm text-gray-500" id="incidentCount">0 reportes</span>
            </div>

            <div id="incidentsList" class="divide-y divide-gray-200">
                <!-- Se llenarán dinámicamente -->
            </div>
        </div>
    </main>

    <script>
    let rooms = [];
    let incidents = [];

    document.addEventListener('DOMContentLoaded', function() {
        loadRooms();
        loadMyIncidents();
        
        // Fecha actual por defecto
        document.getElementById('incidentDate').valueAsDate = new Date();
        
        // Preview de foto
        document.getElementById('photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImage').src = e.target.result;
                    document.getElementById('photoPreview').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
        
        // Submit form
        document.getElementById('incidentForm').addEventListener('submit', handleSubmit);
    });

    async function loadRooms() {
        try {
            const response = await fetch('/api/rooms');
            const data = await response.json();
            rooms = data.data || [];
            
            const select = document.getElementById('roomId');
            select.innerHTML = '<option value="">Seleccionar aula...</option>' +
                rooms.map(r => `<option value="${r.id}">${r.name}</option>`).join('');
        } catch (error) {
            console.error('Error:', error);
        }
    }

    async function loadMyIncidents() {
        // Datos simulados - reemplazar con API real
        incidents = [
            {
                id: 1,
                type: 'equipamiento',
                title: 'Proyector no funciona',
                room: 'Aula 301',
                date: '2025-11-13',
                priority: 'alta',
                status: 'pendiente'
            },
            {
                id: 2,
                type: 'limpieza',
                title: 'Pizarra sin borrar',
                room: 'Aula 217',
                date: '2025-11-12',
                priority: 'baja',
                status: 'en_proceso'
            }
        ];
        
        displayIncidents();
    }

    function displayIncidents() {
        const container = document.getElementById('incidentsList');
        document.getElementById('incidentCount').textContent = `${incidents.length} reportes`;
        
        if (incidents.length === 0) {
            container.innerHTML = '<div class="p-8 text-center text-gray-500">No has reportado incidencias aún</div>';
            return;
        }
        
        container.innerHTML = incidents.map(incident => `
            <div class="p-4 hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2 py-1 ${getPriorityClass(incident.priority)} text-xs font-medium rounded">
                                ${incident.priority.toUpperCase()}
                            </span>
                            <span class="px-2 py-1 ${getStatusClass(incident.status)} text-xs font-medium rounded">
                                ${getStatusText(incident.status)}
                            </span>
                        </div>
                        <h4 class="font-semibold text-gray-900">${incident.title}</h4>
                        <div class="flex items-center gap-4 mt-2 text-sm text-gray-600">
                            <span><i class="fas fa-door-open mr-1"></i>${incident.room}</span>
                            <span><i class="fas fa-calendar mr-1"></i>${formatDate(incident.date)}</span>
                            <span><i class="fas fa-tag mr-1"></i>${getTypeText(incident.type)}</span>
                        </div>
                    </div>
                    <button onclick="viewIncident(${incident.id})" class="text-brand-primary hover:text-brand-hover">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
        `).join('');
    }

    function getPriorityClass(priority) {
        const classes = {
            baja: 'bg-gray-100 text-gray-700',
            media: 'bg-blue-100 text-blue-700',
            alta: 'bg-orange-100 text-orange-700',
            urgente: 'bg-red-100 text-red-700'
        };
        return classes[priority] || classes.media;
    }

    function getStatusClass(status) {
        const classes = {
            pendiente: 'bg-yellow-100 text-yellow-700',
            en_proceso: 'bg-blue-100 text-blue-700',
            resuelta: 'bg-green-100 text-green-700',
            rechazada: 'bg-red-100 text-red-700'
        };
        return classes[status] || classes.pendiente;
    }

    function getStatusText(status) {
        const texts = {
            pendiente: 'Pendiente',
            en_proceso: 'En Proceso',
            resuelta: 'Resuelta',
            rechazada: 'Rechazada'
        };
        return texts[status] || status;
    }

    function getTypeText(type) {
        const texts = {
            equipamiento: 'Equipamiento',
            infraestructura: 'Infraestructura',
            limpieza: 'Limpieza',
            otro: 'Otro'
        };
        return texts[type] || type;
    }

    function formatDate(dateStr) {
        const date = new Date(dateStr + 'T00:00:00');
        return date.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });
    }

    async function handleSubmit(e) {
        e.preventDefault();
        
        const formData = {
            type: document.getElementById('incidentType').value,
            priority: document.getElementById('priority').value,
            room_id: document.getElementById('roomId').value,
            date: document.getElementById('incidentDate').value,
            title: document.getElementById('title').value,
            description: document.getElementById('description').value
        };
        
        try {
            // Simulación - reemplazar con API real
            console.log('Enviando incidencia:', formData);
            
            alert('✅ Incidencia reportada exitosamente');
            
            // Limpiar formulario
            document.getElementById('incidentForm').reset();
            document.getElementById('photoPreview').classList.add('hidden');
            document.getElementById('incidentDate').valueAsDate = new Date();
            
            // Recargar lista
            loadMyIncidents();
            
        } catch (error) {
            console.error('Error:', error);
            alert('❌ Error al reportar la incidencia');
        }
    }

    function viewIncident(id) {
        alert('Ver detalles de incidencia #' + id);
    }
    </script>
</body>
</html>
