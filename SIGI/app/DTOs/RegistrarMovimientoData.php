<?php

namespace App\DTOs;

use App\Enums\TipoMovimientoCodigo;

/**
 * Movimiento manual de Kardex (entrada, salida o ajuste).
 */
final readonly class RegistrarMovimientoData
{
    public function __construct(
        public int $idProducto,
        public int $idUsuario,
        public TipoMovimientoCodigo $tipo,
        public int $cantidad,
        public ?string $motivo = null,
    ) {}
}
