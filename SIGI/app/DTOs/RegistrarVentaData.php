<?php

namespace App\DTOs;

/**
 * Datos necesarios para registrar una venta en el POS (web o móvil).
 */
final readonly class RegistrarVentaData
{
    /**
     * @param  list<ItemVentaData>  $items
     */
    public function __construct(
        public int $idUsuario,
        public int $idMetodoPago,
        public array $items,
        public ?float $montoRecibido = null,
        public string $canal = 'POS Web',
    ) {}

    /**
     * Agrupa líneas repetidas del mismo producto para bloquear cada fila una sola vez.
     *
     * @return array<int, int> id_producto => cantidad total
     */
    public function cantidadesPorProducto(): array
    {
        $agrupado = [];
        foreach ($this->items as $item) {
            $agrupado[$item->idProducto] = ($agrupado[$item->idProducto] ?? 0) + $item->cantidad;
        }
        ksort($agrupado); // orden estable de bloqueo → evita deadlocks

        return $agrupado;
    }
}
