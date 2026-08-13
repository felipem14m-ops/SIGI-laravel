<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Venta;
use App\Models\MetodoPago;
use Carbon\Carbon;

class EmpleadoMisVentasController extends Controller
{
    /**
     * Historial detallado de ventas del empleado con filtros y visibilidad completa (total, cambio, factura modal).
     */
    public function index(Request $request)
    {
        $userId = Auth::id();

        $query = Venta::where('id_usuario', $userId)
            ->with(['metodo', 'usuario', 'detalles.producto']);

        // Filtro por Fecha
        if ($request->filled('fecha')) {
            $query->whereDate('fecha_venta', $request->fecha)
                ->orWhereDate('created_at', $request->fecha);
        }

        // Filtro por Método de Pago
        if ($request->filled('metodo')) {
            $query->whereHas('metodo', function ($q) use ($request) {
                $q->where('nombre', $request->metodo);
            });
        }

        $misVentas = $query->latest()->paginate(10)->appends($request->all());
        $metodosPago = MetodoPago::where('activo', 1)->get();

        return view('Empleado.Mis ventas.MisVentas', compact(
            'misVentas',
            'metodosPago'
        ));
    }
}
