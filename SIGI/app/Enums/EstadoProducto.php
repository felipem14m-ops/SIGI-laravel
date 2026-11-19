<?php

namespace App\Enums;

enum EstadoProducto: string
{
    case ACTIVO = 'activo';
    case INACTIVO = 'inactivo';
    case AGOTADO = 'agotado';

    /** @return list<string> */
    public static function valores(): array
    {
        return array_column(self::cases(), 'value');
    }
}
