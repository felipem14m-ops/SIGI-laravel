@extends('layouts.sidebarEmpleado')

@section('title', 'Punto de Venta')
@section('page-title', 'Punto de Venta')

@section('content')

{{-- SweetAlert2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- ===== HEADER BANNER (ESTÍLO DE LA IMAGEN) ===== --}}
<div style="background:linear-gradient(125deg,#1a3da8 0%,#2350d4 45%,#3b6ef8 100%); border-radius:20px; padding:28px 36px; margin-bottom:24px; position:relative; overflow:hidden;">
    <div style="position:absolute; top:-40px; right:100px; width:220px; height:220px; background:rgba(255,255,255,0.05); border-radius:50%;"></div>
    <div style="position:absolute; top:10px; right:0; width:120px; height:120px; background:rgba(255,255,255,0.04); border-radius:50%;"></div>

    <div style="display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; position:relative; z-index:1;">
        <div style="display:flex; align-items:center; gap:20px;">
            <div style="width:54px; height:54px; border-radius:16px; background:rgba(255,255,255,0.18); border:1px solid rgba(255,255,255,0.25); display:flex; align-items:center; justify-content:center; color:#fff; flex-shrink:0;">
                <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <div style="display:inline-block; background:rgba(255,255,255,0.18); border-radius:20px; padding:3px 12px; font-size:10px; font-weight:800; color:#fff; letter-spacing:0.08em; text-transform:uppercase; margin-bottom:6px;">OPERACIÓN COMERCIAL</div>
                <h2 style="font-size:1.8rem; font-weight:900; color:#fff; margin:0 0 4px; letter-spacing:-0.02em;">Punto de Venta</h2>
                <p style="font-size:0.85rem; color:rgba(255,255,255,0.85); margin:0;">Atención al cliente y registro de ventas.</p>
            </div>
        </div>

        <div>
            <div style="font-size:10px; font-weight:800; color:rgba(255,255,255,0.75); text-transform:uppercase; letter-spacing:0.08em; text-align:right; margin-bottom:2px;">MIS VENTAS HOY</div>
            <div id="ingresos-hoy" style="font-size:2rem; font-weight:900; color:#fff; text-align:right; letter-spacing:-0.02em;">${{ number_format($ingresosHoy ?? 0, 0, ',', '.') }}</div>
        </div>
    </div>
</div>

