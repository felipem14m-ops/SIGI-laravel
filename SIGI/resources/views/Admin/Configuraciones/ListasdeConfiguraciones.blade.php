@extends('layouts.sidebarAdmin')

@section('title', 'Configuración General')
@section('page-title', 'Configuración')

@section('content')

{{-- SweetAlert2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- ===== HEADER BANNER ===== --}}
<div style="background:linear-gradient(125deg,#1a3da8 0%,#2350d4 45%,#3b6ef8 100%); border-radius:20px; padding:30px 36px; margin-bottom:24px; position:relative; overflow:hidden;">
    <div style="position:absolute; top:-40px; right:90px; width:220px; height:220px; background:rgba(255,255,255,0.04); border-radius:50%;"></div>
    <div style="position:absolute; bottom:-30px; right:-10px; width:140px; height:140px; background:rgba(255,255,255,0.05); border-radius:50%;"></div>
    <div style="position:relative; z-index:1;">
        <div style="display:inline-block; background:rgba(255,255,255,0.18); border-radius:20px; padding:4px 14px; font-size:11px; font-weight:800; color:#fff; letter-spacing:0.08em; text-transform:uppercase; margin-bottom:10px;">AJUSTES DEL SISTEMA</div>
        <h2 style="font-size:1.85rem; font-weight:900; color:#fff; margin:0 0 6px; letter-spacing:-0.02em;">Configuración General</h2>
        <p style="font-size:0.88rem; color:rgba(255,255,255,0.8); margin:0;">Personaliza el comportamiento y los métodos de pago de SIGI.</p>
    </div>
</div>

{{-- ===== ALERTA DE ÉXITO (SweetAlert auto-fire) ===== --}}
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: '¡Listo!',
            text: "{{ session('success') }}",
            confirmButtonColor: '#2563eb',
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
        });
    });
</script>
@endif

