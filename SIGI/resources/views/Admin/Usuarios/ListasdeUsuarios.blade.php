@extends('layouts.sidebarAdmin')

@section('title', 'Administración de Usuarios')
@section('page-title', 'Administración de Usuarios')

@section('content')

{{-- SweetAlert2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Flash Messages --}}
@if(session('success'))
<div style="margin-bottom:20px; padding:14px 18px; background:#f0fdf4; border:1px solid #bbf7d0; color:#15803d; border-radius:14px; display:flex; align-items:center; gap:10px; font-size:0.875rem; font-weight:600;">
    <svg width="18" height="18" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div style="margin-bottom:20px; padding:14px 18px; background:#fff1f2; border:1px solid #fecdd3; color:#be123c; border-radius:14px; font-size:0.875rem;">
    <div style="display:flex; align-items:center; gap:8px; font-weight:700; margin-bottom:6px;">
        <svg width="18" height="18" fill="none" stroke="#e11d48" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        Corrige los siguientes errores:
    </div>
    <ul style="list-style:disc; padding-left:24px; margin:0; line-height:1.6;">
        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
    </ul>
</div>
@endif

{{-- ===== HEADER BANNER ===== --}}
<div style="background:linear-gradient(125deg,#1a3da8 0%,#2350d4 45%,#3b6ef8 100%); border-radius:20px; padding:32px 36px; margin-bottom:24px; position:relative; overflow:hidden;">
    <div style="position:absolute; top:-40px; right:100px; width:220px; height:220px; background:rgba(255,255,255,0.05); border-radius:50%;"></div>
    <div style="position:absolute; top:10px; right:0; width:120px; height:120px; background:rgba(255,255,255,0.04); border-radius:50%;"></div>

    <div style="display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; position:relative; z-index:1;">
        <div style="display:flex; align-items:center; gap:16px;">
            <div style="background:rgba(255,255,255,0.12); padding:12px; border-radius:16px; display:flex; align-items:center; justify-content:center;">
                <svg width="28" height="28" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div>
                <div style="font-size:11px; font-weight:700; color:rgba(255,255,255,0.65); letter-spacing:0.1em; text-transform:uppercase; margin-bottom:4px;">ADMINISTRACIÓN</div>
                <h2 style="font-size:1.9rem; font-weight:900; color:#fff; margin:0 0 4px; letter-spacing:-0.02em;">Gestión de Usuarios</h2>
                <p style="font-size:0.875rem; color:rgba(255,255,255,0.75); margin:0;">Controla los accesos y permisos del sistema</p>
            </div>
        </div>
        <div style="display:flex; align-items:center; gap:12px; flex-shrink:0;">
            <div style="display:flex; align-items:center; gap:8px; background:rgba(255,255,255,0.12); border:1px solid rgba(255,255,255,0.2); color:#fff; padding:10px 18px; border-radius:30px; font-size:13px; font-weight:700;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                {{ $usuarios->count() }} registrados
            </div>
            <button onclick="openModal('create-modal')"
                style="display:flex; align-items:center; gap:8px; background:#fff; color:#1a3da8; padding:10px 20px; border-radius:30px; font-size:13px; font-weight:800; border:none; cursor:pointer; box-shadow:0 4px 16px rgba(0,0,0,0.15);"
                onmouseover="this.style.transform='translateY(-1px)'"
                onmouseout="this.style.transform=''">
                <svg width="16" height="16" fill="none" stroke="#1a3da8" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                Nuevo Usuario
            </button>
        </div>
    </div>
</div>