{{-- ===== LAYOUT PRINCIPAL DEL POS ===== --}}
<div style="display:grid; grid-template-columns: 1fr 380px; gap:24px; align-items:start;">

    {{-- ===== PANEL IZQUIERDO: CATÁLOGO DE PRODUCTOS ===== --}}
    <div style="background:#fff; border-radius:20px; border:1px solid #e8ecf4; padding:24px; box-shadow:0 1px 6px rgba(0,0,0,0.04);">
        
        {{-- FILTROS DE BÚSQUEDA Y CATEGORÍAS --}}
        <div style="display:flex; gap:16px; margin-bottom:20px;">
            <div style="flex:1; position:relative;">
                <svg style="position:absolute; left:14px; top:50%; transform:translateY(-50%); pointer-events:none;" width="16" height="16" fill="none" stroke="#94a3b8" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" id="pos-search" placeholder="Buscar por nombre o código..."
                    style="width:100%; padding:11px 14px 11px 42px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; font-family:inherit;"
                    onkeyup="filtrarProductosPOS()"
                    onfocus="this.style.borderColor='#2563eb'; this.style.background='#fff'; this.style.boxShadow='0 0 0 3px rgba(37,99,235,0.1)'"
                    onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'" />
            </div>

            <div style="width:220px;">
                <select id="pos-category" onchange="filtrarProductosPOS()"
                    style="width:100%; padding:11px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; cursor:pointer; font-family:inherit; appearance:none; -webkit-appearance:none; background-image:url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2216%22 height=%2216%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%2364748b%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22><polyline points=%226 9 12 15 18 9%22/></svg>'); background-repeat:no-repeat; background-position:right 14px center; padding-right:38px;"
                    onfocus="this.style.borderColor='#2563eb'; this.style.boxShadow='0 0 0 3px rgba(37,99,235,0.1)'"
                    onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                    <option value="">Todas las Categorías</option>
                    @foreach($categorias ?? [] as $cat)
                        <option value="{{ $cat->id_categoria }}">{{ $cat->nombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- TABLA DE PRODUCTOS EN POS --}}
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; text-align:left;">
                <thead>
                    <tr style="background:#f8fafc; border-bottom:1.5px solid #eef2f7;">
                        <th style="padding:12px 16px; font-size:11px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase; width:60px;">IMG</th>
                        <th style="padding:12px 16px; font-size:11px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase;">PRODUCTO</th>
                        <th style="padding:12px 16px; font-size:11px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase; text-align:center;">STOCK</th>
                        <th style="padding:12px 16px; font-size:11px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase; text-align:right;">PRECIO</th>
                        <th style="padding:12px 16px; font-size:11px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase; text-align:right;">ACCIÓN</th>
                    </tr>
                </thead>
                <tbody id="pos-products-body">
                    @forelse($productos ?? [] as $prod)
                    <tr class="pos-prod-row" 
                        data-name="{{ strtolower($prod->nombre) }}" 
                        data-code="{{ strtolower($prod->codigoUnico) }}" 
                        data-cat="{{ $prod->id_categoria }}"
                        style="border-bottom:1px solid #f1f5f9;" 
                        onmouseover="this.style.background='#fafbfd'" 
                        onmouseout="this.style.background='transparent'">

                        {{-- Imagen --}}
                        <td style="padding:14px 16px;">
                            @if($prod->imagen)
                            <img src="{{ asset('storage/' . $prod->imagen) }}" alt="{{ $prod->nombre }}"
                                style="width:42px; height:42px; object-fit:contain; border-radius:10px; background:#fafafa; border:1px solid #e2e8f0; padding:2px;">
                            @else
                            <div style="width:42px; height:42px; border-radius:10px; background:linear-gradient(135deg,#eff6ff,#dbeafe); color:#2563eb; display:flex; align-items:center; justify-content:center; font-size:16px; font-weight:900;">
                                {{ mb_strtoupper(mb_substr($prod->nombre, 0, 1)) }}
                            </div>
                            @endif
                        </td>

                        {{-- Producto --}}
                        <td style="padding:14px 16px;">
                            <div style="font-weight:900; color:#0f172a; font-size:0.92rem;">{{ $prod->nombre }}</div>
                            <div style="font-size:11px; color:#64748b; font-weight:600;">{{ $prod->categoria->nombre ?? 'Sin categoría' }}</div>
                        </td>

                        {{-- Stock --}}
                        <td style="padding:14px 16px; text-align:center;">
                            <span style="display:inline-block; background:#eff6ff; color:#2563eb; font-weight:800; font-size:11px; padding:4px 12px; border-radius:8px;">
                                {{ $prod->stock_actual }} unidades
                            </span>
                        </td>

                        {{-- Precio --}}
                        <td style="padding:14px 16px; text-align:right; font-weight:900; color:#0f172a; font-size:0.95rem;">
                            ${{ number_format($prod->precio_venta, 0, ',', '.') }}
                        </td>

                        {{-- Acción --}}
                        <td style="padding:14px 16px; text-align:right;">
                            <button type="button" 
                                onclick="agregarAlCarrito({{ $prod->id_producto }}, '{{ addslashes($prod->nombre) }}', {{ $prod->precio_venta }}, {{ $prod->stock_actual }})"
                                style="background:#2563eb; color:#fff; border:none; padding:8px 18px; border-radius:10px; font-size:12px; font-weight:800; cursor:pointer; display:inline-flex; align-items:center; gap:6px; box-shadow:0 3px 10px rgba(37,99,235,0.25); transition:all .2s;"
                                onmouseover="this.style.background='#1d4ed8'; this.style.transform='translateY(-1px)'" 
                                onmouseout="this.style.background='#2563eb'; this.style.transform=''">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                                Añadir
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding:48px 16px; text-align:center;">
                            <div style="display:flex; flex-direction:column; align-items:center; gap:8px; color:#94a3b8;">
                                <svg width="36" height="36" fill="none" stroke="#cbd5e1" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                <span style="font-size:0.875rem; font-weight:600;">No hay productos disponibles para la venta.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ===== PANEL DERECHO: CARRITO DE COMPRAS ===== --}}
    <div style="background:#fff; border-radius:20px; border:1px solid #e8ecf4; overflow:hidden; box-shadow:0 1px 6px rgba(0,0,0,0.04); display:flex; flex-direction:column;">
        
        {{-- HEADER DEL CARRITO --}}
        <div style="background:linear-gradient(125deg,#1a3da8 0%,#2563eb 100%); padding:18px 22px; display:flex; align-items:center; justify-content:space-between; color:#fff;">
            <div style="display:flex; align-items:center; gap:10px; font-weight:900; font-size:1.05rem;">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                </svg>
                Carrito
            </div>
            <button type="button" onclick="limpiarCarrito()"
                style="background:transparent; border:none; color:rgba(255,255,255,0.85); font-size:12px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:4px;"
                onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.85)'">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Limpiar
            </button>
        </div>

        {{-- LISTADO DE ÍTEMS EN EL CARRITO --}}
        <div id="cart-container" style="padding:24px 20px; min-height:260px; max-height:360px; overflow-y:auto; display:flex; flex-direction:column; justify-content:center; align-items:center;">
            
            {{-- Estado Vacío --}}
            <div id="cart-empty-state" style="text-align:center; color:#94a3b8;">
                <div style="width:70px; height:70px; margin:0 auto 12px; border-radius:50%; background:#f8fafc; border:1px solid #e2e8f0; display:flex; align-items:center; justify-content:center; color:#cbd5e1;">
                    <svg width="34" height="34" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <p style="font-size:0.875rem; font-weight:600; margin:0;">Selecciona productos</p>
            </div>

            {{-- Elementos Agregados Dinámicamente --}}
            <div id="cart-items-list" style="width:100%; display:none; flex-direction:column; gap:12px;"></div>

        </div>

        {{-- PIE DEL CARRITO / PAGO --}}
        <div style="padding:20px 22px; border-top:1.5px solid #f1f5f9; background:#fafbfd;">
            
            {{-- Método de Pago --}}
            <div style="margin-bottom:18px;">
                <label style="display:block; font-size:10px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:8px;">MÉTODO DE PAGO</label>
                <select id="metodo_pago"
                    style="width:100%; padding:11px 14px; background:#fff; border:1.5px solid #e2e8f0; border-radius:12px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; cursor:pointer; font-family:inherit; appearance:none; -webkit-appearance:none; background-image:url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2216%22 height=%2216%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%2364748b%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22><polyline points=%226 9 12 15 18 9%22/></svg>'); background-repeat:no-repeat; background-position:right 14px center; padding-right:38px;">
                    @forelse($metodos ?? [] as $metodo)
                        <option value="{{ $metodo->id_metodo }}">{{ $metodo->nombre }}</option>
                    @empty
                        <option value="1">Efectivo</option>
                    @endforelse
                </select>
            </div>

            {{-- Total --}}
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
                <span style="font-size:11px; font-weight:900; color:#64748b; text-transform:uppercase; letter-spacing:0.08em;">TOTAL A COBRAR:</span>
                <span id="cart-total-text" style="font-size:1.8rem; font-weight:900; color:#2563eb; letter-spacing:-0.02em;">$0.00</span>
            </div>

            {{-- Botón Finalizar --}}
            <button type="button" id="btn-finalizar-venta" onclick="finalizarVenta()"
                style="width:100%; padding:14px; background:#2563eb; color:#fff; border:none; border-radius:14px; font-size:0.95rem; font-weight:900; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; box-shadow:0 4px 16px rgba(37,99,235,0.3); transition:all .2s;"
                onmouseover="this.style.background='#1d4ed8'; this.style.transform='translateY(-1px)'" 
                onmouseout="this.style.background='#2563eb'; this.style.transform=''">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                FINALIZAR VENTA (F10)
            </button>
        </div>

    </div>