@if($errors->any())
<div style="background:#fef2f2; border:1px solid #fca5a5; border-radius:14px; padding:14px 20px; margin-bottom:20px;">
    <div style="font-size:0.88rem; font-weight:700; color:#991b1b; margin-bottom:6px;">Corrige los errores antes de continuar:</div>
    <ul style="margin:0; padding-left:18px;">
        @foreach($errors->all() as $error)
        <li style="font-size:0.83rem; color:#b91c1c; margin-bottom:2px;">{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

{{-- ===== LAYOUT PRINCIPAL ===== --}}
<div style="display:grid; grid-template-columns:220px 1fr; gap:20px; align-items:start;">

    {{-- ── Menú lateral ── --}}
    <div style="background:#fff; border-radius:18px; border:1px solid #e8ecf4; padding:16px; box-shadow:0 2px 8px rgba(0,0,0,0.04);">
        <p style="font-size:10px; font-weight:800; color:#94a3b8; text-transform:uppercase; letter-spacing:0.1em; padding:0 6px; margin:0 0 10px;">AJUSTES DE SISTEMA</p>

        <button id="tab-metodos-btn" onclick="showTab('metodos')"
            style="width:100%; display:flex; align-items:center; gap:10px; padding:11px 12px; border-radius:12px; border:none; cursor:pointer; font-size:13px; font-weight:700; transition:all .15s; margin-bottom:4px; background:#eff6ff; color:#2563eb;">
            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
            </svg>
            Métodos de Pago
        </button>

        <button id="tab-seguridad-btn" onclick="showTab('seguridad')"
            style="width:100%; display:flex; align-items:center; gap:10px; padding:11px 12px; border-radius:12px; border:none; cursor:pointer; font-size:13px; font-weight:700; transition:all .15s; background:transparent; color:#64748b;">
            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
            Seguridad
        </button>
    </div>

    {{-- ── Panel derecho ── --}}
    <div>

        {{-- ===================== TAB: Métodos de Pago ===================== --}}
        <div id="tab-metodos" style="background:#fff; border-radius:18px; border:1px solid #e8ecf4; box-shadow:0 2px 8px rgba(0,0,0,0.04); overflow:hidden;">

            {{-- Header --}}
            <div style="display:flex; align-items:center; justify-content:space-between; padding:22px 26px 18px; border-bottom:1px solid #f1f5f9;">
                <div>
                    <div style="font-size:1rem; font-weight:900; color:#0f172a; margin-bottom:2px;">Métodos de Pago</div>
                    <div style="font-size:12px; color:#94a3b8; font-weight:500;">Gestiona las formas en que tus clientes pueden pagar.</div>
                </div>
                <button onclick="abrirModalAgregar()"
                    style="display:inline-flex; align-items:center; gap:8px; background:#2563eb; color:#fff; border:none; padding:10px 18px; border-radius:12px; font-size:13px; font-weight:800; cursor:pointer; transition:background .15s;"
                    onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563eb'">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Agregar
                </button>
            </div>

            {{-- Tabla de métodos --}}
            <div style="padding:8px 0 0;">
                @forelse($metodos as $metodo)
                <div style="display:flex; align-items:center; justify-content:space-between; padding:14px 26px; border-bottom:1px solid #f8fafc; transition:background .12s;" onmouseover="this.style.background='#fafbff'" onmouseout="this.style.background='transparent'">

                    {{-- Ícono + info --}}
                    <div style="display:flex; align-items:center; gap:14px;">
                        <div style="width:42px; height:42px; border-radius:13px; flex-shrink:0; display:flex; align-items:center; justify-content:center; background:{{ $metodo->activo ? '#eff6ff' : '#f8fafc' }};">
                            <svg width="20" height="20" fill="none" stroke="{{ $metodo->activo ? '#2563eb' : '#94a3b8' }}" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <div>
                            <div style="font-size:14px; font-weight:800; color:{{ $metodo->activo ? '#0f172a' : '#94a3b8' }};">
                                {{ $metodo->nombre }}
                            </div>
                            <div style="display:inline-block; margin-top:3px; padding:2px 10px; border-radius:20px; font-size:10.5px; font-weight:800; background:{{ $metodo->activo ? '#dcfce7' : '#fef3c7' }}; color:{{ $metodo->activo ? '#15803d' : '#b45309' }};">
                                {{ $metodo->activo ? 'Activo' : 'Inactivo' }}
                            </div>
                        </div>
                    </div>

                    {{-- Acciones (Botones de icono compactos y refinados) --}}
                    <div style="display:flex; align-items:center; gap:6px;">

                        {{-- Editar --}}
                        <button type="button"
                            onclick="abrirModalEditar({{ $metodo->id_metodo }}, '{{ addslashes($metodo->nombre) }}')"
                            title="Editar Método"
                            style="width:32px; height:32px; border-radius:10px; border:1px solid #dbeafe; background:#eff6ff; color:#2563eb; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all .15s;"
                            onmouseover="this.style.background='#dbeafe'; this.style.transform='scale(1.05)';" onmouseout="this.style.background='#eff6ff'; this.style.transform='scale(1)';">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>

                        {{-- Toggle Activar / Desactivar --}}
                        <form id="form-toggle-{{ $metodo->id_metodo }}" method="POST"
                            action="{{ route('configuraciones.update', $metodo->id_metodo) }}" style="display:none;">
                            @csrf @method('PUT')
                            <input type="hidden" name="toggle_activo" value="1">
                        </form>
                        <button type="button"
                            onclick="confirmarToggle({{ $metodo->id_metodo }}, '{{ addslashes($metodo->nombre) }}', {{ $metodo->activo ? 'true' : 'false' }})"
                            title="{{ $metodo->activo ? 'Desactivar' : 'Activar' }}"
                            style="width:32px; height:32px; border-radius:10px; border:1px solid {{ $metodo->activo ? '#fef08a' : '#bbf7d0' }}; background:{{ $metodo->activo ? '#fefce8' : '#f0fdf4' }}; color:{{ $metodo->activo ? '#d97706' : '#16a34a' }}; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all .15s;"
                            onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                            @if($metodo->activo)
                            {{-- Ícono Desactivar (prohibido/ban) --}}
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                            @else
                            {{-- Ícono Activar (check circle) --}}
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            @endif
                        </button>

                        {{-- Eliminar --}}
                        <form id="form-destroy-{{ $metodo->id_metodo }}" method="POST"
                            action="{{ route('configuraciones.destroy', $metodo->id_metodo) }}" style="display:none;">
                            @csrf @method('DELETE')
                        </form>
                        <button type="button"
                            onclick="confirmarEliminar({{ $metodo->id_metodo }}, '{{ addslashes($metodo->nombre) }}')"
                            title="Eliminar Método"
                            style="width:32px; height:32px; border-radius:10px; border:1px solid #fecdd3; background:#fff1f2; color:#e11d48; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all .15s;"
                            onmouseover="this.style.background='#ffe4e6'; this.style.transform='scale(1.05)';" onmouseout="this.style.background='#fff1f2'; this.style.transform='scale(1)';">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>

                    </div>
                </div>
                @empty
                <div style="padding:50px 20px; text-align:center; color:#94a3b8;">
                    <svg width="44" height="44" fill="none" stroke="#cbd5e1" stroke-width="1.4" viewBox="0 0 24 24" style="margin:0 auto 12px; display:block;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    <p style="font-size:13px; font-weight:700; margin:0 0 6px;">No hay métodos de pago</p>
                    <p style="font-size:12px; font-weight:500; margin:0;">Haz clic en <strong>Agregar</strong> para crear el primero.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- ===================== TAB: Seguridad ===================== --}}
        <div id="tab-seguridad" style="display:none; background:#fff; border-radius:18px; border:1px solid #e8ecf4; box-shadow:0 2px 8px rgba(0,0,0,0.04);">
            <div style="padding:22px 26px 18px; border-bottom:1px solid #f1f5f9;">
                <div style="font-size:1rem; font-weight:900; color:#0f172a; margin-bottom:2px;">Seguridad de la Cuenta</div>
                <div style="font-size:12px; color:#94a3b8; font-weight:500;">Actualiza tus credenciales de acceso al sistema.</div>
            </div>
            <div style="padding:26px;">
                <div style="display:flex; align-items:flex-start; gap:12px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:14px; padding:16px 18px; margin-bottom:22px;">
                    <div style="width:32px; height:32px; border-radius:10px; background:#2563eb; color:#fff; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:1px;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div style="font-size:13px; font-weight:600; color:#1e40af; line-height:1.6;">
                        Para cambiar la contraseña general de administrador, ve al módulo de&nbsp;
                        <a href="{{ route('usuarios.index') }}" style="font-weight:900; color:#2563eb; text-decoration:underline;">Usuarios</a>
                        &nbsp;y edita tu perfil.
                    </div>
                </div>
                <a href="{{ route('usuarios.index') }}"
                    style="display:inline-flex; align-items:center; gap:8px; background:#f1f5f9; color:#334155; border:1px solid #e2e8f0; padding:11px 20px; border-radius:12px; font-size:13px; font-weight:800; text-decoration:none; transition:background .15s;"
                    onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Ir a Gestión de Usuarios
                </a>
            </div>
        </div>

    </div>{{-- /panel derecho --}}
