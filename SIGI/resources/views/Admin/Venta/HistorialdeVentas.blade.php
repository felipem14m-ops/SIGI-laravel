@extends('layouts.sidebarAdmin')

@section('title', 'Historial de Ventas')
@section('page-title', 'Historial de Ventas')

@section('content')

{{-- SweetAlert2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- ===== HEADER BANNER ===== --}}
<div style="background:linear-gradient(125deg,#1a3da8 0%,#2350d4 45%,#3b6ef8 100%); border-radius:20px; padding:32px 36px; margin-bottom:24px; position:relative; overflow:hidden;">
    <div style="position:absolute; top:-40px; right:100px; width:220px; height:220px; background:rgba(255,255,255,0.05); border-radius:50%;"></div>
    <div style="position:absolute; top:10px; right:0; width:120px; height:120px; background:rgba(255,255,255,0.04); border-radius:50%;"></div>

    <div style="display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; position:relative; z-index:1;">
        <div>
            <div style="display:inline-block; background:rgba(255,255,255,0.18); border-radius:20px; padding:4px 14px; font-size:11px; font-weight:800; color:#fff; letter-spacing:0.08em; text-transform:uppercase; margin-bottom:10px;">AUDITORÍA DE VENTAS</div>
            <h2 style="font-size:2rem; font-weight:900; color:#fff; margin:0 0 6px; letter-spacing:-0.02em;">Historial de Ventas</h2>
            <p style="font-size:0.9rem; color:rgba(255,255,255,0.85); margin:0;">Consulta y seguimiento detallado de todas tus transacciones.</p>
        </div>

        <div style="display:flex; align-items:center; gap:12px; flex-shrink:0;">
            {{-- Badge Contador --}}
            <div style="display:flex; align-items:center; gap:8px; background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.25); color:#fff; padding:8px 18px; border-radius:12px; font-size:12px; font-weight:800;">
                <span style="font-size:22px; font-weight:900; line-height:1;">{{ $ventas->total() ?? count($ventas) }}</span>
                <span style="font-size:10px; opacity:0.85; letter-spacing:0.07em; text-transform:uppercase;">REGISTROS</span>
            </div>

            {{-- Botón Nueva Venta --}}
            <a href="{{ route('ventas.index') }}"
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

{{-- ===== ALERTAS DE SESIÓN ===== --}}
@if(session('success'))
<div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:14px; padding:14px 20px; margin-bottom:20px; display:flex; align-items:center; gap:10px;">
    <svg width="20" height="20" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <span style="font-size:0.875rem; color:#15803d; font-weight:700;">{{ session('success') }}</span>
</div>
@endif

