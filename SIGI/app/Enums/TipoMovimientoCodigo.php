<?php

namespace App\Enums;

/**
 * Tipos de movimiento del Kardex. El valor coincide con `tipos_movimiento.codigo`.
 */
enum TipoMovimientoCodigo: string
{
    case ENTRADA = 'ENTRADA';
    case SALIDA = 'SALIDA';
    case AJUSTE = 'AJUSTE';

    public function nombre(): string
    {
        return match ($this) {
            self::ENTRADA => 'Entrada de mercancía',
            self::SALIDA => 'Salida de mercancía',
            self::AJUSTE => 'Ajuste de inventario',
        };
    }

    public function signo(): string
    {
        return $this === self::SALIDA ? '-' : '+';
    }

    /**
     * Calcula el stock resultante tras aplicar el movimiento.
     * ENTRADA suma, SALIDA resta y AJUSTE fija el valor contado físicamente.
     */
    public function aplicar(int $stockActual, int $cantidad): int
    {
        return match ($this) {
            self::ENTRADA => $stockActual + $cantidad,
            self::SALIDA => $stockActual - $cantidad,
            self::AJUSTE => $cantidad,
        };
    }

    public static function desdeTexto(string $valor): self
    {
        return self::from(strtoupper(trim($valor)));
    }

    /** @return list<string> valores en mayúscula y minúscula, útil para reglas `in:` */
    public static function valoresAceptados(): array
    {
        $valores = array_column(self::cases(), 'value');

        return [...$valores, ...array_map('strtolower', $valores)];
    }
}
