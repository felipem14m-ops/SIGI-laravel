<?php

namespace App\Exceptions;

final class MontoInsuficienteException extends ReglaNegocioException
{
    public static function para(float $recibido, float $total): self
    {
        return new self(sprintf(
            'El monto recibido ($%s) es menor que el total de la venta ($%s).',
            number_format($recibido, 2),
            number_format($total, 2),
        ));
    }
}
