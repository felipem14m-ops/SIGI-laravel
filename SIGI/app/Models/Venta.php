<?php

namespace App\Models;

use App\Enums\EstadoVenta;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Venta extends Model
{
    protected $table = 'venta';

    protected $primaryKey = 'id_venta';

    protected $fillable = [
        'id_usuario',
        'id_metodo',
        'fecha_venta',
        'total',
        'monto_recibido',
        'cambio',
        'estado',
    ];

    protected $attributes = [
        'estado' => 'completada',
        'total' => 0,
        'cambio' => 0,
    ];

    protected function casts(): array
    {
        return [
            'fecha_venta' => 'datetime',
            'total' => 'decimal:2',
            'monto_recibido' => 'decimal:2',
            'cambio' => 'decimal:2',
        ];
    }

    /* ---------------------------------------------------------------------
     | Relaciones
     * -------------------------------------------------------------------*/

    /** Cajero que procesó la venta. */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    /** Método de pago utilizado. */
    public function metodo(): BelongsTo
    {
        return $this->belongsTo(MetodoPago::class, 'id_metodo', 'id_metodo');
    }

    /** Líneas de detalle de la venta. */
    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleVenta::class, 'id_venta', 'id_venta');
    }

    /* ---------------------------------------------------------------------
     | Scopes
     * -------------------------------------------------------------------*/

    public function scopeCompletadas(Builder $query): Builder
    {
        return $query->where('estado', EstadoVenta::COMPLETADA->value);
    }

    public function scopeDelDia(Builder $query, Carbon|string|null $fecha = null): Builder
    {
        $fecha ??= today();

        return $query->whereDate('fecha_venta', $fecha);
    }

    public function scopeDeCajero(Builder $query, User|int|null $usuario): Builder
    {
        if ($usuario === null) {
            return $query;
        }

        return $query->where('id_usuario', $usuario instanceof User ? $usuario->id_usuario : $usuario);
    }

    public function scopeConDetalleCompleto(Builder $query): Builder
    {
        return $query->with(['usuario', 'metodo', 'detalles.producto']);
    }

    /* ---------------------------------------------------------------------
     | Comportamiento
     * -------------------------------------------------------------------*/

    public function perteneceA(User $usuario): bool
    {
        return (int) $this->id_usuario === (int) $usuario->id_usuario;
    }

    public function cantidadArticulos(): int
    {
        return (int) $this->detalles->sum('cantidad');
    }
}
