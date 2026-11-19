<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Concerns\RespondeJson;
use App\Http\Controllers\Controller;

/**
 * Controlador base de la API REST: comparte el formato de respuesta.
 */
abstract class ApiController extends Controller
{
    use RespondeJson;

    protected function porPagina(int $porDefecto = 20): int
    {
        return min(max((int) request('per_page', $porDefecto), 1), 100);
    }
}
