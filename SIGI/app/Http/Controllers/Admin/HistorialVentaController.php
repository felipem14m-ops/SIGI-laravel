<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MetodoPago;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HistorialVentaController extends Controller
{
    public function __invoke(Request $request): View
    {
        $ventas = Venta::with(['usuario', 'metodo', 'detalles'])
            ->when($request->filled('fecha'), fn ($q) => $q->delDia($request->fecha))
            ->when($request->filled('cajero'), fn ($q) => $q->whereHas('usuario', fn ($u) => $u->where('nombre', 'like', "%{$request->cajero}%")))
            ->when($request->filled('metodo'), fn ($q) => $q->whereHas('metodo', fn ($m) => $m->where('nombre', 'like', "%{$request->metodo}%")))
            ->latest('fecha_venta')
            ->paginate(15)
            ->withQueryString();

        return view('Admin.Venta.HistorialdeVentas', [
            'ventas' => $ventas,
            'metodosPago' => MetodoPago::orderBy('nombre')->get(),
        ]);
    }
}
