<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Categoria\ActualizarCategoriaRequest;
use App\Http\Requests\Categoria\GuardarCategoriaRequest;
use App\Models\Categoria;
use App\Services\CategoriaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoriaController extends Controller
{
    public function __construct(private readonly CategoriaService $categorias) {}

    public function index(Request $request): View
    {
        $categorias = Categoria::withCount('productos')
            ->when($request->filled('search'), fn ($q) => $q->where('nombre', 'like', '%'.$request->search.'%'))
            ->when($request->filled('status'), fn ($q) => $q->where('activa', (bool) $request->status))
            ->orderBy('nombre')
            ->get();

        return view('Admin.Categorias.ListasdeCategorias', compact('categorias'));
    }

    public function store(GuardarCategoriaRequest $request): RedirectResponse
    {
        $this->categorias->crear($request->datos(), $request->file('imagen'));

        return to_route('categorias.index')->with('success', '¡Categoría registrada exitosamente!');
    }

    public function update(ActualizarCategoriaRequest $request, Categoria $categoria): RedirectResponse
    {
        $this->categorias->actualizar($categoria, $request->datos(), $request->file('imagen'));

        return to_route('categorias.index')->with('success', '¡Categoría actualizada con éxito!');
    }

    public function alternarEstado(Categoria $categoria): RedirectResponse
    {
        $this->categorias->alternarEstado($categoria);
        $texto = $categoria->activa ? 'activada' : 'desactivada';

        return to_route('categorias.index')->with('success', "Categoría \"{$categoria->nombre}\" {$texto} con éxito.");
    }

    public function destroy(Categoria $categoria): RedirectResponse
    {
        $this->categorias->eliminar($categoria);

        return to_route('categorias.index')->with('success', 'Categoría eliminada exitosamente.');
    }
}