</div>{{-- /grid --}}


{{-- ========================= MODAL: AGREGAR ========================= --}}
<div id="modal-agregar" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.55); backdrop-filter:blur(5px); z-index:1000; align-items:center; justify-content:center; padding:20px;">
    <div style="background:#fff; border-radius:22px; padding:32px 30px; width:100%; max-width:430px; box-shadow:0 30px 80px rgba(0,0,0,0.22); position:relative; animation:slideUp .22s ease;">
        <button onclick="cerrarModal('modal-agregar')"
            style="position:absolute; top:15px; right:15px; width:32px; height:32px; border-radius:50%; border:1px solid #e2e8f0; background:#fff; cursor:pointer; font-size:18px; color:#64748b; display:flex; align-items:center; justify-content:center; line-height:1;">
            &times;
        </button>

        <div style="width:48px; height:48px; border-radius:14px; background:#eff6ff; display:flex; align-items:center; justify-content:center; margin-bottom:14px;">
            <svg width="22" height="22" fill="none" stroke="#2563eb" stroke-width="2.2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
            </svg>
        </div>
        <div style="font-size:1.1rem; font-weight:900; color:#0f172a; margin-bottom:4px;">Nuevo Método de Pago</div>
        <div style="font-size:12px; color:#94a3b8; font-weight:500; margin-bottom:22px;">Ingresa el nombre del método (Ej: Efectivo, Nequi, Tarjeta).</div>

        <form method="POST" action="{{ route('configuraciones.store') }}">
            @csrf
            <label style="display:block; font-size:11px; font-weight:800; color:#475569; letter-spacing:.06em; text-transform:uppercase; margin-bottom:6px;">Nombre del método</label>
            <input type="text" name="nombre" id="input-nuevo-nombre" required maxlength="50"
                placeholder="Ej: Nequi"
                style="width:100%; padding:12px 14px; border:1.5px solid #e2e8f0; border-radius:12px; font-size:14px; font-weight:600; color:#0f172a; box-sizing:border-box; outline:none; transition:border .2s; margin-bottom:20px;"
                onfocus="this.style.borderColor='#2563eb'; this.style.boxShadow='0 0 0 3px rgba(37,99,235,.1)'"
                onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
            <div style="display:flex; gap:10px;">
                <button type="button" onclick="cerrarModal('modal-agregar')"
                    style="flex:1; padding:12px; border-radius:12px; border:1.5px solid #e2e8f0; background:#fff; color:#64748b; font-size:13px; font-weight:700; cursor:pointer;"
                    onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">Cancelar</button>
                <button type="submit"
                    style="flex:1; padding:12px; border-radius:12px; border:none; background:#2563eb; color:#fff; font-size:13px; font-weight:800; cursor:pointer;"
                    onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563eb'">Guardar</button>
            </div>
        </form>
    </div>
