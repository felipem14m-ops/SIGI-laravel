<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $productos  = Producto::with(['categoria', 'proveedor'])->get();
        $categorias = Categoria::where('activa', true)->orderBy('nombre')->get();
        $proveedores = Proveedor::where('activo', true)->orderBy('nombre')->get();
        return view('Admin.Productos.ListasProducto', compact('productos', 'categorias', 'proveedores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo'            => ['required', 'string', 'max:50', 'unique:productos,codigoUnico'],
            'nombre'            => ['required', 'string', 'max:150', 'unique:productos,nombre'],
            'descripcion'       => ['nullable', 'string'],
            'id_categoria'      => ['required', 'integer', 'exists:categorias,id_categoria'],
            'id_proveedor'      => ['nullable', 'integer', 'exists:proveedores,id_proveedor'],
            'precio_venta'      => ['required', 'numeric', 'min:0'],
            'precio_costo'      => ['nullable', 'numeric', 'min:0'],
            'alerta_minima'     => ['nullable', 'integer', 'min:0'],
            'stock_inicial'     => ['nullable', 'integer', 'min:0'],
            'fechaVencimiento'  => ['nullable', 'date'],
            'estado'            => ['nullable', 'string', 'in:activo,inactivo,agotado'],
            'imagen'            => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
        ]);

        $producto = new Producto();
        $producto->codigoUnico      = $validated['codigo'];
        $producto->nombre           = $validated['nombre'];
        $producto->descripcion      = $validated['descripcion'] ?? null;
        $producto->id_categoria     = $validated['id_categoria'];
        $producto->id_proveedor     = $validated['id_proveedor'] ?? null;
        $producto->precio_venta     = $validated['precio_venta'];
        $producto->precio_costo     = $validated['precio_costo'] ?? 0;
        $producto->stock_minimo     = $validated['alerta_minima'] ?? 0;
        $producto->stock_actual     = $validated['stock_inicial'] ?? 0;
        $producto->fechaCreacion    = now();
        $producto->fechaVencimiento = $validated['fechaVencimiento'] ?? null;
        $producto->estado           = $validated['estado'] ?? 'activo';

        if ($request->hasFile('imagen')) {
            $producto->imagen = $request->file('imagen')->store('productos', 'public');
        }

        $producto->save();

        return redirect()
            ->route('productos.index')
            ->with('success', '¡Producto registrado exitosamente!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $producto = Producto::findOrFail($id);

        // ── Toggle rápido de estado ──────────────────────────────────────────
        if ($request->has('toggle_status')) {
            $producto->estado = ($producto->estado === 'activo') ? 'inactivo' : 'activo';
            $producto->save();

            $statusText = $producto->estado === 'activo' ? 'activado' : 'desactivado';
            return redirect()
                ->route('productos.index')
                ->with('success', "Producto \"{$producto->nombre}\" {$statusText} con éxito.");
        }

        // ── Edición completa ────────────────────────────────────────────────
        $validated = $request->validate([
            'codigo'            => ['required', 'string', 'max:50', "unique:productos,codigoUnico,{$producto->id_producto},id_producto"],
            'nombre'            => ['required', 'string', 'max:150', "unique:productos,nombre,{$producto->id_producto},id_producto"],
            'id_categoria'      => ['required', 'integer', 'exists:categorias,id_categoria'],
            'id_proveedor'      => ['nullable', 'integer', 'exists:proveedores,id_proveedor'],
            'precio_venta'      => ['required', 'numeric', 'min:0'],
            'precio_costo'      => ['nullable', 'numeric', 'min:0'],
            'stock_minimo'      => ['nullable', 'integer', 'min:0'],
            'stock_actual'      => ['nullable', 'integer', 'min:0'],
            'fechaVencimiento'  => ['nullable', 'date'],
            'descripcion'       => ['nullable', 'string'],
            'estado'            => ['required', 'string', 'in:activo,inactivo,agotado'],
            'imagen'            => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
        ]);

        $producto->codigoUnico      = $validated['codigo'];
        $producto->nombre           = $validated['nombre'];
        $producto->id_categoria     = $validated['id_categoria'];
        $producto->id_proveedor     = $validated['id_proveedor'] ?? null;
        $producto->precio_venta     = $validated['precio_venta'];
        $producto->precio_costo     = $validated['precio_costo'] ?? 0;
        $producto->stock_minimo     = $validated['stock_minimo'] ?? 0;
        $producto->stock_actual     = $validated['stock_actual'] ?? 0;
        $producto->fechaVencimiento = $validated['fechaVencimiento'] ?? null;
        $producto->descripcion      = $validated['descripcion'] ?? null;
        $producto->estado           = $validated['estado'];

        if ($request->hasFile('imagen')) {
            // Borrar imagen anterior si existe
            if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $producto->imagen = $request->file('imagen')->store('productos', 'public');
        }

        $producto->save();

        return redirect()
            ->route('productos.index')
            ->with('success', '¡Producto actualizado con éxito!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $producto = Producto::findOrFail($id);

        // Borrar imagen del disco antes de eliminar el registro
        if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
            Storage::disk('public')->delete($producto->imagen);
        }

        $producto->delete();

        return redirect()
            ->route('productos.index')
            ->with('success', 'Producto eliminado exitosamente.');
    }
}
