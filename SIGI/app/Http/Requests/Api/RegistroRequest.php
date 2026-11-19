<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Auto-registro desde la app móvil. SIEMPRE crea un Empleado:
 * el rol NO se acepta desde el cliente (antes cualquiera podía
 * registrarse como Administrador enviando `id_rol: 1`).
 */
class RegistroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['password' => $this->input('password') ?? $this->input('contrasena')]);
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:100'],
            'numeroIdentificacion' => ['required', 'string', 'max:20', 'unique:users,numeroIdentificacion'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }

    public function messages(): array
    {
        return ['password.required' => 'La contraseña es requerida (campo "password" o "contrasena").'];
    }
}