{{-- ===== PANEL DE FILTROS ===== --}}
<div style="background:#fff; border-radius:20px; border:1px solid #e8ecf4; padding:24px 28px; margin-bottom:20px; box-shadow:0 1px 6px rgba(0,0,0,0.04);">
    <form method="GET" action="{{ route('ventas.historial') }}" id="filter-form">
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
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <input type="text" name="cajero" value="{{ request('cajero') }}" placeholder="Nombre de cajero..."
                        style="width:100%; padding:11px 14px 11px 42px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; font-family:inherit;"
                        onfocus="this.style.borderColor='#2563eb'; this.style.background='#fff'; this.style.boxShadow='0 0 0 3px rgba(37,99,235,0.1)'"
                        onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'" />
                </div>
            </div>

            {{-- Método de Pago --}}
            <div style="flex:0 0 200px;">
                <label style="display:block; font-size:10px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:8px;">MÉTODO DE PAGO</label>
                <select name="metodo" style="width:100%; padding:11px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; cursor:pointer; font-family:inherit; appearance:none; -webkit-appearance:none; background-image:url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2216%22 height=%2216%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%2364748b%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22><polyline points=%226 9 12 15 18 9%22/></svg>'); background-repeat:no-repeat; background-position:right 14px center; padding-right:38px;">
                    <option value="">Todos los métodos</option>
                    <option value="efectivo" {{ request('metodo')=='efectivo' ? 'selected' : '' }}>Efectivo</option>
                    <option value="nequi" {{ request('metodo')=='nequi' ? 'selected' : '' }}>Nequi / Daviplata</option>
                    <option value="tarjeta" {{ request('metodo')=='tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                    <option value="transferencia" {{ request('metodo')=='transferencia' ? 'selected' : '' }}>Transferencia</option>
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

                <a href="{{ route('ventas.historial') }}"
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
    @if(method_exists($ventas, 'firstItem'))
    <strong style="color:#1e293b;">{{ $ventas->firstItem() }}-{{ $ventas->lastItem() }}</strong> de <strong style="color:#1e293b;">{{ $ventas->total() }}</strong>
    @else
    <strong style="color:#1e293b;">{{ count($ventas) }}</strong>
    @endif
    registros
</div>

{{-- ===== TABLA PRINCIPAL ===== --}}
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
                @forelse($ventas as $venta)
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
                                {{ mb_strtoupper(mb_substr($venta->usuario->name ?? 'U', 0, 1)) }}
                            </div>
                            <span style="font-size:0.875rem; font-weight:700; color:#1e293b;">{{ $venta->usuario->name ?? 'Sin usuario' }}</span>
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

                    {{-- Cambio --}}
                    <td style="padding:16px 20px; text-align:right; white-space:nowrap;">
                        @php $cambio = $venta->cambio ?? 0; @endphp
                        @if($cambio > 0)
                        <span style="font-weight:900; color:#16a34a; font-size:0.92rem;">${{ number_format($cambio, 0, ',', '.') }}</span>
                        @else
                        <span style="font-weight:700; color:#94a3b8; font-size:0.92rem;">$0</span>
                        @endif
                    </td>

                    {{-- Acciones --}}
                    <td style="padding:16px 20px; text-align:center;">
                        <button type="button"
                            onclick="verDetalleVenta({{ $venta->id_venta }})"
                            title="Ver detalle"
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
                {{-- Estado vacío con datos de demostración --}}
                @php
                $demoVentas = [
                ['folio'=>'00032','fecha'=>'24/07/2026 01:49 PM','cajero'=>'Felipe Montes','metodo'=>'Efectivo','metodo_key'=>'efectivo','items'=>3,'total'=>48000,'cambio'=>2000],
                ['folio'=>'00031','fecha'=>'21/05/2026 08:37 AM','cajero'=>'Felipe Montes','metodo'=>'Tarjeta','metodo_key'=>'tarjeta','items'=>8,'total'=>96000,'cambio'=>0],
                ['folio'=>'00030','fecha'=>'21/05/2026 08:25 AM','cajero'=>'Felipe Montes','metodo'=>'Efectivo','metodo_key'=>'efectivo','items'=>2,'total'=>32000,'cambio'=>18000],
                ['folio'=>'00029','fecha'=>'14/05/2026 01:17 PM','cajero'=>'Adan','metodo'=>'Efectivo','metodo_key'=>'efectivo','items'=>1,'total'=>17000,'cambio'=>3000],
                ['folio'=>'00028','fecha'=>'14/05/2026 01:17 PM','cajero'=>'Adan','metodo'=>'Efectivo','metodo_key'=>'efectivo','items'=>2,'total'=>34000,'cambio'=>16000],
                ['folio'=>'00027','fecha'=>'14/05/2026 06:37 AM','cajero'=>'Adan','metodo'=>'Efectivo','metodo_key'=>'efectivo','items'=>1,'total'=>2000,'cambio'=>0],
                ];
                $metodosBadge = [
                'efectivo' => ['bg'=>'#f0fdf4','color'=>'#16a34a'],
                'tarjeta' => ['bg'=>'#eff6ff','color'=>'#2563eb'],
                'nequi' => ['bg'=>'#faf5ff','color'=>'#7c3aed'],
                'transferencia' => ['bg'=>'#fff7ed','color'=>'#d97706'],
                ];
                @endphp
                @foreach($demoVentas as $demo)
                <tr style="border-bottom:1px solid #f1f5f9;" onmouseover="this.style.background='#fafbfd'" onmouseout="this.style.background='transparent'">
                    <td style="padding:16px 20px;"><span style="font-size:0.875rem; font-weight:800; color:#2563eb;">#{{ $demo['folio'] }}</span></td>
                    <td style="padding:16px 20px; font-size:0.875rem; color:#475569; font-weight:600; white-space:nowrap;">{{ $demo['fecha'] }}</td>
                    <td style="padding:16px 20px;">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div style="width:32px; height:32px; border-radius:50%; background:linear-gradient(135deg,#2563eb,#3b82f6); color:#fff; display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:800;">{{ mb_strtoupper(mb_substr($demo['cajero'],0,1)) }}</div>
                            <span style="font-size:0.875rem; font-weight:700; color:#1e293b;">{{ $demo['cajero'] }}</span>
                        </div>
                    </td>
                    <td style="padding:16px 20px;">
                        <span style="display:inline-flex; align-items:center; gap:5px; background:{{ $metodosBadge[$demo['metodo_key']]['bg'] }}; color:{{ $metodosBadge[$demo['metodo_key']]['color'] }}; padding:5px 12px; border-radius:8px; font-size:11px; font-weight:800;">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            {{ $demo['metodo'] }}
                        </span>
                    </td>
                    <td style="padding:16px 20px; text-align:center;"><span style="display:inline-block; background:#2563eb; color:#fff; font-weight:900; font-size:11px; padding:5px 12px; border-radius:8px;">{{ $demo['items'] }} und</span></td>
                    <td style="padding:16px 20px; text-align:right; font-weight:900; color:#0f172a; font-size:0.95rem;">${{ number_format($demo['total'],0,',','.') }}</td>
                    <td style="padding:16px 20px; text-align:right;">
                        @if($demo['cambio'] > 0)
                        <span style="font-weight:900; color:#16a34a; font-size:0.92rem;">${{ number_format($demo['cambio'],0,',','.') }}</span>
                        @else
                        <span style="font-weight:700; color:#94a3b8;">$0</span>
                        @endif
                    </td>
                    <td style="padding:16px 20px; text-align:center;">
                        <button type="button" onclick="verDetalleDemo('{{ $demo['folio'] }}')" title="Ver detalle"
                            style="width:38px; height:38px; border-radius:12px; border:1px solid #dbeafe; background:#eff6ff; color:#2563eb; display:inline-flex; align-items:center; justify-content:center; cursor:pointer;"
                            onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
                            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </td>
                </tr>
                @endforeach
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Table Footer / Paginador --}}
    <div style="padding:14px 24px; background:#f8fafc; border-top:1px solid #eef2f7; display:flex; align-items:center; justify-content:space-between;">
        @php
            $countVentas = method_exists($ventas, 'total') ? $ventas->total() : count($ventas);
        @endphp
        <span style="font-size:13px; color:#64748b; font-weight:600;">
            {{ $countVentas }} {{ $countVentas === 1 ? 'venta' : 'ventas' }}
        </span>

        <div style="display:flex; align-items:center; gap:6px;">
            @if(method_exists($ventas, 'hasPages') && $ventas->hasPages())
                {{-- Botón Anterior --}}
                @if($ventas->onFirstPage())
                    <button disabled style="padding:6px 16px; border-radius:8px; border:1px solid #e2e8f0; background:#f1f5f9; color:#94a3b8; font-size:13px; font-weight:600; cursor:not-allowed;">Ant.</button>
                @else
                    <a href="{{ $ventas->previousPageUrl() }}" style="padding:6px 16px; border-radius:8px; border:1px solid #e2e8f0; background:#fff; color:#475569; font-size:13px; font-weight:600; text-decoration:none;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#fff'">Ant.</a>
                @endif

                {{-- Números de página --}}
                @php
                    $start = max(1, $ventas->currentPage() - 1);
                    $end = min($ventas->lastPage(), $ventas->currentPage() + 1);
                @endphp

                @for($i = $start; $i <= $end; $i++)
                    @if($i == $ventas->currentPage())
                        <button style="padding:6px 14px; border-radius:8px; border:none; background:#1e3fa8; color:#fff; font-size:13px; font-weight:700;">{{ $i }}</button>
                    @else
                        <a href="{{ $ventas->url($i) }}" style="padding:6px 14px; border-radius:8px; border:1px solid #e2e8f0; background:#fff; color:#475569; font-size:13px; font-weight:600; text-decoration:none;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#fff'">{{ $i }}</a>
                    @endif
                @endfor

                {{-- Botón Siguiente --}}
                @if($ventas->hasMorePages())
                    <a href="{{ $ventas->nextPageUrl() }}" style="padding:6px 16px; border-radius:8px; border:1px solid #e2e8f0; background:#fff; color:#475569; font-size:13px; font-weight:600; text-decoration:none;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#fff'">Sig.</a>
                @else
                    <button disabled style="padding:6px 16px; border-radius:8px; border:1px solid #e2e8f0; background:#f1f5f9; color:#94a3b8; font-size:13px; font-weight:600; cursor:not-allowed;">Sig.</button>
                @endif
            @else
                <button disabled style="padding:6px 16px; border-radius:8px; border:1px solid #e2e8f0; background:#f1f5f9; color:#94a3b8; font-size:13px; font-weight:600; cursor:not-allowed;">Ant.</button>
                <button style="padding:6px 14px; border-radius:8px; border:none; background:#1e3fa8; color:#fff; font-size:13px; font-weight:700;">1</button>
                <button disabled style="padding:6px 16px; border-radius:8px; border:1px solid #e2e8f0; background:#f1f5f9; color:#94a3b8; font-size:13px; font-weight:600; cursor:not-allowed;">Sig.</button>
            @endif
        </div>
    </div>
