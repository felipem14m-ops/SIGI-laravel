<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AlertaController extends Controller
{
    public function __invoke(Request $request, DashboardService $dashboard): View
    {
        $alertas = Producto::with(['categoria', 'proveedor'])
            ->enAlerta()
            ->buscar($request->search)
            ->when($request->tipo_alerta === 'agotado', fn ($q) => $q->agotados())
            ->when($request->tipo_alerta === 'bajo', fn ($q) => $q->where('stock_actual', '>', 0))
            ->when($request->filled('estado'), fn ($q) => $q->where('estado', $request->estado))
            ->orderBy('stock_actual')
            ->paginate(10)
            ->withQueryString();

        return view('Admin.Alertas.ListasdeAlerta', ['alertas' => $alertas, ...$dashboard->resumenAlertas()]);
    }
}
