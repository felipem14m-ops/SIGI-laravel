@extends('layouts.sidebarAdmin')

@section('title', 'Catálogo de Productos')
@section('page-title', 'Catálogo de Productos')

@section('content')

{{-- SweetAlert2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- ===== HEADER BANNER ===== --}}
<div style="background:linear-gradient(125deg,#1a3da8 0%,#2350d4 45%,#3b6ef8 100%); border-radius:20px; padding:32px 36px; margin-bottom:24px; position:relative; overflow:hidden;">
    <div style="position:absolute; top:-40px; right:100px; width:220px; height:220px; background:rgba(255,255,255,0.05); border-radius:50%;"></div>
    <div style="position:absolute; top:10px; right:0; width:120px; height:120px; background:rgba(255,255,255,0.04); border-radius:50%;"></div>

    <div style="display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; position:relative; z-index:1;">
        <div>
            <div style="display:inline-block; background:rgba(255,255,255,0.18); border-radius:20px; padding:4px 14px; font-size:11px; font-weight:800; color:#fff; letter-spacing:0.08em; text-transform:uppercase; margin-bottom:10px;">GESTIÓN COMERCIAL</div>
            <h2 style="font-size:2rem; font-weight:900; color:#fff; margin:0 0 6px; letter-spacing:-0.02em;">Catálogo de Productos</h2>
            <p style="font-size:0.9rem; color:rgba(255,255,255,0.85); margin:0;">Organiza las clasificaciones de tus productos de forma profesional.</p>
        </div>

        <div style="display:flex; align-items:center; gap:12px; flex-shrink:0;">
            {{-- Badge Contador --}}
            <div style="display:flex; align-items:center; gap:8px; background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.25); color:#fff; padding:8px 16px; border-radius:12px; font-size:12px; font-weight:800;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <div style="display:flex; flex-direction:column; line-height:1.1;">
                    <span style="font-size:14px; font-weight:900;">{{ $productos->count() }}</span>
                    <span style="font-size:9px; opacity:0.8; letter-spacing:0.05em; text-transform:uppercase;">Prodcutos</span>
                </div>
            </div>

            {{-- Botones Toggle Vista --}}
            <div style="display:flex; background:rgba(255,255,255,0.2); padding:3px; border-radius:10px;">
                <button id="btn-vista-cards" type="button"
                    onclick="mostrarVistaCards()"
                    style="background:#fff; color:#1a3da8; border:none; padding:8px; border-radius:7px; display:flex; align-items:center; justify-content:center; cursor:pointer; transition:all .2s;"
                    title="Vista tarjetas">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                </button>
                <button id="btn-vista-tabla" type="button"
                    onclick="mostrarVistaTabla()"
                    style="background:transparent; color:#fff; border:none; padding:8px; border-radius:7px; display:flex; align-items:center; justify-content:center; cursor:pointer; transition:all .2s;"
                    title="Vista tabla">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            {{-- Botón Nueva Categoría --}}
            <button type="button" onclick="openModal('create-modal')"
                style="display:flex; align-items:center; gap:8px; background:#fff; color:#1a3da8; padding:10px 20px; border-radius:12px; font-size:13px; font-weight:800; border:none; cursor:pointer; box-shadow:0 4px 16px rgba(0,0,0,0.15);"
                onmouseover="this.style.transform='translateY(-1px)'"
                onmouseout="this.style.transform=''">
                <svg width="16" height="16" fill="none" stroke="#1a3da8" stroke-width="3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Nuevo Producto
            </button>
        </div>
    </div>
</div>

{{-- ===== FILTER PANEL ===== --}}
<div style="background:#fff; border-radius:16px; border:1px solid #e8ecf4; padding:20px 24px; margin-bottom:28px; box-shadow:0 1px 6px rgba(0,0,0,0.04);">
    <form method="GET" action="{{ route('productos.index') }}">
        <div style="display:grid; grid-template-columns:1fr 240px auto auto; gap:16px; align-items:end;">

            <div>
                <label style="display:block; font-size:11px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:8px;">NOMBRE DE PRODUCTO</label>
                <div style="position:relative;">
                    <svg style="position:absolute; left:14px; top:50%; transform:translateY(-50%); pointer-events:none;" width="16" height="16" fill="none" stroke="#94a3b8" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" placeholder="Ej. Calzado Deportivo..."
                        style="width:100%; padding:11px 14px 11px 42px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; font-family:inherit;"
                        onfocus="this.style.borderColor='#3b82f6'; this.style.background='#fff'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'"
                        onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'" />
                </div>
            </div>

            <div>
                <label style="display:block; font-size:11px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:8px;">ESTADO</label>
                <select name="status"
                    style="width:100%; padding:11px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; cursor:pointer; font-family:inherit;"
                    onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'"
                    onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                    <option value="">Todos los estados</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Activas</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactivas</option>
                </select>
            </div>

            <button type="submit"
                style="display:inline-flex; align-items:center; gap:8px; background:#2563eb; color:#fff; padding:11px 24px; border-radius:12px; font-size:0.875rem; font-weight:700; border:none; cursor:pointer; white-space:nowrap; box-shadow:0 3px 10px rgba(37,99,235,0.25);"
                onmouseover="this.style.background='#1d4ed8'"
                onmouseout="this.style.background='#2563eb'">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Aplicar Filtros
            </button>

            <a href="{{ route('productos.index') }}"
                style="display:inline-flex; align-items:center; gap:7px; background:#f8fafc; border:1px solid #e2e8f0; color:#475569; padding:11px 20px; border-radius:12px; font-size:0.875rem; font-weight:700; text-decoration:none; white-space:nowrap;"
                onmouseover="this.style.background='#f1f5f9'"
                onmouseout="this.style.background='#f8fafc'">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 15.07M9 5h-.582m0 0l-3.5 3.5M8.418 5L12 8.5" />
                </svg>
                Limpiar
            </a>

        </div>
    </form>
    <div style="font-size:12px; color:#64748b; font-weight:700; margin-top:14px;">
        Encontradas {{ $productos->count() }} {{ $productos->count() === 1 ? 'producto' : 'productos' }}
    </div>
