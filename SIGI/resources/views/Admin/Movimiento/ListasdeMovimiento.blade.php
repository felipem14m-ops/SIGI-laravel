@extends('layouts.sidebarAdmin')

@section('title', 'Gestión de Movimientos de Inventario')
@section('page-title', 'Gestión de Movimientos')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- ===== HEADER BANNER ===== --}}
<div style="background:linear-gradient(125deg,#1a3da8 0%,#2350d4 45%,#3b6ef8 100%); border-radius:20px; padding:32px 36px; margin-bottom:24px; position:relative; overflow:hidden;">
    <div style="position:absolute; top:-40px; right:100px; width:220px; height:220px; background:rgba(255,255,255,0.05); border-radius:50%;"></div>
    <div style="position:absolute; top:10px; right:0; width:120px; height:120px; background:rgba(255,255,255,0.04); border-radius:50%;"></div>

    <div style="display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; position:relative; z-index:1;">
        <div>
            <div style="display:inline-block; background:rgba(255,255,255,0.18); border-radius:20px; padding:4px 14px; font-size:11px; font-weight:800; color:#fff; letter-spacing:0.08em; text-transform:uppercase; margin-bottom:10px;">CONTROL Y AUDITORÍA</div>
            <h2 style="font-size:2rem; font-weight:900; color:#fff; margin:0 0 6px; letter-spacing:-0.02em;">Movimientos de Inventario</h2>
            <p style="font-size:0.9rem; color:rgba(255,255,255,0.85); margin:0;">Registro, control de entradas, salidas y kardex en tiempo real.</p>
        </div>

        <div style="display:flex; align-items:center; gap:12px; flex-shrink:0;">
            {{-- Badge contador --}}
            <div style="display:flex; align-items:center; gap:8px; background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.25); color:#fff; padding:8px 18px; border-radius:12px; font-size:12px; font-weight:800;">
                <span style="font-size:22px; font-weight:900; line-height:1;">{{ method_exists($movimientos, 'total') ? $movimientos->total() : count($movimientos) }}</span>
                <span style="font-size:10px; opacity:0.85; letter-spacing:0.07em; text-transform:uppercase;">MOVIMIENTOS</span>
            </div>

            {{-- Botón Nuevo Movimiento --}}
            <button type="button"
                onclick="abrirModalMovimiento()"
                style="display:inline-flex; align-items:center; gap:8px; background:#fff; color:#1a3da8; padding:10px 20px; border-radius:12px; font-size:13px; font-weight:800; border:none; cursor:pointer; box-shadow:0 2px 10px rgba(0,0,0,0.12);"
                onmouseover="this.style.background='#eff6ff'"
                onmouseout="this.style.background='#fff'">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Nuevo Movimiento
            </button>
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

@if(session('error'))
<div style="background:#fff1f2; border:1px solid #fecdd3; border-radius:14px; padding:14px 20px; margin-bottom:20px; display:flex; align-items:center; gap:10px;">
    <svg width="20" height="20" fill="none" stroke="#e11d48" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <span style="font-size:0.875rem; color:#b91c1c; font-weight:700;">{{ session('error') }}</span>
</div>
@endif

