<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - FICCT SGA</title>
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

    <main class="flex-1 lg:ml-64 p-4 sm:p-6 lg:p-8">
        <!-- Header -->
        <div class="mb-6 sm:mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Panel Administrativo</h1>
            <p class="text-gray-500 mt-1 text-sm sm:text-base">Bienvenido al sistema de gestión académica</p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
            <!-- Docentes Activos -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Docentes Activos</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalTeachers ?? 0 }}</p>
                        <span class="inline-flex items-center text-xs font-semibold text-green-600 mt-2">
                            +12%
                            <svg class="w-3 h-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                        </span>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Estudiantes -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Estudiantes</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalStudents ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Aulas Libres Hoy -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Aulas Libres Hoy</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $freeRoomsToday ?? 0 }}</p>
                        <span class="inline-flex items-center text-xs font-semibold text-red-600 mt-2">-2</span>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Materias Activas -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Materias Activas</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalSubjects ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección Principal -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Clases en Curso y Próximas -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900">Clases en Curso y Próximas</h3>
                    <a href="#" class="text-sm font-medium text-brand-primary hover:text-brand-hover">Ver todas</a>
                </div>
                <div class="divide-y divide-gray-100">
                    @if($currentClass)
                    <!-- Clase en curso -->
                    <div class="p-5 flex items-center gap-4 hover:bg-gray-50 transition-colors">
                        <div class="w-20 flex-shrink-0 text-center">
                            <span class="block text-sm font-bold text-green-600">En curso</span>
                            <span class="text-xs text-gray-500">hasta {{ \Carbon\Carbon::parse($currentClass->end_time)->format('H:i') }}</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">{{ $currentClass->assignment->subject->name ?? 'Materia' }}</h4>
                            <p class="text-sm text-gray-500">{{ $currentClass->assignment->teacher->user->name ?? 'Docente' }} • Aula {{ $currentClass->room->name ?? 'N/A' }}</p>
                        </div>
                        <div class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Activa</div>
                    </div>
                    @endif

                    @forelse($upcomingSchedules as $schedule)
                    <div class="p-5 flex items-center gap-4 hover:bg-gray-50 transition-colors">
                        <div class="w-20 flex-shrink-0 text-center">
                            <span class="block text-lg font-bold text-gray-900">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}</span>
                            <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">{{ $schedule->assignment->subject->name ?? 'Materia' }}</h4>
                            <p class="text-sm text-gray-500">{{ $schedule->assignment->teacher->user->name ?? 'Docente' }} • Aula {{ $schedule->room->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    @empty
                    @if(!$currentClass)
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-gray-500">No hay clases programadas para hoy</p>
                    </div>
                    @endif
                    @endforelse
                </div>
            </div>

            <!-- Calendario y Eventos -->
            <div class="space-y-6">
                <!-- Mini Calendario -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">{{ now()->format('F Y') }}</h3>
                    <div class="grid grid-cols-7 gap-2 text-center text-xs">
                        <div class="font-semibold text-gray-500">L</div>
                        <div class="font-semibold text-gray-500">M</div>
                        <div class="font-semibold text-gray-500">M</div>
                        <div class="font-semibold text-gray-500">J</div>
                        <div class="font-semibold text-gray-500">V</div>
                        <div class="font-semibold text-gray-500">S</div>
                        <div class="font-semibold text-gray-500">D</div>
                        
                        @php
                            $today = now();
                            $firstDay = $today->copy()->startOfMonth();
                            $lastDay = $today->copy()->endOfMonth();
                            $startPadding = ($firstDay->dayOfWeek + 6) % 7;
                        @endphp
                        
                        @for($i = 0; $i < $startPadding; $i++)
                            <div class="py-2"></div>
                        @endfor
                        
                        @for($day = 1; $day <= $lastDay->day; $day++)
                            <div class="py-2 {{ $day == $today->day ? 'bg-brand-primary text-white rounded-full font-bold' : 'text-gray-700' }}">
                                {{ $day }}
                            </div>
                        @endfor
                    </div>
                </div>

                <!-- Próximos Eventos -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Próximos Eventos</h3>
                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <div class="flex-shrink-0 text-center">
                                <div class="text-xs text-gray-500 uppercase">NOV</div>
                                <div class="text-2xl font-bold text-brand-primary">10</div>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">Inicio 2do Parcial</h4>
                                <p class="text-sm text-gray-500">Toda la facultad</p>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <div class="flex-shrink-0 text-center">
                                <div class="text-xs text-gray-500 uppercase">NOV</div>
                                <div class="text-2xl font-bold text-brand-primary">25</div>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">Entrega de Notas</h4>
                                <p class="text-sm text-gray-500">Fecha límite docentes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

</body>
</html>
