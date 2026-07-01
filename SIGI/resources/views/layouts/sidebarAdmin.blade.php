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
                @yield('sidebar-menu')
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