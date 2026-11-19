<?php

namespace App\Http\Controllers\Empleado;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\MetodoPago;
use App\Models\Producto;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PuntoVentaController extends Controller
{
    public function __invoke(Request $request, DashboardService $dashboard): View
    {
        return view('Empleado.Ventas.ventas', [
            'productos' => Producto::with('categoria')->disponibles()->orderBy('nombre')->get(),
            'categorias' => Categoria::activas()->orderBy('nombre')->get(),
            'metodos' => MetodoPago::activos()->orderBy('nombre')->get(),
            'ingresosHoy' => $dashboard->ingresosDelDia($request->user()),
        ]);
    }
}
