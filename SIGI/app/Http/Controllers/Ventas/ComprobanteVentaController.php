<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Models\Venta;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

/**
 * Detalle (modal) y factura POS de una venta.
 * VentaPolicy garantiza que un empleado solo vea sus propias ventas.
 */
class ComprobanteVentaController extends Controller
{
    public function show(Venta $venta): JsonResponse
    {
        Gate::authorize('view', $venta);

        return response()->json([
            'success' => true,
            'venta' => $venta->load(['usuario', 'metodo', 'detalles.producto']),
        ]);
    }

    public function factura(Venta $venta): View
    {
        Gate::authorize('view', $venta);

        return view('Admin.Venta.facturaPOS', [
            'venta' => $venta->load(['usuario', 'metodo', 'detalles.producto']),
        ]);
    }
}
