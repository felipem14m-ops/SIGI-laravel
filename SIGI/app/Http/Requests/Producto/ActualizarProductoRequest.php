<?php

namespace App\Http\Requests\Producto;

use App\Models\Producto;

/**
 * Validación para editar un producto. Admite actualizaciones parciales
 * (útil para la app móvil): solo se validan los campos enviados.
 */
class ActualizarProductoRequest extends GuardarProductoRequest
{
    public function rules(): array
    {
        return collect(parent::rules())
            ->map(fn (array $reglas) => in_array('required', $reglas, true) ? ['sometimes', ...$reglas] : $reglas)
            ->all();
    }

    protected function idIgnorado(): ?int
    {
        $producto = $this->route('producto');

        return $producto instanceof Producto ? $producto->id_producto : (int) $producto;
    }
}