{{-- ===== FILTER PANEL ===== --}}
<div style="background:#fff; border-radius:16px; border:1px solid #e8ecf4; padding:20px 24px; margin-bottom:20px; box-shadow:0 1px 6px rgba(0,0,0,0.04);">
    <form method="GET" action="{{ route('usuarios.index') }}">
        <div style="display:grid; grid-template-columns:1fr 260px auto; gap:16px; align-items:end;">

            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:8px;">Usuario / Correo</label>
                <div style="position:relative;">
                    <svg style="position:absolute; left:14px; top:50%; transform:translateY(-50%); pointer-events:none;" width="16" height="16" fill="none" stroke="#94a3b8" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ej. Juan Pérez..."
                        style="width:100%; padding:10px 14px 10px 42px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:10px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box;"
                        onfocus="this.style.borderColor='#3b82f6'; this.style.background='#fff'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'"
                        onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'" />
                </div>
            </div>

            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:8px;">Rol de Sistema</label>
                <select name="role_id"
                    style="width:100%; padding:10px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:10px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; cursor:pointer;"
                    onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'"
                    onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                    <option value="">Todos los roles</option>
                    @foreach($roles as $role)
                    <option value="{{ $role->id_rol }}" {{ request('role_id') == $role->id_rol ? 'selected' : '' }}>{{ $role->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div style="display:flex; gap:10px; align-items:center;">
                <button type="submit"
                    style="display:inline-flex; align-items:center; gap:8px; background:#1e3fa8; color:#fff; padding:10px 22px; border-radius:10px; font-size:0.875rem; font-weight:700; border:none; cursor:pointer; white-space:nowrap; box-shadow:0 2px 8px rgba(30,63,168,0.2);"
                    onmouseover="this.style.background='#1730a0'"
                    onmouseout="this.style.background='#1e3fa8'">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Aplicar Filtros
                </button>
                <a href="{{ route('usuarios.index') }}"
                    style="display:inline-flex; align-items:center; gap:7px; background:#f1f5f9; color:#475569; padding:10px 18px; border-radius:10px; font-size:0.875rem; font-weight:700; text-decoration:none; white-space:nowrap;"
                    onmouseover="this.style.background='#e2e8f0'"
                    onmouseout="this.style.background='#f1f5f9'">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 15.07M9 5h-.582m0 0l-3.5 3.5M8.418 5L12 8.5" />
                    </svg>
                    Limpiar
                </a>
            </div>

        </div>
    </form>
</div>

{{-- ===== USERS TABLE ===== --}}
<div style="background:#fff; border-radius:16px; border:1px solid #e8ecf4; overflow:hidden; box-shadow:0 1px 6px rgba(0,0,0,0.04);">
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; text-align:left;">
            <thead>
                <tr style="background:#f8fafc; border-bottom:1.5px solid #eef2f7;">
                    <th style="padding:14px 20px; font-size:11px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase;">Usuario</th>
                    <th style="padding:14px 20px; font-size:11px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase;">Correo Electrónico</th>
                    <th style="padding:14px 20px; font-size:11px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase;">Identificación</th>
                    <th style="padding:14px 20px; font-size:11px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase;">Rol</th>
                    <th style="padding:14px 20px; font-size:11px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase;">Último Acceso</th>
                    <th style="padding:14px 20px; font-size:11px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase;">Estado</th>
                    <th style="padding:14px 20px; font-size:11px; font-weight:700; color:#64748b; letter-spacing:0.07em; text-transform:uppercase; text-align:right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $usuario)
                <tr style="border-bottom:1px solid #f1f5f9;" onmouseover="this.style.background='#fafbfd'" onmouseout="this.style.background='transparent'">

                    {{-- Avatar + Name --}}
                    <td style="padding:16px 20px;">
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div style="width:38px; height:38px; border-radius:50%; background:{{ $usuario->id_rol == 1 ? '#2563eb' : '#334155' }}; color:#fff; display:flex; align-items:center; justify-content:center; font-size:15px; font-weight:800; flex-shrink:0;">
                                {{ strtoupper(substr($usuario->nombre, 0, 1)) }}
                            </div>
                            <span style="font-weight:700; color:#1e293b; font-size:0.875rem;">{{ $usuario->nombre }}</span>
                        </div>
                    </td>

                    {{-- Email --}}
                    <td style="padding:16px 20px; font-size:0.875rem; color:#2563eb; font-weight:500;">{{ $usuario->email }}</td>

                    {{-- ID --}}
                    <td style="padding:16px 20px; font-size:0.875rem; color:#475569; font-weight:500;">{{ $usuario->numeroIdentificacion }}</td>

                    {{-- Role Badge --}}
                    <td style="padding:16px 20px;">
                        @if($usuario->id_rol == 1)
                        <span style="display:inline-flex; align-items:center; gap:5px; background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; padding:5px 12px; border-radius:999px; font-size:12px; font-weight:700;">
                            <svg width="11" height="11" fill="none" stroke="#3b82f6" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Administrador
                        </span>
                        @else
                        <span style="display:inline-flex; align-items:center; gap:5px; background:#f8fafc; color:#475569; border:1px solid #e2e8f0; padding:5px 12px; border-radius:999px; font-size:12px; font-weight:700;">
                            <svg width="11" height="11" fill="none" stroke="#64748b" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Empleado
                        </span>
                        @endif
                    </td>

                    {{-- Last Access --}}
                    <td style="padding:16px 20px; font-size:12px; color:#94a3b8;">
                        {{ $usuario->fecha_creacion ? \Carbon\Carbon::parse($usuario->fecha_creacion)->format('d/m/Y H:i') : '—' }}
                    </td>

                    {{-- Status --}}
                    <td style="padding:16px 20px;">
                        @if($usuario->activo == 1)
                        <span style="display:inline-flex; align-items:center; gap:5px; background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0; padding:5px 14px; border-radius:999px; font-size:11px; font-weight:800; letter-spacing:0.05em;">
                            <span style="width:6px; height:6px; background:#22c55e; border-radius:50%;"></span>
                            ACTIVO
                        </span>
                        @else
                        <span style="display:inline-flex; align-items:center; gap:5px; background:#fff1f2; color:#be123c; border:1px solid #fecdd3; padding:5px 14px; border-radius:999px; font-size:11px; font-weight:800; letter-spacing:0.05em;">
                            <span style="width:6px; height:6px; background:#f43f5e; border-radius:50%;"></span>
                            INACTIVO
                        </span>
                        @endif
                    </td>

                    {{-- Actions --}}
                    <td style="padding:16px 20px; text-align:right;">
                        <div style="display:flex; align-items:center; justify-content:flex-end; gap:6px;">

                            {{-- ✏️ Editar --}}
                            <button type="button"
                                title="Editar usuario"
                                onclick="openEditModal('{{ $usuario->id_usuario }}','{{ addslashes($usuario->nombre) }}','{{ $usuario->email }}','{{ $usuario->numeroIdentificacion }}','{{ $usuario->id_rol }}','{{ $usuario->activo }}')"
                                style="width:34px; height:34px; border-radius:8px; border:1.5px solid #dbeafe; background:#eff6ff; color:#2563eb; display:inline-flex; align-items:center; justify-content:center; cursor:pointer;"
                                onmouseover="this.style.background='#dbeafe'"
                                onmouseout="this.style.background='#eff6ff'">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>

                            {{-- 🔄 Toggle Activar/Desactivar --}}
                            <form id="toggle-form-{{ $usuario->id_usuario }}" method="POST" action="{{ route('usuarios.update', $usuario->id_usuario) }}" style="display:inline;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="toggle_status" value="1" />
                                <button type="button"
                                    title="{{ $usuario->activo == 1 ? 'Desactivar usuario' : 'Activar usuario' }}"
                                    onclick="confirmToggle('{{ $usuario->id_usuario }}','{{ $usuario->nombre }}',{{ $usuario->activo }})"
                                    style="width:34px; height:34px; border-radius:8px; border:1.5px solid {{ $usuario->activo == 1 ? '#fde68a' : '#bbf7d0' }}; background:{{ $usuario->activo == 1 ? '#fffbeb' : '#f0fdf4' }}; color:{{ $usuario->activo == 1 ? '#d97706' : '#16a34a' }}; display:inline-flex; align-items:center; justify-content:center; cursor:pointer;"
                                    onmouseover="this.style.background='{{ $usuario->activo == 1 ? '#fde68a' : '#bbf7d0' }}'"
                                    onmouseout="this.style.background='{{ $usuario->activo == 1 ? '#fffbeb' : '#f0fdf4' }}'">
                                    @if($usuario->activo == 1)
                                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                    </svg>
                                    @else
                                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    @endif
                                </button>
                            </form>

                            {{-- 🗑️ Eliminar --}}
                            <form id="delete-form-{{ $usuario->id_usuario }}" method="POST" action="{{ route('usuarios.destroy', $usuario->id_usuario) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    title="Eliminar usuario"
                                    onclick="confirmDelete('{{ $usuario->id_usuario }}','{{ addslashes($usuario->nombre) }}')"
                                    style="width:34px; height:34px; border-radius:8px; border:1.5px solid #fecdd3; background:#fff1f2; color:#e11d48; display:inline-flex; align-items:center; justify-content:center; cursor:pointer;"
                                    onmouseover="this.style.background='#fecdd3'"
                                    onmouseout="this.style.background='#fff1f2'">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
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
                            <svg width="44" height="44" fill="none" stroke="#cbd5e1" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span style="font-size:0.875rem; font-weight:500;">No se encontraron usuarios registrados.</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Table Footer --}}
    <div style="padding:14px 24px; background:#f8fafc; border-top:1px solid #eef2f7; display:flex; align-items:center; justify-content:space-between;">
        <span style="font-size:13px; color:#64748b; font-weight:600;">{{ $usuarios->count() }} usuarios</span>
        <div style="display:flex; align-items:center; gap:6px;">
            <button disabled style="padding:6px 16px; border-radius:8px; border:1px solid #e2e8f0; background:#f1f5f9; color:#94a3b8; font-size:13px; font-weight:600; cursor:not-allowed;">Ant.</button>
            <button style="padding:6px 14px; border-radius:8px; border:none; background:#1e3fa8; color:#fff; font-size:13px; font-weight:700;">1</button>
            <button disabled style="padding:6px 16px; border-radius:8px; border:1px solid #e2e8f0; background:#f1f5f9; color:#94a3b8; font-size:13px; font-weight:600; cursor:not-allowed;">Sig.</button>
        </div>
    </div>
