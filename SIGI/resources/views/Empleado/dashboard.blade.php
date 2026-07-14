@extends('layouts.sidebarEmpleado')

@section('title', 'Empleado')
@section('page-title', 'Panel de Empleado')

{{-- ===================== PAGE CONTENT ===================== --}}

@section('content')

{{-- Greeting Banner --}}
<div class="bg-gradient-to-r from-indigo-600 to-indigo-800 rounded-2xl p-6 md:p-8 mb-8 text-white shadow-xl shadow-indigo-900/20">
    <p class="text-indigo-200 text-sm font-medium">Bienvenido de vuelta,</p>
    <h2 class="text-2xl font-extrabold mt-1">{{ Auth::user()->nombre }} 👋</h2>
    <p class="text-indigo-200 text-sm mt-2">Aquí tienes un resumen de tu actividad de hoy.</p>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center space-x-4 hover:shadow-md transition">
        <div class="bg-indigo-50 text-indigo-600 p-3 rounded-xl shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Ventas Hoy</p>
            <p class="text-2xl font-extrabold text-slate-900 mt-0.5">0</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center space-x-4 hover:shadow-md transition">
        <div class="bg-emerald-50 text-emerald-600 p-3 rounded-xl shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total del Día</p>
            <p class="text-2xl font-extrabold text-slate-900 mt-0.5">$0</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center space-x-4 hover:shadow-md transition">
        <div class="bg-amber-50 text-amber-600 p-3 rounded-xl shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Productos</p>
            <p class="text-2xl font-extrabold text-slate-900 mt-0.5">0</p>
        </div>
    </div>
</div>

{{-- Recent Activity --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100">
        <h2 class="text-sm font-bold text-slate-900">Mis Ventas Recientes</h2>
    </div>
    <div class="p-6 text-center text-slate-400 text-sm py-16">
        <svg class="w-10 h-10 mx-auto mb-3 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        No tienes ventas registradas hoy.
    </div>
</div>

@endsection
