<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoriasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categorias = Categoria::all();
        return view('Admin.Categorias.ListasdeCategorias', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'      => ['required', 'string', 'max:80', 'unique:categorias,nombre'],
            'descripcion' => ['nullable', 'string'],
            'imagen'      => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
        ]);

        $categoria = new Categoria();
        $categoria->nombre      = $validated['nombre'];
        $categoria->descripcion = $validated['descripcion'] ?? null;
        $categoria->activa      = 1; // ← columna real en la BD

        if ($request->hasFile('imagen')) {
            // store() devuelve la ruta relativa dentro del disco 'public'
            $categoria->imagen = $request->file('imagen')->store('categorias', 'public');
        }

        $categoria->save();

        return redirect()
            ->route('categorias.index')
            ->with('success', '¡Categoría registrada exitosamente!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $categoria = Categoria::findOrFail($id);

        // ── Toggle rápido de estado ──────────────────────────────────────────
        if ($request->has('toggle_status')) {
            $categoria->activa = !$categoria->activa; // ← columna real en la BD
            $categoria->save();

            $statusText = $categoria->activa ? 'activada' : 'desactivada';
            return redirect()
                ->route('categorias.index')
                ->with('success', "Categoría \"{$categoria->nombre}\" {$statusText} con éxito.");
        }

        // ── Edición completa ────────────────────────────────────────────────
        $validated = $request->validate([
            'nombre'      => ['required', 'string', 'max:80', "unique:categorias,nombre,{$categoria->id_categoria},id_categoria"],
            'descripcion' => ['nullable', 'string'],
            'activa'      => ['required', 'integer', 'in:0,1'], // ← columna real en la BD
            'imagen'      => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
        ]);

        $categoria->nombre      = $validated['nombre'];
        $categoria->descripcion = $validated['descripcion'] ?? null;
        $categoria->activa      = $validated['activa'];

        if ($request->hasFile('imagen')) {
            // Borrar imagen anterior si existe
            if ($categoria->imagen && Storage::disk('public')->exists($categoria->imagen)) {
                Storage::disk('public')->delete($categoria->imagen);
            }
            $categoria->imagen = $request->file('imagen')->store('categorias', 'public');
        }

        $categoria->save();

        return redirect()
            ->route('categorias.index')
            ->with('success', '¡Categoría actualizada con éxito!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $categoria = Categoria::findOrFail($id);

        // Borrar imagen del disco antes de eliminar el registro
        if ($categoria->imagen && Storage::disk('public')->exists($categoria->imagen)) {
            Storage::disk('public')->delete($categoria->imagen);
        }

        $categoria->delete();

        return redirect()
            ->route('categorias.index')
            ->with('success', 'Categoría eliminada exitosamente.');
    }
}
