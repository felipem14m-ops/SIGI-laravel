<?php

namespace App\DTOs;

/**
 * Línea del carrito: producto y cantidad solicitada.
 */
final readonly class ItemVentaData
{
    public function __construct(
        public int $idProducto,
        public int $cantidad,
    ) {}

    /** @param array{id:int|string, cantidad:int|string} $item */
    public static function desdeArray(array $item): self
    {
        return new self((int) $item['id'], (int) $item['cantidad']);
    }
}
