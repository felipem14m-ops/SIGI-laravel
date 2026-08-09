<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movimiento;
use App\Models\Producto;
use App\Models\TipoMovimiento;

class MovimientoController extends Controller
{
    public function index(Request $request)
    {
        $query = Movimiento::with(['producto', 'usuario', 'tipoMovimiento'])
            ->orderBy('id_movimiento', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('producto', function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('codigoUnico', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tipo')) {
            $tipoStr = strtoupper($request->tipo);
            $query->whereHas('tipoMovimiento', function ($q) use ($tipoStr) {
                $q->where('codigo', 'like', "%{$tipoStr}%");
            });
        }

        $movimientos = $query->paginate(15)->withQueryString();
        $productos = Producto::where('estado', 'activo')->get();

        return view('Admin.Movimiento.ListasdeMovimiento', compact('movimientos', 'productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_producto' => 'required|exists:productos,id_producto',
            'cantidad' => 'required|integer|min:1',
            'tipo' => 'nullable|string|in:entrada,salida,ajuste',
            'id_tipo_movimiento' => 'nullable|exists:tipos_movimiento,id_tipo',
            'motivo' => 'nullable|string|max:255',
        ]);

        try {
            $producto = Producto::findOrFail($request->id_producto);

            $codigoTipo = 'ENTRADA';
            if ($request->filled('tipo')) {
                $codigoTipo = strtoupper($request->tipo);
            } elseif ($request->filled('id_tipo_movimiento')) {
                $tm = TipoMovimiento::find($request->id_tipo_movimiento);
                if ($tm) {
                    $codigoTipo = $tm->codigo;
                }
            }

            $signo = in_array($codigoTipo, ['SALIDA']) ? '-' : '+';
            $tipoMovimiento = TipoMovimiento::firstOrCreate(
                ['codigo' => $codigoTipo],
                ['nombre' => ucfirst(strtolower($codigoTipo)), 'signo' => $signo]
            );

            $stockAnterior = $producto->stock_actual;
            $cantidad = (int) $request->cantidad;

            if ($codigoTipo === 'SALIDA') {
                if ($producto->stock_actual < $cantidad) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Stock insuficiente. Disponible: ' . $producto->stock_actual
                        ], 422);
                    }
                    return back()->with('error', 'Stock insuficiente. Disponible: ' . $producto->stock_actual);
                }
                $producto->stock_actual -= $cantidad;
            } elseif ($codigoTipo === 'ENTRADA') {
                $producto->stock_actual += $cantidad;
            } elseif ($codigoTipo === 'AJUSTE') {
                $producto->stock_actual = $cantidad;
            }

            $producto->save();

            Movimiento::create([
                'id_producto' => $producto->id_producto,
                'id_usuario' => auth()->id(),
                'id_tipo_movimiento' => $tipoMovimiento->id_tipo,
                'cantidad' => $cantidad,
                'stock_anterior' => $stockAnterior,
                'stock_resultante' => $producto->stock_actual,
                'motivo' => $request->motivo ?? 'Ajuste manual',
                'fecha' => now()->toDateString()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Movimiento registrado correctamente.'
                ]);
            }

            return back()->with('success', 'Movimiento de inventario registrado correctamente.');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Error al registrar movimiento: ' . $e->getMessage());
        }
    }
}