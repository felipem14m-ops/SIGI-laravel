<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

class AlertasController extends Controller
{
    public function index(Request $request)
    {
        // Consulta base: Productos donde el stock actual sea menor o igual al stock mínimo
        $query = Producto::with(['categoria', 'proveedor'])
            ->whereColumn('stock_actual', '<=', 'stock_minimo');

        // Filtro por Nombre o Código
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('codigoUnico', 'like', "%{$search}%");
            });
        }

        // Filtro por Tipo de Alerta (agotado: stock = 0, bajo: stock > 0 y stock <= stock_minimo)
        if ($request->filled('tipo_alerta')) {
            if ($request->tipo_alerta === 'agotado') {
                $query->where('stock_actual', '<=', 0);
            } elseif ($request->tipo_alerta === 'bajo') {
                $query->where('stock_actual', '>', 0);
            }
        }

        // Filtro por Estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $alertas = $query->paginate(10)->withQueryString();

        // Contadores para Tarjetas Resumen
        $totalAgotados = Producto::where('stock_actual', '<=', 0)->count();
        $totalBajoStock = Producto::whereColumn('stock_actual', '<=', 'stock_minimo')
            ->where('stock_actual', '>', 0)
            ->count();
        $totalAlertas = $totalAgotados + $totalBajoStock;

        return view('Admin.Alertas.ListasdeAlerta', compact('alertas', 'totalAgotados', 'totalBajoStock', 'totalAlertas'));
    }
}
