<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\MetodoPagoResource;
use App\Http\Resources\ProveedorResource;
use App\Models\MetodoPago;
use App\Models\Proveedor;
use Illuminate\Http\JsonResponse;

/**
 * Catálogos de apoyo de solo lectura para la app móvil.
 */
class CatalogoController extends ApiController
{
    public function metodosPago(): JsonResponse
    {
        return $this->exito(MetodoPagoResource::collection(MetodoPago::activos()->orderBy('nombre')->get()));
    }

    public function proveedores(): JsonResponse
    {
        return $this->exito(ProveedorResource::collection(Proveedor::activos()->orderBy('nombre')->get()));
    }
}
