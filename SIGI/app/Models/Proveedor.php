<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedores';

    // Antes faltaba: Eloquent buscaba la columna `id` y findOrFail() fallaba.
    protected $primaryKey = 'id_proveedor';

    protected $fillable = [
        'nombre',
        'telefono',
        'email',
        'empresa',
        'activo',
        'fecha_registro',
    ];

    protected $attributes = [
        'activo' => true,
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'fecha_registro' => 'datetime',
        ];
    }

    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class, 'id_proveedor', 'id_proveedor');
    }

    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('activo', true);
    }

    public function alternarEstado(): void
    {
        $this->activo = ! $this->activo;
    }
}