</div>

{{-- ========================= MODAL: EDITAR ========================= --}}
<div id="modal-editar" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.55); backdrop-filter:blur(5px); z-index:1000; align-items:center; justify-content:center; padding:20px;">
    <div style="background:#fff; border-radius:22px; padding:32px 30px; width:100%; max-width:430px; box-shadow:0 30px 80px rgba(0,0,0,0.22); position:relative; animation:slideUp .22s ease;">
        <button onclick="cerrarModal('modal-editar')"
            style="position:absolute; top:15px; right:15px; width:32px; height:32px; border-radius:50%; border:1px solid #e2e8f0; background:#fff; cursor:pointer; font-size:18px; color:#64748b; display:flex; align-items:center; justify-content:center; line-height:1;">
            &times;
        </button>

        <div style="width:48px; height:48px; border-radius:14px; background:#eff6ff; display:flex; align-items:center; justify-content:center; margin-bottom:14px;">
            <svg width="22" height="22" fill="none" stroke="#2563eb" stroke-width="2.2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
        </div>
        <div style="font-size:1.1rem; font-weight:900; color:#0f172a; margin-bottom:4px;">Editar Método de Pago</div>
        <div style="font-size:12px; color:#94a3b8; font-weight:500; margin-bottom:22px;">Modifica el nombre del método seleccionado.</div>

        <form method="POST" id="form-editar" action="">
            @csrf @method('PUT')
            <label style="display:block; font-size:11px; font-weight:800; color:#475569; letter-spacing:.06em; text-transform:uppercase; margin-bottom:6px;">Nombre del método</label>
            <input type="text" name="nombre" id="input-editar-nombre" required maxlength="50"
                style="width:100%; padding:12px 14px; border:1.5px solid #e2e8f0; border-radius:12px; font-size:14px; font-weight:600; color:#0f172a; box-sizing:border-box; outline:none; transition:border .2s; margin-bottom:20px;"
                onfocus="this.style.borderColor='#2563ebff'; this.style.boxShadow='0 0 0 3px rgba(37,99,235,.1)'"
                onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
            <div style="display:flex; gap:10px;">
                <button type="button" onclick="cerrarModal('modal-editar')"
                    style="flex:1; padding:12px; border-radius:12px; border:1.5px solid #e2e8f0; background:#fff; color:#64748b; font-size:13px; font-weight:700; cursor:pointer;"
                    onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">Cancelar</button>
                <button type="submit"
                    style="flex:1; padding:12px; border-radius:12px; border:none; background:#d97706; color:#fff; font-size:13px; font-weight:800; cursor:pointer;"
                    onmouseover="this.style.background='#2563ebff'" onmouseout="this.style.background='#2563ebff'">Actualizar</button>
            </div>
        </form>
    </div>
