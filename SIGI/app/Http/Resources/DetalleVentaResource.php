<?php

namespace App\Http\Resources;

use App\Models\DetalleVenta;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin DetalleVenta */
class DetalleVentaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_detalle' => $this->id_detalle,
            'id_producto' => $this->id_producto,
            'cantidad' => $this->cantidad,
            'precioUnitario' => (float) $this->precioUnitario,
            'subtotal' => (float) $this->subtotal,
            'producto' => $this->whenLoaded('producto', fn () => [
                'id_producto' => $this->producto->id_producto,
                'nombre' => $this->producto->nombre,
                'codigoUnico' => $this->producto->codigoUnico,
                'imagen_url' => $this->producto->imagen_url,
            ]),
        ];
    }
}
