<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movimiento;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MovimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $productos = Producto::with(['categoria', 'proveedor'])
            ->where('estado', 'activo')
            ->get();

        $query = Movimiento::with(['producto', 'usuario'])
            ->latest('fecha')
            ->latest('id_movimiento');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('producto', function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('codigoUnico', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tipo')) {
            $query->where('origen', $request->tipo);
        }

        $movimientos = $query->paginate(15)->withQueryString();

        return view('Admin.Movimiento.ListasdeMovimiento', compact('movimientos', 'productos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_producto' => ['required', 'exists:productos,id_producto'],
            'tipo'        => ['required', 'in:entrada,salida,ajuste'],
            'cantidad'    => ['required', 'integer', 'min:1'],
            'motivo'      => ['nullable', 'string', 'max:255'],
        ]);

        try {
            DB::transaction(function () use ($request) {
                $producto = Producto::findOrFail($request->id_producto);
                $stockAnterior = $producto->stock_actual;
                $cantidad = (int) $request->cantidad;
                $tipo = $request->tipo;

                if ($tipo === 'entrada') {
                    $stockResultante = $stockAnterior + $cantidad;
                } else if ($tipo === 'salida') {
                    if ($stockAnterior < $cantidad) {
                        throw new \Exception("Stock insuficiente. El stock actual es de {$stockAnterior} unidades.");
                    }
                    $stockResultante = $stockAnterior - $cantidad;
                } else { // ajuste
                    $stockResultante = $cantidad;
                }

                // Guardar movimiento
                Movimiento::create([
                    'id_producto'        => $producto->id_producto,
                    'id_usuario'         => Auth::id() ?? 1,
                    'id_tipo_movimiento' => $tipo === 'entrada' ? 1 : ($tipo === 'salida' ? 2 : 3),
                    'cantidad'           => $cantidad,
                    'stock_anterior'     => $stockAnterior,
                    'stock_resultante'   => $stockResultante,
                    'origen'             => $tipo,
                    'motivo'             => $request->motivo ?? 'Ajuste manual de inventario',
                    'fecha'              => now(),
                ]);

                // Actualizar stock del producto
                $producto->stock_actual = $stockResultante;
                $producto->save();
            });

            return redirect()
                ->route('movimientos.index')
                ->with('success', '¡Movimiento de inventario registrado con éxito!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
}