</div>

{{-- ===== VISTA: TARJETAS ===== --}}
<div id="vista-cards">
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(320px, 1fr)); gap:24px;">

        @forelse($productos as $producto)
        <div style="background:#fff; border-radius:20px; border:1px solid #e8ecf4; padding:16px; box-shadow:0 4px 16px rgba(0,0,0,0.03); display:flex; flex-direction:column; position:relative;">
            <div style="position:relative; width:100%; height:180px; border-radius:14px; overflow:hidden; background:#fafafa; margin-bottom:16px; display:flex; align-items:center; justify-content:center; padding:8px;">
                @if($producto->imagen)
                <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" style="max-width:100%; max-height:100%; object-fit:contain;" />
                @else
                <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; background:linear-gradient(135deg,#eff6ff,#dbeafe); color:#3b82f6; font-weight:900; font-size:2.5rem; letter-spacing:-0.02em;">
                    {{ mb_strtoupper(mb_substr($producto->nombre, 0, 1)) }}
                </div>
                @endif
                @if($producto->estado === 'activo')
                <span style="position:absolute; top:10px; right:10px; background:#dcfce7; color:#15803d; border:1px solid #bbf7d0; padding:4px 10px; border-radius:999px; font-size:11px; font-weight:800;">Activo</span>
                @elseif($producto->estado === 'agotado')
                <span style="position:absolute; top:10px; right:10px; background:#fffbeb; color:#b45309; border:1px solid #fef3c7; padding:4px 10px; border-radius:999px; font-size:11px; font-weight:800;">Agotado</span>
                @else
                <span style="position:absolute; top:10px; right:10px; background:#f1f5f9; color:#64748b; border:1px solid #e2e8f0; padding:4px 10px; border-radius:999px; font-size:11px; font-weight:800;">Inactivo</span>
                @endif
            </div>

            <div style="font-size:11px; font-weight:800; color:#3b82f6; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:2px;">{{ $producto->codigoUnico }}</div>
            <h3 style="font-size:1.1rem; font-weight:900; color:#0f172a; margin:0 0 4px;">{{ $producto->nombre }}</h3>
            <div style="font-size:12px; color:#64748b; font-weight:600; margin-bottom:8px;">{{ $producto->categoria->nombre ?? 'Sin categoría' }}</div>
            
            @if($producto->descripcion)
            <p style="font-size:12px; color:#64748b; margin:0 0 12px; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; min-height:32px;">{{ $producto->descripcion }}</p>
            @else
            <div style="min-height:32px; margin-bottom:12px;"></div>
            @endif

            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; background:#f8fafc; padding:10px 14px; border-radius:12px; border:1px solid #f1f5f9;">
                <div>
                    <span style="display:block; font-size:10px; color:#94a3b8; font-weight:700; text-transform:uppercase;">PRECIO</span>
                    <span style="font-size:1.1rem; font-weight:900; color:#0f172a;">${{ number_format($producto->precio_venta, 2) }}</span>
                </div>
                <div style="text-align:right;">
                    <span style="display:block; font-size:10px; color:#94a3b8; font-weight:700; text-transform:uppercase;">STOCK</span>
                    <span style="font-size:0.95rem; font-weight:800; color:{{ $producto->stock_actual <= $producto->stock_minimo ? '#e11d48' : '#2563eb' }};">{{ $producto->stock_actual }} un.</span>
                </div>
            </div>

            <div style="display:flex; justify-content:center; align-items:center; gap:8px; margin-top:auto;">
                {{-- ✏️ Editar --}}
                <button type="button"
                    title="Editar producto"
                    onclick="openEditModal('{{ $producto->id_producto }}','{{ addslashes($producto->codigoUnico) }}','{{ addslashes($producto->nombre) }}','{{ $producto->id_categoria }}','{{ $producto->id_proveedor }}','{{ $producto->precio_costo }}','{{ $producto->precio_venta }}','{{ $producto->stock_actual }}','{{ $producto->stock_minimo }}','{{ $producto->estado }}','{{ $producto->fechaVencimiento }}','{{ addslashes($producto->descripcion ?? '') }}')"
                    style="flex:1; height:38px; border-radius:12px; border:1px solid #dbeafe; background:#eff6ff; color:#2563eb; display:inline-flex; align-items:center; justify-content:center; cursor:pointer;"
                    onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </button>

                {{-- 🚫 Toggle Estado --}}
                <form id="toggle-form-{{ $producto->id_producto }}" method="POST" action="{{ route('productos.update', $producto->id_producto) }}" style="display:inline; flex:1;">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="toggle_status" value="1" />
                    <button type="button"
                        title="{{ $producto->estado === 'activo' ? 'Desactivar producto' : 'Activar producto' }}"
                        onclick="confirmToggle('{{ $producto->id_producto }}','{{ addslashes($producto->nombre) }}','{{ $producto->estado }}')"
                        style="width:100%; height:38px; border-radius:12px; border:1px solid #fef3c7; background:#fffbeb; color:#d97706; display:inline-flex; align-items:center; justify-content:center; cursor:pointer;"
                        onmouseover="this.style.background='#fde68a'" onmouseout="this.style.background='#fffbeb'">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                    </button>
                </form>

                {{-- 🗑️ Eliminar --}}
                <form id="delete-form-{{ $producto->id_producto }}" method="POST" action="{{ route('productos.destroy', $producto->id_producto) }}" style="display:inline; flex:1;">
                    @csrf
                    @method('DELETE')
                    <button type="button"
                        title="Eliminar producto"
                        onclick="confirmDelete('{{ $producto->id_producto }}','{{ addslashes($producto->nombre) }}')"
                        style="width:100%; height:38px; border-radius:12px; border:1px solid #fecdd3; background:#fff1f2; color:#e11d48; display:inline-flex; align-items:center; justify-content:center; cursor:pointer;"
                        onmouseover="this.style.background='#fecdd3'" onmouseout="this.style.background='#fff1f2'">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div style="grid-column:1/-1; padding:64px 24px; text-align:center; background:#fff; border-radius:20px; border:1px solid #e8ecf4;">
            <div style="display:flex; flex-direction:column; align-items:center; gap:10px; color:#94a3b8;">
                <svg width="48" height="48" fill="none" stroke="#cbd5e1" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <span style="font-size:0.9rem; font-weight:600;">No se encontraron productos registrados.</span>
            </div>
        </div>
        @endforelse

    </div>
</div>

{{-- ===== VISTA: TABLA ===== --}}
<div id="vista-tabla" style="display:none;">
    <div style="background:#fff; border-radius:16px; border:1px solid #e8ecf4; overflow:hidden; box-shadow:0 1px 6px rgba(0,0,0,0.04);">
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; text-align:left;">
                <thead>
                    <tr style="background:#f8fafc; border-bottom:1.5px solid #eef2f7;">
                        <th style="padding:14px 20px; font-size:11px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase;">Código</th>
                        <th style="padding:14px 20px; font-size:11px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase;">Nombre</th>
                        <th style="padding:14px 20px; font-size:11px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase;">Categoría</th>
                        <th style="padding:14px 20px; font-size:11px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase;">Proveedor</th>
                        <th style="padding:14px 20px; font-size:11px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase;">Estado</th>
                        <th style="padding:14px 20px; font-size:11px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase;">Precio</th>
                        <th style="padding:14px 20px; font-size:11px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase;">Stock</th>
                        <th style="padding:14px 20px; font-size:11px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase;">Vencimiento</th>
                        <th style="padding:14px 20px; font-size:11px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase; text-align:right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productos as $index => $producto)
                    <tr style="border-bottom:1px solid #f1f5f9;" onmouseover="this.style.background='#fafbfd'" onmouseout="this.style.background='transparent'">

                        {{-- CÓDIGO --}}
                        <td style="padding:16px 20px; font-size:0.875rem; color:#475569; font-weight:700; white-space:nowrap;">
                            {{ $producto->codigoUnico }}
                        </td>

                        {{-- NOMBRE (Avatar/Imagen + Nombre) --}}
                        <td style="padding:16px 20px;">
                            <div style="display:flex; align-items:center; gap:12px;">
                                @if($producto->imagen)
                                <img src="{{ asset('storage/' . $producto->imagen) }}"
                                    alt="{{ $producto->nombre }}"
                                    style="width:38px; height:38px; object-fit:cover; border-radius:50%; flex-shrink:0; border:1px solid #e2e8f0;">
                                @else
                                <div style="width:38px; height:38px; border-radius:50%; background:#2563eb; color:#fff; display:flex; align-items:center; justify-content:center; font-size:15px; font-weight:800; flex-shrink:0;">
                                    {{ mb_strtoupper(mb_substr($producto->nombre, 0, 1)) }}
                                </div>
                                @endif
                                <span style="font-weight:700; color:#1e293b; font-size:0.875rem;">{{ $producto->nombre }}</span>
                            </div>
                        </td>

                        {{-- CATEGORÍA --}}
                        <td style="padding:16px 20px; font-size:0.875rem; color:#475569; font-weight:600;">
                            {{ $producto->categoria->nombre ?? 'Sin categoría' }}
                        </td>

                        {{-- PROVEEDOR --}}
                        <td style="padding:16px 20px; font-size:0.875rem; color:#475569; font-weight:500;">
                            {{ $producto->proveedor->nombre ?? '—' }}
                        </td>

                        {{-- ESTADO --}}
                        <td style="padding:16px 20px;">
                            @if($producto->estado === 'activo')
                            <span style="display:inline-flex; align-items:center; gap:5px; background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0; padding:5px 14px; border-radius:999px; font-size:11px; font-weight:800; letter-spacing:0.05em;">
                                <span style="width:6px; height:6px; background:#22c55e; border-radius:50%;"></span>
                                ACTIVO
                            </span>
                            @elseif($producto->estado === 'agotado')
                            <span style="display:inline-flex; align-items:center; gap:5px; background:#fffbeb; color:#b45309; border:1px solid #fef3c7; padding:5px 14px; border-radius:999px; font-size:11px; font-weight:800; letter-spacing:0.05em;">
                                <span style="width:6px; height:6px; background:#f59e0b; border-radius:50%;"></span>
                                AGOTADO
                            </span>
                            @else
                            <span style="display:inline-flex; align-items:center; gap:5px; background:#fff1f2; color:#be123c; border:1px solid #fecdd3; padding:5px 14px; border-radius:999px; font-size:11px; font-weight:800; letter-spacing:0.05em;">
                                <span style="width:6px; height:6px; background:#f43f5e; border-radius:50%;"></span>
                                INACTIVO
                            </span>
                            @endif
                        </td>

                        {{-- PRECIO --}}
                        <td style="padding:16px 20px; font-size:0.875rem; color:#0f172a; font-weight:800; white-space:nowrap;">
                            ${{ number_format($producto->precio_venta, 2) }}
                        </td>

                        {{-- STOCK --}}
                        <td style="padding:16px 20px; font-size:0.875rem; font-weight:700; color:{{ $producto->stock_actual <= $producto->stock_minimo ? '#e11d48' : '#1e293b' }};">
                            {{ $producto->stock_actual }}
                            @if($producto->stock_actual <= $producto->stock_minimo)
                                <span style="font-size:10px; background:#ffe4e6; color:#e11d48; padding:2px 6px; border-radius:6px; margin-left:4px;">Bajo</span>
                            @endif
                        </td>

                        {{-- VENCIMIENTO --}}
                        <td style="padding:16px 20px; font-size:0.85rem; color:#64748b; white-space:nowrap;">
                            {{ $producto->fechaVencimiento ? \Carbon\Carbon::parse($producto->fechaVencimiento)->format('d/m/Y') : '—' }}
                        </td>

                        {{-- Acciones --}}
                        <td style="padding:14px 20px; text-align:right;">
                            <div style="display:flex; align-items:center; justify-content:flex-end; gap:6px;">

                                {{-- ✏️ Editar --}}
                                <button type="button"
                                    title="Editar Producto"
                                    onclick="openEditModal('{{ $producto->id_producto }}','{{ addslashes($producto->codigoUnico) }}','{{ addslashes($producto->nombre) }}','{{ $producto->id_categoria }}','{{ $producto->id_proveedor }}','{{ $producto->precio_costo }}','{{ $producto->precio_venta }}','{{ $producto->stock_actual }}','{{ $producto->stock_minimo }}','{{ $producto->estado }}','{{ $producto->fechaVencimiento }}','{{ addslashes($producto->descripcion ?? '') }}')"
                                    style="width:36px; height:36px; border-radius:10px; border:1px solid #dbeafe; background:#eff6ff; color:#2563eb; display:inline-flex; align-items:center; justify-content:center; cursor:pointer;"
                                    onmouseover="this.style.background='#dbeafe'"
                                    onmouseout="this.style.background='#eff6ff'">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>

                                {{-- 🚫 Desactivar / Activar --}}
                                <form id="toggle-form-{{ $producto->id_producto }}" method="POST" action="{{ route('productos.update', $producto->id_producto) }}" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="toggle_status" value="1" />
                                    <button type="button"
                                        title="{{ $producto->estado === 'activo' ? 'Desactivar producto' : 'Activar producto' }}"
                                        onclick="confirmToggle('{{ $producto->id_producto }}','{{ addslashes($producto->nombre) }}','{{ $producto->estado }}')"
                                        style="width:36px; height:36px; border-radius:10px; border:1px solid #fef3c7; background:#fffbeb; color:#d97706; display:inline-flex; align-items:center; justify-content:center; cursor:pointer;"
                                        onmouseover="this.style.background='#fde68a'"
                                        onmouseout="this.style.background='#fffbeb'">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                        </svg>
                                    </button>
                                </form>

                                {{-- 🗑️ Eliminar --}}
                                <form id="delete-form-{{ $producto->id_producto }}" method="POST" action="{{ route('productos.destroy', $producto->id_producto) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        title="Eliminar producto"
                                        onclick="confirmDelete('{{ $producto->id_producto }}','{{ addslashes($producto->nombre) }}')"
                                        style="width:36px; height:36px; border-radius:10px; border:1px solid #fecdd3; background:#fff1f2; color:#e11d48; display:inline-flex; align-items:center; justify-content:center; cursor:pointer;"
                                        onmouseover="this.style.background='#fecdd3'"
                                        onmouseout="this.style.background='#fff1f2'">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="padding:64px 24px; text-align:center;">
                            <div style="display:flex; flex-direction:column; align-items:center; gap:10px; color:#94a3b8;">
                                <svg width="40" height="40" fill="none" stroke="#cbd5e1" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                <span style="font-size:0.875rem; font-weight:600;">No se encontraron productos registradas.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Table Footer / Paginador --}}
        <div style="padding:14px 24px; background:#f8fafc; border-top:1px solid #eef2f7; display:flex; align-items:center; justify-content:space-between;">
            <span style="font-size:13px; color:#64748b; font-weight:600;">{{ $productos->count() }} {{ $productos->count() === 1 ? 'producto' : 'productos' }}</span>
            <div style="display:flex; align-items:center; gap:6px;">
                <button disabled style="padding:6px 16px; border-radius:8px; border:1px solid #e2e8f0; background:#f1f5f9; color:#94a3b8; font-size:13px; font-weight:600; cursor:not-allowed;">Ant.</button>
                <button style="padding:6px 14px; border-radius:8px; border:none; background:#1e3fa8; color:#fff; font-size:13px; font-weight:700;">1</button>
                <button disabled style="padding:6px 16px; border-radius:8px; border:1px solid #e2e8f0; background:#f1f5f9; color:#94a3b8; font-size:13px; font-weight:600; cursor:not-allowed;">Sig.</button>
            </div>
        </div>
    </div>
