<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIGI – @yield('title', 'Panel')</title>

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
                <!-- DASHBOARD SECTION -->
                <div class="space-y-1">
                    <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Dashboard</p>
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-sm font-semibold {{ Request::routeIs('dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/30' }} transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        <span>Panel Principal</span>
                    </a>
                </div>

                <!-- GESTIÓN COMERCIAL SECTION -->
                <div class="space-y-1 pt-4">
                    <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Gestión Comercial</p>
                    
                    <a href="#" class="flex items-center justify-between px-4 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800/30 transition duration-200">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            <span>Productos</span>
                        </div>
                    </a>

                    <a href="#" class="flex items-center justify-between px-4 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800/30 transition duration-200">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            <span>Categorías</span>
                        </div>
                    </a>

                    <a href="#" class="flex items-center justify-between px-4 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800/30 transition duration-200">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span>Punto de Venta</span>
                        </div>
                    </a>

                    <a href="#" class="flex items-center justify-between px-4 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800/30 transition duration-200">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            <span>Historial de Ventas</span>
                        </div>
                    </a>

                    <a href="#" class="flex items-center justify-between px-4 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800/30 transition duration-200">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            <span>Inventario</span>
                        </div>
                    </a>

                    <a href="#" class="flex items-center justify-between px-4 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800/30 transition duration-200">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span>Alertas de Stock</span>
                        </div>
                        <span class="bg-red-500 text-white text-[9px] font-black w-4 h-4 rounded-full flex items-center justify-center">!</span>
                    </a>

                    <a href="#" class="flex items-center justify-between px-4 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800/30 transition duration-200">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Proveedores</span>
                        </div>
                    </a>
                </div>

                <!-- ADMINISTRACIÓN SECTION -->
                <div class="space-y-1 pt-4">
                    <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Administración</p>

                    <a href="{{ route('usuarios.index') }}" class="flex items-center justify-between px-4 py-2.5 rounded-xl text-sm font-semibold {{ Request::routeIs('usuarios.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/30' }} transition duration-200">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <span>Usuarios</span>
                        </div>
                    </a>

                    <a href="#" class="flex items-center justify-between px-4 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800/30 transition duration-200">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Reportes</span>
                        </div>
                    </a>

                    <a href="#" class="flex items-center justify-between px-4 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800/30 transition duration-200">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Configuración</span>
                        </div>
                    </a>
                </div>
            </nav>
        </div>

        {{-- Logout Section --}}
        <div class="p-4 border-t border-slate-900">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center space-x-3 px-4 py-2.5 rounded-xl text-sm text-slate-400 hover:text-white hover:bg-slate-800/20 transition duration-200">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span>Cerrar Sesión</span>
                </button>
            </form>
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
                {{-- Notification Bell --}}
                <div class="relative cursor-pointer hover:bg-slate-50 p-2 rounded-full transition">
                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="absolute top-1 right-1 bg-red-500 text-white text-[9px] font-black w-4.5 h-4.5 rounded-full flex items-center justify-center border-2 border-white shadow">2</span>
                </div>

                {{-- User Profile Pill --}}
                <div class="flex items-center space-x-3 pl-4 border-l border-slate-150">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-slate-800 leading-none">{{ Auth::user()->nombre }}</p>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ Auth::user()->role->nombre ?? 'Usuario' }}</p>
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