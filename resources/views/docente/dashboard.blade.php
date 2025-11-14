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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <span class="font-bold text-lg tracking-tight">FICCT <span class="opacity-80 font-normal">Docente</span></span>
                </div>
                
                <div class="flex items-center gap-4">
                    <span class="text-sm hidden sm:block opacity-90">
                        {{ Auth::user()->name ?? 'Docente' }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-2 bg-white/10 rounded-full hover:bg-white/20 transition-colors" title="Cerrar Sesión">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Mi Agenda de Hoy</h1>
                <p class="text-gray-500">{{ now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
            </div>
            @if($schedulesToday->count() > 0)
                <div class="bg-white px-4 py-2 rounded-full shadow-sm border border-gray-200 text-sm font-medium text-gray-700 flex items-center gap-2 self-start sm:self-auto">
                    <span class="flex h-3 w-3 relative justify-center items-center">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </span>
                    {{ $schedulesToday->count() }} clases programadas hoy
                </div>
            @endif
        </div>

        @if (session('status'))
            <div class="mb-6 rounded-lg bg-green-50 p-4 text-sm text-green-800 border-l-4 border-green-500 shadow-sm">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-green-500 mr-3" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-medium">{{ session('status') }}</span>
                </div>
            </div>
        @endif
        @if (session('error'))
             <div class="mb-6 rounded-lg bg-red-50 p-4 text-sm text-red-800 border-l-4 border-red-500 shadow-sm">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-red-500 mr-3" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 space-y-8">

                @if($currentClass)
                    <div class="bg-white rounded-2xl shadow-lg border-2 border-brand-primary/20 overflow-hidden relative">
                        <div class="bg-brand-primary text-white text-sm font-bold uppercase tracking-wider py-1.5 px-6 flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <span class="relative flex h-3 w-3">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-50"></span>
                                  <span class="relative inline-flex rounded-full h-3 w-3 bg-white"></span>
                                </span>
                                En Curso Ahora
                            </div>
                            <span>{{ substr($currentClass->start_time, 0, 5) }} - {{ substr($currentClass->end_time, 0, 5) }}</span>
                        </div>

                        <div class="p-6 sm:p-8">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                                <div>
                                    <h2 class="text-3xl font-bold text-gray-900">
                                        {{ $currentClass->assignment->group->subject->name ?? 'Materia Desconocida' }}
                                    </h2>
                                    <p class="text-lg text-gray-600 mt-1">
                                        Grupo {{ $currentClass->assignment->group->code ?? '--' }}
                                    </p>
                                </div>
                                <div class="mt-4 sm:mt-0 bg-gray-100 px-4 py-2 rounded-xl flex items-center gap-2">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="text-xl font-bold text-gray-800">{{ $currentClass->room->name ?? 'Sin Aula' }}</span>
                                </div>
                            </div>

                            @if($currentClass->attendanceToday->isNotEmpty())
                                <div class="mb-6 bg-green-50 text-green-800 px-4 py-3 rounded-lg flex items-center gap-2 font-medium border border-green-200">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    Ya has marcado asistencia para esta clase.
                                </div>
                            @endif

                            @if($currentClass->cancellation)
                                <div class="mb-6 bg-blue-50 text-blue-800 px-4 py-3 rounded-lg flex items-center gap-2 font-medium border border-blue-200">
                                     <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                    Clase cambiada a modalidad {{ ucfirst($currentClass->cancellation->cancellation_type) }}.
                                </div>
                            @endif

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @if($currentClass->attendanceToday->isEmpty() && !$currentClass->cancellation)
                                    <form method="POST" action="{{ route('docente.asistencia.store', $currentClass->id) }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center justify-center gap-3 bg-brand-primary hover:bg-brand-hover text-white font-semibold px-6 py-4 rounded-xl transition-all active:scale-95 shadow-md hover:shadow-lg">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            MARCAR ASISTENCIA
                                        </button>
                                    </form>
                                @endif

                                @if(!$currentClass->cancellation)
                                    <form method="POST" action="{{ route('docente.clase.virtual', $currentClass->id) }}" onsubmit="return confirm('¿Estás seguro de cambiar esta clase a VIRTUAL? Se notificará a los estudiantes.');">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center justify-center gap-3 bg-white border-2 border-blue-600 text-blue-700 font-semibold px-6 py-4 rounded-xl transition-all hover:bg-blue-50 active:scale-95">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                            Cambiar a Virtual
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @elseif($schedulesToday->count() > 0 && $upcomingClasses->isEmpty())
                     <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200 text-center">
                        <div class="inline-flex bg-green-100 p-4 rounded-full text-green-600 mb-4">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">¡Jornada Completada!</h2>
                        <p class="text-gray-500 mt-2">Has finalizado todas tus clases programadas para hoy.</p>
                    </div>
                @elseif($schedulesToday->isEmpty())
                    <div class="bg-white p-12 rounded-2xl shadow-sm border border-gray-200 text-center">
                        <img src="https://placehold.co/200x150/f3f4f6/a3a3a3?text=Día+Libre" alt="Relax" class="mx-auto mb-6 opacity-50 grayscale">
                        <h2 class="text-xl font-bold text-gray-900">Hoy no tienes clases programadas</h2>
                        <p class="text-gray-500 mt-2">Aprovecha el tiempo para preparar material o descansar.</p>
                    </div>
                @else
                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200 text-center">
                        <div class="inline-flex bg-amber-100 p-4 rounded-full text-amber-600 mb-4">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">No tienes clase en este momento</h2>
                        <p class="text-gray-500 mt-2">Revisa tus próximas clases a continuación.</p>
                    </div>
                @endif

                @if($upcomingClasses->isNotEmpty())
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Próximas Clases de Hoy
                        </h3>
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 divide-y divide-gray-100">
                            @foreach($upcomingClasses as $schedule)
                                <div class="p-5 flex flex-col sm:flex-row sm:items-center gap-4 hover:bg-gray-50 transition-colors">
                                    <div class="sm:w-32 flex-shrink-0">
                                        <p class="text-lg font-bold text-gray-900">
                                            {{ substr($schedule->start_time, 0, 5) }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            a {{ substr($schedule->end_time, 0, 5) }}
                                        </p>
                                    </div>
                                    
                                    <div class="flex-1">
                                        <h4 class="font-bold text-gray-900">
                                            {{ $schedule->assignment->group->subject->name ?? 'Materia' }}
                                        </h4>
                                        <div class="flex items-center gap-4 mt-1 text-sm text-gray-600">
                                            <span>Grupo {{ $schedule->assignment->group->code ?? '--' }}</span>
                                            <span class="flex items-center gap-1 bg-gray-100 px-2 py-0.5 rounded">
                                                <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                {{ $schedule->room->name ?? 'Sin Aula' }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            <div class="space-y-6">
                
                <div class="bg-brand-primary rounded-xl shadow-lg p-6 text-white relative overflow-hidden">
                     <svg class="absolute -right-10 -bottom-10 w-40 h-40 text-white/10" fill="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z" /><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" /></svg>
                    
                    <h3 class="text-lg font-bold mb-2 relative z-10">Accesos Rápidos</h3>
                    <p class="text-white/80 text-sm mb-6 relative z-10">
                        Gestiona tus actividades académicas desde aquí.
                    </p>
                    <div class="space-y-3 relative z-10">
                        <a href="{{ route('docente.weekly-schedule') }}" class="w-full text-left flex items-center gap-3 bg-white/10 hover:bg-white/20 p-3 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            <span>Ver Horario Semanal Completo</span>
                        </a>
                        <a href="{{ route('docente.attendance-history') }}" class="w-full text-left flex items-center gap-3 bg-white/10 hover:bg-white/20 p-3 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            <span>Historial de Asistencias</span>
                        </a>
                        <a href="{{ route('docente.report-incident') }}" class="w-full text-left flex items-center gap-3 bg-white/10 hover:bg-white/20 p-3 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            <span>Reportar Incidencia en Aula</span>
                        </a>
                        <a href="{{ route('docente.justifications') }}" class="w-full text-left flex items-center gap-3 bg-white/10 hover:bg-white/20 p-3 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            <span>Mis Justificaciones</span>
                        </a>
                    </div>
                </div>

                @if($schedulesToday->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                        <h3 class="font-semibold text-gray-900 mb-4">Resumen del Día</h3>
                        <ul class="space-y-4">
                            <li class="flex justify-between text-sm">
                                <span class="text-gray-500">Total clases hoy</span>
                                <span class="font-medium text-gray-900">{{ $schedulesToday->count() }}</span>
                            </li>
                             <li class="flex justify-between text-sm">
                                <span class="text-gray-500">Horas lectivas</span>
                                <span class="font-medium text-gray-900">
                                    {{ number_format($schedulesToday->sum(fn($s) => (strtotime($s->end_time) - strtotime($s->start_time)) / 3600), 1) }} hrs
                                </span>
                            </li>
                        </ul>
                    </div>
                @endif

            </div>

        </div>
    </main>

</body>
</html>