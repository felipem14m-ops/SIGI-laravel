<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Proveedor;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\MetodoPago;
use App\Models\Movimiento;
use App\Models\TipoMovimiento;

class ApiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 1. DASHBOARD & MÉTRICAS
    |--------------------------------------------------------------------------
    */
    public function dashboard()
    {
        $hoy = Carbon::today();

        $totalProductos = Producto::where('estado', 'activo')->count();
        $alertasStock   = Producto::whereColumn('stock_actual', '<=', 'stock_minimo')->count();

        $ventasHoyQuery = Venta::where(function ($q) use ($hoy) {
            $q->whereDate('fecha_venta', $hoy)->orWhereDate('created_at', $hoy);
        })->where('estado', 'completada');

        $ventasHoyCount = (clone $ventasHoyQuery)->count();
        $ingresosHoy    = (clone $ventasHoyQuery)->sum('total');

        $ultimasVentas = Venta::with(['metodo:id_metodo,nombre', 'usuario:id_usuario,nombre'])
            ->latest('id_venta')
            ->take(5)
            ->get();

        $productosBajoStock = Producto::whereColumn('stock_actual', '<=', 'stock_minimo')
            ->select('id_producto', 'codigoUnico', 'nombre', 'stock_actual', 'stock_minimo', 'precio_venta', 'imagen')
            ->take(5)
            ->get()
            ->map(function ($p) {
                $p->imagen_url = $p->imagen ? asset('storage/' . $p->imagen) : null;
                $p->tipo_alerta = $p->stock_actual <= 0 ? 'agotado' : 'bajo_stock';
                return $p;
            });

        return response()->json([
            'success' => true,
            'data'    => [
                'total_productos'      => $totalProductos,
                'alertas_stock'        => $alertasStock,
                'ventas_hoy_cantidad'  => $ventasHoyCount,
                'ingresos_hoy_total'   => (float)$ingresosHoy,
                'ultimas_ventas'       => $ultimasVentas,
                'productos_bajo_stock' => $productosBajoStock,
            ]
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | 2. PRODUCTOS
    |--------------------------------------------------------------------------
    */

    /**
     * Listado de productos con filtros (categoría, estado, búsqueda, disponibles)
     */
    public function productos(Request $request)
    {
        $query = Producto::with(['categoria:id_categoria,nombre', 'proveedor:id_proveedor,nombre']);

        // Búsqueda por texto (nombre o código de barra)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('codigoUnico', 'like', "%{$search}%");
            });
        }

        // Filtro por categoría
        if ($request->filled('id_categoria')) {
            $query->where('id_categoria', $request->id_categoria);
        }

        // Filtro por estado (activo, inactivo, agotado)
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtro solo disponibles (stock > 0 y activo) - ideal para POS móvil
        if ($request->boolean('solo_disponibles')) {
            $query->where('estado', 'activo')->where('stock_actual', '>', 0);
        }

        $query->orderBy('nombre', 'asc');

        if ($request->boolean('paginate')) {
            $productos = $query->paginate($request->input('per_page', 20));
            $productos->getCollection()->transform(function ($p) {
                $p->imagen_url = $p->imagen ? asset('storage/' . $p->imagen) : null;
                return $p;
            });
        } else {
            $productos = $query->get()->map(function ($p) {
                $p->imagen_url = $p->imagen ? asset('storage/' . $p->imagen) : null;
                return $p;
            });
        }

        return response()->json([
            'success' => true,
            'data'    => $productos
        ]);
    }

    /**
     * Detalle de un producto por ID
     */
    public function producto($id)
    {
        $producto = Producto::with(['categoria', 'proveedor'])->find($id);

        if (!$producto) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado.'
            ], 404);
        }

        $producto->imagen_url = $producto->imagen ? asset('storage/' . $producto->imagen) : null;

        return response()->json([
            'success' => true,
            'data'    => $producto
        ]);
    }

    /**
     * Búsqueda rápida por código de barras (escáner de cámara en Flutter)
     */
    public function productoPorCodigo($codigo)
    {
        $producto = Producto::with(['categoria', 'proveedor'])
            ->where('codigoUnico', $codigo)
            ->first();

        if (!$producto) {
            return response()->json([
                'success' => false,
                'message' => "No se encontró ningún producto con el código: {$codigo}"
            ], 404);
        }

        $producto->imagen_url = $producto->imagen ? asset('storage/' . $producto->imagen) : null;

        return response()->json([
            'success' => true,
            'data'    => $producto
        ]);
    }

    /**
     * Registrar nuevo producto
     */
    public function guardarProducto(Request $request)
    {
        $validated = $request->validate([
            'codigoUnico'      => 'required|string|max:50|unique:productos,codigoUnico',
            'nombre'           => 'required|string|max:150|unique:productos,nombre',
            'descripcion'      => 'nullable|string',
            'id_categoria'     => 'required|integer|exists:categorias,id_categoria',
            'id_proveedor'     => 'nullable|integer|exists:proveedores,id_proveedor',
            'precio_venta'     => 'required|numeric|min:0',
            'precio_costo'     => 'nullable|numeric|min:0',
            'stock_minimo'     => 'nullable|integer|min:0',
            'stock_actual'     => 'nullable|integer|min:0',
            'fechaVencimiento' => 'nullable|date',
            'estado'           => 'nullable|string|in:activo,inactivo,agotado',
            'imagen'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $producto = new Producto();
        $producto->codigoUnico      = $validated['codigoUnico'];
        $producto->nombre           = $validated['nombre'];
        $producto->descripcion      = $validated['descripcion'] ?? null;
        $producto->id_categoria     = $validated['id_categoria'];
        $producto->id_proveedor     = $validated['id_proveedor'] ?? null;
        $producto->precio_venta     = $validated['precio_venta'];
        $producto->precio_costo     = $validated['precio_costo'] ?? 0;
        $producto->stock_minimo     = $validated['stock_minimo'] ?? 0;
        $producto->stock_actual     = $validated['stock_actual'] ?? 0;
        $producto->fechaCreacion    = now();
        $producto->fechaVencimiento = $validated['fechaVencimiento'] ?? null;
        $producto->estado           = $validated['estado'] ?? 'activo';

        if ($request->hasFile('imagen')) {
            $producto->imagen = $request->file('imagen')->store('productos', 'public');
        }

        $producto->save();
        $producto->imagen_url = $producto->imagen ? asset('storage/' . $producto->imagen) : null;

        return response()->json([
            'success' => true,
            'message' => 'Producto creado exitosamente.',
            'data'    => $producto
        ], 201);
    }

    /**
     * Actualizar producto existente
     */
    public function actualizarProducto(Request $request, $id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado.'
            ], 404);
        }

        $validated = $request->validate([
            'codigoUnico'      => "nullable|string|max:50|unique:productos,codigoUnico,{$producto->id_producto},id_producto",
            'nombre'           => "nullable|string|max:150|unique:productos,nombre,{$producto->id_producto},id_producto",
            'descripcion'      => 'nullable|string',
            'id_categoria'     => 'nullable|integer|exists:categorias,id_categoria',
            'id_proveedor'     => 'nullable|integer|exists:proveedores,id_proveedor',
            'precio_venta'     => 'nullable|numeric|min:0',
            'precio_costo'     => 'nullable|numeric|min:0',
            'stock_minimo'     => 'nullable|integer|min:0',
            'stock_actual'     => 'nullable|integer|min:0',
            'fechaVencimiento' => 'nullable|date',
            'estado'           => 'nullable|string|in:activo,inactivo,agotado',
            'imagen'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $producto->fill($request->only([
            'codigoUnico', 'nombre', 'descripcion', 'id_categoria', 'id_proveedor',
            'precio_venta', 'precio_costo', 'stock_minimo', 'stock_actual',
            'fechaVencimiento', 'estado'
        ]));

        if ($request->hasFile('imagen')) {
            if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $producto->imagen = $request->file('imagen')->store('productos', 'public');
        }

        $producto->save();
        $producto->imagen_url = $producto->imagen ? asset('storage/' . $producto->imagen) : null;

        return response()->json([
            'success' => true,
            'message' => 'Producto actualizado correctamente.',
            'data'    => $producto
        ]);
    }

    /**
     * Eliminar producto
     */
    public function eliminarProducto($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado.'
            ], 404);
        }

        // Si tiene ventas asociadas, se recomienda marcarlo inactivo en vez de borrarlo
        $tieneVentas = DetalleVenta::where('id_producto', $id)->exists();
        if ($tieneVentas) {
            $producto->estado = 'inactivo';
            $producto->save();

            return response()->json([
                'success' => true,
                'message' => 'El producto posee historial de ventas; ha sido marcado como "inactivo".'
            ]);
        }

        if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
            Storage::disk('public')->delete($producto->imagen);
        }

        $producto->delete();

        return response()->json([
            'success' => true,
            'message' => 'Producto eliminado exitosamente.'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | 3. CATEGORÍAS
    |--------------------------------------------------------------------------
    */

    public function categorias(Request $request)
    {
        $query = Categoria::withCount('productos');

        if ($request->boolean('solo_activas', true)) {
            $query->where('activa', 1);
        }

        if ($request->filled('search')) {
            $query->where('nombre', 'like', "%{$request->search}%");
        }

        $categorias = $query->orderBy('nombre')->get()->map(function ($c) {
            $c->imagen_url = $c->imagen ? asset('storage/' . $c->imagen) : null;
            return $c;
        });

        return response()->json([
            'success' => true,
            'data'    => $categorias
        ]);
    }

    public function categoria($id)
    {
        $categoria = Categoria::with(['productos' => function ($q) {
            $q->where('estado', 'activo');
        }])->find($id);

        if (!$categoria) {
            return response()->json([
                'success' => false,
                'message' => 'Categoría no encontrada.'
            ], 404);
        }

        $categoria->imagen_url = $categoria->imagen ? asset('storage/' . $categoria->imagen) : null;

        return response()->json([
            'success' => true,
            'data'    => $categoria
        ]);
    }

    public function guardarCategoria(Request $request)
    {
        $validated = $request->validate([
            'nombre'      => 'required|string|max:80|unique:categorias,nombre',
            'descripcion' => 'nullable|string',
            'imagen'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $categoria = new Categoria();
        $categoria->nombre      = $validated['nombre'];
        $categoria->descripcion = $validated['descripcion'] ?? null;
        $categoria->activa      = 1;

        if ($request->hasFile('imagen')) {
            $categoria->imagen = $request->file('imagen')->store('categorias', 'public');
        }

        $categoria->save();
        $categoria->imagen_url = $categoria->imagen ? asset('storage/' . $categoria->imagen) : null;

        return response()->json([
            'success' => true,
            'message' => 'Categoría creada exitosamente.',
            'data'    => $categoria
        ], 201);
    }

    public function actualizarCategoria(Request $request, $id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json([
                'success' => false,
                'message' => 'Categoría no encontrada.'
            ], 404);
        }

        $validated = $request->validate([
            'nombre'      => "nullable|string|max:80|unique:categorias,nombre,{$categoria->id_categoria},id_categoria",
            'descripcion' => 'nullable|string',
            'activa'      => 'nullable|boolean',
            'imagen'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $categoria->fill($request->only(['nombre', 'descripcion', 'activa']));

        if ($request->hasFile('imagen')) {
            if ($categoria->imagen && Storage::disk('public')->exists($categoria->imagen)) {
                Storage::disk('public')->delete($categoria->imagen);
            }
            $categoria->imagen = $request->file('imagen')->store('categorias', 'public');
        }

        $categoria->save();
        $categoria->imagen_url = $categoria->imagen ? asset('storage/' . $categoria->imagen) : null;

        return response()->json([
            'success' => true,
            'message' => 'Categoría actualizada correctamente.',
            'data'    => $categoria
        ]);
    }

    public function eliminarCategoria($id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json([
                'success' => false,
                'message' => 'Categoría no encontrada.'
            ], 404);
        }

        if ($categoria->productos()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar la categoría porque tiene productos asignados.'
            ], 422);
        }

        if ($categoria->imagen && Storage::disk('public')->exists($categoria->imagen)) {
            Storage::disk('public')->delete($categoria->imagen);
        }

        $categoria->delete();

        return response()->json([
            'success' => true,
            'message' => 'Categoría eliminada exitosamente.'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | 4. VENTAS / TERMINAL POS MÓVIL
    |--------------------------------------------------------------------------
    */

    /**
     * Listado e historial de ventas
     */
    public function ventas(Request $request)
    {
        $query = Venta::with([
            'usuario:id_usuario,nombre,email',
            'metodo:id_metodo,nombre',
            'detalles.producto:id_producto,nombre,codigoUnico'
        ])->orderBy('id_venta', 'desc');

        // Filtro por fecha específica
        if ($request->filled('fecha')) {
            $query->whereDate('fecha_venta', $request->fecha);
        }

        // Filtro por cajero / usuario
        if ($request->filled('id_usuario')) {
            $query->where('id_usuario', $request->id_usuario);
        }

        // Filtro por método de pago
        if ($request->filled('id_metodo')) {
            $query->where('id_metodo', $request->id_metodo);
        }

        $ventas = $query->paginate($request->input('per_page', 15));

        return response()->json([
            'success' => true,
            'data'    => $ventas
        ]);
    }

    /**
     * Detalle completo de una venta (recibo / ticket POS)
     */
    public function venta($id)
    {
        $venta = Venta::with([
            'usuario:id_usuario,nombre,email',
            'metodo:id_metodo,nombre',
            'detalles.producto:id_producto,nombre,codigoUnico,precio_venta,imagen'
        ])->find($id);

        if (!$venta) {
            return response()->json([
                'success' => false,
                'message' => 'Venta no encontrada.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $venta
        ]);
    }

    /**
     * Registrar una venta desde Flutter (con validación de stock, cálculo y Kardex)
     */
    public function guardarVenta(Request $request)
    {
        $request->validate([
            'metodo_pago_id' => 'required|exists:metodos_pago,id_metodo',
            'items'          => 'required|array|min:1',
            'items.*.id'     => 'required|exists:productos,id_producto',
            'items.*.cantidad' => 'required|integer|min:1',
            'monto_recibido' => 'nullable|numeric|min:0',
        ]);

        try {
            $resultado = DB::transaction(function () use ($request) {
                // Tipo de movimiento de Kardex para salida por venta
                $tipoMovimiento = TipoMovimiento::firstOrCreate(
                    ['codigo' => 'SALIDA'],
                    ['nombre' => 'Salida por Venta', 'signo' => '-']
                );

                $totalCalculado = 0;
                $usuarioId = auth()->id() ?? $request->input('id_usuario', 1);

                // Crear cabecera de la venta temporalmente con total 0
                $venta = Venta::create([
                    'id_usuario'     => $usuarioId,
                    'id_metodo'      => $request->metodo_pago_id,
                    'fecha_venta'    => now(),
                    'total'          => 0,
                    'monto_recibido' => 0,
                    'cambio'         => 0,
                    'estado'         => 'completada'
                ]);

                $detallesCreados = [];

                foreach ($request->items as $item) {
                    $producto = Producto::lockForUpdate()->findOrFail($item['id']);
                    $cantidad = (int)$item['cantidad'];

                    if ($producto->stock_actual < $cantidad) {
                        throw new \Exception("Stock insuficiente para \"{$producto->nombre}\". Disponible: {$producto->stock_actual}, solicitado: {$cantidad}");
                    }

                    $precioUnitario = (float)$producto->precio_venta;
                    $subtotal = $precioUnitario * $cantidad;
                    $totalCalculado += $subtotal;

                    // Crear detalle
                    $detalle = DetalleVenta::create([
                        'id_venta'       => $venta->id_venta,
                        'id_producto'    => $producto->id_producto,
                        'cantidad'       => $cantidad,
                        'precioUnitario' => $precioUnitario,
                        'subtotal'       => $subtotal,
                    ]);

                    $detallesCreados[] = $detalle;

                    // Descontar inventario
                    $stockAnterior = $producto->stock_actual;
                    $producto->stock_actual -= $cantidad;

                    // Si llega a 0, actualizar estado a agotado
                    if ($producto->stock_actual <= 0) {
                        $producto->estado = 'agotado';
                    }
                    $producto->save();

                    // Registrar en Kardex / Movimiento
                    Movimiento::create([
                        'id_producto'        => $producto->id_producto,
                        'id_usuario'         => $usuarioId,
                        'id_tipo_movimiento' => $tipoMovimiento->id_tipo,
                        'cantidad'           => $cantidad,
                        'stock_anterior'     => $stockAnterior,
                        'stock_resultante'   => $producto->stock_actual,
                        'motivo'             => "Venta #{$venta->id_venta} (POS Móvil)",
                        'fecha'              => now()->toDateString(),
                    ]);
                }

                $montoRecibido = $request->filled('monto_recibido')
                    ? (float)$request->monto_recibido
                    : $totalCalculado;

                if ($montoRecibido < $totalCalculado) {
                    throw new \Exception("El monto recibido ($" . number_format($montoRecibido, 2) . ") es menor que el total de la venta ($" . number_format($totalCalculado, 2) . ")");
                }

                $cambio = max(0, $montoRecibido - $totalCalculado);

                // Actualizar venta con totales definitivos
                $venta->update([
                    'total'          => $totalCalculado,
                    'monto_recibido' => $montoRecibido,
                    'cambio'         => $cambio,
                ]);

                $venta->load(['usuario:id_usuario,nombre', 'metodo:id_metodo,nombre', 'detalles.producto:id_producto,nombre,codigoUnico']);

                return $venta;
            });

            return response()->json([
                'success' => true,
                'message' => '¡Venta registrada exitosamente!',
                'data'    => $resultado
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | 5. MÉTODOS DE PAGO
    |--------------------------------------------------------------------------
    */
    public function metodosPago()
    {
        $metodos = MetodoPago::where('activo', 1)->select('id_metodo', 'nombre', 'activo')->get();

        return response()->json([
            'success' => true,
            'data'    => $metodos
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | 6. ALERTAS DE STOCK
    |--------------------------------------------------------------------------
    */
    public function alertas(Request $request)
    {
        $query = Producto::with(['categoria:id_categoria,nombre', 'proveedor:id_proveedor,nombre'])
            ->whereColumn('stock_actual', '<=', 'stock_minimo');

        if ($request->filled('tipo_alerta')) {
            if ($request->tipo_alerta === 'agotado') {
                $query->where('stock_actual', '<=', 0);
            } elseif ($request->tipo_alerta === 'bajo') {
                $query->where('stock_actual', '>', 0);
            }
        }

        $alertas = $query->orderBy('stock_actual', 'asc')->get()->map(function ($p) {
            $p->imagen_url = $p->imagen ? asset('storage/' . $p->imagen) : null;
            $p->tipo_alerta = $p->stock_actual <= 0 ? 'agotado' : 'bajo_stock';
            return $p;
        });

        return response()->json([
            'success' => true,
            'total'   => $alertas->count(),
            'data'    => $alertas
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | 7. MOVIMIENTOS DE INVENTARIO (KARDEX)
    |--------------------------------------------------------------------------
    */
    public function movimientos(Request $request)
    {
        $query = Movimiento::with([
            'producto:id_producto,nombre,codigoUnico',
            'usuario:id_usuario,nombre',
            'tipoMovimiento:id_tipo,codigo,nombre,signo'
        ])->orderBy('id_movimiento', 'desc');

        if ($request->filled('id_producto')) {
            $query->where('id_producto', $request->id_producto);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('producto', function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('codigoUnico', 'like', "%{$search}%");
            });
        }

        $movimientos = $query->paginate($request->input('per_page', 20));

        return response()->json([
            'success' => true,
            'data'    => $movimientos
        ]);
    }

    public function guardarMovimiento(Request $request)
    {
        $request->validate([
            'id_producto' => 'required|exists:productos,id_producto',
            'tipo'        => 'required|string|in:ENTRADA,SALIDA,AJUSTE,entrada,salida,ajuste',
            'cantidad'    => 'required|integer|min:1',
            'motivo'      => 'nullable|string|max:255',
        ]);

        try {
            $producto = Producto::findOrFail($request->id_producto);
            $codigoTipo = strtoupper($request->tipo);

            $signo = in_array($codigoTipo, ['SALIDA']) ? '-' : '+';
            $tipoMovimiento = TipoMovimiento::firstOrCreate(
                ['codigo' => $codigoTipo],
                ['nombre' => ucfirst(strtolower($codigoTipo)), 'signo' => $signo]
            );

            $stockAnterior = $producto->stock_actual;
            $cantidad = (int)$request->cantidad;

            if ($codigoTipo === 'SALIDA') {
                if ($producto->stock_actual < $cantidad) {
                    return response()->json([
                        'success' => false,
                        'message' => "Stock insuficiente. Disponible: {$producto->stock_actual}"
                    ], 422);
                }
                $producto->stock_actual -= $cantidad;
            } elseif ($codigoTipo === 'ENTRADA') {
                $producto->stock_actual += $cantidad;
            } elseif ($codigoTipo === 'AJUSTE') {
                $producto->stock_actual = $cantidad;
            }

            // Actualizar estado si se agota o reactiva
            if ($producto->stock_actual <= 0) {
                $producto->estado = 'agotado';
            } elseif ($producto->estado === 'agotado' && $producto->stock_actual > 0) {
                $producto->estado = 'activo';
            }

            $producto->save();

            $movimiento = Movimiento::create([
                'id_producto'        => $producto->id_producto,
                'id_usuario'         => auth()->id() ?? 1,
                'id_tipo_movimiento' => $tipoMovimiento->id_tipo,
                'cantidad'           => $cantidad,
                'stock_anterior'     => $stockAnterior,
                'stock_resultante'   => $producto->stock_actual,
                'motivo'             => $request->motivo ?? 'Movimiento registrado desde App',
                'fecha'              => now()->toDateString(),
            ]);

            $movimiento->load(['producto:id_producto,nombre,codigoUnico', 'tipoMovimiento']);

            return response()->json([
                'success' => true,
                'message' => 'Movimiento registrado correctamente.',
                'data'    => $movimiento
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | 8. PROVEEDORES
    |--------------------------------------------------------------------------
    */
    public function proveedores()
    {
        $proveedores = Proveedor::where('activo', 1)->orderBy('nombre')->get();

        return response()->json([
            'success' => true,
            'data'    => $proveedores
        ]);
    }
}

