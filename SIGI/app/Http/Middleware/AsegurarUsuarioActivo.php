<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Cierra la sesión (o revoca el token) de un usuario desactivado por el
 * administrador mientras estaba conectado.
 */
class AsegurarUsuarioActivo
{
    public function handle(Request $request, Closure $next): Response
    {
        $usuario = $request->user();

        if ($usuario && ! $usuario->activo) {
            if ($request->expectsJson()) {
                $usuario->currentAccessToken()?->delete();

                return response()->json([
                    'success' => false,
                    'message' => 'Tu cuenta se encuentra inactiva. Contacta al administrador.',
                ], Response::HTTP_FORBIDDEN);
            }

            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Tu cuenta se encuentra inactiva. Contacta al administrador.',
            ]);
        }

        return $next($request);
    }
}
