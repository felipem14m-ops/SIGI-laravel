<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Producto\ActualizarProductoRequest;
use App\Http\Requests\Producto\GuardarProductoRequest;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Services\ProductoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductoController extends Controller
{
    public function __construct(private readonly ProductoService $productos) {}

    public function index(): View
    {
        return view('Admin.Productos.ListasProducto', [
            'productos' => Producto::with(['categoria', 'proveedor'])->latest('id_producto')->get(),
            'categorias' => Categoria::activas()->orderBy('nombre')->get(),
            'proveedores' => Proveedor::activos()->orderBy('nombre')->get(),
        ]);
    }

    public function store(GuardarProductoRequest $request): RedirectResponse
    {
        $this->productos->crear($request->datos(), $request->file('imagen'), $request->user()->id_usuario);

        return to_route('productos.index')->with('success', '¡Producto registrado exitosamente!');
    }

    public function update(ActualizarProductoRequest $request, Producto $producto): RedirectResponse
    {
        $this->productos->actualizar($producto, $request->datos(), $request->file('imagen'), $request->user()->id_usuario);

        return to_route('productos.index')->with('success', '¡Producto actualizado con éxito!');
    }

    public function alternarEstado(Producto $producto): RedirectResponse
    {
        $this->productos->alternarEstado($producto);
        $texto = $producto->estaActivo() ? 'activado' : 'desactivado';

        return to_route('productos.index')->with('success', "Producto \"{$producto->nombre}\" {$texto} con éxito.");
    }

    public function destroy(Producto $producto): RedirectResponse
    {
        $mensaje = $this->productos->eliminar($producto)
            ? 'Producto eliminado exitosamente.'
            : 'El producto tiene historial de ventas o movimientos; se marcó como inactivo para conservar la trazabilidad.';

        return to_route('productos.index')->with('success', $mensaje);
    }
}
