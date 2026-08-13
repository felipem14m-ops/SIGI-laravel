@extends('layouts.sidebarEmpleado')

@section('title', 'Consultar Productos')
@section('page-title', 'Consultar Productos')

@section('content')

{{-- ===== HEADER BANNER ===== --}}
<div style="background:linear-gradient(125deg,#1a3da8 0%,#2350d4 45%,#3b6ef8 100%); border-radius:20px; padding:28px 36px; margin-bottom:24px; position:relative; overflow:hidden;">
    <div style="position:absolute; top:-40px; right:100px; width:220px; height:220px; background:rgba(255,255,255,0.05); border-radius:50%;"></div>
    <div style="position:absolute; top:10px; right:0; width:120px; height:120px; background:rgba(255,255,255,0.04); border-radius:50%;"></div>

    <div style="display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; position:relative; z-index:1;">
        <div>
            <div style="display:inline-block; background:rgba(255,255,255,0.18); border-radius:20px; padding:3px 12px; font-size:10px; font-weight:800; color:#fff; letter-spacing:0.08em; text-transform:uppercase; margin-bottom:6px;">
                CATÁLOGO DIGITAL
            </div>
            <h2 style="font-size:1.8rem; font-weight:900; color:#fff; margin:0 0 4px; letter-spacing:-0.02em;">
                Consultar Productos
            </h2>
            <p style="font-size:0.85rem; color:rgba(255,255,255,0.85); margin:0;">
                Explora el inventario disponible y verifica existencias en tiempo real.
            </p>
        </div>

        <div style="display:flex; align-items:center; gap:12px; flex-shrink:0;">
            {{-- Badge Contador --}}
            <div style="display:flex; align-items:center; gap:8px; background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.25); color:#fff; padding:8px 16px; border-radius:12px; font-size:12px; font-weight:800;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <div style="display:flex; flex-direction:column; line-height:1.1;">
                    <span style="font-size:14px; font-weight:900;">{{ count($productos ?? []) }}</span>
                    <span style="font-size:9px; opacity:0.8; letter-spacing:0.05em; text-transform:uppercase;">Productos</span>
                </div>
            </div>

            {{-- Botones Toggle Vista (Tarjetas / DataTable) --}}
            <div style="display:flex; background:rgba(255,255,255,0.2); padding:3px; border-radius:10px;">
                <button id="btn-vista-cards" type="button" onclick="mostrarVistaCards()"
                    style="background:#fff; color:#1a3da8; border:none; padding:8px; border-radius:7px; display:flex; align-items:center; justify-content:center; cursor:pointer; transition:all .2s;"
                    title="Vista tarjetas">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                </button>
                <button id="btn-vista-tabla" type="button" onclick="mostrarVistaTabla()"
                    style="background:transparent; color:#fff; border:none; padding:8px; border-radius:7px; display:flex; align-items:center; justify-content:center; cursor:pointer; transition:all .2s;"
                    title="Vista tabla datatable">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <a href="{{ route('empleado.ventas.index') }}"
                style="display:inline-flex; align-items:center; gap:8px; background:#fff; color:#1a3da8; padding:10px 20px; border-radius:12px; font-size:13px; font-weight:800; text-decoration:none; box-shadow:0 4px 16px rgba(0,0,0,0.15);"
                onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform=''">
                <svg width="16" height="16" fill="none" stroke="#1a3da8" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 000-4z" />
                </svg>
                Ir a POS
            </a>
        </div>
    </div>
</div>

{{-- ===== BARRA DE BÚSQUEDA FLOTANTE ===== --}}
<div style="background:#fff; border-radius:16px; border:1px solid #e8ecf4; padding:16px 20px; margin-bottom:24px; box-shadow:0 1px 6px rgba(0,0,0,0.04);">
    <div style="position:relative;">
        <svg style="position:absolute; left:14px; top:50%; transform:translateY(-50%); pointer-events:none;" width="16" height="16" fill="none" stroke="#94a3b8" stroke-width="2.2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <input type="text" id="pos-search" placeholder="Buscar por nombre o código único de producto..."
            style="width:100%; padding:11px 14px 11px 42px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; font-family:inherit;"
            onkeyup="filtrarCatalogo()"
            onfocus="this.style.borderColor='#2563eb'; this.style.background='#fff'; this.style.boxShadow='0 0 0 3px rgba(37,99,235,0.1)'"
            onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'" />
    </div>
</div>

