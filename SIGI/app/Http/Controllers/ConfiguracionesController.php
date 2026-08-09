<?php

namespace App\Http\Controllers;

use App\Models\MetodoPago;
use Illuminate\Http\Request;

class ConfiguracionesController extends Controller
{
    /**
     * Muestra el listado de métodos de pago y la vista de configuraciones.
     */
    public function index()
    {
        $metodos = MetodoPago::all();
        return view('Admin.Configuraciones.ListasdeConfiguraciones', compact('metodos'));
    }

    /**
     * (Reservado para futura vista de creación independiente.)
     */
    public function create()
    {
        return redirect()->route('configuraciones.index');
    }

    /**
     * Guarda un nuevo método de pago.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50|unique:metodos_pago,nombre',
        ]);

        MetodoPago::create([
            'nombre' => $request->nombre,
            'activo' => 1,
        ]);

        return redirect()->route('configuraciones.index', ['tab' => 'metodos'])
            ->with('success', 'Método de pago agregado correctamente.');
    }

    /**
     * Actualiza el nombre y/o el estado activo de un método de pago.
     * Si viene el campo "toggle_activo", solo invierte el estado.
     * Si viene el campo "nombre", actualiza el nombre.
     */
    public function update(Request $request, $id)
    {
        $metodo = MetodoPago::findOrFail($id);

        if ($request->has('toggle_activo')) {
            // Solo cambiar el estado activo/inactivo
            $metodo->activo = $metodo->activo ? 0 : 1;
            $metodo->save();

            return redirect()->route('configuraciones.index', ['tab' => 'metodos'])
                ->with('success', 'Estado del método actualizado correctamente.');
        }

        // Actualizar nombre
        $request->validate([
            'nombre' => "required|string|max:50|unique:metodos_pago,nombre,{$id},id_metodo",
        ]);

        $metodo->update(['nombre' => $request->nombre]);

        return redirect()->route('configuraciones.index', ['tab' => 'metodos'])
            ->with('success', 'Método de pago actualizado correctamente.');
    }

    /**
     * Elimina un método de pago.
     */
    public function destroy($id)
    {
        $metodo = MetodoPago::findOrFail($id);
        $metodo->delete();

        return redirect()->route('configuraciones.index', ['tab' => 'metodos'])
            ->with('success', 'Método de pago eliminado.');
    }
}