</div>

{{-- ===== MODAL: CREAR USUARIO ===== --}}
<div id="create-modal" style="display:none; position:fixed; inset:0; z-index:9000; overflow-y:auto;">
    <div style="position:fixed; inset:0; background:rgba(10,18,46,0.6); backdrop-filter:blur(5px);" onclick="closeModal('create-modal')"></div>
    <div style="display:flex; align-items:center; justify-content:center; min-height:100vh; padding:20px; pointer-events:none;">
        <div style="position:relative; background:#fff; border-radius:20px; max-width:430px; width:100%; padding:32px; box-shadow:0 24px 64px rgba(0,0,0,0.2); z-index:1; pointer-events:all;">

            <div style="display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid #f1f5f9; padding-bottom:16px; margin-bottom:22px;">
                <div>
                    <h3 style="font-size:1.1rem; font-weight:800; color:#0f172a; margin:0 0 2px;">Registrar Nuevo Usuario</h3>
                    <p style="font-size:12px; color:#94a3b8; margin:0;">Completa todos los campos requeridos</p>
                </div>
                <button onclick="closeModal('create-modal')" style="background:#f1f5f9; border:none; width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; cursor:pointer; color:#64748b;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('usuarios.store') }}" method="POST" style="display:flex; flex-direction:column; gap:14px;">
                @csrf
                <div>
                    <label style="display:block; font-size:10px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">Nombre Completo</label>
                    <input type="text" name="nombre" required placeholder="Ej. Juan Pérez"
                        style="width:100%; padding:10px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:10px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; font-family:inherit;"
                        onfocus="this.style.borderColor='#3b82f6'; this.style.background='#fff'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'"
                        onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'" />
                </div>
                <div>
                    <label style="display:block; font-size:10px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">Correo Electrónico</label>
                    <input type="email" name="email" required placeholder="ejemplo@correo.com"
                        style="width:100%; padding:10px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:10px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; font-family:inherit;"
                        onfocus="this.style.borderColor='#3b82f6'; this.style.background='#fff'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'"
                        onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'" />
                </div>
                <div>
                    <label style="display:block; font-size:10px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">Número de Identificación</label>
                    <input type="text" name="numeroIdentificacion" required placeholder="Identificación única"
                        style="width:100%; padding:10px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:10px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; font-family:inherit;"
                        onfocus="this.style.borderColor='#3b82f6'; this.style.background='#fff'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'"
                        onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'" />
                </div>
                <div>
                    <label style="display:block; font-size:10px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">Contraseña</label>
                    <input type="password" name="contrasena" required placeholder="Mínimo 8 caracteres"
                        style="width:100%; padding:10px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:10px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; font-family:inherit;"
                        onfocus="this.style.borderColor='#3b82f6'; this.style.background='#fff'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'"
                        onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'" />
                </div>
                <div>
                    <label style="display:block; font-size:10px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">Rol de Sistema</label>
                    <select name="id_rol" required
                        style="width:100%; padding:10px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:10px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; font-family:inherit;"
                        onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'"
                        onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                        @foreach($roles as $role)
                        <option value="{{ $role->id_rol }}">{{ $role->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display:flex; gap:10px; padding-top:4px;">
                    <button type="button" onclick="closeModal('create-modal')"
                        style="flex:1; padding:12px; background:#f1f5f9; color:#475569; font-weight:700; border:none; border-radius:12px; font-size:0.875rem; cursor:pointer; font-family:inherit;"
                        onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">Cancelar</button>
                    <button type="submit"
                        style="flex:1; padding:12px; background:#1e3fa8; color:#fff; font-weight:700; border:none; border-radius:12px; font-size:0.875rem; cursor:pointer; font-family:inherit; box-shadow:0 4px 14px rgba(30,63,168,0.3);"
                        onmouseover="this.style.background='#1730a0'" onmouseout="this.style.background='#1e3fa8'">Registrar Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== MODAL: EDITAR USUARIO ===== --}}
