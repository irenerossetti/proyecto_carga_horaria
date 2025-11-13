<!DOCTYPE html>
<html lang="es" class="h-full bg-[#F5F5F5]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión - FICCT SGA</title>

    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Instrument Sans', 'sans-serif'] },
                    colors: {
                        brand: {
                            primary: '#881F34',
                            hover: '#6d1829'
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="min-h-full font-sans antialiased text-neutral-900 bg-[#F5F5F5]">

    <nav class="bg-brand-primary text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <span class="font-bold text-lg tracking-tight">FICCT <span class="opacity-80 font-normal">Estudiante</span></span>
                </div>
                
                <div class="flex items-center gap-4">
                    <span class="text-sm hidden sm:block opacity-90">{{ Auth::user()->name ?? 'Estudiante' }}</span>
                     <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-2 bg-white/10 rounded-full hover:bg-white/20 transition-colors" title="Cerrar Sesión">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h1 class="text-2xl font-bold text-gray-900">Mis Materias</h1>
                <div class="text-sm font-medium text-gray-500 bg-white px-4 py-2 rounded-full shadow-sm border border-gray-100 inline-flex items-center gap-2 self-start sm:self-auto">
                <svg class="w-4 h-4 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                {{ ($currentDate ?? now())->format('d/m/Y') }}
            </div>
        </div>

        <!-- Notificaciones -->
        <div class="space-y-3">
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg flex items-start gap-3 shadow-sm">
                <div class="p-1 bg-blue-100 rounded-full text-blue-600 mt-0.5">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-blue-900">¡Clase Virtual Hoy!</h3>
                    <p class="text-sm text-blue-800 mt-0.5">
                        Tu clase de <span class="font-semibold">Ingeniería de Software I</span> (14:00) será virtual.
                        <a href="#" class="underline hover:text-blue-600 ml-1">Ver enlace Zoom</a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Lista de Materias -->
        @if(isset($enrolledSubjects) && count($enrolledSubjects) > 0)
            <!-- Aquí se mostrarían las materias reales del estudiante cuando se implemente la inscripción -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-6">
                @foreach($enrolledSubjects as $subject)
                    <!-- Materias dinámicas -->
                @endforeach
            </div>
        @else
            <!-- Vista de ejemplo mientras no hay sistema de inscripción -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-6">
            
            <!-- Materia 1 -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                <div class="h-2 bg-brand-primary"></div>
                <div class="p-5">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-bold text-lg text-gray-900 leading-tight">Sistemas de Información I</h3>
                            <p class="text-sm text-gray-500">Grupo SC</p>
                        </div>
                        <span class="px-2 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded-md border border-green-100">
                            Normal
                        </span>
                    </div>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center gap-3 text-gray-700">
                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <div>
                                <p class="font-medium">Lun, Mie, Vie</p>
                                <p class="text-gray-500">07:00 - 08:30</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 text-gray-700">
                             <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            <span class="font-medium">Aula 217-23</span>
                        </div>
                        <div class="flex items-center gap-3 text-gray-700">
                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            <span>MSc. Angélica Garzón</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Materia 2 (Ejemplo con cambio) -->
            <div class="bg-white rounded-xl shadow-sm border border-amber-300 overflow-hidden hover:shadow-md transition-shadow relative">
                <div class="h-2 bg-amber-500"></div>
                <span class="absolute top-4 right-4 h-3 w-3 bg-amber-500 rounded-full animate-pulse"></span>

                <div class="p-5">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-bold text-lg text-gray-900 leading-tight">Base de Datos II</h3>
                            <p class="text-sm text-gray-500">Grupo SA</p>
                        </div>
                        <span class="px-2 py-1 bg-amber-50 text-amber-700 text-xs font-semibold rounded-md border border-amber-200">
                            Cambio Aula
                        </span>
                    </div>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center gap-3 text-gray-700">
                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <div>
                                <p class="font-medium">Mar, Jue</p>
                                <p class="text-gray-500">09:00 - 11:30</p>
                            </div>
                        </div>
                         <div class="flex items-center gap-3 text-amber-800 bg-amber-50 p-2 -mx-2 rounded-lg">
                             <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            <span class="font-bold">HOY: Aula 236-21</span>
                        </div>
                    </div>
                </div>
            </div>

             <!-- Materia 3 (Ejemplo Virtual) -->
             <div class="bg-white rounded-xl shadow-sm border border-blue-300 overflow-hidden hover:shadow-md transition-shadow">
                <div class="h-2 bg-blue-500"></div>
                <div class="p-5">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-bold text-lg text-gray-900 leading-tight">Ingeniería de Software I</h3>
                            <p class="text-sm text-gray-500">Grupo SB</p>
                        </div>
                        <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-md border border-blue-200">
                            Virtual
                        </span>
                    </div>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center gap-3 text-gray-700">
                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <div>
                                <p class="font-medium">Lun, Mie</p>
                                <p class="text-gray-500">14:00 - 15:30</p>
                            </div>
                        </div>
                         <div class="flex items-center gap-3 text-blue-800 bg-blue-50 p-2 -mx-2 rounded-lg">
                             <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            <a href="#" class="font-bold underline">Unirse a la clase (Zoom)</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        @endif
    </main>

</body>
</html>
