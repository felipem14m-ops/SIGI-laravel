@extends('layouts.sidebarEmpleado')

@section('title', 'Historial de Ventas')
@section('page-title', 'Historial de Ventas')

@section('content')

{{-- SweetAlert2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- ===== HEADER BANNER (ESTÍLO DE LA IMAGEN) ===== --}}
<div style="background:linear-gradient(125deg,#1a3da8 0%,#2350d4 45%,#3b6ef8 100%); border-radius:20px; padding:32px 36px; margin-bottom:24px; position:relative; overflow:hidden;">
    <div style="position:absolute; top:-40px; right:100px; width:220px; height:220px; background:rgba(255,255,255,0.05); border-radius:50%;"></div>
    <div style="position:absolute; top:10px; right:0; width:120px; height:120px; background:rgba(255,255,255,0.04); border-radius:50%;"></div>

    <div style="display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; position:relative; z-index:1;">
        <div>
            <div style="display:inline-block; background:rgba(255,255,255,0.18); border-radius:20px; padding:4px 14px; font-size:11px; font-weight:800; color:#fff; letter-spacing:0.08em; text-transform:uppercase; margin-bottom:10px;">
                AUDITORÍA DE VENTAS
            </div>
            <h2 style="font-size:2rem; font-weight:900; color:#fff; margin:0 0 6px; letter-spacing:-0.02em;">
                Historial de Ventas
            </h2>
            <p style="font-size:0.9rem; color:rgba(255,255,255,0.85); margin:0;">
                Consulta y seguimiento detallado de todas tus transacciones.
            </p>
        </div>

        <div style="display:flex; align-items:center; gap:12px; flex-shrink:0;">
            {{-- Badge Contador --}}
            <div style="display:flex; align-items:center; gap:8px; background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.25); color:#fff; padding:8px 18px; border-radius:12px; font-size:12px; font-weight:800;">
                <span style="font-size:22px; font-weight:900; line-height:1;">{{ $misVentas->total() ?? count($misVentas) }}</span>
                <span style="font-size:10px; opacity:0.85; letter-spacing:0.07em; text-transform:uppercase;">REGISTROS</span>
            </div>

            {{-- Botón Nueva Venta --}}
            <a href="{{ route('empleado.ventas.index') }}"
                style="display:inline-flex; align-items:center; gap:8px; background:#fff; color:#1a3da8; padding:10px 20px; border-radius:12px; font-size:13px; font-weight:800; text-decoration:none; box-shadow:0 2px 10px rgba(0,0,0,0.12);"
                onmouseover="this.style.background='#eff6ff'"
                onmouseout="this.style.background='#fff'">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Nueva Venta
            </a>
        </div>
    </div>
</div>

