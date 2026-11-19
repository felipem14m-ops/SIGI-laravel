<?php

namespace App\Http\Resources;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Producto */
class ProductoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_producto' => $this->id_producto,
            'codigoUnico' => $this->codigoUnico,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'id_categoria' => $this->id_categoria,
            'id_proveedor' => $this->id_proveedor,
            'precio_venta' => (float) $this->precio_venta,
            'precio_costo' => (float) $this->precio_costo,
            'stock_minimo' => $this->stock_minimo,
            'stock_actual' => $this->stock_actual,
            'estado' => $this->estado,
            'tipo_alerta' => $this->tipoAlerta(),
            'fechaVencimiento' => $this->fechaVencimiento?->toDateString(),
            'imagen' => $this->imagen,
            'imagen_url' => $this->imagen_url,
            'categoria' => new CategoriaResource($this->whenLoaded('categoria')),
            'proveedor' => new ProveedorResource($this->whenLoaded('proveedor')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
