<?php

namespace App\Http\Controllers\Empleado;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\View\View;

/**
 * Catálogo de solo lectura para que el empleado consulte precios y stock.
 */
class ConsultaProductoController extends Controller
{
    public function __invoke(): View
    {
        return view('Empleado.Consultas.consultas', [
            'productos' => Producto::with('categoria')->activos()->orderBy('nombre')->get(),
            'categorias' => Categoria::activas()->orderBy('nombre')->get(),
        ]);
    }
}
