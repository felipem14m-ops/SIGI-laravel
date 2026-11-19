<?php

namespace App\Services;

use App\DTOs\RegistrarMovimientoData;
use App\Enums\TipoMovimientoCodigo;
use App\Exceptions\StockInsuficienteException;
use App\Models\Movimiento;
use App\Models\Producto;
use App\Models\TipoMovimiento;
use Illuminate\Support\Facades\DB;

/**
 * Única puerta de entrada para modificar el stock de un producto.
 *
 * Toda variación de inventario queda registrada en el Kardex
 * (`movimiento_inventario`) con stock anterior y resultante, garantizando
 * la trazabilidad exigida por el SRS.
 */
class InventarioService
{
    public const ORIGEN_MANUAL = 'manual';

    public const ORIGEN_AUTOMATICO = 'automatico';

    /**
     * Registra un movimiento manual (Kardex) de forma atómica y con bloqueo
     * pesimista de la fila del producto para evitar condiciones de carrera.
     */
    public function registrarMovimiento(RegistrarMovimientoData $datos): Movimiento
    {
        return DB::transaction(function () use ($datos) {
            $producto = Producto::query()->lockForUpdate()->findOrFail($datos->idProducto);

            return $this->aplicar(
                producto: $producto,
                tipo: $datos->tipo,
                cantidad: $datos->cantidad,
                idUsuario: $datos->idUsuario,
                motivo: $datos->motivo ?: 'Movimiento manual',
                origen: self::ORIGEN_MANUAL,
            );
        });
    }

    /**
     * Aplica un movimiento sobre un producto YA bloqueado dentro de una
     * transacción abierta por el llamador (p. ej. VentaService).
     *
     * @throws StockInsuficienteException
     */
    public function aplicar(
        Producto $producto,
        TipoMovimientoCodigo $tipo,
        int $cantidad,
        int $idUsuario,
        string $motivo,
        string $origen = self::ORIGEN_AUTOMATICO,
    ): Movimiento {
        if ($tipo === TipoMovimientoCodigo::SALIDA && ! $producto->tieneStock($cantidad)) {
            throw StockInsuficienteException::para($producto, $cantidad);
        }

        $stockAnterior = $producto->stock_actual;
        $producto->establecerStock($tipo->aplicar($stockAnterior, $cantidad));
        $producto->save();

        return Movimiento::create([
            'id_producto' => $producto->id_producto,
            'id_usuario' => $idUsuario,
            'id_tipo_movimiento' => TipoMovimiento::para($tipo)->id_tipo,
            'cantidad' => $cantidad,
            'stock_anterior' => $stockAnterior,
            'stock_resultante' => $producto->stock_actual,
            'origen' => $origen,
            'motivo' => $motivo,
            'fecha' => now(),
        ]);
    }
}
