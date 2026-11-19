<?php

namespace App\Services;

use App\Contracts\AlmacenImagenes;
use App\DTOs\RegistrarMovimientoData;
use App\Enums\EstadoProducto;
use App\Enums\TipoMovimientoCodigo;
use App\Models\Producto;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

/**
 * Gestión del catálogo de productos (CU: registrar, editar, eliminar producto).
 */
class ProductoService
{
    private const CARPETA_IMAGENES = 'productos';

    public function __construct(
        private readonly AlmacenImagenes $imagenes,
        private readonly InventarioService $inventario,
    ) {}

    /**
     * Crea el producto. Si trae stock inicial, se registra como ENTRADA en
     * el Kardex para que el inventario sea trazable desde el primer día.
     *
     * @param  array<string, mixed>  $datos  atributos validados del modelo
     */
    public function crear(array $datos, ?UploadedFile $imagen, int $idUsuario): Producto
    {
        return DB::transaction(function () use ($datos, $imagen, $idUsuario) {
            $stockInicial = (int) Arr::pull($datos, 'stock_actual', 0);

            $producto = new Producto($datos);
            $producto->fechaCreacion = now();
            $producto->estado ??= EstadoProducto::ACTIVO->value;
            $producto->stock_actual = 0;

            if ($imagen) {
                $producto->imagen = $this->imagenes->guardar($imagen, self::CARPETA_IMAGENES);
            }

            $producto->save();

            if ($stockInicial > 0) {
                $this->inventario->registrarMovimiento(new RegistrarMovimientoData(
                    idProducto: $producto->id_producto,
                    idUsuario: $idUsuario,
                    tipo: TipoMovimientoCodigo::ENTRADA,
                    cantidad: $stockInicial,
                    motivo: 'Stock inicial al registrar el producto',
                ));
            }

            return $producto->refresh();
        });
    }

    /**
     * Actualiza el producto. Si cambia el stock, la diferencia se registra
     * como AJUSTE en el Kardex en lugar de sobrescribir el valor a ciegas.
     *
     * @param  array<string, mixed>  $datos
     */
    public function actualizar(Producto $producto, array $datos, ?UploadedFile $imagen, int $idUsuario): Producto
    {
        return DB::transaction(function () use ($producto, $datos, $imagen, $idUsuario) {
            $nuevoStock = Arr::pull($datos, 'stock_actual');

            $producto->fill($datos);

            if ($imagen) {
                $producto->imagen = $this->imagenes->reemplazar($producto->imagen, $imagen, self::CARPETA_IMAGENES);
            }

            $producto->save();

            if ($nuevoStock !== null && (int) $nuevoStock !== $producto->stock_actual) {
                $this->inventario->registrarMovimiento(new RegistrarMovimientoData(
                    idProducto: $producto->id_producto,
                    idUsuario: $idUsuario,
                    tipo: TipoMovimientoCodigo::AJUSTE,
                    cantidad: (int) $nuevoStock,
                    motivo: 'Ajuste desde la edición del producto',
                ));
            }

            return $producto->refresh();
        });
    }

    public function alternarEstado(Producto $producto): Producto
    {
        $producto->alternarEstado();
        $producto->save();

        return $producto;
    }

    /**
     * Elimina el producto. Si tiene historial (ventas o Kardex) no se borra
     * para no romper la integridad contable: se marca como inactivo.
     *
     * @return bool true si se eliminó físicamente, false si solo se inactivó
     */
    public function eliminar(Producto $producto): bool
    {
        if ($producto->tieneHistorialDeVentas() || $producto->movimientos()->exists()) {
            $producto->estado = EstadoProducto::INACTIVO->value;
            $producto->save();

            return false;
        }

        $this->imagenes->eliminar($producto->imagen);
        $producto->delete();

        return true;
    }
}
