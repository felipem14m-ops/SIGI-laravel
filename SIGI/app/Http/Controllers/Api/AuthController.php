<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Registro de nuevo usuario (Empleado por defecto)
     */
    public function register(Request $request)
    {
        $request->validate([
            'nombre'               => 'required|string|max:255',
            'numeroIdentificacion' => 'required|string|max:20|unique:users,numeroIdentificacion',
            'email'                => 'required|string|email|max:255|unique:users,email',
            'password'             => 'nullable|string|min:6',
            'contrasena'           => 'nullable|string|min:6',
            'id_rol'               => 'nullable|integer|exists:roles,id_rol',
        ]);

        $password = $request->input('password') ?? $request->input('contrasena');
        if (!$password) {
            return response()->json([
                'success' => false,
                'message' => 'La contraseña es requerida (campo "password" o "contrasena").'
            ], 422);
        }

        $user = User::create([
            'id_rol'               => $request->input('id_rol', 2), // 2 = Empleado
            'nombre'               => $request->nombre,
            'numeroIdentificacion' => $request->numeroIdentificacion,
            'email'                => $request->email,
            'contrasena'           => Hash::make($password),
            'activo'               => 1,
        ]);

        $token = $user->createToken('flutter_token')->plainTextToken;
        $user->load('role');

        return response()->json([
            'success'      => true,
            'message'      => 'Usuario registrado exitosamente.',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => [
                'id_usuario'           => $user->id_usuario,
                'nombre'               => $user->nombre,
                'email'                => $user->email,
                'numeroIdentificacion' => $user->numeroIdentificacion,
                'id_rol'               => $user->id_rol,
                'rol_nombre'           => $user->role->nombre ?? ($user->id_rol == 1 ? 'Administrador' : 'Empleado'),
                'activo'               => (bool)$user->activo,
            ]
        ], 201);
    }

    /**
     * Inicio de sesión para Flutter / Postman
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'      => 'required|string|email',
            'password'   => 'nullable|string',
            'contrasena' => 'nullable|string',
        ]);

        $password = $request->input('password') ?? $request->input('contrasena');
        if (!$password) {
            return response()->json([
                'success' => false,
                'message' => 'La contraseña es requerida (campo "password" o "contrasena").'
            ], 422);
        }

        if (!Auth::attempt(['email' => $request->email, 'password' => $password])) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas. Verifica tu correo y contraseña.'
            ], 401);
        }

        $user = User::with('role')->where('email', $request->email)->firstOrFail();

        // Verificar si la cuenta está activa
        if (!$user->activo) {
            return response()->json([
                'success' => false,
                'message' => 'Tu cuenta se encuentra inactiva. Contacta al administrador.'
            ], 403);
        }

        $token = $user->createToken('flutter_token')->plainTextToken;

        return response()->json([
            'success'      => true,
            'message'      => 'Inicio de sesión exitoso.',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => [
                'id_usuario'           => $user->id_usuario,
                'nombre'               => $user->nombre,
                'email'                => $user->email,
                'numeroIdentificacion' => $user->numeroIdentificacion,
                'id_rol'               => $user->id_rol,
                'rol_nombre'           => $user->role->nombre ?? ($user->id_rol == 1 ? 'Administrador' : 'Empleado'),
                'activo'               => (bool)$user->activo,
            ]
        ]);
    }

    /**
     * Información del usuario autenticado actual
     */
    public function me(Request $request)
    {
        $user = $request->user()->load('role');

        return response()->json([
            'success' => true,
            'user'    => [
                'id_usuario'           => $user->id_usuario,
                'nombre'               => $user->nombre,
                'email'                => $user->email,
                'numeroIdentificacion' => $user->numeroIdentificacion,
                'id_rol'               => $user->id_rol,
                'rol_nombre'           => $user->role->nombre ?? ($user->id_rol == 1 ? 'Administrador' : 'Empleado'),
                'activo'               => (bool)$user->activo,
            ]
        ]);
    }

    /**
     * Cierre de sesión (revocación de token actual)
     */
    public function logout(Request $request)
    {
        if ($request->user() && $request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada exitosamente.'
        ]);
    }
}
