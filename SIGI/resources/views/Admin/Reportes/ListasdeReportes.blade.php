@extends('layouts.sidebarAdmin')

@section('title', 'Reportes y Análisis')
@section('page-title', 'Reportes')

@section('content')

{{-- ===== HEADER BANNER ===== --}}
<div style="background:linear-gradient(125deg,#1a3da8 0%,#2350d4 45%,#3b6ef8 100%); border-radius:20px; padding:32px 36px; margin-bottom:28px; position:relative; overflow:hidden;">
    <div style="position:absolute; top:-50px; right:80px; width:260px; height:260px; background:rgba(255,255,255,0.04); border-radius:50%;"></div>
    <div style="position:absolute; bottom:-30px; right:-20px; width:160px; height:160px; background:rgba(255,255,255,0.05); border-radius:50%;"></div>
    <div style="position:relative; z-index:1;">
        <div style="display:inline-block; background:rgba(255,255,255,0.18); border-radius:20px; padding:4px 14px; font-size:11px; font-weight:800; color:#fff; letter-spacing:0.08em; text-transform:uppercase; margin-bottom:12px;">CENTRO DE INTELIGENCIA</div>
        <h2 style="font-size:2rem; font-weight:900; color:#fff; margin:0 0 8px; letter-spacing:-0.02em;">Reportes y Análisis</h2>
        <p style="font-size:0.9rem; color:rgba(255,255,255,0.8); margin:0; max-width:560px;">
            Genera y exporta informes detallados de ventas, inventario, movimientos y rentabilidad del negocio.
        </p>
    </div>
</div>

