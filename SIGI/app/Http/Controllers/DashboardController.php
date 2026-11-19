<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Panel principal: muestra el dashboard que corresponde al rol del usuario.
 */
class DashboardController extends Controller
{
    public function __construct(private readonly DashboardService $dashboard) {}

    public function __invoke(Request $request): View
    {
        $usuario = $request->user();

        return $usuario->esAdministrador()
            ? view('Admin.dashboard', $this->dashboard->resumenAdministrador())
            : view('Empleado.dashboard', $this->dashboard->resumenEmpleado($usuario));
    }
}
