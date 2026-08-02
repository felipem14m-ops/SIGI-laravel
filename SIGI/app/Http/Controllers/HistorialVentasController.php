<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;



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
            $query->whereDate('created_at', $request->fecha);
        }

        if ($request->filled('cajero')) {
            $query->whereHas('usuario', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->cajero . '%');
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

        return view('Admin.Venta.HistorialdeVentas',compact('ventas')
        );
    }

    public function show(Venta $venta)
    {
        // Ver detalle de la venta
    }

    public function factura(Venta $venta)
    {
        // Imprimir factura
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
