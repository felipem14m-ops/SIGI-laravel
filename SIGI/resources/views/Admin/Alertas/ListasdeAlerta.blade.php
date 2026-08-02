@extends('layouts.sidebarAdmin')

@section('title', 'Alertas de Stock')
@section('page-title', 'Alertas de Stock')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- ===== HEADER BANNER ===== --}}
<div style="background:linear-gradient(125deg,#1a3da8 0%,#2350d4 45%,#3b6ef8 100%); border-radius:20px; padding:32px 36px; margin-bottom:24px; position:relative; overflow:hidden;">
    <div style="position:absolute; top:-40px; right:100px; width:220px; height:220px; background:rgba(255,255,255,0.05); border-radius:50%;"></div>
    <div style="position:absolute; top:10px; right:0; width:120px; height:120px; background:rgba(255,255,255,0.04); border-radius:50%;"></div>

    <div style="display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; position:relative; z-index:1;">
        <div>
            <div style="display:inline-block; background:rgba(255,255,255,0.18); border-radius:20px; padding:4px 14px; font-size:11px; font-weight:800; color:#fff; letter-spacing:0.08em; text-transform:uppercase; margin-bottom:10px;">MONITOREO DE INVENTARIO</div>
            <h2 style="font-size:2rem; font-weight:900; color:#fff; margin:0 0 6px; letter-spacing:-0.02em; display:flex; align-items:center; gap:10px;">
                <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                Alertas de Stock
            </h2>
            <p style="font-size:0.9rem; color:rgba(255,255,255,0.85); margin:0;">Productos que requieren atención inmediata para evitar desabastecimiento.</p>
        </div>

        <div style="display:flex; align-items:center; gap:12px; flex-shrink:0;">
            {{-- Badge Agotados --}}
            <div style="display:flex; align-items:center; gap:8px; background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.25); color:#fff; padding:8px 18px; border-radius:12px; font-size:12px; font-weight:800;">
                <span style="font-size:20px; font-weight:900; line-height:1;">🔥 {{ $totalAgotados }}</span>
                <span style="font-size:10px; opacity:0.85; letter-spacing:0.07em; text-transform:uppercase;">AGOTADOS</span>
            </div>

            {{-- Badge Bajo Stock --}}
            <div style="display:flex; align-items:center; gap:8px; background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.25); color:#fff; padding:8px 18px; border-radius:12px; font-size:12px; font-weight:800;">
                <span style="font-size:20px; font-weight:900; line-height:1;">⚠️ {{ $totalBajoStock }}</span>
                <span style="font-size:10px; opacity:0.85; letter-spacing:0.07em; text-transform:uppercase;">BAJO MÍNIMO</span>
            </div>
        </div>
    </div>
</div>

{{-- ===== TARJETAS RESUMEN DE ALERTAS ===== --}}
<div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:20px; margin-bottom:24px;">

    {{-- Tarjeta 1: Agotados --}}
    <div style="background:#fff; border-radius:20px; border:1px solid #e8ecf4; padding:22px 26px; box-shadow:0 1px 6px rgba(0,0,0,0.04); display:flex; align-items:center; gap:16px;">
        <div style="width:52px; height:52px; border-radius:16px; background:#ffe4e6; color:#e11d48; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <div>
            <div style="font-size:2rem; font-weight:900; color:#0f172a; line-height:1;">{{ $totalAgotados }}</div>
            <div style="font-size:11px; font-weight:800; color:#e11d48; text-transform:uppercase; letter-spacing:0.07em; margin-top:4px;">AGOTADOS</div>
        </div>
    </div>

    {{-- Tarjeta 2: Bajo Stock --}}
    <div style="background:#fff; border-radius:20px; border:1px solid #e8ecf4; padding:22px 26px; box-shadow:0 1px 6px rgba(0,0,0,0.04); display:flex; align-items:center; gap:16px;">
        <div style="width:52px; height:52px; border-radius:16px; background:#fef3c7; color:#d97706; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <div>
            <div style="font-size:2rem; font-weight:900; color:#0f172a; line-height:1;">{{ $totalBajoStock }}</div>
            <div style="font-size:11px; font-weight:800; color:#d97706; text-transform:uppercase; letter-spacing:0.07em; margin-top:4px;">BAJO STOCK</div>
        </div>
    </div>

    {{-- Tarjeta 3: Total Alertas --}}
    <div style="background:#fff; border-radius:20px; border:1px solid #e8ecf4; padding:22px 26px; box-shadow:0 1px 6px rgba(0,0,0,0.04); display:flex; align-items:center; gap:16px;">
        <div style="width:52px; height:52px; border-radius:16px; background:#eff6ff; color:#2563eb; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
        </div>
        <div>
            <div style="font-size:2rem; font-weight:900; color:#0f172a; line-height:1;">{{ $totalAlertas }}</div>
            <div style="font-size:11px; font-weight:800; color:#2563eb; text-transform:uppercase; letter-spacing:0.07em; margin-top:4px;">TOTAL ALERTAS</div>
        </div>
    </div>

