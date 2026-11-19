<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\EliminacionNoPermitidaException;
use App\Http\Controllers\Controller;
use App\Http\Requests\MetodoPago\GuardarMetodoPagoRequest;
use App\Models\MetodoPago;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Módulo de Configuraciones → métodos de pago.
 */
class MetodoPagoController extends Controller
{
    public function index(): View
    {
        return view('Admin.Configuraciones.ListasdeConfiguraciones', [
            'metodos' => MetodoPago::withCount('ventas')->orderBy('nombre')->get(),
        ]);
    }

    public function store(GuardarMetodoPagoRequest $request): RedirectResponse
    {
        MetodoPago::create([...$request->validated(), 'activo' => true]);

        return $this->volver('Método de pago agregado correctamente.');
    }

    public function update(GuardarMetodoPagoRequest $request, MetodoPago $metodo): RedirectResponse
    {
        $metodo->update($request->validated());

        return $this->volver('Método de pago actualizado correctamente.');
    }

    public function alternarEstado(MetodoPago $metodo): RedirectResponse
    {
        $metodo->alternarEstado();
        $metodo->save();

        return $this->volver('Estado del método actualizado correctamente.');
    }

    public function destroy(MetodoPago $metodo): RedirectResponse
    {
        if ($metodo->ventas()->exists()) {
            throw EliminacionNoPermitidaException::porDependencias(
                "el método \"{$metodo->nombre}\"",
                'ya se usó en ventas. Desactívalo en su lugar.',
            );
        }

        $metodo->delete();

        return $this->volver('Método de pago eliminado.');
    }

    private function volver(string $mensaje): RedirectResponse
    {
        return to_route('configuraciones.index', ['tab' => 'metodos'])->with('success', $mensaje);
    }
}
