<?php

namespace App\Http\Requests\Venta;

use App\DTOs\ItemVentaData;
use App\DTOs\RegistrarVentaData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegistrarVentaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'integer', 'exists:productos,id_producto'],
            'items.*.cantidad' => ['required', 'integer', 'min:1'],
            'metodo_pago_id' => ['required', 'integer', Rule::exists('metodos_pago', 'id_metodo')->where('activo', 1)],
            'monto_recibido' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function attributes(): array
    {
        return [
            'items' => 'carrito',
            'items.*.id' => 'producto',
            'items.*.cantidad' => 'cantidad',
            'metodo_pago_id' => 'método de pago',
            'monto_recibido' => 'monto recibido',
        ];
    }

    /** Convierte la petición en el DTO que consume VentaService. */
    public function toData(string $canal): RegistrarVentaData
    {
        return new RegistrarVentaData(
            idUsuario: (int) $this->user()->id_usuario,
            idMetodoPago: (int) $this->validated('metodo_pago_id'),
            items: array_map(ItemVentaData::desdeArray(...), $this->validated('items')),
            montoRecibido: $this->filled('monto_recibido') ? (float) $this->validated('monto_recibido') : null,
            canal: $canal,
        );
    }
}
