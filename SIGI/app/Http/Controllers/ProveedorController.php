<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedor;


class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $proveedores = Proveedor::all();
        return view('Admin.Proveedores.ListasdeProvedores', compact('proveedores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'   => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:proveedores,email'],
            'telefono' => ['required', 'string', 'max:50'],
            'empresa'  => ['required', 'string', 'max:255'],
        ]);

        $proveedor = new Proveedor();
        $proveedor->nombre   = $request->post('nombre');
        $proveedor->email    = $request->post('email');
        $proveedor->telefono = $request->post('telefono');
        $proveedor->empresa  = $request->post('empresa');
        $proveedor->activo   = 1;

        $proveedor->save();

        return redirect()
            ->route('proveedores.index')
            ->with('success', '¡Proveedor registrado exitosamente!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $proveedor = Proveedor::findOrFail($id);

        if ($request->has('toggle_status')) {
            $proveedor->activo = $proveedor->activo ? 0 : 1;
            $proveedor->save();

            $statusText = $proveedor->activo ? 'activado' : 'desactivado';
            return redirect()->route('proveedores.index')->with('success', "Proveedor {$statusText} con éxito.");
        }

        $request->validate([
            'nombre'   => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:proveedores,email,' . $id . ',id_proveedor'],
            'telefono' => ['required', 'string', 'max:50'],
            'empresa'  => ['required', 'string', 'max:255'],
            'activo'   => ['required', 'integer', 'in:0,1'],
        ]);

        $proveedor->nombre   = $request->post('nombre');
        $proveedor->email    = $request->post('email');
        $proveedor->telefono = $request->post('telefono');
        $proveedor->empresa  = $request->post('empresa');
        $proveedor->activo   = $request->post('activo');

        $proveedor->save();

        return redirect()
            ->route('proveedores.index')
            ->with('success', '¡Proveedor actualizado con éxito!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $proveedor->delete();

        return redirect()->route('proveedores.index')->with('success', 'Proveedor eliminado exitosamente.');
    }
}
