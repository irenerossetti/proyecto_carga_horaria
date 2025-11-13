<aside class="w-64 bg-gray-900 text-white flex flex-col fixed h-full">
    <div class="p-6 border-b border-gray-800">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-brand-primary rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/></svg>
            </div>
            <div><h1 class="font-bold text-sm">FICCT SGA</h1><p class="text-xs text-gray-400">GESTIÓN ACADÉMICA</p></div>
        </div>
    </div>
    <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
        <h3 class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">PRINCIPAL</h3>
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            Dashboard
        </a>
        <h3 class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2">ACADÉMICO</h3>
        <a href="{{ route('periods.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('periods.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Periodos
        </a>
        <a href="{{ route('teachers.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('teachers.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            Docentes
        </a>
        <a href="{{ route('students.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('students.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            Estudiantes
        </a>
        <a href="{{ route('rooms.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('rooms.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            Aulas
        </a>
        <h3 class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2">SISTEMA</h3>
        <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('reports.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Reportes
        </a>
        <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('settings.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Configuración
        </a>
    </nav>
    <div class="p-4 border-t border-gray-800">
        <div class="flex items-center gap-3 px-3 py-2">
            <div class="w-8 h-8 bg-brand-primary rounded-full flex items-center justify-center">
                <span class="text-sm font-bold">{{ substr(Auth::user()->name ?? 'A', 0, 1) }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium truncate">{{ Auth::user()->name ?? 'Administrador' }}</p>
                <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email ?? '' }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit" class="w-full text-left px-3 py-2 text-sm text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Cerrar Sesión
            </button>
        </form>
    </div>
</aside>
