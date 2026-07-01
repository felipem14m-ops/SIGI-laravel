<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Registro</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased bg-slate-50 min-h-screen flex items-center justify-center p-0 md:p-8">
        <div class="w-full max-w-7xl bg-white rounded-none md:rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row min-h-screen md:min-h-[85vh]">
            
            <!-- Left Side: Register Form -->
            <div class="w-full md:w-7/12 p-6 sm:p-10 lg:p-12 flex flex-col justify-between bg-white">
                
                <!-- Back to Home -->
                <div>
                    <a href="/" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 px-4 py-2 rounded-full transition duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver al inicio
                    </a>
                </div>

                <!-- Form Container -->
                <div class="my-8 max-w-xl mx-auto w-full">
                    <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Crear Cuenta</h2>
                    <p class="text-slate-500 mt-2 text-sm">Registra tus datos para unirte a la plataforma</p>

                    <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-5">
                        @csrf

                        <!-- Nombre Completo -->
                        <div>
                            <label for="nombre" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Nombre Completo</label>
                            <div class="relative rounded-xl shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required autofocus placeholder="Ej. Juan Carlos Pérez" 
                                    class="block w-full pl-10 pr-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition duration-200">
                            </div>
                            <x-input-error :messages="$errors->get('nombre')" class="mt-1" />
                        </div>

                        <!-- Row 2: Identificacion & Email -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <!-- Número de Identificación -->
                            <div>
                                <label for="numeroIdentificacion" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Número de Identificación</label>
                                <div class="relative rounded-xl shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                        </svg>
                                    </div>
                                    <input type="text" name="numeroIdentificacion" id="numeroIdentificacion" value="{{ old('numeroIdentificacion') }}" required placeholder="Cédula o NIT" 
                                        class="block w-full pl-10 pr-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition duration-200">
                                </div>
                                <x-input-error :messages="$errors->get('numeroIdentificacion')" class="mt-1" />
                            </div>

                            <!-- Correo Electrónico -->
                            <div>
                                <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Correo Electrónico</label>
                                <div class="relative rounded-xl shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="ejemplo@sigi.com" 
                                        class="block w-full pl-10 pr-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition duration-200">
                                </div>
                                <x-input-error :messages="$errors->get('email')" class="mt-1" />
                            </div>
                        </div>

                        <!-- Row 3: Contraseña & Confirmación -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <!-- Contraseña -->
                            <div>
                                <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Contraseña</label>
                                <div class="relative rounded-xl shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                    </div>
                                    <input type="password" name="password" id="password" required placeholder="Mínimo 8 caracteres" 
                                        class="block w-full pl-10 pr-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition duration-200">
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="mt-1" />
                            </div>

                            <!-- Confirmar Contraseña -->
                            <div>
                                <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Confirmar Contraseña</label>
                                <div class="relative rounded-xl shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                    </div>
                                    <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Mínimo 8 caracteres" 
                                        class="block w-full pl-10 pr-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition duration-200">
                                </div>
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                            </div>
                        </div>

                        <!-- Checkbox TyC -->
                        <div class="flex items-start mt-2">
                            <div class="flex items-center h-5">
                                <input id="terms" name="terms" type="checkbox" required class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-slate-350 rounded transition duration-150">
                            </div>
                            <div class="ml-3 text-xs">
                                <label for="terms" class="font-normal text-slate-500">
                                    He leído y acepto los <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium">Términos de Servicio</a> y la <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium">Política de Privacidad</a> de SIGI.
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button type="submit" class="w-full flex items-center justify-center px-6 py-3.5 border border-transparent rounded-xl shadow-lg text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200">
                                Crear Cuenta
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Footer Link -->
                <div class="text-center text-sm text-slate-500 mt-2">
                    ¿Ya tienes una cuenta institucional? <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold transition duration-200">Inicia sesión</a>
                </div>
            </div>

            <!-- Right Side: Decorative Gradient Panel -->
            <div class="hidden md:flex w-full md:w-5/12 bg-gradient-to-br from-slate-800 via-indigo-900 to-indigo-950 p-12 flex-col justify-between text-white relative overflow-hidden">
                <!-- Background decorative shapes -->
                <div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-blue-400 via-transparent to-transparent"></div>
                <div class="absolute -right-20 -bottom-20 w-80 h-80 rounded-full bg-indigo-500/10 blur-3xl"></div>

                <!-- Logo Section -->
                <div class="flex items-center justify-end space-x-2 relative z-10">
                    <span class="text-2xl font-bold tracking-wider">SIGI</span>
                    <div class="bg-indigo-600 p-2 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Center Content -->
                <div class="my-auto max-w-md relative z-10">
                    <h1 class="text-4xl font-extrabold tracking-tight leading-tight">Únete a nosotros</h1>
                    <p class="mt-4 text-indigo-200/80 font-light text-base leading-relaxed">
                        Accede a las herramientas de gestión de inventario, colabora con tu equipo y lleva el control comercial al siguiente nivel.
                    </p>
                </div>

                <!-- Footer Copyright -->
                <div class="text-right text-xs text-indigo-300/50 relative z-10">
                    © 2026 SIGI. Todos los derechos reservados.
                </div>
            </div>

        </div>
    </body>
</html>