{{-- ===== VISTA: TARJETAS (CARDS) ===== --}}
<div id="vista-cards">
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:24px;">
        @forelse($productos ?? [] as $prod)
        <div class="prod-item-row"
            data-name="{{ strtolower($prod->nombre) }}"
            data-code="{{ strtolower($prod->codigoUnico) }}"
            style="background:#fff; border-radius:20px; border:1px solid #e8ecf4; padding:20px; box-shadow:0 1px 6px rgba(0,0,0,0.03); display:flex; flex-direction:column; justify-content:space-between; transition:all 0.2s;"
            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 24px rgba(0,0,0,0.06)';"
            onmouseout="this.style.transform=''; this.style.boxShadow='0 1px 6px rgba(0,0,0,0.03)';">

            {{-- Imagen --}}
            <div style="width:100%; height:160px; border-radius:14px; background:#f8fafc; display:flex; align-items:center; justify-content:center; overflow:hidden; margin-bottom:16px;">
                @if($prod->imagen)
                <img src="{{ asset('storage/' . $prod->imagen) }}" alt="{{ $prod->nombre }}" style="max-width:100%; max-height:100%; object-fit:contain;">
                @else
                <div style="width:70px; height:70px; border-radius:16px; background:linear-gradient(135deg,#eff6ff,#dbeafe); color:#2563eb; display:flex; align-items:center; justify-content:center; font-size:26px; font-weight:900;">
                    {{ mb_strtoupper(mb_substr($prod->nombre, 0, 1)) }}
                </div>
                @endif
            </div>

            {{-- Info Básica --}}
            <div>
                <span style="font-size:10px; font-weight:800; color:#94a3b8; letter-spacing:0.06em; display:block; margin-bottom:4px;">
                    #{{ str_pad($prod->id_producto, 4, '0', STR_PAD_LEFT) }} &bull; {{ $prod->codigoUnico }}
                </span>
                <h3 style="font-size:1.05rem; font-weight:900; color:#0f172a; margin:0 0 8px; line-height:1.3;">
                    {{ $prod->nombre }}
                </h3>

                {{-- Badges de Categoría y Stock --}}
                <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap; margin-bottom:16px;">
                    <span style="background:#eff6ff; color:#2563eb; font-size:10px; font-weight:800; padding:4px 10px; border-radius:8px; text-transform:uppercase;">
                        &bull; {{ mb_strtoupper($prod->categoria->nombre ?? 'SIN CATEGORÍA') }}
                    </span>

                    @if($prod->stock_actual <= 0)
                        <span style="background:#fef2f2; color:#ef4444; font-size:10px; font-weight:800; padding:4px 10px; border-radius:8px; text-transform:uppercase;">
                            &bull; AGOTADO
                        </span>
                    @elseif($prod->stock_actual <= ($prod->stock_minimo ?? 5))
                        <span style="background:#fffbeb; color:#d97706; font-size:10px; font-weight:800; padding:4px 10px; border-radius:8px; text-transform:uppercase;">
                            &bull; {{ $prod->stock_actual }} UND (BAJO)
                        </span>
                    @else
                        <span style="background:#f0fdf4; color:#16a34a; font-size:10px; font-weight:800; padding:4px 10px; border-radius:8px; text-transform:uppercase;">
                            &bull; {{ $prod->stock_actual }} UND
                        </span>
                    @endif
                </div>
            </div>

            {{-- Precio --}}
            <div style="display:flex; align-items:center; justify-content:space-between; background:#f8fafc; padding:12px 16px; border-radius:12px; border:1px solid #f1f5f9;">
                <span style="font-size:10px; font-weight:800; color:#64748b; uppercase tracking-widest;">PRECIO VENTA</span>
                <span style="font-size:1.3rem; font-weight:900; color:#0f172a;">${{ number_format($prod->precio_venta, 0, ',', '.') }}</span>
            </div>
        </div>
        @empty
        <div style="grid-column: 1 / -1; padding:60px 20px; text-align:center; background:#fff; border-radius:20px; border:1px solid #e8ecf4;">
            <div style="display:flex; flex-direction:column; align-items:center; gap:10px; color:#94a3b8;">
                <svg width="48" height="48" fill="none" stroke="#cbd5e1" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <p style="font-size:1rem; font-weight:800; color:#64748b; margin:0;">No hay productos registrados en el sistema.</p>
            </div>
        </div>
        @endforelse
    </div>
</div>

