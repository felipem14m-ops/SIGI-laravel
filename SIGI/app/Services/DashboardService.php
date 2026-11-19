<?php

namespace App\Services;

use App\Models\Producto;
use App\Models\User;
use App\Models\Venta;

/**
 * Métricas de los paneles principales. Antes estas consultas vivían dentro
 * de las vistas Blade (@php ... @endphp), mezclando presentación y datos.
 */
class DashboardService
{
    /** @return array<string, mixed> */
    public function resumenAdministrador(): array
    {
        $ventasHoy = Venta::query()->completadas()->delDia();

        return [
            'totalProductos' => Producto::query()->activos()->count(),
            'ventasHoy' => (clone $ventasHoy)->count(),
            'ingresosHoy' => (float) (clone $ventasHoy)->sum('total'),
            'alertasStock' => Producto::query()->enAlerta()->count(),
            'totalUsuarios' => User::query()->count(),
            'ventasDelDia' => (clone $ventasHoy)
                ->with(['metodo', 'detalles', 'usuario'])
                ->latest('fecha_venta')
                ->take(10)
                ->get(),
        ];
    }

    /** @return array<string, mixed> */
    public function resumenEmpleado(User $empleado, int $porPagina = 5): array
    {
        $ventasHoy = Venta::query()->completadas()->delDia()->deCajero($empleado);

        $cantidad = (clone $ventasHoy)->count();
        $ingresos = (float) (clone $ventasHoy)->sum('total');

        return [
            'totalVentasHoy' => $cantidad,
            'totalIngresosHoy' => $ingresos,
            'ticketPromedio' => $cantidad > 0 ? $ingresos / $cantidad : 0,
            'misVentasHoy' => (clone $ventasHoy)
                ->with(['metodo', 'detalles'])
                ->latest('fecha_venta')
                ->paginate($porPagina),
        ];
    }

    /** @return array{totalAgotados:int, totalBajoStock:int, totalAlertas:int} */
    public function resumenAlertas(): array
    {
        $agotados = Producto::query()->agotados()->count();
        $bajoStock = Producto::query()->bajoStock()->count();

        return [
            'totalAgotados' => $agotados,
            'totalBajoStock' => $bajoStock,
            'totalAlertas' => $agotados + $bajoStock,
        ];
    }

    public function ingresosDelDia(?User $cajero = null): float
    {
        return (float) Venta::query()->completadas()->delDia()->deCajero($cajero)->sum('total');
    }

    /** Para la API móvil: métricas + últimas ventas + productos en alerta. */
    public function resumenApi(): array
    {
        $ventasHoy = Venta::query()->completadas()->delDia();

        return [
            'total_productos' => Producto::query()->activos()->count(),
            'alertas_stock' => Producto::query()->enAlerta()->count(),
            'ventas_hoy_cantidad' => (clone $ventasHoy)->count(),
            'ingresos_hoy_total' => (float) (clone $ventasHoy)->sum('total'),
            'ultimas_ventas' => Venta::query()
                ->with(['metodo:id_metodo,nombre', 'usuario:id_usuario,nombre'])
                ->latest('id_venta')
                ->take(5)
                ->get(),
            'productos_bajo_stock' => Producto::query()->enAlerta()->take(5)->get()
                ->map(fn (Producto $p) => [
                    'id_producto' => $p->id_producto,
                    'codigoUnico' => $p->codigoUnico,
                    'nombre' => $p->nombre,
                    'stock_actual' => $p->stock_actual,
                    'stock_minimo' => $p->stock_minimo,
                    'precio_venta' => $p->precio_venta,
                    'imagen_url' => $p->imagen_url,
                    'tipo_alerta' => $p->tipoAlerta(),
                ]),
        ];
    }
}
