<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * Agrega el atributo calculado `imagen_url` a modelos con columna `imagen`
 * guardada en el disco `public`.
 */
trait TieneImagen
{
    public function initializeTieneImagen(): void
    {
        $this->append('imagen_url');
    }

    protected function imagenUrl(): Attribute
    {
        return Attribute::get(
            fn () => $this->imagen ? asset('storage/'.$this->imagen) : null,
        );
    }
}
