<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Producto\ActualizarProductoRequest;
use App\Http\Requests\Producto\GuardarProductoRequest;
use App\Http\Resources\ProductoResource;
use App\Models\Producto;
use App\Services\ProductoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductoController extends ApiController
{
    public function __construct(private readonly ProductoService $productos) {}

    /**
     * Filtros: search, id_categoria, estado, solo_disponibles, paginate, per_page.
     */
    public function index(Request $request): JsonResponse
    {
        $consulta = Producto::with(['categoria:id_categoria,nombre', 'proveedor:id_proveedor,nombre'])
            ->buscar($request->search)
            ->when($request->filled('id_categoria'), fn ($q) => $q->where('id_categoria', $request->id_categoria))
            ->when($request->filled('estado'), fn ($q) => $q->where('estado', $request->estado))
            ->when($request->boolean('solo_disponibles'), fn ($q) => $q->disponibles())
            ->orderBy('nombre');

        $resultado = $request->boolean('paginate')
            ? $consulta->paginate($this->porPagina())
            : $consulta->get();

        return $this->exito(ProductoResource::collection($resultado));
    }

    public function show(Producto $producto): JsonResponse
    {
        return $this->exito(new ProductoResource($producto->load(['categoria', 'proveedor'])));
    }

    /** Búsqueda inmediata por código de barras (escáner de la app). */
    public function porCodigo(string $codigo): JsonResponse
    {
        $producto = Producto::with(['categoria', 'proveedor'])->where('codigoUnico', $codigo)->first();

        return $producto
            ? $this->exito(new ProductoResource($producto))
            : $this->error("No se encontró ningún producto con el código: {$codigo}", 404);
    }

    public function store(GuardarProductoRequest $request): JsonResponse
    {
        $producto = $this->productos->crear($request->datos(), $request->file('imagen'), $request->user()->id_usuario);

        return $this->creado(new ProductoResource($producto), 'Producto creado exitosamente.');
    }

    public function update(ActualizarProductoRequest $request, Producto $producto): JsonResponse
    {
        $producto = $this->productos->actualizar($producto, $request->datos(), $request->file('imagen'), $request->user()->id_usuario);

        return $this->exito(new ProductoResource($producto), 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto): JsonResponse
    {
        $mensaje = $this->productos->eliminar($producto)
            ? 'Producto eliminado exitosamente.'
            : 'El producto posee historial; ha sido marcado como "inactivo".';

        return $this->exito(mensaje: $mensaje);
    }
}
