<?php

namespace App\Http\Middleware;

use App\Enums\Rol;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Restringe una ruta a uno o varios roles.
 *
 * Uso: ->middleware('role:admin') o ->middleware('role:admin,empleado')
 *
 * Antes no existía control por rol: un Empleado podía abrir /usuarios,
 * /configuraciones o eliminar productos escribiendo la URL.
 */
class AsegurarRol
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $usuario = $request->user();

        $permitidos = array_map(Rol::desdeAlias(...), $roles);

        if (! $usuario || ! $usuario->tieneRol(...$permitidos)) {
            abort(Response::HTTP_FORBIDDEN, 'No tienes permisos para acceder a este módulo.');
        }

        return $next($request);
    }
}
