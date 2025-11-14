<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'FICCT SGA') - Coordinador</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Instrument Sans', 'sans-serif'] },
                    colors: {
                        brand: { 
                            primary: '#881F34', 
                            hover: '#6d1829',
                            light: '#FDF2F4'
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="h-full font-sans antialiased text-gray-900 bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        
        <!-- Overlay para móvil -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>
        
        <!-- Sidebar -->
        <div id="sidebar" class="w-64 bg-brand-primary flex flex-col text-white flex-shrink-0 fixed lg:relative h-full transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out z-50">
            <div class="h-16 flex items-center px-6 border-b border-white/10">
                <span class="font-bold text-lg tracking-tight">FICCT <span class="opacity-80 font-normal">Coordinador</span></span>
            </div>
            
            <nav class="flex-1 py-6 px-3 space-y-1 overflow-y-auto">
                <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2.5 gap-3 {{ request()->routeIs('dashboard') ? 'bg-white/10' : 'text-white/70 hover:text-white hover:bg-white/5' }} rounded-lg transition-colors">
                    <i class="fas fa-th-large w-5"></i>
                    <span class="text-sm font-medium">Panel Control</span>
                </a>
                
                <div class="text-xs font-semibold text-white/40 uppercase tracking-wider mt-6 mb-2 px-2">Validaciones</div>
                
                <a href="{{ route('coordinator.workload-validation') }}" class="flex items-center px-3 py-2.5 gap-3 {{ request()->routeIs('coordinator.workload-validation') ? 'bg-white/10' : 'text-white/70 hover:text-white hover:bg-white/5' }} rounded-lg transition-colors">
                    <i class="fas fa-clipboard-check w-5"></i>
                    <span class="text-sm font-medium">Carga Horaria</span>
                </a>
                
                <a href="{{ route('coordinator.schedule-validation') }}" class="flex items-center px-3 py-2.5 gap-3 {{ request()->routeIs('coordinator.schedule-validation') ? 'bg-white/10' : 'text-white/70 hover:text-white hover:bg-white/5' }} rounded-lg transition-colors">
                    <i class="fas fa-calendar-check w-5"></i>
                    <span class="text-sm font-medium">Horarios</span>
                </a>
                
                <div class="text-xs font-semibold text-white/40 uppercase tracking-wider mt-6 mb-2 px-2">Reportes</div>
                
                <a href="{{ route('coordinator.attendance-reports') }}" class="flex items-center px-3 py-2.5 gap-3 {{ request()->routeIs('coordinator.attendance-reports') ? 'bg-white/10' : 'text-white/70 hover:text-white hover:bg-white/5' }} rounded-lg transition-colors">
                    <i class="fas fa-chart-bar w-5"></i>
                    <span class="text-sm font-medium">Asistencia</span>
                </a>
                
                <div class="text-xs font-semibold text-white/40 uppercase tracking-wider mt-6 mb-2 px-2">Gestión</div>
                
                <a href="#" class="flex items-center px-3 py-2.5 gap-3 text-white/70 hover:text-white hover:bg-white/5 rounded-lg transition-colors">
                    <i class="fas fa-door-open w-5"></i>
                    <span class="text-sm font-medium">Gestión Aulas</span>
                </a>
                
                <a href="#" class="flex items-center px-3 py-2.5 gap-3 text-white/70 hover:text-white hover:bg-white/5 rounded-lg transition-colors">
                    <i class="fas fa-exclamation-triangle w-5"></i>
                    <span class="text-sm font-medium">Conflictos</span>
                </a>
            </nav>
            
            <div class="p-4 border-t border-white/10 mt-auto">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-sm font-bold">
                        {{ substr(Auth::user()->name ?? 'C', 0, 1) }}
                    </div>
                    <div class="flex-1 overflow-hidden">
                        <p class="text-sm font-medium truncate">{{ Auth::user()->name ?? 'Coordinador' }}</p>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-xs text-white/50 hover:text-white truncate text-left w-full">Cerrar Sesión</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenido Principal -->
        <div class="flex-1 flex flex-col overflow-hidden w-full">
            <!-- Header -->
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 lg:px-8 flex-shrink-0">
                <button id="menuButton" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-bars text-gray-600 text-xl"></i>
                </button>
                
                <h2 class="text-base sm:text-lg font-semibold text-gray-900">@yield('header', 'Panel de Coordinación')</h2>
                
                <div class="flex items-center gap-2 sm:gap-4">
                    <span class="text-xs sm:text-sm text-gray-500 font-medium hidden sm:inline">
                        {{ date('d/m/Y') }}
                    </span>
                </div>
            </header>

            <!-- Contenido -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>
    
    <script>
        const menuButton = document.getElementById('menuButton');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        
        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
        
        menuButton?.addEventListener('click', toggleSidebar);
        overlay?.addEventListener('click', toggleSidebar);
        
        if (window.innerWidth < 1024) {
            document.querySelectorAll('#sidebar a').forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 1024) {
                        toggleSidebar();
                    }
                });
            });
        }
    </script>
</body>
</html>
