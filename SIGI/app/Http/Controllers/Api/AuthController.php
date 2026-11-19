<?php

namespace App\Http\Controllers\Api;

use App\Enums\Rol;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegistroRequest;
use App\Http\Resources\UsuarioResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

/**
 * Autenticación por token (Laravel Sanctum) para la app Flutter y Postman.
 */
class AuthController extends ApiController
{
    private const TOKEN_POR_DEFECTO = 'flutter_token';

    public function register(RegistroRequest $request): JsonResponse
    {
        $usuario = User::create([
            'id_rol' => Rol::EMPLEADO->value,
            'nombre' => $request->validated('nombre'),
            'numeroIdentificacion' => $request->validated('numeroIdentificacion'),
            'email' => $request->validated('email'),
            'contrasena' => $request->validated('password'),
            'activo' => true,
        ]);

        return $this->respuestaConToken($usuario, 'Usuario registrado exitosamente.', Response::HTTP_CREATED);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $usuario = User::with('role')->where('email', $request->validated('email'))->first();

        if (! $usuario || ! Hash::check($request->validated('password'), $usuario->contrasena)) {
            return $this->error('Credenciales incorrectas. Verifica tu correo y contraseña.', Response::HTTP_UNAUTHORIZED);
        }

        if (! $usuario->activo) {
            return $this->error('Tu cuenta se encuentra inactiva. Contacta al administrador.', Response::HTTP_FORBIDDEN);
        }

        return $this->respuestaConToken($usuario, 'Inicio de sesión exitoso.', Response::HTTP_OK, $request->validated('device_name'));
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'user' => new UsuarioResource($request->user()->load('role')),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()?->delete();

        return $this->exito(mensaje: 'Sesión cerrada exitosamente.');
    }

    private function respuestaConToken(User $usuario, string $mensaje, int $status, ?string $dispositivo = null): JsonResponse
    {
        $token = $usuario->createToken($dispositivo ?: self::TOKEN_POR_DEFECTO)->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => $mensaje,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => new UsuarioResource($usuario->load('role')),
        ], $status);
    }
}