{{-- ===== PANEL DE FILTROS ===== --}}
<div style="background:#fff; border-radius:20px; border:1px solid #e8ecf4; padding:22px 28px; margin-bottom:20px; box-shadow:0 1px 6px rgba(0,0,0,0.04);">
    <form method="GET" action="{{ route('movimientos.index') }}" id="filter-form">
        <div style="display:flex; align-items:flex-end; gap:16px; flex-wrap:wrap;">

            {{-- Buscar Producto --}}
            <div style="flex:1; min-width:240px;">
                <label style="display:block; font-size:10px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:8px;">BUSCAR POR PRODUCTO O CÓDIGO</label>
                <div style="position:relative;">
                    <svg style="position:absolute; left:14px; top:50%; transform:translateY(-50%); pointer-events:none;" width="16" height="16" fill="none" stroke="#94a3b8" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Nombre o código único del producto..."
                        style="width:100%; padding:11px 14px 11px 42px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; font-family:inherit;"
                        onfocus="this.style.borderColor='#2563eb'; this.style.background='#fff';"
                        onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc';" />
                </div>
            </div>

            {{-- Tipo de Movimiento --}}
            <div style="flex:0 0 200px;">
                <label style="display:block; font-size:10px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:8px;">TIPO DE MOVIMIENTO</label>
                <select name="tipo"
                    style="width:100%; padding:11px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; cursor:pointer; font-family:inherit;">
                    <option value="">Todos los tipos</option>
                    <option value="entrada" {{ request('tipo') === 'entrada' ? 'selected' : '' }}>Entrada (+)</option>
                    <option value="salida" {{ request('tipo') === 'salida' ? 'selected' : '' }}>Salida (-)</option>
                    <option value="ajuste" {{ request('tipo') === 'ajuste' ? 'selected' : '' }}>Ajuste (=)</option>
                </select>
            </div>

            {{-- Botones --}}
            <div style="display:flex; gap:8px;">
                <button type="submit"
                    style="display:inline-flex; align-items:center; gap:8px; background:#1e3fa8; color:#fff; padding:11px 20px; border-radius:12px; font-size:13px; font-weight:700; border:none; cursor:pointer;">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                    </svg>
                    Filtrar
                </button>

                <a href="{{ route('movimientos.index') }}"
                    style="display:inline-flex; align-items:center; gap:8px; background:#f8fafc; color:#64748b; border:1.5px solid #e2e8f0; padding:11px 20px; border-radius:12px; font-size:13px; font-weight:700; text-decoration:none; cursor:pointer;"
                    onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
                    Limpiar
                </a>
            </div>

        </div>
    </form>
</div>