</div>

{{-- SCRIPT INTERACTIVO DEL PUNTO DE VENTA --}}
<script>
    let carrito = [];

    function filtrarProductosPOS() {
        const searchVal = document.getElementById('pos-search').value.toLowerCase();
        const catVal    = document.getElementById('pos-category').value;
        const rows      = document.querySelectorAll('.pos-prod-row');

        rows.forEach(row => {
            const name = row.getAttribute('data-name');
            const code = row.getAttribute('data-code');
            const cat  = row.getAttribute('data-cat');

            const matchSearch = name.includes(searchVal) || code.includes(searchVal);
            const matchCat    = catVal === "" || cat === catVal;

            row.style.display = (matchSearch && matchCat) ? "" : "none";
        });
    }

    function agregarAlCarrito(id, nombre, precio, stockMax) {
        const index = carrito.findIndex(item => item.id === id);
        if (index > -1) {
            if (carrito[index].cantidad + 1 > stockMax) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Stock Límite',
                    text: `No hay más stock disponible de ${nombre}`,
                    confirmColor: '#2563eb'
                });
                return;
            }
            carrito[index].cantidad++;
        } else {
            carrito.push({
                id: id,
                nombre: nombre,
                precio: parseFloat(precio),
                cantidad: 1,
                stockMax: stockMax
            });
        }
        renderizarCarrito();
    }

    function cambiarCantidad(index, delta) {
        const nuevaCant = carrito[index].cantidad + delta;
        if (nuevaCant <= 0) {
            carrito.splice(index, 1);
        } else if (nuevaCant > carrito[index].stockMax) {
            Swal.fire({
                icon: 'warning',
                title: 'Stock Límite',
                text: 'No puedes agregar más unidades que el stock disponible',
                confirmColor: '#2563eb'
            });
        } else {
            carrito[index].cantidad = nuevaCant;
        }
        renderizarCarrito();
    }

    function eliminarDelCarrito(index) {
        carrito.splice(index, 1);
        renderizarCarrito();
    }

    function limpiarCarrito() {
        carrito = [];
        renderizarCarrito();
    }

    function renderizarCarrito() {
        const emptyState = document.getElementById('cart-empty-state');
        const itemsList  = document.getElementById('cart-items-list');
        const container  = document.getElementById('cart-container');
        const totalSpan  = document.getElementById('cart-total-text');

        if (carrito.length === 0) {
            emptyState.style.display = 'block';
            itemsList.style.display  = 'none';
            container.style.justifyContent = 'center';
            totalSpan.textContent = '$0.00';
            return;
        }

        emptyState.style.display = 'none';
        itemsList.style.display  = 'flex';
        container.style.justifyContent = 'flex-start';

        let total = 0;
        let html = '';

        carrito.forEach((item, index) => {
            const subtotal = item.precio * item.cantidad;
            total += subtotal;

            html += `
                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:12px 14px; display:flex; align-items:center; justify-content:space-between; gap:10px;">
                    <div style="flex:1; min-width:0;">
                        <div style="font-size:0.875rem; font-weight:800; color:#0f172a; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">${item.nombre}</div>
                        <div style="font-size:11px; font-weight:700; color:#2563eb; margin-top:2px;">$${item.precio.toLocaleString('es-CO')}</div>
                    </div>
                    <div style="display:flex; align-items:center; gap:6px; background:#fff; border:1px solid #cbd5e1; border-radius:8px; padding:2px 6px;">
                        <button type="button" onclick="cambiarCantidad(${index}, -1)" style="border:none; background:transparent; font-weight:900; color:#64748b; cursor:pointer; font-size:14px;">-</button>
                        <span style="font-size:12px; font-weight:900; color:#0f172a; min-width:18px; text-align:center;">${item.cantidad}</span>
                        <button type="button" onclick="cambiarCantidad(${index}, 1)" style="border:none; background:transparent; font-weight:900; color:#64748b; cursor:pointer; font-size:14px;">+</button>
                    </div>
                    <button type="button" onclick="eliminarDelCarrito(${index})" style="border:none; background:transparent; color:#ef4444; cursor:pointer; padding:4px;" title="Eliminar">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            `;
        });

        itemsList.innerHTML = html;
        totalSpan.textContent = '$' + total.toLocaleString('es-CO', { minimumFractionDigits: 2 });
    }

    function finalizarVenta() {
        if (carrito.length === 0) {
            Swal.fire({
                icon: 'info',
                title: 'Carrito vacío',
                text: 'Agrega al menos un producto para procesar la venta.',
                confirmButtonColor: '#2563eb'
            });
            return;
        }

        const selectEl  = document.getElementById('metodo_pago');
        const metodoPagoId   = parseInt(selectEl.value);
        const totalNum  = carrito.reduce((acc, i) => acc + (i.precio * i.cantidad), 0);
        const totalFormatted = totalNum.toLocaleString('es-CO', {minimumFractionDigits: 0, maximumFractionDigits: 0});

        if (!metodoPagoId) {
            Swal.fire({ icon: 'warning', title: 'Selecciona un método de pago', confirmButtonColor: '#2563eb' });
            return;
        }

        // Modal SweetAlert con cálculo de Monto Recibido y Cambio a Entregar
        Swal.fire({
            title: '<span style="font-weight:900; color:#334155; font-size:1.8rem;">Monto Recibido</span>',
            icon: 'info',
            iconColor: '#38bdf8',
            html: `
                <div style="background:#f8fafc; border-radius:18px; padding:20px; text-align:center; margin:15px 0 20px 0;">
                    <div style="font-size:11px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">TOTAL A COBRAR</div>
                    <div style="font-size:2.3rem; font-weight:900; color:#2563eb; letter-spacing:-0.02em;">$${totalFormatted}</div>
                </div>

                <div style="text-align:left; margin-bottom:10px;">
                    <label style="display:block; font-size:0.875rem; font-weight:700; color:#475569; margin-bottom:8px;">Ingrese el monto recibido:</label>
                    <input id="swal-monto-recibido" type="number" step="any" min="0" placeholder="0" value="${totalNum}"
                        style="width:100%; padding:14px; border:2.5px solid #3b82f6; border-radius:14px; font-size:1.6rem; font-weight:900; text-align:center; color:#1e293b; outline:none; box-sizing:border-box;"
                        onfocus="this.select()">
                </div>

                <div id="swal-cambio-box" style="background:#f0fdf4; border:1.5px solid #bbf7d0; border-radius:12px; padding:12px 16px; text-align:center; margin-top:14px; display:flex; align-items:center; justify-content:space-between;">
                    <span style="font-size:12px; font-weight:800; color:#16a34a; text-transform:uppercase; letter-spacing:0.06em;">Cambio a entregar:</span>
                    <span id="swal-cambio-valor" style="font-size:1.3rem; font-weight:900; color:#15803d;">$0</span>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Registrar Venta',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#64748b',
            didOpen: () => {
                const input = document.getElementById('swal-monto-recibido');
                const cambioBox = document.getElementById('swal-cambio-box');
                const cambioValor = document.getElementById('swal-cambio-valor');

                function actualizarCambio() {
                    const rec = parseFloat(input.value) || 0;
                    const cambio = rec - totalNum;
                    if (cambio >= 0) {
                        cambioBox.style.background = '#f0fdf4';
                        cambioBox.style.borderColor = '#bbf7d0';
                        cambioValor.style.color = '#15803d';
                        cambioValor.textContent = '$' + cambio.toLocaleString('es-CO', {minimumFractionDigits: 0, maximumFractionDigits: 0});
                    } else {
                        cambioBox.style.background = '#fff1f2';
                        cambioBox.style.borderColor = '#fecdd3';
                        cambioValor.style.color = '#e11d48';
                        cambioValor.textContent = 'Monto insuficiente (-$' + Math.abs(cambio).toLocaleString('es-CO', {minimumFractionDigits: 0, maximumFractionDigits: 0}) + ')';
                    }
                }

                input.addEventListener('input', actualizarCambio);
                actualizarCambio();
                input.focus();
                input.select();
            },
            preConfirm: () => {
                const input = document.getElementById('swal-monto-recibido');
                const rec = parseFloat(input.value);
                if (isNaN(rec) || rec < totalNum) {
                    Swal.showValidationMessage(`El monto recibido debe ser mayor o igual al total a cobrar ($${totalFormatted})`);
                    return false;
                }
                return {
                    montoRecibido: rec,
                    cambio: rec - totalNum
                };
            }
        }).then(async result => {
            if (!result.isConfirmed) return;

            const { montoRecibido, cambio } = result.value;

            const payload = {
                items: carrito.map(i => ({ id: i.id, cantidad: i.cantidad })),
                metodo_pago_id: metodoPagoId,
                total: parseFloat(totalNum.toFixed(2)),
                monto_recibido: parseFloat(montoRecibido),
                cambio: parseFloat(cambio.toFixed(2))
            };

            try {
                const res = await fetch("{{ route('ventas.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify(payload)
                });

                const data = await res.json();

                if (res.ok && data.success) {
                    limpiarCarrito();
                    const cambioFmt = cambio.toLocaleString('es-CO', {minimumFractionDigits: 0, maximumFractionDigits: 0});

                    Swal.fire({
                        icon: 'success',
                        title: '¡Venta Registrada Exitosamente!',
                        html: `
                            <div style="margin-top:10px;">
                                <p style="font-size:1rem; color:#475569; margin-bottom:6px;">Transacción completada con éxito.</p>
                                <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:12px; padding:12px; text-align:center; margin-top:10px;">
                                    <div style="font-size:11px; font-weight:800; color:#16a34a; text-transform:uppercase;">CAMBIO A DEVOLVER</div>
                                    <div style="font-size:1.8rem; font-weight:900; color:#15803d;">$${cambioFmt}</div>
                                </div>
                            </div>
                        `,
                        confirmButtonText: 'Aceptar',
                        confirmButtonColor: '#2563eb'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al procesar la venta',
                        text: data.message || 'No se pudo registrar la venta en la base de datos.',
                        confirmButtonColor: '#2563eb'
                    });
                }
            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Red',
                    text: 'Ocurrió un error al procesar la solicitud.',
                    confirmButtonColor: '#2563eb'
                });
            }
        });
    }

    // Tecla rápida F10 para finalizar venta
    document.addEventListener('keydown', function(e) {
        if (e.key === 'F10') {
            e.preventDefault();
            finalizarVenta();
        }
    });
</script>

@endsection
