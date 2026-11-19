<?php

namespace App\Http\Requests\MetodoPago;

use App\Models\MetodoPago;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuardarMetodoPagoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $metodo = $this->route('metodo');
        $id = $metodo instanceof MetodoPago ? $metodo->id_metodo : $metodo;

        return [
            'nombre' => ['required', 'string', 'max:50', Rule::unique('metodos_pago', 'nombre')->ignore($id, 'id_metodo')],
        ];
    }
}
