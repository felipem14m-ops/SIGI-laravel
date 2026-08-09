<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\MetodoPago;



class HistorialVentasController extends Controller
{
    public function index(Request $request)
    {
        $query = Venta::with([
                    'usuario',
                    'metodo',
                    'detalles'
                 ])
                 ->latest();

        if ($request->filled('fecha')) {
            $query->where(function ($q) use ($request) {
                $q->whereDate('fecha_venta', $request->fecha)
                  ->orWhereDate('created_at', $request->fecha);
            });
        }

        if ($request->filled('cajero')) {
            $query->whereHas('usuario', function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->cajero . '%');
            });
        }

        if ($request->filled('metodo')) {
            $query->whereHas('metodo', function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->metodo . '%');
            });
        }

        $ventas = $query
                    ->paginate(15)
                    ->withQueryString();

        $metodosPago = MetodoPago::orderBy('nombre')->get();

        return view('Admin.Venta.HistorialdeVentas', compact('ventas', 'metodosPago'));
    }

    public function show($id)
    {
        $venta = Venta::with(['usuario', 'metodo', 'detalles.producto'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'venta' => $venta
        ]);
    }

    public function factura($id)
    {
        $venta = Venta::with(['usuario', 'metodo', 'detalles.producto'])->findOrFail($id);

        return view('Admin.Venta.facturaPOS', compact('venta'));
    }

    public function exportarPdf()
    {
        // Exportar PDF
    }

    public function exportarExcel()
    {
        // Exportar Excel
    }
}