{{-- ===== TABLA DE MOVIMIENTOS ===== --}}
<div style="background:#fff; border-radius:20px; border:1px solid #e8ecf4; overflow:hidden; box-shadow:0 1px 6px rgba(0,0,0,0.04);">
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; text-align:left;">
            <thead>
                <tr style="background:#f8fafc; border-bottom:1.5px solid #eef2f7;">
                    <th style="padding:13px 18px; font-size:10px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase;">FECHA</th>
                    <th style="padding:13px 18px; font-size:10px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase;">PRODUCTO</th>
                    <th style="padding:13px 18px; font-size:10px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase; text-align:center;">TIPO</th>
                    <th style="padding:13px 18px; font-size:10px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase; text-align:center;">CANTIDAD</th>
                    <th style="padding:13px 18px; font-size:10px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase; text-align:center;">STOCK ANT.</th>
                    <th style="padding:13px 18px; font-size:10px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase; text-align:center;">STOCK NUEVO</th>
                    <th style="padding:13px 18px; font-size:10px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase;">MOTIVO / USUARIO</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movimientos as $mov)
                @php
                    $codigo = strtolower($mov->tipoMovimiento->codigo ?? $mov->origen);
                    $tipoClass = [
                        'entrada' => ['bg'=>'#f0fdf4','color'=>'#16a34a','label'=>'Entrada (+)'],
                        'salida'  => ['bg'=>'#fff1f2','color'=>'#e11d48','label'=>'Salida (-)'],
                        'ajuste'  => ['bg'=>'#eff6ff','color'=>'#2563eb','label'=>'Ajuste (=)'],
                    ];
                    $badge = $tipoClass[$codigo] ?? ['bg'=>'#f8fafc','color'=>'#64748b','label'=>strtoupper($codigo)];
                @endphp
                <tr style="border-bottom:1px solid #f1f5f9;" onmouseover="this.style.background='#fafbfd'" onmouseout="this.style.background='transparent'">

                    {{-- Fecha --}}
                    <td style="padding:14px 18px; font-size:0.85rem; color:#475569; font-weight:600; white-space:nowrap;">
                        {{ $mov->fecha ? \Carbon\Carbon::parse($mov->fecha)->format('d/m/Y h:i A') : $mov->created_at->format('d/m/Y h:i A') }}
                    </td>

                    {{-- Producto --}}
                    <td style="padding:14px 18px;">
                        <div style="font-weight:800; color:#0f172a; font-size:0.875rem;">
                            {{ $mov->producto->nombre ?? 'Producto no encontrado' }}
                        </div>
                        <div style="font-size:11px; color:#94a3b8; font-weight:600;">
                            Cód: {{ $mov->producto->codigoUnico ?? 'N/A' }}
                        </div>
                    </td>

                    {{-- Tipo --}}
                    <td style="padding:14px 18px; text-align:center;">
                        <span style="display:inline-block; background:{{ $badge['bg'] }}; color:{{ $badge['color'] }}; font-weight:800; font-size:11px; padding:4px 12px; border-radius:20px;">
                            {{ $badge['label'] }}
                        </span>
                    </td>

                    {{-- Cantidad --}}
                    <td style="padding:14px 18px; text-align:center; font-weight:900; font-size:0.9rem; color:#0f172a;">
                        {{ $mov->cantidad }} un.
                    </td>

                    {{-- Stock Anterior --}}
                    <td style="padding:14px 18px; text-align:center; font-size:0.875rem; color:#64748b; font-weight:700;">
                        {{ $mov->stock_anterior }}
                    </td>

                    {{-- Stock Nuevo --}}
                    <td style="padding:14px 18px; text-align:center; font-size:0.875rem; color:#1e293b; font-weight:900;">
                        {{ $mov->stock_resultante }}
                    </td>

                    {{-- Motivo / Usuario --}}
                    <td style="padding:14px 18px;">
                        <div style="font-size:0.85rem; color:#334155; font-weight:600;">{{ $mov->motivo ?? 'Sin especificación' }}</div>
                        <div style="font-size:11px; color:#94a3b8; font-weight:600;">Por: {{ $mov->usuario->nombre ?? ($mov->usuario->name ?? 'Sistema') }}</div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding:48px 24px; text-align:center;">
                        <div style="display:flex; flex-direction:column; align-items:center; gap:8px; color:#94a3b8;">
                            <svg width="40" height="40" fill="none" stroke="#cbd5e1" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <span style="font-size:0.875rem; font-weight:600;">No hay movimientos registrados.</span>
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
            $countMov = method_exists($movimientos, 'total') ? $movimientos->total() : count($movimientos);
        @endphp
        <span style="font-size:13px; color:#64748b; font-weight:600;">
            {{ $countMov }} {{ $countMov === 1 ? 'movimiento' : 'movimientos' }}
        </span>

        <div style="display:flex; align-items:center; gap:6px;">
            @if(method_exists($movimientos, 'hasPages') && $movimientos->hasPages())
                @if($movimientos->onFirstPage())
                    <button disabled style="padding:6px 16px; border-radius:8px; border:1px solid #e2e8f0; background:#f1f5f9; color:#94a3b8; font-size:13px; font-weight:600; cursor:not-allowed;">Ant.</button>
                @else
                    <a href="{{ $movimientos->previousPageUrl() }}" style="padding:6px 16px; border-radius:8px; border:1px solid #e2e8f0; background:#fff; color:#475569; font-size:13px; font-weight:600; text-decoration:none;">Ant.</a>
                @endif

                @php
                    $start = max(1, $movimientos->currentPage() - 1);
                    $end = min($movimientos->lastPage(), $movimientos->currentPage() + 1);
                @endphp

                @for($i = $start; $i <= $end; $i++)
                    @if($i == $movimientos->currentPage())
                        <button style="padding:6px 14px; border-radius:8px; border:none; background:#1e3fa8; color:#fff; font-size:13px; font-weight:700;">{{ $i }}</button>
                    @else
                        <a href="{{ $movimientos->url($i) }}" style="padding:6px 14px; border-radius:8px; border:1px solid #e2e8f0; background:#fff; color:#475569; font-size:13px; font-weight:600; text-decoration:none;">{{ $i }}</a>
                    @endif
                @endfor

                @if($movimientos->hasMorePages())
                    <a href="{{ $movimientos->nextPageUrl() }}" style="padding:6px 16px; border-radius:8px; border:1px solid #e2e8f0; background:#fff; color:#475569; font-size:13px; font-weight:600; text-decoration:none;">Sig.</a>
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

