<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIGI – @yield('title', 'Panel Empleado')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts / Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800 min-h-screen">

<div class="flex min-h-screen">

    {{-- ========== SIDEBAR ========== --}}
    <aside
        id="sidebar"
        class="bg-[#0b0f19] text-white w-64 shrink-0 flex flex-col justify-between 
               fixed md:static inset-y-0 left-0 z-50 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out border-r border-slate-900"
    >
        <div>
            {{-- Brand Logo --}}
            <div class="flex items-center space-x-3 px-6 py-5 border-b border-slate-900">
                <div class="bg-blue-600 p-2.5 rounded-xl shrink-0 shadow-lg shadow-blue-500/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-xl font-black tracking-wider text-white block leading-none">SIGI</span>
                    <span class="text-[9px] font-bold text-slate-500 tracking-widest uppercase block mt-1">INVENTARIO</span>
                </div>
            </div>

            {{-- Sidebar Menu --}}
            <nav class="py-6 px-4 space-y-6">
                <!-- DASHBOARD -->
                <div>
                    <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Dashboard</p>
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-sm font-semibold {{ Request::routeIs('dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/30' }} transition duration-200">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" stroke="currentColor" stroke-width="2"/>
                        </svg>
                        <span>Panel Principal</span>
                    </a>
                </div>

                <!-- MIS OPERACIONES -->
                <div class="space-y-1">
                    <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Mis Operaciones</p>
                    
                    <a href="#" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800/30 transition duration-200">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span>Nueva Venta</span>
                    </a>

                    <a href="#" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800/30 transition duration-200">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <span>Consultar Productos</span>
                    </a>

                    <a href="#" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800/30 transition duration-200">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Mis Ventas</span>
                    </a>
                </div>

                <!-- SESIÓN -->
                <div class="space-y-1">
                    <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Sesión</p>
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <button type="submit" class="w-full flex items-center space-x-3 px-4 py-2.5 rounded-xl text-sm font-medium text-rose-500 hover:bg-rose-500/10 transition duration-200">
                            <svg class="w-5 h-5 shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span>Cerrar Sesión</span>
                        </button>
                    </form>
                </div>
            </nav>
        </div>

        {{-- Security Badge --}}
        <div class="p-4 border-t border-slate-900 flex items-center space-x-2 text-[11px] text-slate-500 font-medium">
            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            <span>Sistema Seguro v1.0</span>
        </div>
    </aside>

    {{-- ========== MAIN AREA ========== --}}
    <div class="flex-1 flex flex-col min-h-screen min-w-0">
        
        {{-- Navbar --}}
        <header class="bg-white border-b border-slate-150 h-20 flex items-center justify-between px-6 sticky top-0 z-40">
            <div class="flex items-center space-x-4">
                {{-- Toggle Button (Mobile only) --}}
                <button id="sidebar-toggle" class="md:hidden p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 leading-tight">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-xs text-slate-400 mt-0.5">Sistema de Gestión SIGI</p>
                </div>
            </div>

            {{-- Right Info --}}
            <div class="flex items-center space-x-5">
                {{-- User Profile Pill --}}
                <div class="flex items-center space-x-3 pl-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-slate-800 leading-none">{{ Auth::user()->nombre }}</p>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ Auth::user()->role->nombre ?? 'Empleado' }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white text-base font-bold shadow-lg shadow-blue-500/20">
                        {{ strtoupper(substr(Auth::user()->nombre, 0, 1)) }}
                    </div>
                </div>
            </div>
        </header>

        {{-- Content Area --}}
        <main class="flex-1 p-6 md:p-8 bg-slate-50">
            @yield('content')
        </main>
    </div>

</div>

{{-- Mobile overlay background --}}
<div id="sidebar-overlay" class="fixed inset-0 bg-slate-900/40 z-40 hidden md:hidden transition-opacity"></div>

<script>
    const toggleBtn = document.getElementById('sidebar-toggle');
    const sidebar   = document.getElementById('sidebar');
    const overlay   = document.getElementById('sidebar-overlay');

    function toggleMenu() {
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }

    toggleBtn?.addEventListener('click', toggleMenu);
    overlay?.addEventListener('click', toggleMenu);
</script>
</body>
</html>
