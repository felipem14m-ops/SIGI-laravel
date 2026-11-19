<?php

namespace App\Http\Requests\Categoria;

use App\Models\Categoria;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuardarCategoriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:80', Rule::unique('categorias', 'nombre')->ignore($this->idIgnorado(), 'id_categoria')],
            'descripcion' => ['nullable', 'string'],
            'activa' => ['sometimes', 'boolean'],
            'imagen' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
        ];
    }

    /** @return array<string, mixed> */
    public function datos(): array
    {
        return collect($this->validated())->except('imagen')->all();
    }

    protected function idIgnorado(): ?int
    {
        $categoria = $this->route('categoria');

        return match (true) {
            $categoria instanceof Categoria => $categoria->id_categoria,
            $categoria !== null => (int) $categoria,
            default => null,
        };
    }
}
