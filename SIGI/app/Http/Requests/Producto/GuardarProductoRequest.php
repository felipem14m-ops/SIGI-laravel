<?php

namespace App\Http\Requests\Producto;

use App\Enums\EstadoProducto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validación para registrar un producto (web y API).
 *
 * Acepta los nombres de campo del formulario web (`codigo`, `alerta_minima`,
 * `stock_inicial`) y los normaliza a las columnas reales del modelo.
 */
class GuardarProductoRequest extends FormRequest
{
    /** Alias del formulario web → atributo del modelo. */
    protected const ALIAS = [
        'codigo' => 'codigoUnico',
        'alerta_minima' => 'stock_minimo',
        'stock_inicial' => 'stock_actual',
    ];

    public function authorize(): bool
    {
        return true; // la autorización por rol la aplica el middleware `role`
    }

    protected function prepareForValidation(): void
    {
        $normalizados = [];
        foreach (self::ALIAS as $alias => $atributo) {
            if ($this->has($alias) && ! $this->has($atributo)) {
                $normalizados[$atributo] = $this->input($alias);
            }
        }
        $this->merge($normalizados);
    }

    public function rules(): array
    {
        return [
            'codigoUnico' => ['required', 'string', 'max:50', Rule::unique('productos', 'codigoUnico')->ignore($this->idIgnorado(), 'id_producto')],
            'nombre' => ['required', 'string', 'max:150', Rule::unique('productos', 'nombre')->ignore($this->idIgnorado(), 'id_producto')],
            'descripcion' => ['nullable', 'string'],
            'id_categoria' => ['required', 'integer', 'exists:categorias,id_categoria'],
            'id_proveedor' => ['nullable', 'integer', 'exists:proveedores,id_proveedor'],
            'precio_venta' => ['required', 'numeric', 'min:0'],
            'precio_costo' => ['nullable', 'numeric', 'min:0'],
            'stock_minimo' => ['nullable', 'integer', 'min:0'],
            'stock_actual' => ['nullable', 'integer', 'min:0'],
            'fechaVencimiento' => ['nullable', 'date'],
            'estado' => ['nullable', Rule::enum(EstadoProducto::class)],
            'imagen' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:3072'],
        ];
    }

    public function attributes(): array
    {
        return [
            'codigoUnico' => 'código',
            'id_categoria' => 'categoría',
            'id_proveedor' => 'proveedor',
            'precio_venta' => 'precio de venta',
            'precio_costo' => 'precio de costo',
            'stock_minimo' => 'stock mínimo',
            'stock_actual' => 'stock',
            'fechaVencimiento' => 'fecha de vencimiento',
        ];
    }

    /**
     * Atributos listos para el modelo (sin la imagen, que se gestiona aparte).
     *
     * @return array<string, mixed>
     */
    public function datos(): array
    {
        $datos = collect($this->validated())->except('imagen');

        // Campos numéricos no nulos en BD: un input vacío equivale a 0.
        foreach (['precio_costo', 'stock_minimo'] as $campo) {
            if ($datos->has($campo) && $datos->get($campo) === null) {
                $datos->put($campo, 0);
            }
        }

        if ($datos->has('estado') && $datos->get('estado') === null) {
            $datos->forget('estado');
        }

        return $datos->all();
    }

    protected function idIgnorado(): ?int
    {
        return null;
    }
}
