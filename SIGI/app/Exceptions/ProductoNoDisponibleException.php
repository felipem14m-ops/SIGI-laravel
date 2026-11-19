<?php

namespace App\Exceptions;

use App\Models\Producto;

final class ProductoNoDisponibleException extends ReglaNegocioException
{
    public static function para(Producto $producto): self
    {
        return new self("El producto \"{$producto->nombre}\" no está disponible para la venta.");
    }
}