<div id="edit-modal" style="display:none; position:fixed; inset:0; z-index:9000; overflow-y:auto;">
    <div style="position:fixed; inset:0; background:rgba(10,18,46,0.6); backdrop-filter:blur(5px);" onclick="closeModal('edit-modal')"></div>
    <div style="display:flex; align-items:center; justify-content:center; min-height:100vh; padding:20px; pointer-events:none;">
        <div style="position:relative; background:#fff; border-radius:20px; max-width:430px; width:100%; padding:32px; box-shadow:0 24px 64px rgba(0,0,0,0.2); z-index:1; pointer-events:all;">

            <div style="display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid #f1f5f9; padding-bottom:16px; margin-bottom:22px;">
                <div>
                    <h3 style="font-size:1.1rem; font-weight:800; color:#0f172a; margin:0 0 2px;">Editar Usuario</h3>
                    <p style="font-size:12px; color:#94a3b8; margin:0;">Modifica los datos del usuario</p>
                </div>
                <button onclick="closeModal('edit-modal')" style="background:#f1f5f9; border:none; width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; cursor:pointer; color:#64748b;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="edit-form" method="POST" style="display:flex; flex-direction:column; gap:14px;">
                @csrf
                @method('PUT')
                <div>
                    <label style="display:block; font-size:10px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">Nombre Completo</label>
                    <input type="text" id="edit-nombre" name="nombre" required
                        style="width:100%; padding:10px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:10px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; font-family:inherit;"
                        onfocus="this.style.borderColor='#3b82f6'; this.style.background='#fff'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'"
                        onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'" />
                </div>
                <div>
                    <label style="display:block; font-size:10px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">Correo Electrónico</label>
                    <input type="email" id="edit-email" name="email" required
                        style="width:100%; padding:10px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:10px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; font-family:inherit;"
                        onfocus="this.style.borderColor='#3b82f6'; this.style.background='#fff'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'"
                        onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'" />
                </div>
                <div>
                    <label style="display:block; font-size:10px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">Número de Identificación</label>
                    <input type="text" id="edit-identificacion" name="numeroIdentificacion" required
                        style="width:100%; padding:10px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:10px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; font-family:inherit;"
                        onfocus="this.style.borderColor='#3b82f6'; this.style.background='#fff'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'"
                        onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'" />
                </div>
                <div>
                    <label style="display:block; font-size:10px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">Contraseña (opcional)</label>
                    <input type="password" name="contrasena" placeholder="Dejar vacío para conservar"
                        style="width:100%; padding:10px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:10px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; font-family:inherit;"
                        onfocus="this.style.borderColor='#3b82f6'; this.style.background='#fff'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'"
                        onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'" />
                </div>
                <div>
                    <label style="display:block; font-size:10px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">Rol de Sistema</label>
                    <select id="edit-rol" name="id_rol" required
                        style="width:100%; padding:10px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:10px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; font-family:inherit;"
                        onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'"
                        onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                        @foreach($roles as $role)
                        <option value="{{ $role->id_rol }}">{{ $role->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:10px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">Estado</label>
                    <select id="edit-activo" name="activo" required
                        style="width:100%; padding:10px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:10px; font-size:0.875rem; color:#1e293b; outline:none; box-sizing:border-box; font-family:inherit;"
                        onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'"
                        onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
                <div style="display:flex; gap:10px; padding-top:4px;">
                    <button type="button" onclick="closeModal('edit-modal')"
                        style="flex:1; padding:12px; background:#f1f5f9; color:#475569; font-weight:700; border:none; border-radius:12px; font-size:0.875rem; cursor:pointer; font-family:inherit;"
                        onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">Cancelar</button>
                    <button type="submit"
                        style="flex:1; padding:12px; background:#1e3fa8; color:#fff; font-weight:700; border:none; border-radius:12px; font-size:0.875rem; cursor:pointer; font-family:inherit; box-shadow:0 4px 14px rgba(30,63,168,0.3);"
                        onmouseover="this.style.background='#1730a0'" onmouseout="this.style.background='#1e3fa8'">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== JAVASCRIPT ===== --}}