</div>

{{-- ===== MODAL: CREAR PRODUCTO ===== --}}
<div id="create-modal" style="display:none; position:fixed; inset:0; z-index:9000; overflow-y:auto;">
    <div style="position:fixed; inset:0; background:rgba(10,18,46,0.6); backdrop-filter:blur(5px);" onclick="closeModal('create-modal')"></div>
    <div style="display:flex; align-items:center; justify-content:center; min-height:100vh; padding:20px; pointer-events:none;">
        <div style="position:relative; background:#fff; border-radius:28px; max-width:760px; width:100%; overflow:hidden; box-shadow:0 24px 64px rgba(0,0,0,0.25); z-index:1; pointer-events:all;">

            {{-- Modal Header --}}
            <div style="background:#1d4ed8; padding:20px 28px; display:flex; align-items:center; justify-content:space-between; color:#fff;">
                <div style="display:flex; align-items:center; gap:12px;">
                    <div style="background:rgba(255,255,255,0.2); width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <h3 style="font-size:1.25rem; font-weight:800; color:#fff; margin:0;">Nuevo Producto</h3>
                </div>
                <button type="button" onclick="closeModal('create-modal')" style="background:transparent; border:none; color:#fff; cursor:pointer; display:flex; align-items:center; justify-content:center; opacity:0.9;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.9'">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <form
                action="{{ route('productos.store') }}"
                method="POST"
                enctype="multipart/form-data"
                style="padding:28px; display:flex; flex-direction:column; gap:20px;">

                @csrf

                {{-- Fila: Código Único + Nombre del Producto --}}
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">

                    {{-- Código Único --}}
                    <div>
                        <label style="display:flex; align-items:center; gap:6px; font-size:11px; font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:8px;">
                            <svg width="14" height="14" fill="none" stroke="#475569" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 5v14M8 5v14M12 5v14M16 5v10M20 5v14" />
                            </svg>
                            CÓDIGO ÚNICO *
                        </label>
                        <input type="text" name="codigo" required placeholder="Ej: PROD-001"
                            style="width:100%; padding:12px 16px; background:#fff; border:1.5px solid #e2e8f0; border-radius:12px; font-size:0.9rem; color:#1e293b; outline:none; box-sizing:border-box; font-family:inherit;"
                            onfocus="this.style.borderColor='#2563eb'; this.style.boxShadow='0 0 0 3px rgba(37,99,235,0.1)'"
                            onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'" />
                    </div>

                    {{-- Nombre del producto --}}
                    <div>
                        <label style="display:flex; align-items:center; gap:6px; font-size:11px; font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:8px;">
                            <svg width="14" height="14" fill="none" stroke="#475569" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            NOMBRE DEL PRODUCTO *
                        </label>
                        <input type="text" name="nombre" required placeholder="Ej: Zapatos Deportivos Nike"
                            style="width:100%; padding:12px 16px; background:#fff; border:1.5px solid #e2e8f0; border-radius:12px; font-size:0.9rem; color:#1e293b; outline:none; box-sizing:border-box; font-family:inherit;"
                            onfocus="this.style.borderColor='#2563eb'; this.style.boxShadow='0 0 0 3px rgba(37,99,235,0.1)'"
                            onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'" />
                    </div>

                </div>

                {{-- Fila: Categoría + Proveedor --}}
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">

                    {{-- Categoría --}}
                    <div>
                        <label style="display:flex; align-items:center; gap:6px; font-size:11px; font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:8px;">
                            <svg width="14" height="14" fill="none" stroke="#475569" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v7a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                            </svg>
                            CATEGORÍA *
                        </label>
                        <select name="id_categoria" required
                            style="width:100%; padding:12px 16px; background:#fff; border:1.5px solid #e2e8f0; border-radius:12px; font-size:0.9rem; color:#1e293b; outline:none; box-sizing:border-box; font-family:inherit; appearance:none; -webkit-appearance:none; background-image:url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2216%22 height=%2216%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%2364748b%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22><polyline points=%226 9 12 15 18 9%22/></svg>'); background-repeat:no-repeat; background-position:right 14px center; padding-right:38px;"
                            onfocus="this.style.borderColor='#2563eb'; this.style.boxShadow='0 0 0 3px rgba(37,99,235,0.1)'"
                            onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                            <option value="" selected disabled>Seleccionar categoría...</option>
                            @foreach($categorias ?? [] as $categoria)
                                <option value="{{ $categoria->id_categoria }}">{{ $categoria->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Proveedor --}}
                    <div>
                        <label style="display:flex; align-items:center; gap:6px; font-size:11px; font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:8px;">
                            <svg width="14" height="14" fill="none" stroke="#475569" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16V6a1 1 0 011-1h9v11M3 16a2 2 0 104 0m-4 0a2 2 0 004 0m9 0a2 2 0 104 0m-4 0a2 2 0 004 0m0 0h2v-5l-3-4h-4v9" />
                            </svg>
                            PROVEEDOR
                        </label>
                        <select name="id_proveedor"
                            style="width:100%; padding:12px 16px; background:#fff; border:1.5px solid #e2e8f0; border-radius:12px; font-size:0.9rem; color:#1e293b; outline:none; box-sizing:border-box; font-family:inherit; appearance:none; -webkit-appearance:none; background-image:url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2216%22 height=%2216%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%2364748b%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22><polyline points=%226 9 12 15 18 9%22/></svg>'); background-repeat:no-repeat; background-position:right 14px center; padding-right:38px;"
                            onfocus="this.style.borderColor='#2563eb'; this.style.boxShadow='0 0 0 3px rgba(37,99,235,0.1)'"
                            onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                            <option value="" selected>Sin proveedor asignado</option>
                            @foreach($proveedores ?? [] as $proveedor)
                                <option value="{{ $proveedor->id_proveedor }}">{{ $proveedor->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                {{-- Fila: Precio Costo, Precio Venta, Stock Inicial, Alerta Mín --}}
                <div style="display:grid; grid-template-columns:1fr 1fr 1fr 1fr; gap:16px;">

                    {{-- Precio Costo --}}
                    <div>
                        <label style="display:flex; align-items:center; gap:6px; font-size:11px; font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:8px;">
                            <svg width="14" height="14" fill="none" stroke="#475569" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 1v22M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" />
                            </svg>
                            PRECIO COSTO
                        </label>
                        <div style="display:flex; align-items:stretch; border:1.5px solid #e2e8f0; border-radius:12px; overflow:hidden; background:#fff;" id="precioCostoWrap">
                            <span style="display:flex; align-items:center; justify-content:center; width:34px; background:#f1f5f9; color:#64748b; font-weight:700; border-right:1.5px solid #e2e8f0;">$</span>
                            <input type="number" name="precio_costo" placeholder="0.00" min="0" step="0.01"
                                style="width:100%; padding:12px 12px; background:#fff; border:none; font-size:0.9rem; color:#1e293b; outline:none; box-sizing:border-box; font-family:inherit;"
                                onfocus="document.getElementById('precioCostoWrap').style.borderColor='#2563eb'; document.getElementById('precioCostoWrap').style.boxShadow='0 0 0 3px rgba(37,99,235,0.1)'"
                                onblur="document.getElementById('precioCostoWrap').style.borderColor='#e2e8f0'; document.getElementById('precioCostoWrap').style.boxShadow='none'" />
                        </div>
                    </div>

                    {{-- Precio Venta --}}
                    <div>
                        <label style="display:flex; align-items:center; gap:6px; font-size:11px; font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:8px;">
                            <svg width="14" height="14" fill="none" stroke="#475569" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 1v22M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" />
                            </svg>
                            PRECIO VENTA
                        </label>
                        <div style="display:flex; align-items:stretch; border:1.5px solid #e2e8f0; border-radius:12px; overflow:hidden; background:#fff;" id="precioVentaWrap">
                            <span style="display:flex; align-items:center; justify-content:center; width:34px; background:#f1f5f9; color:#64748b; font-weight:700; border-right:1.5px solid #e2e8f0;">$</span>
                            <input type="number" name="precio_venta" placeholder="0.00" min="0" step="0.01"
                                style="width:100%; padding:12px 12px; background:#fff; border:none; font-size:0.9rem; color:#1e293b; outline:none; box-sizing:border-box; font-family:inherit;"
                                onfocus="document.getElementById('precioVentaWrap').style.borderColor='#2563eb'; document.getElementById('precioVentaWrap').style.boxShadow='0 0 0 3px rgba(37,99,235,0.1)'"
                                onblur="document.getElementById('precioVentaWrap').style.borderColor='#e2e8f0'; document.getElementById('precioVentaWrap').style.boxShadow='none'" />
                        </div>
                    </div>

                    {{-- Stock Inicial --}}
                    <div>
                        <label style="display:flex; align-items:center; gap:6px; font-size:11px; font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:8px;">
                            <svg width="14" height="14" fill="none" stroke="#475569" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z" />
                            </svg>
                            STOCK INICIAL
                        </label>
                        <input type="number" name="stock_inicial" placeholder="0" min="0" step="1"
                            style="width:100%; padding:12px 16px; background:#fff; border:1.5px solid #e2e8f0; border-radius:12px; font-size:0.9rem; color:#1e293b; outline:none; box-sizing:border-box; font-family:inherit;"
                            onfocus="this.style.borderColor='#2563eb'; this.style.boxShadow='0 0 0 3px rgba(37,99,235,0.1)'"
                            onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'" />
                    </div>

                    {{-- Alerta Mín --}}
                    <div>
                        <label style="display:flex; align-items:center; gap:6px; font-size:11px; font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:8px;">
                            <svg width="14" height="14" fill="none" stroke="#475569" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                            </svg>
                            ALERTA MÍN.
                        </label>
                        <input type="number" name="alerta_minima" placeholder="5" min="0" step="1"
                            style="width:100%; padding:12px 16px; background:#fff; border:1.5px solid #e2e8f0; border-radius:12px; font-size:0.9rem; color:#1e293b; outline:none; box-sizing:border-box; font-family:inherit;"
                            onfocus="this.style.borderColor='#2563eb'; this.style.boxShadow='0 0 0 3px rgba(37,99,235,0.1)'"
                            onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'" />
                    </div>

                </div>

                {{-- Fila: Fecha de Vencimiento --}}
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                    <div>
                        <label style="display:flex; align-items:center; gap:6px; font-size:11px; font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:8px;">
                            <svg width="14" height="14" fill="none" stroke="#475569" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            FECHA DE VENCIMIENTO (OPCIONAL)
                        </label>
                        <input type="date" name="fechaVencimiento"
                            style="width:100%; padding:12px 16px; background:#fff; border:1.5px solid #e2e8f0; border-radius:12px; font-size:0.9rem; color:#1e293b; outline:none; box-sizing:border-box; font-family:inherit;"
                            onfocus="this.style.borderColor='#2563eb'; this.style.boxShadow='0 0 0 3px rgba(37,99,235,0.1)'"
                            onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'" />
                    </div>
                </div>

                {{-- Fila: Descripción + Imagen --}}
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">

                    {{-- Descripción --}}
                    <div>
                        <label style="display:flex; align-items:center; gap:6px; font-size:11px; font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:8px;">
                            <svg width="14" height="14" fill="none" stroke="#475569" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                            DESCRIPCIÓN DEL PRODUCTO
                        </label>
                        <textarea name="descripcion" placeholder="Detalles, material, tallas..." rows="5"
                            style="width:100%; padding:12px 16px; background:#fff; border:1.5px solid #e2e8f0; border-radius:12px; font-size:0.9rem; color:#1e293b; outline:none; box-sizing:border-box; resize:none; font-family:inherit;"
                            onfocus="this.style.borderColor='#2563eb'; this.style.boxShadow='0 0 0 3px rgba(37,99,235,0.1)'"
                            onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'"></textarea>
                    </div>

                    {{-- Imagen Dropzone --}}
                    <div>
                        <label style="display:flex; align-items:center; gap:6px; font-size:11px; font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:8px;">
                            <svg width="14" height="14" fill="none" stroke="#475569" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            IMAGEN
                        </label>

                        <div style="border:2px dashed #cbd5e1; border-radius:14px; padding:18px; background:#fafbfd; text-align:center;">

                            <label
                                for="cat-file-input"
                                style="display:inline-block; padding:8px 18px; background:#2563eb; color:white; border-radius:10px; cursor:pointer; font-weight:700; font-size:13px;">
                                Seleccionar Imagen
                            </label>

                            <input
                                id="cat-file-input"
                                name="imagen"
                                type="file"
                                accept="image/*"
                                style="display:none;"
                                onchange="mostrarImagen(event)">

                            <p id="nombre-imagen" style="margin-top:12px; font-size:13px; color:#64748b;">
                                Ningún archivo seleccionado
                            </p>

                            <img id="preview-imagen" style="display:none; margin-top:15px; width:120px; height:120px; object-fit:cover; border-radius:12px; border:2px solid #e2e8f0;">

                        </div>
                    </div>

                </div>

                {{-- Modal Footer --}}
                <div style="display:flex; align-items:center; justify-content:flex-end; gap:12px; margin-top:8px;">
                    <button type="button" onclick="closeModal('create-modal')"
                        style="padding:10px 24px; background:#f8fafc; color:#475569; font-weight:700; border:none; border-radius:30px; font-size:0.875rem; cursor:pointer; font-family:inherit;"
                        onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
                        Cancelar
                    </button>
                    <button type="submit"
                        style="display:flex; align-items:center; gap:8px; padding:10px 26px; background:#1d4ed8; color:#fff; font-weight:800; border:none; border-radius:30px; font-size:0.875rem; cursor:pointer; font-family:inherit; box-shadow:0 4px 14px rgba(29,78,216,0.35);"
                        onmouseover="this.style.background='#1e40af'" onmouseout="this.style.background='#1d4ed8'">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Guardar Cambios
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- ===== MODAL: EDITAR PRODUCTO ===== --}}
<div id="edit-modal" style="display:none; position:fixed; inset:0; z-index:9000; overflow-y:auto;">
    <div style="position:fixed; inset:0; background:rgba(10,18,46,0.6); backdrop-filter:blur(5px);" onclick="closeModal('edit-modal')"></div>
    <div style="display:flex; align-items:center; justify-content:center; min-height:100vh; padding:20px; pointer-events:none;">
        <div style="position:relative; background:#fff; border-radius:20px; max-width:680px; width:100%; padding:32px; box-shadow:0 24px 64px rgba(0,0,0,0.2); z-index:1; pointer-events:all;">

            <div style="display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid #f1f5f9; padding-bottom:16px; margin-bottom:22px;">
                <div>
                    <h3 style="font-size:1.2rem; font-weight:800; color:#0f172a; margin:0 0 2px;">Editar Producto</h3>
                    <p style="font-size:12px; color:#94a3b8; margin:0;">Modifica los detalles del producto seleccionado</p>
                </div>
                <button type="button" onclick="closeModal('edit-modal')" style="background:#f1f5f9; border:none; width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; cursor:pointer; color:#64748b;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="edit-form" method="POST" enctype="multipart/form-data" style="display:flex; flex-direction:column; gap:16px;">
                @csrf
                @method('PUT')

                {{-- Fila: Código + Nombre --}}
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div>
                        <label style="display:block; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:6px;">Código Único *</label>
                        <input type="text" id="edit-codigo" name="codigo" required
                            style="width:100%; padding:10px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:10px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; font-family:inherit;" />
                    </div>
                    <div>
                        <label style="display:block; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:6px;">Nombre del Producto *</label>
                        <input type="text" id="edit-nombre" name="nombre" required
                            style="width:100%; padding:10px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:10px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; font-family:inherit;" />
                    </div>
                </div>

                {{-- Fila: Categoría + Proveedor --}}
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div>
                        <label style="display:block; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:6px;">Categoría *</label>
                        <select id="edit-categoria" name="id_categoria" required
                            style="width:100%; padding:10px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:10px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; font-family:inherit;">
                            @foreach($categorias ?? [] as $categoria)
                                <option value="{{ $categoria->id_categoria }}">{{ $categoria->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display:block; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:6px;">Proveedor</label>
                        <select id="edit-proveedor" name="id_proveedor"
                            style="width:100%; padding:10px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:10px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; font-family:inherit;">
                            <option value="">Sin proveedor asignado</option>
                            @foreach($proveedores ?? [] as $proveedor)
                                <option value="{{ $proveedor->id_proveedor }}">{{ $proveedor->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Fila: Precios y Stock --}}
                <div style="display:grid; grid-template-columns:1fr 1fr 1fr 1fr; gap:12px;">
                    <div>
                        <label style="display:block; font-size:10px; font-weight:700; color:#64748b; text-transform:uppercase; margin-bottom:6px;">Precio Costo</label>
                        <input type="number" id="edit-precio-costo" name="precio_costo" step="0.01" min="0"
                            style="width:100%; padding:10px 12px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:10px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; font-family:inherit;" />
                    </div>
                    <div>
                        <label style="display:block; font-size:10px; font-weight:700; color:#64748b; text-transform:uppercase; margin-bottom:6px;">Precio Venta *</label>
                        <input type="number" id="edit-precio-venta" name="precio_venta" step="0.01" min="0" required
                            style="width:100%; padding:10px 12px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:10px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; font-family:inherit;" />
                    </div>
                    <div>
                        <label style="display:block; font-size:10px; font-weight:700; color:#64748b; text-transform:uppercase; margin-bottom:6px;">Stock Actual</label>
                        <input type="number" id="edit-stock-actual" name="stock_actual" min="0"
                            style="width:100%; padding:10px 12px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:10px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; font-family:inherit;" />
                    </div>
                    <div>
                        <label style="display:block; font-size:10px; font-weight:700; color:#64748b; text-transform:uppercase; margin-bottom:6px;">Alerta Mín.</label>
                        <input type="number" id="edit-stock-minimo" name="stock_minimo" min="0"
                            style="width:100%; padding:10px 12px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:10px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; font-family:inherit;" />
                    </div>
                </div>

                {{-- Fila: Estado + Vencimiento --}}
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div>
                        <label style="display:block; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:6px;">Estado *</label>
                        <select id="edit-estado" name="estado" required
                            style="width:100%; padding:10px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:10px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; font-family:inherit;">
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                            <option value="agotado">Agotado</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:6px;">Fecha Vencimiento (Opcional)</label>
                        <input type="date" id="edit-vencimiento" name="fechaVencimiento"
                            style="width:100%; padding:10px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:10px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; font-family:inherit;" />
                    </div>
                </div>

                {{-- Fila: Descripción + Imagen --}}
                <div>
                    <label style="display:block; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:6px;">Descripción</label>
                    <textarea id="edit-descripcion" name="descripcion" rows="3"
                        style="width:100%; padding:10px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:10px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; resize:none; font-family:inherit;"></textarea>
                </div>
                <div>
                    <label style="display:block; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:6px;">Cambiar Imagen (Opcional)</label>
                    <input type="file" name="imagen" accept="image/*"
                        style="width:100%; font-size:0.85rem; color:#475569;" />
                </div>

                <div style="display:flex; gap:10px; padding-top:10px;">
                    <button type="button" onclick="closeModal('edit-modal')"
                        style="flex:1; padding:12px; background:#f1f5f9; color:#475569; font-weight:700; border:none; border-radius:12px; font-size:0.875rem; cursor:pointer; font-family:inherit;">Cancelar</button>
                    <button type="submit"
                        style="flex:1; padding:12px; background:#1e3fa8; color:#fff; font-weight:700; border:none; border-radius:12px; font-size:0.875rem; cursor:pointer; font-family:inherit;">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== JAVASCRIPT ===== --}}
