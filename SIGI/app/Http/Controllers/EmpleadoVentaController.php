<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\MetodoPago;
use App\Models\Movimiento;
use App\Models\TipoMovimiento;
use App\Models\Categoria;

class EmpleadoVentaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Punto de Venta (Empleado)
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $productos = Producto::with('categoria')
            ->where('estado', 'activo')
            ->where('stock_actual', '>', 0)
            ->get();

        $categorias = Categoria::where('activa', 1)->get();

        $metodos = MetodoPago::where('activo', 1)->get();

        $userId = Auth::id();
        $ingresosHoy = Venta::where('id_usuario', $userId)
            ->whereDate('created_at', today())
            ->sum('total');

        return view('Empleado.Ventas.ventas', compact(
            'productos',
            'categorias',
            'metodos',
            'ingresosHoy'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Registrar Venta
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:productos,id_producto',
            'items.*.cantidad' => 'required|integer|min:1',
            'metodo_pago_id' => 'required|exists:metodos_pago,id_metodo',
        ]);

        try {

            $venta = DB::transaction(function () use ($request) {

                $tipoMovimiento = TipoMovimiento::where('codigo', 'SALIDA')->firstOrFail();

                $total = 0;

                $venta = Venta::create([
                    'id_usuario' => Auth::id(),
                    'id_metodo' => $request->metodo_pago_id,
                    'fecha_venta' => now(),
                    'total' => 0,
                    'estado' => 'completada'
                ]);

                foreach ($request->items as $item) {

                    $producto = Producto::lockForUpdate()
                        ->findOrFail($item['id']);

                    $cantidad = (int) $item['cantidad'];

                    if ($producto->stock_actual < $cantidad) {
                        throw new \Exception('Stock insuficiente para ' . $producto->nombre . '. Disponible: ' . $producto->stock_actual);
                    }

                    $precio = $producto->precio_venta;

                    $subtotal = $precio * $cantidad;

                    $total += $subtotal;

                    DetalleVenta::create([
                        'id_venta' => $venta->id_venta,
                        'id_producto' => $producto->id_producto,
                        'cantidad' => $cantidad,
                        'precioUnitario' => $precio,
                        'subtotal' => $subtotal
                    ]);

                    $stockAnterior = $producto->stock_actual;

                    $producto->stock_actual -= $cantidad;
                    $producto->save();

                    Movimiento::create([

                        'id_producto' => $producto->id_producto,

                        'id_usuario' => Auth::id(),

                        'id_tipo_movimiento' => $tipoMovimiento->id_tipo,

                        'cantidad' => $cantidad,

                        'stock_anterior' => $stockAnterior,

                        'stock_resultante' => $producto->stock_actual,

                        'motivo' => 'Venta ' . $venta->id_venta,

                        'fecha' => now()->toDateString()

                    ]);
                }

                $montoRecibido = $request->filled('monto_recibido') ? (float)$request->monto_recibido : $total;
                $cambioVal     = max(0, $montoRecibido - $total);

                $venta->update([
                    'total'          => $total,
                    'monto_recibido' => $montoRecibido,
                    'cambio'         => $cambioVal,
                ]);

                return $venta;
            });

            return response()->json([
                'success' => true,
                'venta_id' => $venta->id_venta,
                'message' => 'Venta registrada correctamente.'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, string $id) {}

    public function destroy(string $id) {}
}
