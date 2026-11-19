<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Venta;

/**
 * El administrador ve todas las ventas; el empleado solo las suyas.
 */
class VentaPolicy
{
    public function viewAny(User $usuario): bool
    {
        return true; // el listado se filtra por cajero en el controlador
    }

    public function view(User $usuario, Venta $venta): bool
    {
        return $usuario->esAdministrador() || $venta->perteneceA($usuario);
    }

    public function create(User $usuario): bool
    {
        return $usuario->activo;
    }
}