<script>
    // ─────────────────────────────────────────────────────────────
    //  MODALES
    // ─────────────────────────────────────────────────────────────
    function openModal(id) {
        document.getElementById(id).style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
        document.body.style.overflow = '';
    }

    function openEditModal(id, codigo, nombre, id_categoria, id_proveedor, precio_costo, precio_venta, stock_actual, stock_minimo, estado, fechaVencimiento, descripcion) {
        document.getElementById('edit-form').action = '{{ url('/productos') }}/' + id;
        document.getElementById('edit-codigo').value = codigo;
        document.getElementById('edit-nombre').value = nombre;
        document.getElementById('edit-categoria').value = id_categoria;
        document.getElementById('edit-proveedor').value = id_proveedor ?? '';
        document.getElementById('edit-precio-costo').value = precio_costo;
        document.getElementById('edit-precio-venta').value = precio_venta;
        document.getElementById('edit-stock-actual').value = stock_actual;
        document.getElementById('edit-stock-minimo').value = stock_minimo;
        document.getElementById('edit-estado').value = estado;
        document.getElementById('edit-vencimiento').value = fechaVencimiento ?? '';
        document.getElementById('edit-descripcion').value = descripcion ?? '';
        openModal('edit-modal');
    }

    // ─────────────────────────────────────────────────────────────
    //  TOGGLE ESTADO (Activar / Desactivar)
    // ─────────────────────────────────────────────────────────────
    function confirmToggle(id, nombre, estado) {
        const activo = estado === 'activo';
        const accion = activo ? 'desactivar' : 'activar';
        const accionCap = activo ? 'Desactivar' : 'Activar';

        Swal.fire({
            title: accionCap + ' producto',
            html: '¿Estás seguro de que deseas <strong>' + accion + '</strong> el producto <strong>' + nombre + '</strong>?',
            icon: activo ? 'warning' : 'question',
            showCancelButton: true,
            confirmButtonText: accionCap,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: activo ? '#d97706' : '#16a34a',
            cancelButtonColor: '#6b7280',
        }).then(function(result) {
            if (result.isConfirmed) {
                document.getElementById('toggle-form-' + id).submit();
            }
        });
    }

    // ─────────────────────────────────────────────────────────────
    //  ELIMINAR CATEGORÍA
    // ─────────────────────────────────────────────────────────────
    function confirmDelete(id, nombre) {
        Swal.fire({
            title: '¿Eliminar categoría?',
            html: '¿Estás seguro de eliminar la categoría <strong>' + nombre + '</strong>? Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b'
        }).then(function(result) {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    // ─────────────────────────────────────────────────────────────
    //  PREVIEW DE IMAGEN EN MODAL CREAR
    // ─────────────────────────────────────────────────────────────
    function mostrarImagen(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('preview-imagen');
        const nombreLabel = document.getElementById('nombre-imagen');

        if (file) {
            nombreLabel.textContent = file.name;
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'inline-block';
            };
            reader.readAsDataURL(file);
        } else {
            nombreLabel.textContent = 'Ningún archivo seleccionado';
            preview.style.display = 'none';
            preview.src = '';
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  TOGGLE DE VISTA: TARJETAS ↔ TABLA
    // ─────────────────────────────────────────────────────────────
    const VISTA_KEY = 'sgc_categorias_vista';
    const elCards = document.getElementById('vista-cards');
    const elTabla = document.getElementById('vista-tabla');
    const btnCards = document.getElementById('btn-vista-cards');
    const btnTabla = document.getElementById('btn-vista-tabla');

    const ESTILO_ACTIVO = {
        background: '#fff',
        color: '#1a3da8'
    };
    const ESTILO_INACTIVO = {
        background: 'transparent',
        color: '#fff'
    };

    function cambiarBotonActivo(vista) {
        if (vista === 'cards') {
            Object.assign(btnCards.style, ESTILO_ACTIVO);
            Object.assign(btnTabla.style, ESTILO_INACTIVO);
        } else {
            Object.assign(btnTabla.style, ESTILO_ACTIVO);
            Object.assign(btnCards.style, ESTILO_INACTIVO);
        }
    }

    function mostrarVistaCards() {
        elCards.style.display = 'block';
        elTabla.style.display = 'none';
        cambiarBotonActivo('cards');
        localStorage.setItem(VISTA_KEY, 'cards');
    }

    function mostrarVistaTabla() {
        elTabla.style.display = 'block';
        elCards.style.display = 'none';
        cambiarBotonActivo('tabla');
        localStorage.setItem(VISTA_KEY, 'tabla');
    }

    // Restaurar última vista al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        const vistaGuardada = localStorage.getItem(VISTA_KEY) ?? 'cards';
        vistaGuardada === 'tabla' ? mostrarVistaTabla() : mostrarVistaCards();
    });
</script>

@endsection