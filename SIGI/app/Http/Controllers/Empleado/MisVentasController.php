<?php

namespace App\Http\Controllers\Empleado;

use App\Http\Controllers\Controller;
use App\Models\MetodoPago;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MisVentasController extends Controller
{
    public function __invoke(Request $request): View
    {
        // Antes un orWhere() sin agrupar hacía que el filtro por fecha
        // mostrara ventas de OTROS empleados. Los scopes lo evitan.
        $misVentas = Venta::deCajero($request->user())
            ->with(['metodo', 'usuario', 'detalles.producto'])
            ->when($request->filled('fecha'), fn ($q) => $q->delDia($request->fecha))
            ->when($request->filled('metodo'), fn ($q) => $q->whereHas('metodo', fn ($m) => $m->where('nombre', $request->metodo)))
            ->latest('fecha_venta')
            ->paginate(10)
            ->withQueryString();

        return view('Empleado.Mis ventas.MisVentas', [
            'misVentas' => $misVentas,
            'metodosPago' => MetodoPago::activos()->orderBy('nombre')->get(),
        ]);
    }
}
