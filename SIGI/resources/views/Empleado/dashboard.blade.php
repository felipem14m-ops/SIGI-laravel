@extends('layouts.sidebarEmpleado')

@section('title', 'Dashboard Empleado')
@section('page-title')
Mi Panel - {{ Auth::user()->nombre }}
@endsection

@php
    use App\Models\Venta;
    use Carbon\Carbon;

    $user = Auth::user();
    $userId = $user->id_usuario ?? $user->id;
    $hoy = Carbon::today();

    // Query de ventas del día realizadas por este empleado
    $ventasHoyQuery = Venta::where('id_usuario', $userId)
        ->where(function($q) use ($hoy) {
            $q->whereDate('fecha_venta', $hoy)->orWhereDate('created_at', $hoy);
        });

    $totalVentasHoy   = (clone $ventasHoyQuery)->count();
    $totalIngresosHoy = (clone $ventasHoyQuery)->sum('total');
    $ticketPromedio   = $totalVentasHoy > 0 ? ($totalIngresosHoy / $totalVentasHoy) : 0;

    // Ventas paginadas estilo datatable (5 por página)
    $misVentasHoy = (clone $ventasHoyQuery)
        ->with(['metodo', 'detalles'])
        ->latest()
        ->paginate(5);

    $fechaHoy = Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY');
@endphp

@section('content')

