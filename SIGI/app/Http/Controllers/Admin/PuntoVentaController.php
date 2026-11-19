<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\MetodoPago;
use App\Models\Producto;
use App\Services\DashboardService;
use Illuminate\View\View;

/**
 * Terminal POS del administrador.
 */
class PuntoVentaController extends Controller
{
    public function __invoke(DashboardService $dashboard): View
    {
        return view('Admin.Venta.ListasdeVentas', [
            'productos' => Producto::with('categoria')->disponibles()->orderBy('nombre')->get(),
            'categorias' => Categoria::activas()->orderBy('nombre')->get(),
            'metodos' => MetodoPago::activos()->orderBy('nombre')->get(),
            'ingresosHoy' => $dashboard->ingresosDelDia(),
        ]);
    }
}
