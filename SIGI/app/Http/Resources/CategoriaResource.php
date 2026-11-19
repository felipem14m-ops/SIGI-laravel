<?php

namespace App\Http\Resources;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Categoria */
class CategoriaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_categoria' => $this->id_categoria,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'activa' => $this->activa,
            'imagen' => $this->imagen,
            'imagen_url' => $this->imagen_url,
            'productos_count' => $this->whenCounted('productos'),
            'productos' => ProductoResource::collection($this->whenLoaded('productos')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
