<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ProductoResource;
use App\Models\Producto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AlertaController extends ApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        $alertas = Producto::with(['categoria:id_categoria,nombre', 'proveedor:id_proveedor,nombre'])
            ->enAlerta()
            ->when($request->tipo_alerta === 'agotado', fn ($q) => $q->agotados())
            ->when($request->tipo_alerta === 'bajo', fn ($q) => $q->where('stock_actual', '>', 0))
            ->orderBy('stock_actual')
            ->get();

        return $this->exito(ProductoResource::collection($alertas), extra: ['total' => $alertas->count()]);
    }
}
