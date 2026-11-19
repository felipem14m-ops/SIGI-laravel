<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use RuntimeException;

/**
 * Excepción base para violaciones de reglas de negocio.
 *
 * Se renderiza sola: JSON 422 para peticiones API/AJAX y redirección
 * con mensaje flash `error` para peticiones web. Así los controladores
 * no necesitan bloques try/catch repetidos.
 */
abstract class ReglaNegocioException extends RuntimeException
{
    protected int $status = 422;

    public function render(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $this->getMessage(),
            ], $this->status);
        }

        return back()->withInput()->with('error', $this->getMessage());
    }
}
