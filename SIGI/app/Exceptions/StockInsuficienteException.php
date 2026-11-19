<?php

namespace App\Exceptions;

use App\Models\Producto;

final class StockInsuficienteException extends ReglaNegocioException
{
    public static function para(Producto $producto, int $solicitado): self
    {
        return new self(sprintf(
            'Stock insuficiente para "%s". Disponible: %d, solicitado: %d.',
            $producto->nombre,
            $producto->stock_actual,
            $solicitado,
        ));
    }
}
