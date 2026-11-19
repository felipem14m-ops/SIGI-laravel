<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Movimiento\RegistrarMovimientoRequest;
use App\Models\Movimiento;
use App\Models\Producto;
use App\Services\InventarioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Kardex: consulta y registro manual de movimientos de inventario.
 */
class MovimientoController extends Controller
{
    public function __construct(private readonly InventarioService $inventario) {}

    public function index(Request $request): View
    {
        $movimientos = Movimiento::with(['producto', 'usuario', 'tipoMovimiento'])
            ->buscarProducto($request->search)
            ->deTipo($request->tipo)
            ->latest('id_movimiento')
            ->paginate(15)
            ->withQueryString();

        return view('Admin.Movimiento.ListasdeMovimiento', [
            'movimientos' => $movimientos,
            'productos' => Producto::where('estado', '!=', 'inactivo')->orderBy('nombre')->get(),
        ]);
    }

    public function store(RegistrarMovimientoRequest $request): RedirectResponse|JsonResponse
    {
        $this->inventario->registrarMovimiento($request->toData());

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Movimiento registrado correctamente.']);
        }

        return back()->with('success', 'Movimiento de inventario registrado correctamente.');
    }
}
