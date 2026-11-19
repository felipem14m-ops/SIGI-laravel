<?php

namespace App\Http\Resources;

use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Proveedor */
class ProveedorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_proveedor' => $this->id_proveedor,
            'nombre' => $this->nombre,
            'empresa' => $this->empresa,
            'telefono' => $this->telefono,
            'email' => $this->email,
            'activo' => $this->activo,
        ];
    }
}
