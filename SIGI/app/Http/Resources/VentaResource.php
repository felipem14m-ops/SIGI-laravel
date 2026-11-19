<?php

namespace App\Http\Resources;

use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Venta */
class VentaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_venta' => $this->id_venta,
            'fecha_venta' => $this->fecha_venta,
            'estado' => $this->estado,
            'total' => (float) $this->total,
            'monto_recibido' => (float) $this->monto_recibido,
            'cambio' => (float) $this->cambio,
            'id_usuario' => $this->id_usuario,
            'id_metodo' => $this->id_metodo,
            'usuario' => $this->whenLoaded('usuario', fn () => [
                'id_usuario' => $this->usuario->id_usuario,
                'nombre' => $this->usuario->nombre,
            ]),
            'metodo' => $this->whenLoaded('metodo', fn () => [
                'id_metodo' => $this->metodo->id_metodo,
                'nombre' => $this->metodo->nombre,
            ]),
            'detalles' => DetalleVentaResource::collection($this->whenLoaded('detalles')),
        ];
    }
}