{{-- ===== PANEL DE FILTROS ===== --}}
<div style="background:#fff; border-radius:20px; border:1px solid #e8ecf4; padding:24px 28px; margin-bottom:20px; box-shadow:0 1px 6px rgba(0,0,0,0.04);">
    <form method="GET" action="{{ route('empleado.misventas.index') }}" id="filter-form">
        <div style="display:flex; align-items:flex-end; gap:20px; flex-wrap:wrap;">

            {{-- Fecha de Venta --}}
            <div style="flex:0 0 220px;">
                <label style="display:block; font-size:10px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:8px;">FECHA DE VENTA</label>
                <input type="date" name="fecha" value="{{ request('fecha') }}"
                    style="width:100%; padding:11px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; font-family:inherit;"
                    onfocus="this.style.borderColor='#2563eb'; this.style.boxShadow='0 0 0 3px rgba(37,99,235,0.1)'"
                    onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'" />
            </div>

            {{-- Usuario / Cajero --}}
            <div style="flex:1; min-width:220px;">
                <label style="display:block; font-size:10px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:8px;">USUARIO / CAJERO</label>
                <div style="position:relative;">
                    <svg style="position:absolute; left:14px; top:50%; transform:translateY(-50%); pointer-events:none;" width="16" height="16" fill="none" stroke="#94a3b8" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <input type="text" value="{{ Auth::user()->nombre }}" disabled placeholder="Nombre de cajero..."
                        style="width:100%; padding:11px 14px 11px 42px; background:#f1f5f9; border:1.5px solid #e2e8f0; border-radius:12px; font-size:0.875rem; color:#64748b; outline:none; box-sizing:border-box; font-family:inherit; cursor:not-allowed;" />
                </div>
            </div>

            {{-- Método de Pago --}}
            <div style="flex:0 0 200px;">
                <label style="display:block; font-size:10px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:8px;">MÉTODO DE PAGO</label>
                <select name="metodo" style="width:100%; padding:11px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; cursor:pointer; font-family:inherit; appearance:none; -webkit-appearance:none; background-image:url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2216%22 height=%2216%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%2364748b%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22><polyline points=%226 9 12 15 18 9%22/></svg>'); background-repeat:no-repeat; background-position:right 14px center; padding-right:38px;">
                    <option value="">Todos los métodos</option>
                    @foreach($metodosPago ?? [] as $mp)
                        <option value="{{ $mp->nombre }}" {{ request('metodo') == $mp->nombre ? 'selected' : '' }}>
                            {{ $mp->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Botones --}}
            <div style="display:flex; gap:10px; flex-shrink:0; padding-top:1px;">
                <button type="submit"
                    style="display:inline-flex; align-items:center; gap:8px; background:#2563eb; color:#fff; border:none; padding:11px 22px; border-radius:12px; font-size:13px; font-weight:800; cursor:pointer; box-shadow:0 3px 10px rgba(37,99,235,0.25);"
                    onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563eb'">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                    </svg>
                    Aplicar Filtros
                </button>

                <a href="{{ route('empleado.misventas.index') }}"
                    style="display:inline-flex; align-items:center; gap:8px; background:#f8fafc; color:#64748b; border:1.5px solid #e2e8f0; padding:11px 20px; border-radius:12px; font-size:13px; font-weight:700; text-decoration:none; cursor:pointer;"
                    onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 15.07M9 5h-.582m0 0l-3.5 3.5M8.418 5L12 8.5" />
                    </svg>
                    Limpiar
                </a>
            </div>

        </div>
    </form>
</div>

{{-- ===== CONTEO DE RESULTADOS ===== --}}
<div style="font-size:12px; color:#64748b; font-weight:700; margin-bottom:12px; margin-left:2px;">
    Mostrando
    @if(method_exists($misVentas, 'firstItem'))
    <strong style="color:#1e293b;">{{ $misVentas->firstItem() }}-{{ $misVentas->lastItem() }}</strong> de <strong style="color:#1e293b;">{{ $misVentas->total() }}</strong>
    @else
    <strong style="color:#1e293b;">{{ count($misVentas) }}</strong>
    @endif
    registros
</div>

