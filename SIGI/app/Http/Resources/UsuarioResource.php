<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin User */
class UsuarioResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_usuario' => $this->id_usuario,
            'nombre' => $this->nombre,
            'email' => $this->email,
            'numeroIdentificacion' => $this->numeroIdentificacion,
            'id_rol' => $this->id_rol,
            'rol_nombre' => $this->nombreRol(),
            'activo' => (bool) $this->activo,
        ];
    }
}
