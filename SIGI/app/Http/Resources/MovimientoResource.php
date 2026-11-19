<?php

namespace App\Http\Resources;

use App\Models\Movimiento;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Movimiento */
class MovimientoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_movimiento' => $this->id_movimiento,
            'fecha' => $this->fecha,
            'cantidad' => $this->cantidad,
            'stock_anterior' => $this->stock_anterior,
            'stock_resultante' => $this->stock_resultante,
            'origen' => $this->origen,
            'motivo' => $this->motivo,
            'producto' => $this->whenLoaded('producto', fn () => [
                'id_producto' => $this->producto->id_producto,
                'nombre' => $this->producto->nombre,
                'codigoUnico' => $this->producto->codigoUnico,
            ]),
            'usuario' => $this->whenLoaded('usuario', fn () => [
                'id_usuario' => $this->usuario->id_usuario,
                'nombre' => $this->usuario->nombre,
            ]),
            'tipo_movimiento' => $this->whenLoaded('tipoMovimiento', fn () => [
                'codigo' => $this->tipoMovimiento->codigo,
                'nombre' => $this->tipoMovimiento->nombre,
                'signo' => $this->tipoMovimiento->signo,
            ]),
        ];
    }
}
