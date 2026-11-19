<?php

namespace App\Http\Resources;

use App\Models\MetodoPago;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin MetodoPago */
class MetodoPagoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_metodo' => $this->id_metodo,
            'nombre' => $this->nombre,
            'activo' => $this->activo,
        ];
    }
}
