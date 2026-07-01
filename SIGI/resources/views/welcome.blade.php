<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>SIGI - Sistema de Gestión de Inventario</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-800 bg-slate-50 min-h-screen flex flex-col justify-between selection:bg-indigo-500 selection:text-white">
        
        <!-- Header / Navigation -->
        <header class="w-full py-6 px-6 lg:px-12 bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-slate-100">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center space-x-2">
                    <div class="bg-indigo-600 p-2 rounded-xl text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold tracking-wider text-slate-900">SIGI</span>
                </div>

                <!-- Navigation Links -->
                <nav class="flex items-center space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 px-5 py-2.5 rounded-full transition duration-200">
                                Ir al Panel
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition duration-200">
                                Iniciar Sesión
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 px-5 py-2.5 rounded-full shadow-lg shadow-indigo-600/10 hover:shadow-indigo-600/20 transition duration-200">
                                    Registrarse
                                </a>
                            @endif
                        @endauth
                    @endif
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow">
            <!-- Hero Section -->
            <section class="relative py-20 px-6 lg:px-12 overflow-hidden bg-white">
                <div class="absolute inset-0 opacity-40 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-indigo-200 via-transparent to-transparent"></div>
                
                <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center relative z-10">
                    <!-- Text Info -->
                    <div class="space-y-6">
                        <div class="inline-flex items-center space-x-2 bg-indigo-50 text-indigo-700 px-4 py-1.5 rounded-full text-xs font-semibold tracking-wide">
                            <span>Sistema de Gestión Comercial y Logística</span>
                        </div>
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight leading-tight">
                            Control comercial y de inventario al <span class="text-indigo-600">siguiente nivel</span>
                        </h1>
                        <p class="text-slate-500 text-base sm:text-lg leading-relaxed font-light max-w-xl">
                            Administra stock en tiempo real, procesa ventas rápidas y gestiona tus proveedores desde una plataforma centralizada y fácil de usar.
                        </p>
                        
                        <div class="pt-4 flex flex-wrap gap-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center px-6 py-3.5 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-xl shadow-indigo-600/20 transition duration-200">
                                    Acceder al Panel
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 py-3.5 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-xl shadow-indigo-600/20 transition duration-200">
                                    Empezar Ahora
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-3.5 rounded-xl text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition duration-200">
                                    Ingresar al Sistema
                                </a>
                            @endauth
                        </div>
                    </div>

                    <!-- Hero Visual / Dashboard Preview Mockup -->
                    <div class="relative">
                        <div class="absolute inset-0 bg-indigo-500/10 rounded-3xl blur-3xl -z-10"></div>
                        <div class="bg-gradient-to-br from-slate-900 to-indigo-950 rounded-2xl shadow-2xl p-6 text-white border border-slate-800">
                            <!-- Visual Top Bar -->
                            <div class="flex items-center justify-between border-b border-slate-800 pb-4 mb-6">
                                <div class="flex space-x-2">
                                    <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                    <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                    <div class="w-3 h-3 rounded-full bg-green-500"></div>
                                </div>
                                <span class="text-xs text-slate-400 font-mono">dashboard.sigi.app</span>
                            </div>
                            
                            <!-- Visual Dashboard Data Mock -->
                            <div class="space-y-6">
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="bg-slate-800/50 p-4 rounded-xl border border-slate-700/50">
                                        <span class="text-[10px] text-slate-400 uppercase tracking-wider block">Productos</span>
                                        <span class="text-2xl font-bold mt-1 block">142</span>
                                    </div>
                                    <div class="bg-slate-800/50 p-4 rounded-xl border border-slate-700/50">
                                        <span class="text-[10px] text-slate-400 uppercase tracking-wider block">Ventas Hoy</span>
                                        <span class="text-2xl font-bold mt-1 block">34</span>
                                    </div>
                                    <div class="bg-slate-800/50 p-4 rounded-xl border border-slate-700/50">
                                        <span class="text-[10px] text-slate-400 uppercase tracking-wider block">Proveedores</span>
                                        <span class="text-2xl font-bold mt-1 block">12</span>
                                    </div>
                                </div>
                                
                                <!-- Fake Graph representation -->
                                <div class="bg-slate-800/30 p-4 rounded-xl border border-slate-800">
                                    <span class="text-xs font-semibold text-slate-400 block mb-3">Movimientos de Inventario</span>
                                    <div class="h-28 flex items-end justify-between space-x-2 pt-2">
                                        <div class="w-full bg-indigo-500/30 h-10 rounded-t"></div>
                                        <div class="w-full bg-indigo-500/50 h-16 rounded-t"></div>
                                        <div class="w-full bg-indigo-500/70 h-24 rounded-t"></div>
                                        <div class="w-full bg-indigo-600 h-14 rounded-t"></div>
                                        <div class="w-full bg-indigo-500/40 h-20 rounded-t"></div>
                                        <div class="w-full bg-indigo-500/90 h-28 rounded-t"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Features Grid Section -->
            <section class="py-20 px-6 lg:px-12 bg-slate-50">
                <div class="max-w-7xl mx-auto">
                    <div class="text-center max-w-2xl mx-auto mb-16 space-y-4">
                        <h2 class="text-3xl font-bold text-slate-900 tracking-tight">Soluciones diseñadas para tu negocio</h2>
                        <p class="text-slate-500 font-light">Todas las herramientas operativas integradas en un ecosistema robusto e intuitivo.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <!-- Feature 1 -->
                        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition duration-200 space-y-4">
                            <div class="bg-indigo-50 w-12 h-12 rounded-xl flex items-center justify-center text-indigo-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900">Control de Stock</h3>
                            <p class="text-slate-500 text-sm leading-relaxed font-light">
                                Registra entradas, salidas e inventario crítico en tiempo real de forma fácil.
                            </p>
                        </div>

                        <!-- Feature 2 -->
                        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition duration-200 space-y-4">
                            <div class="bg-indigo-50 w-12 h-12 rounded-xl flex items-center justify-center text-indigo-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900">Gestión de Ventas</h3>
                            <p class="text-slate-500 text-sm leading-relaxed font-light">
                                Factura a tus clientes eligiendo múltiples métodos de pago integrados en el sistema.
                            </p>
                        </div>

                        <!-- Feature 3 -->
                        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition duration-200 space-y-4">
                            <div class="bg-indigo-50 w-12 h-12 rounded-xl flex items-center justify-center text-indigo-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900">Proveedores</h3>
                            <p class="text-slate-500 text-sm leading-relaxed font-light">
                                Administra tu catálogo de proveedores, información de contacto y sus empresas.
                            </p>
                        </div>

                        <!-- Feature 4 -->
                        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition duration-200 space-y-4">
                            <div class="bg-indigo-50 w-12 h-12 rounded-xl flex items-center justify-center text-indigo-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900">Reportes Únicos</h3>
                            <p class="text-slate-500 text-sm leading-relaxed font-light">
                                Obtén métricas de rendimiento y estadísticas de movimientos para tomar mejores decisiones.
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="w-full py-8 px-6 lg:px-12 bg-slate-900 text-slate-400 border-t border-slate-800">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
                <div class="flex items-center space-x-2">
                    <span class="text-lg font-bold tracking-wider text-white">SIGI</span>
                    <span class="text-xs text-slate-500">|</span>
                    <span class="text-xs">Sistema de Gestión de Inventario</span>
                </div>
                <div class="text-xs text-slate-500">
                    © 2026 SIGI. Todos los derechos reservados.
                </div>
            </div>
        </footer>
    </body>
</html>
