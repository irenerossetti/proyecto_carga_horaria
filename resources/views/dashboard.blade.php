@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Panel Administrativo')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    <!-- Tarjetas de estadísticas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Docentes Activos -->
        <div class="bg-white p-6 rounded-xl shadow-card border border-neutral-200/60 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-auto">
                <p class="text-sm font-medium text-neutral-500">Docentes Activos</p>
                <p class="text-3xl font-bold text-neutral-900 mt-1">{{ $totalTeachers ?? 0 }}</p>
            </div>
        </div>

        <!-- Total Estudiantes -->
        <div class="bg-white p-6 rounded-xl shadow-card border border-neutral-200/60 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-green-50 rounded-lg text-green-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-auto">
                <p class="text-sm font-medium text-neutral-500">Total Estudiantes</p>
                <p class="text-3xl font-bold text-neutral-900 mt-1">{{ $totalStudents ?? 0 }}</p>
            </div>
        </div>

        <!-- Asistencias Hoy -->
        <div class="bg-white p-6 rounded-xl shadow-card border border-neutral-200/60 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-purple-50 rounded-lg text-purple-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-auto">
                <p class="text-sm font-medium text-neutral-500">Asistencias Hoy</p>
                <p class="text-3xl font-bold text-neutral-900 mt-1">{{ $todayAttendances ?? 0 }}</p>
            </div>
        </div>

        <!-- Total Materias -->
        <div class="bg-white p-6 rounded-xl shadow-card border border-neutral-200/60 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-orange-50 rounded-lg text-orange-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>
            <div class="mt-auto">
                <p class="text-sm font-medium text-neutral-500">Total Materias</p>
                <p class="text-3xl font-bold text-neutral-900 mt-1">{{ $totalSubjects ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Clases en Curso y Próximas -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-card border border-neutral-200/60 overflow-hidden">
            <div class="px-6 py-4 border-b border-neutral-100 flex items-center justify-between">
                <h3 class="font-semibold text-neutral-900">Clases en Curso y Próximas</h3>
                <a href="#" class="text-sm font-medium text-brand-primary hover:text-brand-hover">Ver todas</a>
            </div>
            <div class="divide-y divide-neutral-100">
                
                <!-- Clase en curso -->
                <div class="p-5 flex items-center gap-4 hover:bg-neutral-50 transition-colors">
                    <div class="w-20 flex-shrink-0 text-center">
                        <span class="block text-sm font-bold text-green-600">En curso</span>
                        <span class="text-xs text-neutral-500">hasta 10:30</span>
                    </div>
                    <div class="h-12 w-1 rounded-full bg-green-500"></div>
                    <div class="flex-1">
                        <h4 class="font-medium text-neutral-900">Algoritmos y Programación I</h4>
                        <div class="flex items-center gap-4 mt-1 text-sm text-neutral-500">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Dr. Gomez
                            </span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Aula A-101
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Próxima clase -->
                <div class="p-5 flex items-center gap-4 hover:bg-neutral-50 transition-colors">
                    <div class="w-20 flex-shrink-0 text-center">
                        <span class="block text-sm font-bold text-blue-600">10:30 AM</span>
                        <span class="text-xs text-neutral-500">a 12:00</span>
                    </div>
                    <div class="h-12 w-1 rounded-full bg-blue-500"></div>
                    <div class="flex-1">
                        <h4 class="font-medium text-neutral-900">Estructuras de Datos</h4>
                        <div class="flex items-center gap-4 mt-1 text-sm text-neutral-500">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Ing. Martinez
                            </span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Aula B-205
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Otra próxima clase -->
                <div class="p-5 flex items-center gap-4 hover:bg-neutral-50 transition-colors">
                    <div class="w-20 flex-shrink-0 text-center">
                        <span class="block text-sm font-bold text-blue-600">16:00 PM</span>
                        <span class="text-xs text-neutral-500">a 17:30</span>
                    </div>
                    <div class="h-12 w-1 rounded-full bg-blue-500"></div>
                    <div class="flex-1">
                        <h4 class="font-medium text-neutral-900">Bases de Datos I</h4>
                        <div class="flex items-center gap-4 mt-1 text-sm text-neutral-500">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Dr. Valdez
                            </span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Lab. Computación
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar derecho con estadísticas -->
        <div class="space-y-6">
            
            <!-- Aulas Libres Hoy -->
            <div class="bg-white rounded-xl shadow-card border border-neutral-200/60 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-semibold text-neutral-900">Aulas Libres Hoy</h4>
                    <span class="text-2xl font-bold text-blue-600">23</span>
                </div>
                <div class="w-full bg-neutral-100 rounded-full h-2">
                    <div class="bg-blue-500 h-2 rounded-full" style="width: 65%"></div>
                </div>
                <p class="text-sm text-neutral-500 mt-2">65% de disponibilidad</p>
            </div>

            <!-- Materias Activas -->
            <div class="bg-white rounded-xl shadow-card border border-neutral-200/60 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-semibold text-neutral-900">Materias Activas</h4>
                    <span class="text-2xl font-bold text-green-600">35</span>
                </div>
                <div class="w-full bg-neutral-100 rounded-full h-2">
                    <div class="bg-green-500 h-2 rounded-full" style="width: 85%"></div>
                </div>
                <p class="text-sm text-neutral-500 mt-2">85% del total</p>
            </div>

            <!-- Calendario Mini -->
            <div class="bg-white rounded-xl shadow-card border border-neutral-200/60 p-6">
                <h4 class="font-semibold text-neutral-900 mb-4">Noviembre 2025</h4>
                <div class="grid grid-cols-7 gap-1 text-center text-sm">
                    <div class="text-neutral-500 font-medium">L</div>
                    <div class="text-neutral-500 font-medium">M</div>
                    <div class="text-neutral-500 font-medium">M</div>
                    <div class="text-neutral-500 font-medium">J</div>
                    <div class="text-neutral-500 font-medium">V</div>
                    <div class="text-neutral-500 font-medium">S</div>
                    <div class="text-neutral-500 font-medium">D</div>
                    
                    <!-- Días del mes (simplificado) -->
                    @for($i = 1; $i <= 30; $i++)
                        <div class="p-1 {{ $i == 10 ? 'bg-brand-primary text-white rounded-full' : 'text-neutral-700' }}">
                            {{ $i }}
                        </div>
                    @endfor
                </div>
            </div>

        </div>
    </div>
</div>
@endsection