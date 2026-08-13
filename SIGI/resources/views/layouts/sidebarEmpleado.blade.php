<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIGI – @yield('title', 'Empleado')</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { font-family: 'Inter', sans-serif; }

        /* ═══ SIDEBAR – mismo gradiente que el banner ═══ */
        body.sidebar-mini .main-sidebar,
        .main-sidebar { background: linear-gradient(180deg, #1a3da8 0%, #162e8a 100%) !important; }

        .brand-link {
            background: #132f8a !important;
            border-bottom: 1px solid rgba(255,255,255,.1) !important;
            padding: 15px 18px !important;
        }

        .nav-sidebar .nav-link {
            color: rgba(255,255,255,.78) !important;
            font-size: 12.5px; font-weight: 600;
            border-radius: 8px; margin: 1px 8px; padding: 8px 12px;
            transition: all .15s;
        }
        .nav-sidebar .nav-link:hover { background: rgba(255,255,255,.12) !important; color: #fff !important; }
        .nav-sidebar .nav-link.active {
            background: rgba(255,255,255,.22) !important;
            color: #fff !important;
            box-shadow: 0 2px 10px rgba(0,0,0,.2);
            font-weight: 700;
        }

        .nav-header { font-size: 9px !important; font-weight: 900 !important; color: rgba(255,255,255,.38) !important; letter-spacing: .14em; padding: 12px 18px 3px; }

        .nav-sidebar .nav-icon { width: 16px; text-align: center; margin-right: 8px; font-size: 13px; color: rgba(255,255,255,.55); }
        .nav-sidebar .nav-link.active .nav-icon,
        .nav-sidebar .nav-link:hover .nav-icon { color: #fff !important; }

        .badge-pos { background: rgba(255,255,255,.18); color: #fff; font-size: 8.5px; font-weight: 900; padding: 2px 7px; border-radius: 20px; border: 1px solid rgba(255,255,255,.3); float: right; margin-top: 3px; }

        /* ═══ TOPBAR ═══ */
        .main-header.navbar { background: linear-gradient(90deg, #1a3da8 0%, #2350d4 100%) !important; box-shadow: 0 2px 12px rgba(26,61,168,.35) !important; border-bottom: none !important; }
        .main-header .navbar-nav .nav-link { color: rgba(255,255,255,.88) !important; font-weight: 600; font-size: 13px; }
        .main-header .navbar-nav .nav-link:hover { color: #fff !important; }
        .main-header [data-widget="pushmenu"] { color: rgba(255,255,255,.88) !important; }

        .topbar-inicio { display: inline-flex; align-items: center; gap: 6px; color: rgba(255,255,255,.9) !important; font-weight: 700; font-size: 13px; padding: 6px 14px; background: rgba(255,255,255,.12); border-radius: 8px; text-decoration: none; transition: background .15s; }
        .topbar-inicio:hover { background: rgba(255,255,255,.2) !important; color: #fff !important; text-decoration: none; }

        .topbar-user { color: rgba(255,255,255,.88) !important; font-weight: 700; font-size: 13px; }

        .topbar-logout { display: inline-flex; align-items: center; gap: 6px; background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.2); color: rgba(255,255,255,.9) !important; font-weight: 700; font-size: 12.5px; padding: 6px 14px; border-radius: 8px; cursor: pointer; transition: background .15s; }
        .topbar-logout:hover { background: rgba(255,255,255,.2); color: #fff !important; }

        .content-wrapper { background: #f1f5f9 !important; }
        .main-sidebar::-webkit-scrollbar { width: 4px; }
        .main-sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,.15); border-radius: 2px; }
        .sidebar { padding-bottom: 20px; }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed" style="font-family:'Inter',sans-serif;">
<div class="wrapper">

    {{-- ========== TOPBAR ========== --}}
    <nav class="main-header navbar navbar-expand">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button" style="font-size:16px;">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
            <li class="nav-item ml-1">
                <a href="{{ route('empleado.ventas.index') }}" class="topbar-inicio">
                    <i class="fas fa-cash-register" style="font-size:13px;"></i> Nueva Venta
                </a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto align-items-center" style="gap:10px;">
            <li class="nav-item">
                <span class="topbar-user">
                    <i class="fas fa-user-circle mr-1" style="font-size:14px; opacity:.75;"></i>
                    {{ Auth::user()->nombre ?? 'Empleado' }}
                </span>
            </li>
            <li class="nav-item mr-2">
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="topbar-logout">
                        <i class="fas fa-sign-out-alt" style="font-size:13px;"></i> Salir
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    {{-- ========== SIDEBAR ========== --}}
    <aside class="main-sidebar elevation-3">

        <a href="{{ route('dashboard') }}" class="brand-link d-flex align-items-center" style="gap:12px;">
            <span style="background:rgba(255,255,255,.18); padding:8px 10px; border-radius:10px; display:inline-flex; align-items:center; flex-shrink:0;">
                <i class="fas fa-store" style="font-size:15px; color:#fff;"></i>
            </span>
            <span class="brand-text text-white" style="font-size:19px; font-weight:900; letter-spacing:-.01em; line-height:1;">
                SIGI
            </span>
        </a>

        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                    <li class="nav-header">PRINCIPAL</li>
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ Request::routeIs('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-th-large"></i>
                            <p>Panel Principal</p>
                        </a>
                    </li>

                    <li class="nav-header">MIS OPERACIONES</li>

                    <li class="nav-item">
                        <a href="{{ route('empleado.ventas.index') }}" class="nav-link {{ Request::routeIs('empleado.ventas.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cash-register"></i>
                            <p>Nueva Venta <span class="badge-pos">POS</span></p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('empleado.consultas.index') }}" class="nav-link {{ Request::routeIs('empleado.consultas.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-search"></i>
                            <p>Consultar Productos</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('empleado.misventas.index') }}" class="nav-link {{ Request::routeIs('empleado.misventas.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-invoice"></i>
                            <p>Mis Ventas</p>
                        </a>
                    </li>

                    <li class="nav-header">SESIÓN</li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" id="logout-form">@csrf
                            <button type="submit" class="nav-link" style="background:none; border:none; width:100%; text-align:left; cursor:pointer; color:rgba(255,180,180,.85) !important;">
                                <i class="nav-icon fas fa-sign-out-alt" style="color:rgba(255,180,180,.85);"></i>
                                <p style="color:rgba(255,180,180,.85);">Cerrar Sesión</p>
                            </button>
                        </form>
                    </li>

                </ul>
            </nav>
        </div>
    </aside>

    {{-- ========== CONTENT ========== --}}
    <div class="content-wrapper" style="padding:0;">
        <div class="content-header" style="background:#fff; border-bottom:1px solid #e8ecf4; padding:13px 24px;">
            <div class="container-fluid px-0">
                <h5 class="mb-0 font-weight-bold" style="color:#1e293b; font-size:15px;">
                    @yield('page-title', 'Panel del Empleado')
                </h5>
            </div>
        </div>
        <div class="content" style="padding:24px;">
            <div class="container-fluid px-0">
                @yield('content')
            </div>
        </div>
    </div>

    <footer class="main-footer" style="background:#fff; border-top:1px solid #e8ecf4; font-size:12px; color:#64748b; padding:10px 20px;">
        <strong>SIGI</strong> &copy; {{ date('Y') }} – Módulo de Empleados
    </footer>

</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
@stack('scripts')

</body>
</html>