{{-- ===== MODAL REGISTRAR MOVIMIENTO ===== --}}
<div id="modal-movimiento" style="display:none; position:fixed; inset:0; z-index:9000; overflow-y:auto;">
    <div style="position:fixed; inset:0; background:rgba(15,23,42,0.6); backdrop-filter:blur(4px);" onclick="cerrarModalMovimiento()"></div>
    <div style="display:flex; align-items:center; justify-content:center; min-height:100vh; padding:20px; pointer-events:none;">
        <div style="position:relative; background:#fff; border-radius:24px; max-width:560px; width:100%; overflow:hidden; box-shadow:0 20px 60px rgba(0,0,0,0.25); pointer-events:all; animation:slideUp 0.2s ease;">

            {{-- Header --}}
            <div style="background:linear-gradient(125deg,#1a3da8 0%,#2563eb 100%); padding:22px 28px; display:flex; align-items:center; justify-content:space-between; color:#fff;">
                <div style="display:flex; align-items:center; gap:12px;">
                    <div style="background:rgba(255,255,255,0.2); width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div>
                        <div style="font-size:10px; font-weight:800; color:rgba(255,255,255,0.8); text-transform:uppercase; letter-spacing:0.08em;">KARDEX DE INVENTARIO</div>
                        <div style="font-size:1.1rem; font-weight:900; color:#fff;">Registrar Movimiento</div>
                    </div>
                </div>
                <button type="button" onclick="cerrarModalMovimiento()" style="width:34px; height:34px; border-radius:8px; border:none; background:rgba(255,255,255,0.15); color:#fff; font-size:18px; cursor:pointer; display:flex; align-items:center; justify-content:center;">×</button>
            </div>

            {{-- Body / Formulario --}}
            <form method="POST" action="{{ route('movimientos.store') }}" style="padding:28px; display:flex; flex-direction:column; gap:20px;">
                @csrf

                {{-- Producto --}}
                <div>
                    <label style="display:block; font-size:11px; font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:8px;">PRODUCTO *</label>
                    <select name="id_producto" required
                        style="width:100%; padding:12px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; font-size:0.875rem; color:#1e293b; outline:none; cursor:pointer; font-family:inherit;">
                        <option value="">-- Selecciona un producto --</option>
                        @foreach($productos as $prod)
                            <option value="{{ $prod->id_producto }}">
                                {{ $prod->nombre }} (Stock actual: {{ $prod->stock_actual }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tipo y Cantidad --}}
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div>
                        <label style="display:block; font-size:11px; font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:8px;">TIPO DE MOVIMIENTO *</label>
                        <select name="tipo" required
                            style="width:100%; padding:12px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; font-size:0.875rem; color:#1e293b; outline:none; cursor:pointer; font-family:inherit;">
                            <option value="entrada">Entrada (+)</option>
                            <option value="salida">Salida (-)</option>
                            <option value="ajuste">Ajuste (Reemplazar Stock)</option>
                        </select>
                    </div>

                    <div>
                        <label style="display:block; font-size:11px; font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:8px;">CANTIDAD *</label>
                        <input type="number" name="cantidad" min="1" required placeholder="Ej: 10"
                            style="width:100%; padding:12px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; font-size:0.875rem; color:#1e293b; outline:none; font-family:inherit; box-sizing:border-box;" />
                    </div>
                </div>

                {{-- Motivo --}}
                <div>
                    <label style="display:block; font-size:11px; font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:8px;">MOTIVO / OBSERVACIÓN</label>
                    <input type="text" name="motivo" placeholder="Ej: Compra de proveedor, merma, devolución..."
                        style="width:100%; padding:12px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; font-size:0.875rem; color:#1e293b; outline:none; font-family:inherit; box-sizing:border-box;" />
                </div>

                {{-- Footer --}}
                <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:8px;">
                    <button type="button" onclick="cerrarModalMovimiento()"
                        style="padding:11px 22px; border-radius:12px; border:1.5px solid #e2e8f0; background:#f8fafc; color:#475569; font-size:13px; font-weight:700; cursor:pointer;">
                        Cancelar
                    </button>
                    <button type="submit"
                        style="padding:11px 24px; border-radius:12px; border:none; background:#1e3fa8; color:#fff; font-size:13px; font-weight:800; cursor:pointer; box-shadow:0 4px 12px rgba(30,63,168,0.25);">
                        Guardar Movimiento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function abrirModalMovimiento() {
        document.getElementById('modal-movimiento').style.display = 'block';
    }
    function cerrarModalMovimiento() {
        document.getElementById('modal-movimiento').style.display = 'none';
    }
</script>

@endsection
