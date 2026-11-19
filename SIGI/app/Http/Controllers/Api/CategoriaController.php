<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Categoria\ActualizarCategoriaRequest;
use App\Http\Requests\Categoria\GuardarCategoriaRequest;
use App\Http\Resources\CategoriaResource;
use App\Models\Categoria;
use App\Services\CategoriaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoriaController extends ApiController
{
    public function __construct(private readonly CategoriaService $categorias) {}

    public function index(Request $request): JsonResponse
    {
        $categorias = Categoria::withCount('productos')
            ->when($request->boolean('solo_activas', true), fn ($q) => $q->activas())
            ->when($request->filled('search'), fn ($q) => $q->where('nombre', 'like', "%{$request->search}%"))
            ->orderBy('nombre')
            ->get();

        return $this->exito(CategoriaResource::collection($categorias));
    }

    public function show(Categoria $categoria): JsonResponse
    {
        $categoria->load(['productos' => fn ($q) => $q->activos()]);

        return $this->exito(new CategoriaResource($categoria));
    }

    public function store(GuardarCategoriaRequest $request): JsonResponse
    {
        $categoria = $this->categorias->crear($request->datos(), $request->file('imagen'));

        return $this->creado(new CategoriaResource($categoria), 'Categoría creada exitosamente.');
    }

    public function update(ActualizarCategoriaRequest $request, Categoria $categoria): JsonResponse
    {
        $categoria = $this->categorias->actualizar($categoria, $request->datos(), $request->file('imagen'));

        return $this->exito(new CategoriaResource($categoria), 'Categoría actualizada correctamente.');
    }

    public function destroy(Categoria $categoria): JsonResponse
    {
        $this->categorias->eliminar($categoria);

        return $this->exito(mensaje: 'Categoría eliminada exitosamente.');
    }
}