{{-- ===== TABLA PRINCIPAL DEL HISTORIAL ===== --}}
<div style="background:#fff; border-radius:20px; border:1px solid #e8ecf4; overflow:hidden; box-shadow:0 1px 6px rgba(0,0,0,0.04);">
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; text-align:left;">
            <thead>
                <tr style="background:#f8fafc; border-bottom:1.5px solid #eef2f7;">
                    <th style="padding:14px 20px; font-size:11px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase;">FOLIO</th>
                    <th style="padding:14px 20px; font-size:11px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase;">FECHA Y HORA</th>
                    <th style="padding:14px 20px; font-size:11px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase;">USUARIO / CAJERO</th>
                    <th style="padding:14px 20px; font-size:11px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase;">MÉTODO PAGO</th>
                    <th style="padding:14px 20px; font-size:11px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase; text-align:center;">ÍTEMS</th>
                    <th style="padding:14px 20px; font-size:11px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase; text-align:right;">TOTAL</th>
                    <th style="padding:14px 20px; font-size:11px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase; text-align:right;">CAMBIO</th>
                    <th style="padding:14px 20px; font-size:11px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase; text-align:center;">ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                @forelse($misVentas as $venta)
                <tr style="border-bottom:1px solid #f1f5f9;" onmouseover="this.style.background='#fafbfd'" onmouseout="this.style.background='transparent'">

                    {{-- Folio --}}
                    <td style="padding:16px 20px;">
                        <span style="font-size:0.875rem; font-weight:800; color:#2563eb; letter-spacing:0.02em;">
                            #{{ str_pad($venta->id_venta, 5, '0', STR_PAD_LEFT) }}
                        </span>
                    </td>

                    {{-- Fecha y Hora --}}
                    <td style="padding:16px 20px; font-size:0.875rem; color:#475569; font-weight:600; white-space:nowrap;">
                        {{ $venta->created_at ? \Carbon\Carbon::parse($venta->created_at)->format('d/m/Y h:i A') : ($venta->fecha_venta ? \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y') : '—') }}
                    </td>

                    {{-- Usuario / Cajero --}}
                    <td style="padding:16px 20px;">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div style="width:32px; height:32px; border-radius:50%; background:linear-gradient(135deg,#2563eb,#3b82f6); color:#fff; display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:800; flex-shrink:0;">
                                {{ mb_strtoupper(mb_substr($venta->usuario->nombre ?? 'U', 0, 1)) }}
                            </div>
                            <span style="font-size:0.875rem; font-weight:700; color:#1e293b;">{{ $venta->usuario->nombre ?? 'Empleado' }}</span>
                        </div>
                    </td>

                    {{-- Método de Pago --}}
                    <td style="padding:16px 20px;">
                        @php
                        $metodo = strtolower($venta->metodo->nombre ?? ($venta->id_metodo ?? 'efectivo'));
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

                    {{-- Ítems --}}
                    <td style="padding:16px 20px; text-align:center;">
                        @php $totalItems = $venta->detalles ? $venta->detalles->sum('cantidad') : 0; @endphp
                        <span style="display:inline-block; background:#2563eb; color:#fff; font-weight:900; font-size:11px; padding:5px 12px; border-radius:8px; min-width:36px; text-align:center;">
                            {{ $totalItems }} und
                        </span>
                    </td>

                    {{-- Total --}}
                    <td style="padding:16px 20px; text-align:right; font-weight:900; color:#0f172a; font-size:0.95rem; white-space:nowrap;">
                        ${{ number_format($venta->total, 0, ',', '.') }}
                    </td>

                    {{-- Cambio / Devolución --}}
                    <td style="padding:16px 20px; text-align:right; white-space:nowrap;">
                        @php $cambio = $venta->cambio ?? 0; @endphp
                        @if($cambio > 0)
                        <span style="font-weight:900; color:#16a34a; font-size:0.92rem;">${{ number_format($cambio, 0, ',', '.') }}</span>
                        @else
                        <span style="font-weight:700; color:#94a3b8; font-size:0.92rem;">$0</span>
                        @endif
                    </td>

                    {{-- Acciones (Ver Factura POS / Ojito) --}}
                    <td style="padding:16px 20px; text-align:center;">
                        <button type="button"
                            onclick="verDetalleVenta({{ $venta->id_venta }})"
                            title="Ver detalle / Factura POS"
                            style="width:38px; height:38px; border-radius:12px; border:1px solid #dbeafe; background:#eff6ff; color:#2563eb; display:inline-flex; align-items:center; justify-content:center; cursor:pointer;"
                            onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
                            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="padding:60px 20px; text-align:center;">
                        <div style="display:inline-flex; flex-direction:column; align-items:center; gap:12px; color:#94a3b8;">
                            <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="opacity:0.4;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 01-2h2a2 2 0 012 2" />
                            </svg>
                            <div style="font-weight:800; font-size:0.95rem; color:#64748b;">No hay ventas registradas</div>
                            <div style="font-size:0.8rem; color:#94a3b8;">Las ventas aparecerán aquí una vez que se registren en el punto de venta.</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginador --}}
    @if(method_exists($misVentas, 'hasPages') && $misVentas->hasPages())
    <div style="padding:14px 24px; background:#f8fafc; border-top:1px solid #eef2f7; display:flex; align-items:center; justify-content:space-between;">
        <span style="font-size:13px; color:#64748b; font-weight:600;">
            {{ $misVentas->total() }} {{ $misVentas->total() === 1 ? 'venta' : 'ventas' }}
        </span>

        <div style="display:flex; align-items:center; gap:6px;">
            {{ $misVentas->links() }}
        </div>
    </div>
    @endif
</div>

{{-- ===== MODAL DETALLE DE VENTA / FACTURA POS ===== --}}
<div id="modal-detalle-venta" style="display:none; position:fixed; inset:0; z-index:9000; background:rgba(15,23,42,0.55); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:20px;">
    <div style="background:#fff; border-radius:24px; width:100%; max-width:560px; box-shadow:0 20px 60px rgba(0,0,0,0.2); overflow:hidden; animation:slideUp .25s ease;">

        {{-- Modal Header --}}
        <div style="background:linear-gradient(125deg,#1a3da8 0%,#2563eb 100%); padding:22px 28px; display:flex; align-items:center; justify-content:space-between;">
            <div style="display:flex; align-items:center; gap:12px;">
                <div style="width:40px; height:40px; border-radius:12px; background:rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center; color:#fff;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 01-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div>
                    <div style="font-size:10px; font-weight:800; color:rgba(255,255,255,0.75); text-transform:uppercase; letter-spacing:0.08em;">RESUMEN</div>
                    <div id="modal-folio" style="font-size:1.1rem; font-weight:900; color:#fff;">Detalle de Venta</div>
                </div>
            </div>
            <button onclick="cerrarModalDetalle()" style="width:36px; height:36px; border-radius:10px; border:none; background:rgba(255,255,255,0.15); color:#fff; font-size:18px; cursor:pointer; display:flex; align-items:center; justify-content:center;" onmouseover="this.style.background='rgba(255,255,255,0.25)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'">×</button>
        </div>

        {{-- Modal Body --}}
        <div id="modal-body" style="padding:28px; display:flex; flex-direction:column; gap:16px;">
            <p style="text-align:center; color:#94a3b8; font-size:0.875rem;">Cargando información...</p>
        </div>

        {{-- Modal Footer --}}
        <div style="padding:18px 28px; border-top:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center;">
            <button id="btn-imprimir-factura-modal" type="button"
                style="padding:10px 18px; border-radius:12px; border:none; background:#2563eb; color:#fff; font-size:13px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:6px;">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 000-4H9a2 2 0 000 4zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Imprimir / Descargar POS
            </button>
            <button onclick="cerrarModalDetalle()"
                style="padding:10px 22px; border-radius:12px; border:1.5px solid #e2e8f0; background:#f8fafc; color:#475569; font-size:13px; font-weight:700; cursor:pointer;"
                onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
                Cerrar
            </button>
        </div>
    </div>
</div>

<style>
    @keyframes slideUp {
        from { transform: translateY(24px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
</style>

{{-- ===== JAVASCRIPT DETALLE / FACTURA POS ===== --}}
<script>
    function cerrarModalDetalle() {
        document.getElementById('modal-detalle-venta').style.display = 'none';
    }

    async function verDetalleVenta(id) {
        document.getElementById('modal-folio').textContent = 'Factura POS #' + String(id).padStart(5, '0');
        document.getElementById('modal-body').innerHTML =
            '<div style="text-align:center; padding:36px; color:#64748b;">' +
            '<svg width="34" height="34" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="animation:spin 1s linear infinite; margin:0 auto 12px; display:block;">' +
            '<path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>' +
            '</svg>' +
            '<p style="font-size:0.875rem; font-weight:600;">Cargando factura POS...</p>' +
            '</div>';
        document.getElementById('modal-detalle-venta').style.display = 'flex';

        try {
            const showBaseUrl = "{{ route('ventas.show', ':id') }}";
            const res = await fetch(showBaseUrl.replace(':id', id), {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });

            if (!res.ok) throw new Error('HTTP ' + res.status);
            const data = await res.json();
            if (!data.success || !data.venta) throw new Error('No se pudo obtener la información de la venta');

            const v = data.venta;
            const fechaStr   = v.fecha_venta ? new Date(v.fecha_venta).toLocaleString('es-CO') : new Date().toLocaleString('es-CO');
            const folioStr   = String(v.id_venta).padStart(5, '0');
            const cajeroStr  = v.usuario ? (v.usuario.nombre || v.usuario.name || 'Empleado POS') : 'Empleado POS';
            const metodoStr  = v.metodo  ? v.metodo.nombre : 'Efectivo';
            const totalNum   = parseFloat(v.total) || 0;
            const montoNum   = parseFloat(v.monto_recibido) || totalNum;
            const cambioNum  = parseFloat(v.cambio) || 0;
            const fmt = (n) => n.toLocaleString('es-CO', { minimumFractionDigits: 0, maximumFractionDigits: 0 });

            let filas = '';
            if (v.detalles && v.detalles.length > 0) {
                v.detalles.forEach(function(d) {
                    var nombre   = d.producto ? d.producto.nombre : 'Producto #' + d.id_producto;
                    var precio   = '$' + fmt(parseFloat(d.precioUnitario || 0));
                    var subtotal = '$' + fmt(parseFloat(d.subtotal || 0));
                    filas += '<tr>' +
                        '<td style="padding:5px 0; font-weight:700;">' + nombre + '</td>' +
                        '<td style="padding:5px 0; text-align:center;">' + precio + '</td>' +
                        '<td style="padding:5px 0; text-align:center;">' + d.cantidad + '</td>' +
                        '<td style="padding:5px 0; text-align:right;">' + subtotal + '</td>' +
                        '</tr>';
                });
            } else {
                filas = '<tr><td colspan="4" style="text-align:center; padding:10px; color:#94a3b8;">Sin ítems registrados</td></tr>';
            }

            var cambioRow = cambioNum > 0
                ? '<div style="display:flex; justify-content:space-between; margin-top:4px;">' +
                  '<span style="color:#16a34a; font-weight:700;">Cambio:</span>' +
                  '<span style="color:#16a34a; font-weight:900;">$' + fmt(cambioNum) + '</span>' +
                  '</div>'
                : '';

            var ticketHtml =
                '<div id="pos-ticket-modal-content" style="background:#fff; border:1.5px dashed #cbd5e1; border-radius:14px; padding:22px; font-family:\'Courier New\', Courier, monospace; font-size:12px; color:#000; max-width:340px; margin:0 auto;">' +
                    '<div style="text-align:center; margin-bottom:10px;">' +
                        '<div style="font-weight:900; font-size:17px; letter-spacing:0.04em;">TIENDA SIGI</div>' +
                        '<div style="font-weight:700; font-size:11px;">NIT: 123.456.789-0</div>' +
                        '<div style="border-top:1px dashed #555; margin:8px 0;"></div>' +
                        '<div style="font-weight:700; font-size:12px;">FACTURA DE VENTA</div>' +
                        '<div style="font-size:10px; color:#444;">RÉGIMEN SIMPLIFICADO</div>' +
                        '<div style="font-size:11px; margin-top:3px; color:#333;">' + fechaStr + '</div>' +
                    '</div>' +

                    '<div style="font-size:11px; margin-bottom:8px; display:flex; flex-direction:column; gap:3px;">' +
                        '<div style="display:flex; justify-content:space-between;"><span>Factura Nro.:</span><span style="font-weight:700;">#' + folioStr + '</span></div>' +
                        '<div style="display:flex; justify-content:space-between;"><span>Vendedor:</span><span style="font-weight:700;">' + cajeroStr + '</span></div>' +
                        '<div style="display:flex; justify-content:space-between;"><span>Método de pago:</span><span style="font-weight:700;">' + metodoStr + '</span></div>' +
                    '</div>' +

                    '<div style="border-top:1px dashed #555; margin:8px 0;"></div>' +

                    '<table style="width:100%; border-collapse:collapse; font-size:11px;">' +
                        '<thead>' +
                            '<tr style="border-bottom:1px dashed #555;">' +
                                '<th style="padding-bottom:5px; text-align:left; width:44%;">Artículo</th>' +
                                '<th style="padding-bottom:5px; text-align:center; width:22%;">Precio</th>' +
                                '<th style="padding-bottom:5px; text-align:center; width:10%;">Cant</th>' +
                                '<th style="padding-bottom:5px; text-align:right; width:24%;">Total</th>' +
                            '</tr>' +
                        '</thead>' +
                        '<tbody>' + filas + '</tbody>' +
                    '</table>' +

                    '<div style="border-top:1px dashed #555; margin:8px 0;"></div>' +

                    '<div style="font-size:12px;">' +
                        '<div style="display:flex; justify-content:space-between; margin-bottom:3px;"><span>Subtotal:</span><span>$' + fmt(totalNum) + '</span></div>' +
                        '<div style="display:flex; justify-content:space-between; margin-bottom:6px;"><span>IVA (0%):</span><span>$0</span></div>' +
                        '<div style="display:flex; justify-content:space-between; font-weight:900; font-size:15px; border-top:1px dashed #555; border-bottom:1px dashed #555; padding:5px 0;">' +
                            '<span>TOTAL:</span><span>$' + fmt(totalNum) + '</span>' +
                        '</div>' +
                        '<div style="margin-top:6px; display:flex; justify-content:space-between;"><span>Efectivo recibido:</span><span style="font-weight:700;">$' + fmt(montoNum) + '</span></div>' +
                        cambioRow +
                    '</div>' +

                    '<div style="border-top:1px dashed #555; margin:10px 0 6px;"></div>' +

                    '<div style="text-align:center; font-size:10px; color:#444;">' +
                        '<div style="font-weight:900; font-size:12px;">¡GRACIAS POR SU COMPRA!</div>' +
                        '<div style="margin-top:2px;">SIGI POS &bull; www.sigipos.co</div>' +
                    '</div>' +
                '</div>';

            document.getElementById('modal-body').innerHTML = ticketHtml;
            document.getElementById('btn-imprimir-factura-modal').onclick = function() { imprimirTicketModal('pos-ticket-modal-content'); };

        } catch (e) {
            document.getElementById('modal-body').innerHTML =
                '<div style="text-align:center; padding:24px;">' +
                '<div style="font-size:2rem; margin-bottom:8px;">⚠️</div>' +
                '<div style="color:#ef4444; font-weight:800; font-size:0.95rem;">Error al cargar el detalle</div>' +
                '<div style="color:#94a3b8; font-size:12px; margin-top:4px;">' + e.message + '</div>' +
                '</div>';
        }
    }

    function imprimirTicketModal(elementId) {
        var ticketEl = document.getElementById(elementId);
        if (!ticketEl) return;
        var printWin = window.open('', '_blank', 'width=420,height=650');
        printWin.document.write('<!DOCTYPE html><html><head><title>Factura POS</title>' +
            '<style>@page{size:80mm auto;margin:0}body{margin:0;padding:10px;font-family:"Courier New",monospace;background:#fff;}</style>' +
            '</head><body onload="window.print();setTimeout(function(){window.close();},800);">' +
            ticketEl.outerHTML +
            '</body></html>');
        printWin.document.close();
    }
</script>

@endsection
