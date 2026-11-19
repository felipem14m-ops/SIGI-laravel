<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\EliminacionNoPermitidaException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Proveedor\ActualizarProveedorRequest;
use App\Http\Requests\Proveedor\GuardarProveedorRequest;
use App\Models\Proveedor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProveedorController extends Controller
{
    public function index(Request $request): View
    {
        $proveedores = Proveedor::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where(fn ($q) => $q->where('nombre', 'like', "%{$request->search}%")
                    ->orWhere('empresa', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%"));
            })
            ->orderBy('nombre')
            ->get();

        return view('Admin.Proveedores.ListasdeProvedores', compact('proveedores'));
    }

    public function store(GuardarProveedorRequest $request): RedirectResponse
    {
        Proveedor::create($request->validated());

        return to_route('proveedores.index')->with('success', '¡Proveedor registrado exitosamente!');
    }

    public function update(ActualizarProveedorRequest $request, Proveedor $proveedor): RedirectResponse
    {
        $proveedor->update($request->validated());

        return to_route('proveedores.index')->with('success', '¡Proveedor actualizado con éxito!');
    }

    public function alternarEstado(Proveedor $proveedor): RedirectResponse
    {
        $proveedor->alternarEstado();
        $proveedor->save();
        $texto = $proveedor->activo ? 'activado' : 'desactivado';

        return to_route('proveedores.index')->with('success', "Proveedor {$texto} con éxito.");
    }

    public function destroy(Proveedor $proveedor): RedirectResponse
    {
        if ($proveedor->productos()->exists()) {
            throw EliminacionNoPermitidaException::porDependencias(
                "el proveedor \"{$proveedor->nombre}\"",
                'tiene productos asociados. Desactívalo en su lugar.',
            );
        }

        $proveedor->delete();

        return to_route('proveedores.index')->with('success', 'Proveedor eliminado exitosamente.');
    }
}
