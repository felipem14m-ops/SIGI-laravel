@extends('layouts.sidebarAdmin')

@section('title', 'Administrador')
@section('page-title', 'Dashboard Administrador')

{{-- ===================== SIDEBAR MENU ===================== --}}
@section('sidebar-menu')

<!-- DASHBOARD SECTION -->
<div class="space-y-1">
    <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Dashboard</p>
    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-sm font-semibold bg-blue-600 text-white shadow-lg shadow-blue-600/30 transition duration-200">
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

    <a href="#" class="flex items-center justify-between px-4 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800/30 transition duration-200">
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

@endsection

{{-- ===================== PAGE CONTENT ===================== --}}
@section('content')

{{-- Welcome Banner --}}
<div class="bg-gradient-to-r from-blue-950 via-blue-900 to-indigo-900 rounded-3xl p-8 mb-6 text-white shadow-xl shadow-blue-950/20 relative overflow-hidden flex flex-col md:flex-row md:items-center md:justify-between">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-blue-500/10 via-transparent to-transparent"></div>
    
    <div class="relative z-10 space-y-3">
        <span class="inline-flex items-center space-x-1.5 bg-white/10 text-white px-3 py-1 rounded-full text-xs font-semibold backdrop-blur-sm">
            <span>🛡️</span>
            <span>Administrador - SIGI</span>
        </span>
        <h2 class="text-3xl font-extrabold tracking-tight">☀️ Buenos días, {{ Auth::user()->nombre }}!</h2>
        <p class="text-blue-200/80 text-sm font-medium">Miércoles, 01 de julio de 2026 • 11:48</p>
    </div>

    <div class="relative z-10 mt-6 md:mt-0 flex items-center space-x-4 shrink-0">
        <a href="#" class="inline-flex items-center space-x-2 bg-white text-blue-900 px-5 py-3 rounded-2xl text-sm font-bold shadow-md hover:bg-slate-50 transition duration-200">
            <svg class="w-4 h-4 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <span>Ver Ventas</span>
        </a>
        <a href="#" class="inline-flex items-center space-x-2 bg-white/10 text-white border border-white/10 px-5 py-3 rounded-2xl text-sm font-bold hover:bg-white/20 transition duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            <span>Usuarios</span>
        </a>
    </div>
</div>

{{-- Stock Alert Banner --}}
<div class="bg-amber-50 border border-amber-200/60 rounded-2xl p-4 mb-6 flex items-center space-x-3 text-amber-800 shadow-sm">
    <span class="text-lg">⚠️</span>
    <p class="text-sm font-semibold">
        Hay 2 producto(s) con stock bajo. <a href="#" class="underline hover:text-amber-900 ml-1">Ver alertas →</a>
    </p>
</div>

{{-- Dashboard Grid (6 Columns) --}}
<div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-5 mb-8">
    <!-- Card 1: Productos -->
    <div class="bg-white rounded-2xl border border-slate-100 p-5 flex flex-col justify-between shadow-sm hover:shadow-md transition">
        <div class="bg-blue-50 text-blue-600 w-10 h-10 rounded-xl flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
        <div class="mt-4">
            <p class="text-3xl font-black text-slate-900 leading-none">5</p>
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-2">Productos</p>
        </div>
    </div>

    <!-- Card 2: Ventas Hoy -->
    <div class="bg-white rounded-2xl border border-slate-100 p-5 flex flex-col justify-between shadow-sm hover:shadow-md transition">
        <div class="bg-emerald-50 text-emerald-600 w-10 h-10 rounded-xl flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
        </div>
        <div class="mt-4">
            <p class="text-3xl font-black text-slate-900 leading-none">0</p>
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-2">Ventas Hoy</p>
        </div>
    </div>

    <!-- Card 3: Ingresos Hoy -->
    <div class="bg-white rounded-2xl border border-slate-100 p-5 flex flex-col justify-between shadow-sm hover:shadow-md transition">
        <div class="bg-emerald-50 text-emerald-600 w-10 h-10 rounded-xl flex items-center justify-center shrink-0">
            <span class="text-lg font-bold">$</span>
        </div>
        <div class="mt-4">
            <p class="text-3xl font-black text-slate-900 leading-none">$0</p>
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-2">Ingresos Hoy</p>
        </div>
    </div>

    <!-- Card 4: Alertas Stock -->
    <div class="bg-white rounded-2xl border border-slate-100 p-5 flex flex-col justify-between shadow-sm hover:shadow-md transition">
        <div class="bg-amber-50 text-amber-500 w-10 h-10 rounded-xl flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        </div>
        <div class="mt-4">
            <p class="text-3xl font-black text-slate-900 leading-none">2</p>
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-2">Alertas Stock</p>
        </div>
    </div>

    <!-- Card 5: Usuarios -->
    <div class="bg-white rounded-2xl border border-slate-100 p-5 flex flex-col justify-between shadow-sm hover:shadow-md transition">
        <div class="bg-purple-50 text-purple-600 w-10 h-10 rounded-xl flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        </div>
        <div class="mt-4">
            <p class="text-3xl font-black text-slate-900 leading-none">2 <span class="text-lg text-slate-300 font-normal">/2</span></p>
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-2">Usuarios</p>
        </div>
    </div>

    <!-- Card 6: Hora Actual -->
    <div class="bg-white rounded-2xl border border-slate-100 p-5 flex flex-col justify-between shadow-sm hover:shadow-md transition">
        <div class="bg-red-50 text-red-500 w-10 h-10 rounded-xl flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="mt-4">
            <p class="text-3xl font-black text-slate-900 leading-none">11:48</p>
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-2">Hora Actual</p>
        </div>
    </div>
</div>

{{-- Content Grid --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Left Side: Ventas de Hoy --}}
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col justify-between">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center space-x-2 text-blue-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <h2 class="text-sm font-bold text-slate-900">Ventas de Hoy</h2>
            </div>
            <a href="#" class="text-xs bg-blue-50 text-blue-600 font-semibold px-3 py-1.5 rounded-lg hover:bg-blue-100 transition">Ver todas →</a>
        </div>
        
        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Ticket</th>
                        <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Hora</th>
                        <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Ítems</th>
                        <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pago</th>
                        <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-slate-50">
                        <td colspan="5" class="px-6 py-16 text-center text-slate-400 text-sm">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <svg class="w-10 h-10 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>No hay ventas registradas aún.</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Right Side: Acciones Rápidas --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-sm font-bold text-slate-900">⚡ Acciones Rápidas</h2>
        </div>
        <div class="p-6 space-y-4">
            <a href="#" class="flex items-center space-x-4 p-4 rounded-2xl border border-slate-100 hover:border-blue-200 hover:bg-blue-50/20 transition duration-200 group">
                <div class="bg-blue-50 text-blue-600 p-3 rounded-xl group-hover:bg-blue-600 group-hover:text-white transition shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-800">Productos</h4>
                    <p class="text-xs text-slate-400 mt-0.5">Gestión de catálogo</p>
                </div>
            </a>

            <a href="#" class="flex items-center space-x-4 p-4 rounded-2xl border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50/20 transition duration-200 group">
                <div class="bg-emerald-50 text-emerald-600 p-3 rounded-xl group-hover:bg-emerald-600 group-hover:text-white transition shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-800">Nueva Venta</h4>
                    <p class="text-xs text-slate-400 mt-0.5">Registrar transacción</p>
                </div>
            </a>
        </div>
    </div>

</div>

@endsection
