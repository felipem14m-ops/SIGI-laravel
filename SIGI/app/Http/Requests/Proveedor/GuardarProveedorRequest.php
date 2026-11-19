<?php

namespace App\Http\Requests\Proveedor;

use App\Models\Proveedor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuardarProveedorRequest extends FormRequest
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
            'email' => ['required', 'string', 'email', 'max:100', Rule::unique('proveedores', 'email')->ignore($id, 'id_proveedor')],
            'telefono' => ['required', 'string', 'max:20'],
            'empresa' => ['required', 'string', 'max:100'],
            'activo' => [$id ? 'required' : 'sometimes', 'boolean'],
        ];
    }

    protected function idIgnorado(): ?int
    {
        $proveedor = $this->route('proveedor');

        return match (true) {
            $proveedor instanceof Proveedor => $proveedor->id_proveedor,
            $proveedor !== null => (int) $proveedor,
            default => null,
        };
    }
}