<script>
    /* ---------- Modals ---------- */
    function openModal(id) {
        document.getElementById(id).style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
        document.body.style.overflow = '';
    }

    function openEditModal(id, nombre, email, identificacion, rol, activo) {
        document.getElementById('edit-form').action = '/usuarios/' + id;
        document.getElementById('edit-nombre').value = nombre;
        document.getElementById('edit-email').value = email;
        document.getElementById('edit-identificacion').value = identificacion;
        document.getElementById('edit-rol').value = rol;
        document.getElementById('edit-activo').value = activo;
        openModal('edit-modal');
    }
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal('create-modal');
            closeModal('edit-modal');
        }
    });

    /* ---------- SweetAlert: Toggle Activar / Desactivar ---------- */
    function confirmToggle(id, nombre, activo) {
        const accion = activo == 1 ? 'desactivar' : 'activar';
        const accionCap = activo == 1 ? 'Desactivar' : 'Activar';
        const icon = activo == 1 ? 'warning' : 'question';
        const btnColor = activo == 1 ? '#d97706' : '#16a34a';

        Swal.fire({
            title: accionCap + ' usuario',
            html: '¿Estás seguro de que deseas <strong>' + accion + '</strong> a <strong>' + nombre + '</strong>?',
            icon: icon,
            showCancelButton: true,
            confirmButtonText: accionCap,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: btnColor,
            cancelButtonColor: '#6b7280',
            borderRadius: '16px',
            customClass: {
                popup: 'swal-popup-custom'
            }
        }).then(function(result) {
            if (result.isConfirmed) {
                document.getElementById('toggle-form-' + id).submit();
            }
        });
    }

    /* ---------- SweetAlert: Eliminar ---------- */
    function confirmDelete(id, nombre) {
        Swal.fire({
            title: 'Eliminar usuario',
            html: '¿Estás seguro de eliminar a <strong>' + nombre + '</strong>? <br><span style="font-size:13px;color:#94a3b8;">Esta acción no se puede deshacer.</span>',
            icon: 'error',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#6b7280',
        }).then(function(result) {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>

@endsection