</div>

{{-- ===== MODAL DETALLE DE VENTA ===== --}}
<div id="modal-detalle-venta" style="display:none; position:fixed; inset:0; z-index:9000; background:rgba(15,23,42,0.55); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:20px;">
    <div style="background:#fff; border-radius:24px; width:100%; max-width:560px; box-shadow:0 20px 60px rgba(0,0,0,0.2); overflow:hidden; animation:slideUp .25s ease;">

        {{-- Modal Header --}}
        <div style="background:linear-gradient(125deg,#1a3da8 0%,#2563eb 100%); padding:22px 28px; display:flex; align-items:center; justify-content:space-between;">
            <div style="display:flex; align-items:center; gap:12px;">
                <div style="width:40px; height:40px; border-radius:12px; background:rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center; color:#fff;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
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
        <div style="padding:18px 28px; border-top:1px solid #f1f5f9; display:flex; justify-content:flex-end; gap:10px;">
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
        from {
            transform: translateY(24px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
</style>

{{-- ===== JAVASCRIPT ===== --}}
<script>
    function verDetalleVenta(id) {
        document.getElementById('modal-folio').textContent = 'Venta #' + String(id).padStart(5, '0');
        document.getElementById('modal-body').innerHTML = `
        <div style="text-align:center; padding:20px;">
            <svg width="40" height="40" fill="none" stroke="#2563eb" stroke-width="1.8" viewBox="0 0 24 24" style="opacity:0.5;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <p style="margin-top:10px; color:#64748b; font-size:0.875rem; font-weight:600;">El detalle estará disponible cuando se conecte el backend.</p>
        </div>
    `;
        document.getElementById('modal-detalle-venta').style.display = 'flex';
    }

    function verDetalleDemo(folio) {
        document.getElementById('modal-folio').textContent = 'Venta #' + folio;
        document.getElementById('modal-body').innerHTML = `
        <div style="background:#f8fafc; border-radius:14px; padding:18px; display:flex; flex-direction:column; gap:10px;">
            <div style="display:flex; justify-content:space-between; font-size:0.875rem;">
                <span style="color:#64748b; font-weight:600;">Folio:</span>
                <span style="color:#0f172a; font-weight:800;">#${folio}</span>
            </div>
            <div style="display:flex; justify-content:space-between; font-size:0.875rem;">
                <span style="color:#64748b; font-weight:600;">Estado:</span>
                <span style="background:#f0fdf4; color:#16a34a; padding:3px 10px; border-radius:6px; font-size:11px; font-weight:800;">Completada</span>
            </div>
        </div>
        <p style="text-align:center; color:#94a3b8; font-size:12px; font-weight:600;">Datos de demostración — conecta el backend para ver el detalle completo.</p>
    `;
        document.getElementById('modal-detalle-venta').style.display = 'flex';
    }

    function cerrarModalDetalle() {
        document.getElementById('modal-detalle-venta').style.display = 'none';
    }

    // Cerrar modal al hacer click fuera
    document.getElementById('modal-detalle-venta').addEventListener('click', function(e) {
        if (e.target === this) cerrarModalDetalle();
    });
</script>

@endsection