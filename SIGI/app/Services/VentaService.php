<?php

namespace App\Services;

use App\DTOs\RegistrarVentaData;
use App\Enums\EstadoVenta;
use App\Enums\TipoMovimientoCodigo;
use App\Exceptions\MontoInsuficienteException;
use App\Exceptions\ProductoNoDisponibleException;
use App\Exceptions\StockInsuficienteException;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

/**
 * Caso de uso "Registrar venta" del POS.
 *
 * Antes esta lógica estaba copiada en VentaController, EmpleadoVentaController
 * y ApiController. Ahora existe en un único lugar y la usan la web y la API.
 */
class VentaService
{
    public function __construct(private readonly InventarioService $inventario) {}

    /**
     * Registra la venta de forma atómica:
     *  1. Bloquea las filas de los productos (lockForUpdate).
     *  2. Valida disponibilidad y stock.
     *  3. Crea la cabecera y los detalles con el precio vigente del producto.
     *  4. Descuenta el inventario dejando rastro en el Kardex.
     *  5. Calcula total, monto recibido y cambio.
     *
     * Si cualquier paso falla se revierte todo (ACID).
     *
     * @throws StockInsuficienteException
     * @throws ProductoNoDisponibleException
     * @throws MontoInsuficienteException
     */
    public function registrar(RegistrarVentaData $datos): Venta
    {
        return DB::transaction(function () use ($datos) {
            $cantidades = $datos->cantidadesPorProducto();

            $productos = Producto::query()
                ->whereKey(array_keys($cantidades))
                ->orderBy('id_producto')
                ->lockForUpdate()
                ->get()
                ->keyBy('id_producto');

            $venta = Venta::create([
                'id_usuario' => $datos->idUsuario,
                'id_metodo' => $datos->idMetodoPago,
                'fecha_venta' => now(),
                'estado' => EstadoVenta::COMPLETADA->value,
            ]);

            $total = 0.0;

            foreach ($cantidades as $idProducto => $cantidad) {
                /** @var Producto $producto */
                $producto = $productos->get($idProducto) ?? throw (new ModelNotFoundException)->setModel(Producto::class, [$idProducto]);

                if (! $producto->estaActivo()) {
                    throw ProductoNoDisponibleException::para($producto);
                }

                $precioUnitario = (float) $producto->precio_venta;
                $subtotal = round($precioUnitario * $cantidad, 2);
                $total += $subtotal;

                DetalleVenta::create([
                    'id_venta' => $venta->id_venta,
                    'id_producto' => $producto->id_producto,
                    'cantidad' => $cantidad,
                    'precioUnitario' => $precioUnitario,
                    'subtotal' => $subtotal,
                ]);

                $this->inventario->aplicar(
                    producto: $producto,
                    tipo: TipoMovimientoCodigo::SALIDA,
                    cantidad: $cantidad,
                    idUsuario: $datos->idUsuario,
                    motivo: "Venta #{$venta->id_venta} ({$datos->canal})",
                );
            }

            $total = round($total, 2);
            $montoRecibido = $datos->montoRecibido ?? $total;

            if ($montoRecibido < $total) {
                throw MontoInsuficienteException::para($montoRecibido, $total);
            }

            $venta->update([
                'total' => $total,
                'monto_recibido' => $montoRecibido,
                'cambio' => round($montoRecibido - $total, 2),
            ]);

            return $venta->load(['usuario', 'metodo', 'detalles.producto']);
        });
    }
}
