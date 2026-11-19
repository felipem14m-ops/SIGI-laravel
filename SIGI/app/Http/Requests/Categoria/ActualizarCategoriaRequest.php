<?php

namespace App\Http\Requests\Categoria;

class ActualizarCategoriaRequest extends GuardarCategoriaRequest
{
    public function rules(): array
    {
        $reglas = parent::rules();
        $reglas['nombre'] = ['sometimes', ...$reglas['nombre']];

        return $reglas;
    }
}
