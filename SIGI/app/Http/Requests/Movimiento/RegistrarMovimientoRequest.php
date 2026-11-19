<?php

namespace App\Http\Requests\Movimiento;

use App\DTOs\RegistrarMovimientoData;
use App\Enums\TipoMovimientoCodigo;
use App\Models\TipoMovimiento;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegistrarMovimientoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_producto' => ['required', 'integer', 'exists:productos,id_producto'],
            'tipo' => ['nullable', 'string', Rule::in(TipoMovimientoCodigo::valoresAceptados())],
            'id_tipo_movimiento' => ['nullable', 'integer', 'exists:tipos_movimiento,id_tipo'],
            // En un AJUSTE la cantidad es el conteo físico, por eso puede ser 0.
            'cantidad' => ['required', 'integer', $this->tipo() === TipoMovimientoCodigo::AJUSTE ? 'min:0' : 'min:1'],
            'motivo' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Resuelve el tipo: campo `tipo` → `id_tipo_movimiento` → ENTRADA por defecto.
     */
    public function tipo(): TipoMovimientoCodigo
    {
        if ($this->filled('tipo') && TipoMovimientoCodigo::tryFrom(strtoupper($this->input('tipo')))) {
            return TipoMovimientoCodigo::desdeTexto($this->input('tipo'));
        }

        if ($this->filled('id_tipo_movimiento')) {
            $codigo = TipoMovimiento::find($this->input('id_tipo_movimiento'))?->codigo;
            if ($codigo && ($tipo = TipoMovimientoCodigo::tryFrom($codigo))) {
                return $tipo;
            }
        }

        return TipoMovimientoCodigo::ENTRADA;
    }

    public function toData(): RegistrarMovimientoData
    {
        return new RegistrarMovimientoData(
            idProducto: (int) $this->validated('id_producto'),
            idUsuario: (int) $this->user()->id_usuario,
            tipo: $this->tipo(),
            cantidad: (int) $this->validated('cantidad'),
            motivo: $this->validated('motivo'),
        );
    }
}