<div class="space-y-6">

    {{-- ===== BANNER PRINCIPAL (ESTÁNDAR DEL PROYECTO) ===== --}}
    <div style="background:linear-gradient(125deg,#1a3da8 0%,#2350d4 45%,#3b6ef8 100%); border-radius:20px; padding:32px 36px; margin-bottom:24px; position:relative; overflow:hidden;">
        <div style="position:absolute; top:-40px; right:100px; width:220px; height:220px; background:rgba(255,255,255,0.05); border-radius:50%;"></div>
        <div style="position:absolute; top:10px; right:0; width:120px; height:120px; background:rgba(255,255,255,0.04); border-radius:50%;"></div>

        <div style="display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; position:relative; z-index:1;">
            <div>
                <div style="display:inline-block; background:rgba(255,255,255,0.18); border-radius:20px; padding:4px 14px; font-size:11px; font-weight:800; color:#fff; letter-spacing:0.08em; text-transform:uppercase; margin-bottom:10px;">
                    TIENDA SIGI
                </div>
                <h2 style="font-size:2rem; font-weight:900; color:#fff; margin:0 0 6px; letter-spacing:-0.02em;">
                    @php
                        $hora = Carbon::now()->hour;
                        $saludo = $hora < 12 ? '☀️ Buenos días' : ($hora < 18 ? '🌤️ Buenas tardes' : '🌙 Buenas noches');
                    @endphp
                    {{ $saludo }}, {{ Auth::user()->nombre }}!
                </h2>
                <p style="font-size:0.9rem; color:rgba(255,255,255,0.85); margin:0; text-transform:capitalize;">
                    {{ $fechaHoy }} &bull; <span id="reloj-live" style="font-weight:700;">{{ Carbon::now()->format('h:i A') }}</span>
                </p>
            </div>

            <div style="display:flex; align-items:center; gap:12px; flex-shrink:0;">
                <a href="{{ route('ventas.index') }}"
                    style="display:inline-flex; align-items:center; gap:8px; background:#fff; color:#1a3da8; padding:12px 22px; border-radius:12px; font-size:13px; font-weight:800; text-decoration:none; box-shadow:0 4px 16px rgba(0,0,0,0.15); transition:all 0.2s;"
                    onmouseover="this.style.transform='translateY(-1px)'; this.style.background='#eff6ff';"
                    onmouseout="this.style.transform=''; this.style.background='#fff';">
                    <svg width="18" height="18" fill="none" stroke="#1a3da8" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Abrir Caja / POS
                </a>
            </div>
        </div>
    </div>

    {{-- ===== TARJETAS DE ESTADÍSTICAS (COLORES ESTÁNDAR DEL DASHBOARD ADMIN) ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        {{-- Ventas Hoy (Verde Emerald) --}}
        <div class="bg-white rounded-2xl border border-slate-100 p-6 flex flex-col justify-between shadow-sm hover:shadow-md hover:border-emerald-200 transition duration-200">
            <div class="bg-emerald-50 text-emerald-600 w-11 h-11 rounded-2xl flex items-center justify-center shrink-0 mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            <div>
                <p class="text-3xl font-black text-slate-900 leading-none">{{ $totalVentasHoy }}</p>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-2">VENTAS HOY</p>
            </div>
        </div>

        {{-- Ingresos Hoy (Verde Emerald) --}}
        <div class="bg-white rounded-2xl border border-slate-100 p-6 flex flex-col justify-between shadow-sm hover:shadow-md hover:border-emerald-200 transition duration-200">
            <div class="bg-emerald-50 text-emerald-600 w-11 h-11 rounded-2xl flex items-center justify-center shrink-0 mb-4">
                <span class="text-xl font-bold">$</span>
            </div>
            <div>
                <p class="text-3xl font-black text-slate-900 leading-none">${{ number_format($totalIngresosHoy, 0, ',', '.') }}</p>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-2">INGRESOS HOY</p>
            </div>
        </div>

        {{-- Ticket Promedio (Amarillo Amber) --}}
        <div class="bg-white rounded-2xl border border-slate-100 p-6 flex flex-col justify-between shadow-sm hover:shadow-md hover:border-amber-200 transition duration-200">
            <div class="bg-amber-50 text-amber-500 w-11 h-11 rounded-2xl flex items-center justify-center shrink-0 mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            </div>
            <div>
                <p class="text-3xl font-black text-slate-900 leading-none">${{ number_format($ticketPromedio, 0, ',', '.') }}</p>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-2">TICKET PROMEDIO</p>
            </div>
        </div>

        {{-- Hora Actual (Rojo Red) --}}
        <div class="bg-white rounded-2xl border border-slate-100 p-6 flex flex-col justify-between shadow-sm hover:shadow-md hover:border-red-200 transition duration-200">
            <div class="bg-red-50 text-red-500 w-11 h-11 rounded-2xl flex items-center justify-center shrink-0 mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-3xl font-black text-slate-900 leading-none" id="reloj-card">{{ Carbon::now()->format('h:i A') }}</p>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-2">HORA ACTUAL</p>
            </div>
        </div>

    </div>

    {{-- ===== ACCIONES RÁPIDAS (ESTÁNDAR CON COLORES AZUL E ÍNDIGO) ===== --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <a href="{{ route('ventas.index') }}" class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md hover:border-emerald-200 transition duration-200 group">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition duration-200 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800 group-hover:text-emerald-600 transition">Registrar Nueva Venta</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Abrir la caja registradora y emitir ticket</p>
                </div>
            </div>
            <div class="w-8 h-8 rounded-full bg-emerald-50 flex items-center justify-center group-hover:bg-emerald-100 text-emerald-500 group-hover:text-emerald-700 transition shrink-0">
                <svg class="w-4 h-4 transform group-hover:translate-x-0.5 transition duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </a>

        <a href="{{ route('productos.index') }}" class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md hover:border-blue-200 transition duration-200 group">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition duration-200 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800 group-hover:text-blue-600 transition">Consultar Catálogo de Productos</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Verifica precios, disponibilidad y stock</p>
                </div>
            </div>
            <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center group-hover:bg-blue-100 text-blue-500 group-hover:text-blue-700 transition shrink-0">
                <svg class="w-4 h-4 transform group-hover:translate-x-0.5 transition duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </a>
    </div>

    {{-- ===== DATATABLE CON PAGINADOR ESTÁNDAR ===== --}}
    <div style="background:#fff; border-radius:20px; border:1px solid #e8ecf4; overflow:hidden; box-shadow:0 1px 6px rgba(0,0,0,0.04);">
        
        {{-- Header Tabla --}}
        <div style="padding:20px 24px; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between; gap:16px;">
            <div style="display:flex; align-items:center; gap:12px;">
                <div style="width:36px; height:36px; border-radius:10px; background:#eff6ff; color:#2563eb; display:flex; align-items:center; justify-content:center;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 01-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h3 style="font-size:1rem; font-weight:800; color:#0f172a; margin:0;">Mis Ventas del Día</h3>
            </div>
            <a href="{{ route('ventas.historial') }}" style="display:inline-flex; align-items:center; gap:6px; font-size:12px; font-weight:800; color:#2563eb; background:#eff6ff; padding:7px 14px; border-radius:10px; text-decoration:none;" onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
                Ver Historial Completo →
            </a>
        </div>

        {{-- Tabla Datatable --}}
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; text-align:left;">
                <thead>
                    <tr style="background:#f8fafc; border-bottom:1px solid #eef2f7;">
                        <th style="padding:14px 20px; font-size:10px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.08em;">TICKET</th>
                        <th style="padding:14px 20px; font-size:10px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.08em;">HORA</th>
                        <th style="padding:14px 20px; font-size:10px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; text-align:center;">ÍTEMS</th>
                        <th style="padding:14px 20px; font-size:10px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.08em;">MÉTODO PAGO</th>
                        <th style="padding:14px 20px; font-size:10px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; text-align:right;">TOTAL</th>
                    </tr>
                </thead>
                <tbody style="divide-y:1px solid #f1f5f9;">
                    @forelse($misVentasHoy as $venta)
                    <tr style="border-bottom:1px solid #f8fafc;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        
                        {{-- Ticket --}}
                        <td style="padding:16px 20px;">
                            <span style="font-size:0.875rem; font-weight:800; color:#2563eb; letter-spacing:0.02em;">
                                #{{ str_pad($venta->id_venta, 5, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>

                        {{-- Hora --}}
                        <td style="padding:16px 20px; font-size:0.875rem; color:#475569; font-weight:600; white-space:nowrap;">
                            {{ \Carbon\Carbon::parse($venta->fecha_venta ?? $venta->created_at)->format('h:i A') }}
                        </td>

                        {{-- Ítems --}}
                        <td style="padding:16px 20px; text-align:center;">
                            @php $totalItems = $venta->detalles ? $venta->detalles->sum('cantidad') : 0; @endphp
                            <span style="display:inline-block; background:#2563eb; color:#fff; font-weight:900; font-size:11px; padding:5px 12px; border-radius:8px; min-width:36px; text-align:center;">
                                {{ $totalItems }} und
                            </span>
                        </td>

                        {{-- Método de Pago --}}
                        <td style="padding:16px 20px;">
                            @php
                            $metodo = strtolower($venta->metodo->nombre ?? 'efectivo');
                            $metodoBadge = [
                                'efectivo' => ['bg'=>'#f0fdf4','color'=>'#16a34a','text'=>'Efectivo'],
                                'nequi' => ['bg'=>'#faf5ff','color'=>'#7c3aed','text'=>'Nequi'],
                                'tarjeta' => ['bg'=>'#eff6ff','color'=>'#2563eb','text'=>'Tarjeta'],
                                'transferencia' => ['bg'=>'#fff7ed','color'=>'#d97706','text'=>'Transferencia'],
                            ];
                            $badge = $metodoBadge[$metodo] ?? ['bg'=>'#f8fafc','color'=>'#475569','text'=>ucfirst($metodo)];
                            @endphp
                            <span style="display:inline-flex; align-items:center; gap:5px; background:{{ $badge['bg'] }}; color:{{ $badge['color'] }}; padding:5px 12px; border-radius:8px; font-size:11px; font-weight:800;">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                {{ $badge['text'] }}
                            </span>
                        </td>

                        {{-- Total --}}
                        <td style="padding:16px 20px; text-align:right; font-weight:900; color:#0f172a; font-size:0.95rem; white-space:nowrap;">
                            ${{ number_format($venta->total, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding:60px 20px; text-align:center;">
                            <div style="display:inline-flex; flex-direction:column; align-items:center; gap:12px; color:#94a3b8;">
                                <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="opacity:0.4;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 02-2 2h2a2 2 0 002-2M9 5a2 2 0 01-2h2a2 2 0 012 2" />
                                </svg>
                                <div style="font-weight:800; font-size:0.95rem; color:#64748b;">No tienes ventas registradas hoy</div>
                                <div style="font-size:0.8rem; color:#94a3b8;">Las ventas procesadas aparecerán listadas aquí con su paginación.</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer Paginador Datatable --}}
        <div style="padding:14px 24px; background:#f8fafc; border-top:1px solid #eef2f7; display:flex; align-items:center; justify-content:space-between;">
            <div style="font-size:12px; font-weight:700; color:#64748b;">
                Mostrando <strong>{{ $misVentasHoy->firstItem() ?? 0 }}</strong> a <strong>{{ $misVentasHoy->lastItem() ?? 0 }}</strong> de <strong>{{ $misVentasHoy->total() }}</strong> registros
            </div>
            <div>
                {{ $misVentasHoy->links() }}
            </div>
        </div>

    </div>

</div>

{{-- Reloj en vivo --}}
<script>
    function actualizarReloj() {
        const ahora = new Date();
        let h = ahora.getHours();
        const m = String(ahora.getMinutes()).padStart(2, '0');
        const ampm = h >= 12 ? 'PM' : 'AM';
        h = h % 12 || 12;
        const texto = h + ':' + m + ' ' + ampm;
        const r1 = document.getElementById('reloj-live');
        const r2 = document.getElementById('reloj-card');
        if (r1) r1.textContent = texto;
        if (r2) r2.textContent = texto;
    }
    setInterval(actualizarReloj, 1000);
    actualizarReloj();
</script>

@endsection