</div>

{{-- ========================= ANIMACIÓN ========================= --}}
<style>
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(24px) scale(.97);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
</style>

{{-- ========================= SCRIPTS ========================= --}}
<script>
    /* ─── Tabs ─── */
    function showTab(tab) {
        ['metodos', 'seguridad'].forEach(function(t) {
            var panel = document.getElementById('tab-' + t);
            var btn = document.getElementById('tab-' + t + '-btn');
            if (t === tab) {
                panel.style.display = 'block';
                btn.style.background = '#eff6ff';
                btn.style.color = '#2563eb';
            } else {
                panel.style.display = 'none';
                btn.style.background = 'transparent';
                btn.style.color = '#64748b';
            }
        });
    }

    /* ─── Modales ─── */
    function abrirModal(id) {
        document.getElementById(id).style.display = 'flex';
    }

    function cerrarModal(id) {
        document.getElementById(id).style.display = 'none';
    }

    function abrirModalAgregar() {
        document.getElementById('input-nuevo-nombre').value = '';
        abrirModal('modal-agregar');
        setTimeout(function() {
            document.getElementById('input-nuevo-nombre').focus();
        }, 100);
    }

    function abrirModalEditar(id, nombre) {
        document.getElementById('form-editar').action = '/configuraciones/metodos/' + id;
        document.getElementById('input-editar-nombre').value = nombre;
        abrirModal('modal-editar');
        setTimeout(function() {
            document.getElementById('input-editar-nombre').focus();
        }, 100);
    }

    /* ─── SweetAlert: Toggle Activar/Desactivar ─── */
    function confirmarToggle(id, nombre, estaActivo) {
        var accion = estaActivo ? 'desactivar' : 'activar';
        var icon = estaActivo ? 'warning' : 'question';
        var btnColor = estaActivo ? '#f59e0b' : '#10b981';
        var btnText = estaActivo ? 'Sí, desactivar' : 'Sí, activar';

        Swal.fire({
            icon: icon,
            title: '¿' + (estaActivo ? 'Desactivar' : 'Activar') + ' método?',
            html: 'El método <strong>' + nombre + '</strong> será ' + accion + 'do. Podrás revertirlo cuando quieras.',
            showCancelButton: true,
            confirmButtonText: btnText,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: btnColor,
            cancelButtonColor: '#94a3b8',
            borderRadius: '14px',
            customClass: {
                popup: 'swal-rounded'
            },
        }).then(function(result) {
            if (result.isConfirmed) {
                document.getElementById('form-toggle-' + id).submit();
            }
        });
    }

    /* ─── SweetAlert: Eliminar ─── */
    function confirmarEliminar(id, nombre) {
        Swal.fire({
            icon: 'error',
            title: '¿Eliminar método?',
            html: 'Estás a punto de eliminar <strong>' + nombre + '</strong>.<br><span style="font-size:13px;color:#64748b;">Esta acción no se puede deshacer.</span>',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            focusCancel: true,
        }).then(function(result) {
            if (result.isConfirmed) {
                document.getElementById('form-destroy-' + id).submit();
            }
        });
    }

    /* ─── Cerrar modales al hacer clic fuera ─── */
    ['modal-agregar', 'modal-editar'].forEach(function(id) {
        document.getElementById(id).addEventListener('click', function(e) {
            if (e.target === this) cerrarModal(id);
        });
    });

    /* ─── Leer tab desde URL ─── */
    document.addEventListener('DOMContentLoaded', function() {
        var params = new URLSearchParams(window.location.search);
        showTab(params.get('tab') || 'metodos');
    });
</script>

@endsection