{{-- ===== VISTA: DATATABLE (TABLA) ===== --}}
<div id="vista-tabla" style="display:none;">
    <div style="background:#fff; border-radius:20px; border:1px solid #e8ecf4; overflow:hidden; box-shadow:0 1px 6px rgba(0,0,0,0.04);">
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; text-align:left;">
                <thead>
                    <tr style="background:#f8fafc; border-bottom:1.5px solid #eef2f7;">
                        <th style="padding:14px 20px; font-size:11px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase; width:60px;">IMG</th>
                        <th style="padding:14px 20px; font-size:11px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase;">CÓDIGO</th>
                        <th style="padding:14px 20px; font-size:11px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase;">PRODUCTO</th>
                        <th style="padding:14px 20px; font-size:11px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase;">CATEGORÍA</th>
                        <th style="padding:14px 20px; font-size:11px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase; text-align:center;">STOCK</th>
                        <th style="padding:14px 20px; font-size:11px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase; text-align:right;">PRECIO VENTA</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productos ?? [] as $prod)
                    <tr class="prod-item-row"
                        data-name="{{ strtolower($prod->nombre) }}"
                        data-code="{{ strtolower($prod->codigoUnico) }}"
                        style="border-bottom:1px solid #f1f5f9;"
                        onmouseover="this.style.background='#fafbfd'"
                        onmouseout="this.style.background='transparent'">

                        {{-- Imagen --}}
                        <td style="padding:14px 20px;">
                            @if($prod->imagen)
                            <img src="{{ asset('storage/' . $prod->imagen) }}" alt="{{ $prod->nombre }}"
                                style="width:42px; height:42px; object-fit:contain; border-radius:10px; background:#fafafa; border:1px solid #e2e8f0; padding:2px;">
                            @else
                            <div style="width:42px; height:42px; border-radius:10px; background:linear-gradient(135deg,#eff6ff,#dbeafe); color:#2563eb; display:flex; align-items:center; justify-content:center; font-size:16px; font-weight:900;">
                                {{ mb_strtoupper(mb_substr($prod->nombre, 0, 1)) }}
                            </div>
                            @endif
                        </td>

                        {{-- Código --}}
                        <td style="padding:14px 20px; font-size:0.875rem; font-weight:800; color:#2563eb;">
                            {{ $prod->codigoUnico }}
                        </td>

                        {{-- Nombre --}}
                        <td style="padding:14px 20px;">
                            <div style="font-weight:900; color:#0f172a; font-size:0.92rem;">{{ $prod->nombre }}</div>
                            @if($prod->descripcion)
                            <div style="font-size:11px; color:#64748b; font-weight:500;">{{ Str::limit($prod->descripcion, 45) }}</div>
                            @endif
                        </td>

                        {{-- Categoría --}}
                        <td style="padding:14px 20px;">
                            <span style="display:inline-block; background:#eff6ff; color:#2563eb; font-weight:800; font-size:11px; padding:4px 12px; border-radius:8px;">
                                {{ $prod->categoria->nombre ?? 'Sin Categoría' }}
                            </span>
                        </td>

                        {{-- Stock --}}
                        <td style="padding:14px 20px; text-align:center;">
                            @if($prod->stock_actual <= 0)
                                <span style="display:inline-block; background:#fef2f2; color:#ef4444; font-weight:800; font-size:11px; padding:4px 12px; border-radius:8px;">
                                    Agotado
                                </span>
                            @else
                                <span style="display:inline-block; background:#f0fdf4; color:#16a34a; font-weight:800; font-size:11px; padding:4px 12px; border-radius:8px;">
                                    {{ $prod->stock_actual }} unidades
                                </span>
                            @endif
                        </td>

                        {{-- Precio --}}
                        <td style="padding:14px 20px; text-align:right; font-weight:900; color:#0f172a; font-size:0.95rem;">
                            ${{ number_format($prod->precio_venta, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding:48px 16px; text-align:center; color:#94a3b8;">
                            No hay productos registrados para mostrar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- SCRIPT PARA INTERCAMBIAR VISTAS Y FILTRAR --}}
<script>
    function mostrarVistaCards() {
        document.getElementById('vista-cards').style.display = 'block';
        document.getElementById('vista-tabla').style.display = 'none';

        document.getElementById('btn-vista-cards').style.background = '#fff';
        document.getElementById('btn-vista-cards').style.color = '#1a3da8';

        document.getElementById('btn-vista-tabla').style.background = 'transparent';
        document.getElementById('btn-vista-tabla').style.color = '#fff';
    }

    function mostrarVistaTabla() {
        document.getElementById('vista-cards').style.display = 'none';
        document.getElementById('vista-tabla').style.display = 'block';

        document.getElementById('btn-vista-tabla').style.background = '#fff';
        document.getElementById('btn-vista-tabla').style.color = '#1a3da8';

        document.getElementById('btn-vista-cards').style.background = 'transparent';
        document.getElementById('btn-vista-cards').style.color = '#fff';
    }

    function filtrarCatalogo() {
        const searchVal = document.getElementById('pos-search').value.toLowerCase();
        const items     = document.querySelectorAll('.prod-item-row');

        items.forEach(item => {
            const name = item.getAttribute('data-name');
            const code = item.getAttribute('data-code');

            if (name.includes(searchVal) || code.includes(searchVal)) {
                item.style.display = "";
            } else {
                item.style.display = "none";
            }
        });
    }
</script>

@endsection
