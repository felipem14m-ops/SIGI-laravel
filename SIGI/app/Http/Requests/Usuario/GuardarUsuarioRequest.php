<?php

namespace App\Http\Requests\Usuario;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuardarUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->idIgnorado();

        return [
            'nombre' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', Rule::unique('users', 'email')->ignore($id, 'id_usuario')],
            'id_rol' => ['required', 'integer', 'exists:roles,id_rol'],
            'numeroIdentificacion' => ['required', 'string', 'max:20', Rule::unique('users', 'numeroIdentificacion')->ignore($id, 'id_usuario')],
            'contrasena' => [$id ? 'nullable' : 'required', 'string', 'min:8'],
            'activo' => [$id ? 'required' : 'sometimes', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'id_rol' => 'rol',
            'numeroIdentificacion' => 'número de identificación',
            'contrasena' => 'contraseña',
        ];
    }

    /** @return array<string, mixed> */
    public function datos(): array
    {
        // La contraseña se cifra con el cast `hashed` del modelo.
        return collect($this->validated())->reject(fn ($v, $k) => $k === 'contrasena' && blank($v))->all();
    }

    protected function idIgnorado(): ?int
    {
        $usuario = $this->route('usuario');

        return match (true) {
            $usuario instanceof User => $usuario->id_usuario,
            $usuario !== null => (int) $usuario,
            default => null,
        };
    }
}