{{-- ===== GRID DE TIPOS DE REPORTES ===== --}}
<div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:20px; margin-bottom:28px;">

    {{-- ── Reporte 1: Ventas ── --}}
    <div style="background:#fff; border-radius:20px; border:1px solid #e8ecf4; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.05); transition:transform .15s, box-shadow .15s;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 24px rgba(37,99,235,0.12)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.05)'">
        {{-- Franja de color --}}
        <div style="height:5px; background:linear-gradient(90deg,#2563eb,#3b82f6);"></div>
        <div style="padding:24px 26px;">
            <div style="display:flex; align-items:flex-start; gap:14px; margin-bottom:16px;">
                <div style="width:48px; height:48px; border-radius:14px; background:#eff6ff; color:#2563eb; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <div style="font-size:1rem; font-weight:900; color:#0f172a; margin-bottom:4px;">Reporte de Ventas</div>
                    <div style="font-size:12px; color:#64748b; font-weight:500; line-height:1.5;">Resumen de transacciones por período, cajero y método de pago.</div>
                </div>
            </div>
            <div style="display:flex; flex-wrap:wrap; gap:6px; margin-bottom:18px;">
                <span style="background:#eff6ff; color:#2563eb; font-size:10px; font-weight:800; padding:3px 10px; border-radius:20px; letter-spacing:0.04em;">Por Fecha</span>
                <span style="background:#eff6ff; color:#2563eb; font-size:10px; font-weight:800; padding:3px 10px; border-radius:20px;">Por Cajero</span>
                <span style="background:#eff6ff; color:#2563eb; font-size:10px; font-weight:800; padding:3px 10px; border-radius:20px;">Por Método Pago</span>
            </div>
            <a href="{{ route('ventas.historial') }}"
                style="display:inline-flex; align-items:center; gap:8px; background:#2563eb; color:#fff; padding:10px 20px; border-radius:12px; font-size:13px; font-weight:800; text-decoration:none; width:100%; justify-content:center;"
                onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563eb'">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Ver Historial de Ventas
            </a>
        </div>
    </div>

    {{-- ── Reporte 2: Inventario ── --}}
    <div style="background:#fff; border-radius:20px; border:1px solid #e8ecf4; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.05); transition:transform .15s, box-shadow .15s;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 24px rgba(16,163,127,0.12)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.05)'">
        <div style="height:5px; background:linear-gradient(90deg,#10b981,#34d399);"></div>
        <div style="padding:24px 26px;">
            <div style="display:flex; align-items:flex-start; gap:14px; margin-bottom:16px;">
                <div style="width:48px; height:48px; border-radius:14px; background:#ecfdf5; color:#10b981; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div>
                    <div style="font-size:1rem; font-weight:900; color:#0f172a; margin-bottom:4px;">Reporte de Inventario</div>
                    <div style="font-size:12px; color:#64748b; font-weight:500; line-height:1.5;">Estado de stock, valorización de productos y análisis de rentabilidad.</div>
                </div>
            </div>
            <div style="display:flex; flex-wrap:wrap; gap:6px; margin-bottom:18px;">
                <span style="background:#ecfdf5; color:#10b981; font-size:10px; font-weight:800; padding:3px 10px; border-radius:20px;">Stock Actual</span>
                <span style="background:#ecfdf5; color:#10b981; font-size:10px; font-weight:800; padding:3px 10px; border-radius:20px;">Valorización</span>
                <span style="background:#ecfdf5; color:#10b981; font-size:10px; font-weight:800; padding:3px 10px; border-radius:20px;">Rentabilidad</span>
            </div>
            <a href="{{ route('movimientos.index') }}"
                style="display:inline-flex; align-items:center; gap:8px; background:#10b981; color:#fff; padding:10px 20px; border-radius:12px; font-size:13px; font-weight:800; text-decoration:none; width:100%; justify-content:center;"
                onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Ver Movimientos de Stock
            </a>
        </div>
    </div>

    {{-- ── Reporte 3: Alertas y Riesgo ── --}}
    <div style="background:#fff; border-radius:20px; border:1px solid #e8ecf4; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.05); transition:transform .15s, box-shadow .15s;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 24px rgba(245,158,11,0.12)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.05)'">
        <div style="height:5px; background:linear-gradient(90deg,#f59e0b,#fcd34d);"></div>
        <div style="padding:24px 26px;">
            <div style="display:flex; align-items:flex-start; gap:14px; margin-bottom:16px;">
                <div style="width:48px; height:48px; border-radius:14px; background:#fffbeb; color:#d97706; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <div style="font-size:1rem; font-weight:900; color:#0f172a; margin-bottom:4px;">Alertas de Riesgo</div>
                    <div style="font-size:12px; color:#64748b; font-weight:500; line-height:1.5;">Productos agotados, bajo mínimo y próximos a vencer.</div>
                </div>
            </div>
            <div style="display:flex; flex-wrap:wrap; gap:6px; margin-bottom:18px;">
                <span style="background:#fffbeb; color:#d97706; font-size:10px; font-weight:800; padding:3px 10px; border-radius:20px;">Agotados</span>
                <span style="background:#fffbeb; color:#d97706; font-size:10px; font-weight:800; padding:3px 10px; border-radius:20px;">Bajo Mínimo</span>
                <span style="background:#fffbeb; color:#d97706; font-size:10px; font-weight:800; padding:3px 10px; border-radius:20px;">Por Vencer</span>
            </div>
            <a href="{{ route('alertas.index') }}"
                style="display:inline-flex; align-items:center; gap:8px; background:#f59e0b; color:#fff; padding:10px 20px; border-radius:12px; font-size:13px; font-weight:800; text-decoration:none; width:100%; justify-content:center;"
                onmouseover="this.style.background='#d97706'" onmouseout="this.style.background='#f59e0b'">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Ver Alertas de Stock
            </a>
        </div>
    </div>

    {{-- ── Reporte 4: Proveedores ── --}}
    <div style="background:#fff; border-radius:20px; border:1px solid #e8ecf4; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.05); transition:transform .15s, box-shadow .15s;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 24px rgba(139,92,246,0.12)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.05)'">
        <div style="height:5px; background:linear-gradient(90deg,#8b5cf6,#a78bfa);"></div>
        <div style="padding:24px 26px;">
            <div style="display:flex; align-items:flex-start; gap:14px; margin-bottom:16px;">
                <div style="width:48px; height:48px; border-radius:14px; background:#f5f3ff; color:#8b5cf6; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <div style="font-size:1rem; font-weight:900; color:#0f172a; margin-bottom:4px;">Reporte de Proveedores</div>
                    <div style="font-size:12px; color:#64748b; font-weight:500; line-height:1.5;">Listado y estado de proveedores activos, categorías y productos asociados.</div>
                </div>
            </div>
            <div style="display:flex; flex-wrap:wrap; gap:6px; margin-bottom:18px;">
                <span style="background:#f5f3ff; color:#8b5cf6; font-size:10px; font-weight:800; padding:3px 10px; border-radius:20px;">Activos</span>
                <span style="background:#f5f3ff; color:#8b5cf6; font-size:10px; font-weight:800; padding:3px 10px; border-radius:20px;">Por Categoría</span>
                <span style="background:#f5f3ff; color:#8b5cf6; font-size:10px; font-weight:800; padding:3px 10px; border-radius:20px;">Contactos</span>
            </div>
            <a href="{{ route('proveedores.index') }}"
                style="display:inline-flex; align-items:center; gap:8px; background:#8b5cf6; color:#fff; padding:10px 20px; border-radius:12px; font-size:13px; font-weight:800; text-decoration:none; width:100%; justify-content:center;"
                onmouseover="this.style.background='#7c3aed'" onmouseout="this.style.background='#8b5cf6'">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Ver Proveedores
            </a>
        </div>
    </div>

    {{-- ── Reporte 5: Productos ── --}}
    <div style="background:#fff; border-radius:20px; border:1px solid #e8ecf4; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.05); transition:transform .15s, box-shadow .15s;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 24px rgba(236,72,153,0.12)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.05)'">
        <div style="height:5px; background:linear-gradient(90deg,#ec4899,#f472b6);"></div>
        <div style="padding:24px 26px;">
            <div style="display:flex; align-items:flex-start; gap:14px; margin-bottom:16px;">
                <div style="width:48px; height:48px; border-radius:14px; background:#fdf2f8; color:#ec4899; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                </div>
                <div>
                    <div style="font-size:1rem; font-weight:900; color:#0f172a; margin-bottom:4px;">Catálogo de Productos</div>
                    <div style="font-size:12px; color:#64748b; font-weight:500; line-height:1.5;">Listado completo de productos por categoría, estado, precio y vencimiento.</div>
                </div>
            </div>
            <div style="display:flex; flex-wrap:wrap; gap:6px; margin-bottom:18px;">
                <span style="background:#fdf2f8; color:#ec4899; font-size:10px; font-weight:800; padding:3px 10px; border-radius:20px;">Por Categoría</span>
                <span style="background:#fdf2f8; color:#ec4899; font-size:10px; font-weight:800; padding:3px 10px; border-radius:20px;">Con Vencimiento</span>
                <span style="background:#fdf2f8; color:#ec4899; font-size:10px; font-weight:800; padding:3px 10px; border-radius:20px;">Activos / Inactivos</span>
            </div>
            <a href="{{ route('productos.index') }}"
                style="display:inline-flex; align-items:center; gap:8px; background:#ec4899; color:#fff; padding:10px 20px; border-radius:12px; font-size:13px; font-weight:800; text-decoration:none; width:100%; justify-content:center;"
                onmouseover="this.style.background='#db2777'" onmouseout="this.style.background='#ec4899'">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Ver Catálogo
            </a>
        </div>
    </div>

    {{-- ── Reporte 6: Usuarios ── --}}
    <div style="background:#fff; border-radius:20px; border:1px solid #e8ecf4; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.05); transition:transform .15s, box-shadow .15s;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 24px rgba(14,165,233,0.12)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.05)'">
        <div style="height:5px; background:linear-gradient(90deg,#0ea5e9,#38bdf8);"></div>
        <div style="padding:24px 26px;">
            <div style="display:flex; align-items:flex-start; gap:14px; margin-bottom:16px;">
                <div style="width:48px; height:48px; border-radius:14px; background:#f0f9ff; color:#0ea5e9; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <div style="font-size:1rem; font-weight:900; color:#0f172a; margin-bottom:4px;">Reporte de Usuarios</div>
                    <div style="font-size:12px; color:#64748b; font-weight:500; line-height:1.5;">Accesos, roles y actividad de cajeros y administradores del sistema.</div>
                </div>
            </div>
            <div style="display:flex; flex-wrap:wrap; gap:6px; margin-bottom:18px;">
                <span style="background:#f0f9ff; color:#0ea5e9; font-size:10px; font-weight:800; padding:3px 10px; border-radius:20px;">Por Rol</span>
                <span style="background:#f0f9ff; color:#0ea5e9; font-size:10px; font-weight:800; padding:3px 10px; border-radius:20px;">Activos</span>
                <span style="background:#f0f9ff; color:#0ea5e9; font-size:10px; font-weight:800; padding:3px 10px; border-radius:20px;">Administradores</span>
            </div>
            <a href="{{ route('usuarios.index') }}"
                style="display:inline-flex; align-items:center; gap:8px; background:#0ea5e9; color:#fff; padding:10px 20px; border-radius:12px; font-size:13px; font-weight:800; text-decoration:none; width:100%; justify-content:center;"
                onmouseover="this.style.background='#0284c7'" onmouseout="this.style.background='#0ea5e9'">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Ver Usuarios
            </a>
        </div>
    </div>

</div>

{{-- ===== NOTA INFORMATIVA ===== --}}
<div style="background:#fff; border-radius:18px; border:1px solid #e8ecf4; padding:22px 26px; box-shadow:0 1px 4px rgba(0,0,0,0.03); display:flex; align-items:flex-start; gap:14px;">
    <div style="width:38px; height:38px; border-radius:12px; background:#eff6ff; color:#2563eb; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:2px;">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </div>
    <div>
        <div style="font-size:0.9rem; font-weight:800; color:#1e293b; margin-bottom:4px;">Exportación próximamente</div>
        <div style="font-size:0.82rem; color:#64748b; font-weight:500; line-height:1.6;">
            En las próximas versiones del sistema SIGI se habilitará la exportación directa en PDF y Excel para cada tipo de reporte, con filtros avanzados por rango de fechas, usuarios y categorías.
        </div>
    </div>
</div>

@endsection