</div>

{{-- ===== PANEL DE FILTROS ===== --}}
<div style="background:#fff; border-radius:20px; border:1px solid #e8ecf4; padding:22px 28px; margin-bottom:20px; box-shadow:0 1px 6px rgba(0,0,0,0.04);">
    <form method="GET" action="{{ route('alertas.index') }}" id="filter-form">
        <div style="display:flex; align-items:flex-end; gap:16px; flex-wrap:wrap;">

            {{-- Nombre / Código --}}
            <div style="flex:1; min-width:240px;">
                <label style="display:block; font-size:10px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:8px;">NOMBRE DEL PRODUCTO</label>
                <div style="position:relative;">
                    <svg style="position:absolute; left:14px; top:50%; transform:translateY(-50%); pointer-events:none;" width="16" height="16" fill="none" stroke="#94a3b8" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Ej. Zapatos Nike, #PROD-001..."
                        style="width:100%; padding:11px 14px 11px 42px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; font-family:inherit;"
                        onfocus="this.style.borderColor='#2563eb'; this.style.background='#fff';"
                        onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc';" />
                </div>
            </div>

            {{-- Tipo de Alerta --}}
            <div style="flex:0 0 200px;">
                <label style="display:block; font-size:10px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:8px;">TIPO DE ALERTA</label>
                <select name="tipo_alerta"
                    style="width:100%; padding:11px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; cursor:pointer; font-family:inherit;">
                    <option value="">Todos los tipos</option>
                    <option value="agotado" {{ request('tipo_alerta') === 'agotado' ? 'selected' : '' }}>Crítico - Agotado</option>
                    <option value="bajo" {{ request('tipo_alerta') === 'bajo' ? 'selected' : '' }}>Bajo Mínimo</option>
                </select>
            </div>

            {{-- Estado del Producto --}}
            <div style="flex:0 0 180px;">
                <label style="display:block; font-size:10px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:8px;">ESTADO</label>
                <select name="estado"
                    style="width:100%; padding:11px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; cursor:pointer; font-family:inherit;">
                    <option value="">Todos los estados</option>
                    <option value="activo" {{ request('estado') === 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="inactivo" {{ request('estado') === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            {{-- Botones --}}
            <div style="display:flex; gap:8px;">
                <button type="submit"
                    style="display:inline-flex; align-items:center; gap:8px; background:#1e3fa8; color:#fff; padding:11px 20px; border-radius:12px; font-size:13px; font-weight:700; border:none; cursor:pointer;">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                    </svg>
                    Aplicar Filtros
                </button>

                <a href="{{ route('alertas.index') }}"
                    style="display:inline-flex; align-items:center; gap:8px; background:#f8fafc; color:#64748b; border:1.5px solid #e2e8f0; padding:11px 20px; border-radius:12px; font-size:13px; font-weight:700; text-decoration:none; cursor:pointer;"
                    onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 15.07M9 5h-.582m0 0l-3.5 3.5M8.418 5L12 8.5" />
                    </svg>
                    Limpiar Filtros
                </a>
            </div>

        </div>
        <div style="font-size:11px; color:#e11d48; font-weight:700; margin-top:14px; display:flex; align-items:center; gap:4px;">
            ▼ Mostrando todas las alertas que requieren atención
        </div>
    </form>
</div>

{{-- ===== TABLA DE ALERTAS ===== --}}
<div style="background:#fff; border-radius:20px; border:1px solid #e8ecf4; overflow:hidden; box-shadow:0 1px 6px rgba(0,0,0,0.04);">
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; text-align:left;">
            <thead>
                <tr style="background:#f8fafc; border-bottom:1.5px solid #eef2f7;">
                    <th style="padding:14px 20px; font-size:10px; font-weight:800; color:#64748b; letter-spacing:0.07em; text-transform:uppercase;">PRODUCTO</th>
                    <th style="padding:14px 20px; font-size:10px; font-weight:800; color:#64748b; letter-spacing:0.07em; text-transform:uppercase;">CATEGORÍA</th>
                    <th style="padding:14px 20px; font-size:10px; font-weight:800; color:#64748b; letter-spacing:0.07em; text-transform:uppercase;">PROVEEDOR</th>
                    <th style="padding:14px 20px; font-size:10px; font-weight:800; color:#64748b; letter-spacing:0.07em; text-transform:uppercase;">NIVEL DE ALERTA</th>
                    <th style="padding:14px 20px; font-size:10px; font-weight:800; color:#64748b; letter-spacing:0.07em; text-transform:uppercase; text-align:center;">STOCK MÍN.</th>
                    <th style="padding:14px 20px; font-size:10px; font-weight:800; color:#64748b; letter-spacing:0.07em; text-transform:uppercase; text-align:center;">STOCK ACTUAL</th>
                    <th style="padding:14px 20px; font-size:10px; font-weight:800; color:#64748b; letter-spacing:0.07em; text-transform:uppercase; text-align:right;">ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                @forelse($alertas as $prod)
                @php
                    $isAgotado = $prod->stock_actual <= 0;
                @endphp
                <tr style="border-bottom:1px solid #f1f5f9;" onmouseover="this.style.background='#fafbfd'" onmouseout="this.style.background='transparent'">

                    {{-- Producto --}}
                    <td style="padding:16px 20px;">
                        <div style="display:flex; align-items:center; gap:12px;">
                            {{-- Icono de Alerta --}}
                            @if($isAgotado)
                            <div style="width:34px; height:34px; border-radius:50%; background:#ffe4e6; color:#e11d48; display:flex; align-items:center; justify-content:center; font-weight:900; font-size:14px; flex-shrink:0;">
                                ×
                            </div>
                            @else
                            <div style="width:34px; height:34px; border-radius:50%; background:#fef3c7; color:#d97706; display:flex; align-items:center; justify-content:center; font-weight:900; font-size:14px; flex-shrink:0;">
                                ↓
                            </div>
                            @endif

                            <div>
                                <div style="font-weight:900; color:#0f172a; font-size:0.9rem;">{{ $prod->nombre }}</div>
                                <div style="font-size:11px; color:#94a3b8; font-weight:700;">#PROD-{{ str_pad($prod->id_producto, 3, '0', STR_PAD_LEFT) }}</div>
                            </div>
                        </div>
                    </td>

                    {{-- Categoría --}}
                    <td style="padding:16px 20px; font-size:0.875rem; color:#475569; font-weight:600;">
                        {{ $prod->categoria->nombre ?? '—' }}
                    </td>

                    {{-- Proveedor --}}
                    <td style="padding:16px 20px; font-size:0.875rem; color:#475569; font-weight:600;">
                        {{ $prod->proveedor->nombre ?? '—' }}
                    </td>

                    {{-- Nivel de Alerta --}}
                    <td style="padding:16px 20px;">
                        @if($isAgotado)
                        <span style="display:inline-flex; align-items:center; gap:6px; background:#ffe4e6; color:#e11d48; font-weight:800; font-size:11px; padding:5px 12px; border-radius:20px;">
                            🔥 Crítico - Agotado
                        </span>
                        @else
                        <span style="display:inline-flex; align-items:center; gap:6px; background:#fef3c7; color:#d97706; font-weight:800; font-size:11px; padding:5px 12px; border-radius:20px;">
                            ⚠️ Bajo Mínimo
                        </span>
                        @endif
                    </td>

                    {{-- Stock Mín. --}}
                    <td style="padding:16px 20px; text-align:center; font-size:0.875rem; color:#64748b; font-weight:700;">
                        {{ $prod->stock_minimo }} und
                    </td>

                    {{-- Stock Actual --}}
                    <td style="padding:16px 20px; text-align:center;">
                        @if($isAgotado)
                        <span style="font-weight:900; color:#e11d48; font-size:0.9rem;">0 und</span>
                        @else
                        <span style="font-weight:900; color:#d97706; font-size:0.9rem;">{{ $prod->stock_actual }} und</span>
                        @endif
                    </td>

                    {{-- Acciones --}}
                    <td style="padding:16px 20px; text-align:right;">
                        <div style="display:flex; align-items:center; justify-content:flex-end; gap:8px;">
                            {{-- Botón Reabastecer -> lleva a Movimientos --}}
                            <a href="{{ route('movimientos.index') }}"
                                style="display:inline-flex; align-items:center; gap:6px; background:#eff6ff; color:#2563eb; border:1px solid #dbeafe; padding:7px 14px; border-radius:10px; font-size:12px; font-weight:800; text-decoration:none;"
                                onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
                                <span style="font-size:14px; font-weight:900;">+</span> Reabastecer
                            </a>

                            {{-- Botón Atender --}}
                            <button type="button"
                                onclick="Swal.fire({icon:'success',title:'Alerta Atendida',text:'Se ha notificado al área de compras para el producto {{ addslashes($prod->nombre) }}.',confirmButtonColor:'#2563eb'})"
                                style="display:inline-flex; align-items:center; gap:6px; background:#f0fdf4; color:#16a34a; border:1px solid #bbf7d0; padding:7px 14px; border-radius:10px; font-size:12px; font-weight:800; cursor:pointer;"
                                onmouseover="this.style.background='#dcfce7'" onmouseout="this.style.background='#f0fdf4'">
                                ✓ Atender
                            </button>
                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding:56px 24px; text-align:center;">
                        <div style="display:flex; flex-direction:column; align-items:center; gap:10px; color:#94a3b8;">
                            <svg width="44" height="44" fill="none" stroke="#22c55e" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span style="font-size:0.95rem; font-weight:700; color:#16a34a;">¡Excelente! No hay alertas de stock pendientes.</span>
                            <span style="font-size:12px; color:#94a3b8;">Todos los productos superan su nivel de stock mínimo.</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Footer Paginación --}}
    <div style="padding:14px 24px; background:#f8fafc; border-top:1px solid #eef2f7; display:flex; align-items:center; justify-content:space-between;">
        @php
            $countAlertas = method_exists($alertas, 'total') ? $alertas->total() : count($alertas);
        @endphp
        <span style="font-size:13px; color:#64748b; font-weight:600;">
            {{ $countAlertas }} {{ $countAlertas === 1 ? 'alerta' : 'alertas' }}
        </span>

        <div style="display:flex; align-items:center; gap:6px;">
            @if(method_exists($alertas, 'hasPages') && $alertas->hasPages())
                @if($alertas->onFirstPage())
                    <button disabled style="padding:6px 16px; border-radius:8px; border:1px solid #e2e8f0; background:#f1f5f9; color:#94a3b8; font-size:13px; font-weight:600; cursor:not-allowed;">Ant.</button>
                @else
                    <a href="{{ $alertas->previousPageUrl() }}" style="padding:6px 16px; border-radius:8px; border:1px solid #e2e8f0; background:#fff; color:#475569; font-size:13px; font-weight:600; text-decoration:none;">Ant.</a>
                @endif

                @php
                    $start = max(1, $alertas->currentPage() - 1);
                    $end = min($alertas->lastPage(), $alertas->currentPage() + 1);
                @endphp

                @for($i = $start; $i <= $end; $i++)
                    @if($i == $alertas->currentPage())
                        <button style="padding:6px 14px; border-radius:8px; border:none; background:#1e3fa8; color:#fff; font-size:13px; font-weight:700;">{{ $i }}</button>
                    @else
                        <a href="{{ $alertas->url($i) }}" style="padding:6px 14px; border-radius:8px; border:1px solid #e2e8f0; background:#fff; color:#475569; font-size:13px; font-weight:600; text-decoration:none;">{{ $i }}</a>
                    @endif
                @endfor

                @if($alertas->hasMorePages())
                    <a href="{{ $alertas->nextPageUrl() }}" style="padding:6px 16px; border-radius:8px; border:1px solid #e2e8f0; background:#fff; color:#475569; font-size:13px; font-weight:600; text-decoration:none;">Sig.</a>
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

@endsection
