<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Registro del Kardex (movimiento de inventario). Es inmutable por diseño:
 * los errores se corrigen con un nuevo movimiento de AJUSTE.
 */
class Movimiento extends Model
{
    protected $table = 'movimiento_inventario';

    protected $primaryKey = 'id_movimiento';

    protected $fillable = [
        'id_producto',
        'id_usuario',
        'id_tipo_movimiento',
        'cantidad',
        'stock_anterior',
        'stock_resultante',
        'origen',
        'motivo',
        'fecha',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'datetime',
            'cantidad' => 'integer',
            'stock_anterior' => 'integer',
            'stock_resultante' => 'integer',
        ];
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    public function tipoMovimiento(): BelongsTo
    {
        return $this->belongsTo(TipoMovimiento::class, 'id_tipo_movimiento', 'id_tipo');
    }

    public function scopeDeProducto(Builder $query, Producto|int|null $producto): Builder
    {
        if ($producto === null) {
            return $query;
        }

        return $query->where('id_producto', $producto instanceof Producto ? $producto->id_producto : $producto);
    }

    public function scopeBuscarProducto(Builder $query, ?string $termino): Builder
    {
        if (blank($termino)) {
            return $query;
        }

        return $query->whereHas('producto', fn (Builder $q) => $q->buscar($termino));
    }

    public function scopeDeTipo(Builder $query, ?string $codigo): Builder
    {
        if (blank($codigo)) {
            return $query;
        }

        return $query->whereHas('tipoMovimiento', fn (Builder $q) => $q->where('codigo', strtoupper($codigo)));
    }
}
