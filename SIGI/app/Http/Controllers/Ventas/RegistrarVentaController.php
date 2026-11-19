<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Venta\RegistrarVentaRequest;
use App\Services\VentaService;
use Illuminate\Http\JsonResponse;

/**
 * Registra una venta desde el POS web (Administrador o Empleado).
 * Responde JSON porque el POS envía el carrito vía fetch().
 */
class RegistrarVentaController extends Controller
{
    public function __invoke(RegistrarVentaRequest $request, VentaService $ventas): JsonResponse
    {
        $venta = $ventas->registrar($request->toData(canal: 'POS Web'));

        return response()->json([
            'success' => true,
            'venta_id' => $venta->id_venta,
            'cambio' => (float) $venta->cambio,
            'message' => 'Venta registrada correctamente.',
        ]);
    }
}
