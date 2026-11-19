<?php

namespace App\Models;

use App\Enums\EstadoProducto;
use App\Models\Concerns\TieneImagen;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Producto del inventario.
 *
 * @property int $id_producto
 * @property string $codigoUnico
 * @property string $nombre
 * @property float $precio_venta
 * @property float $precio_costo
 * @property int $stock_minimo
 * @property int $stock_actual
 * @property string $estado
 * @property ?string $imagen
 */
class Producto extends Model
{
    use HasFactory, TieneImagen;

    protected $table = 'productos';

    protected $primaryKey = 'id_producto';

    protected $fillable = [
        'codigoUnico',
        'nombre',
        'descripcion',
        'id_categoria',
        'id_proveedor',
        'precio_venta',
        'precio_costo',
        'stock_minimo',
        'stock_actual',
        'fechaCreacion',
        'fechaVencimiento',
        'estado',
        'imagen',
    ];

    protected $attributes = [
        'estado' => 'activo',
        'stock_minimo' => 0,
        'stock_actual' => 0,
        'precio_costo' => 0,
    ];

    protected function casts(): array
    {
        return [
            'fechaVencimiento' => 'date',
            'precio_venta' => 'decimal:2',
            'precio_costo' => 'decimal:2',
            'stock_minimo' => 'integer',
            'stock_actual' => 'integer',
        ];
    }

    /* ---------------------------------------------------------------------
     | Relaciones
     * -------------------------------------------------------------------*/

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor', 'id_proveedor');
    }

    public function detallesVenta(): HasMany
    {
        return $this->hasMany(DetalleVenta::class, 'id_producto', 'id_producto');
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(Movimiento::class, 'id_producto', 'id_producto');
    }

    /* ---------------------------------------------------------------------
     | Scopes de consulta
     * -------------------------------------------------------------------*/

    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('estado', EstadoProducto::ACTIVO->value);
    }

    /** Productos que se pueden vender ahora mismo en el POS. */
    public function scopeDisponibles(Builder $query): Builder
    {
        return $query->activos()->where('stock_actual', '>', 0);
    }

    /** Productos en alerta: stock actual ≤ stock mínimo. */
    public function scopeEnAlerta(Builder $query): Builder
    {
        return $query->whereColumn('stock_actual', '<=', 'stock_minimo');
    }

    public function scopeAgotados(Builder $query): Builder
    {
        return $query->where('stock_actual', '<=', 0);
    }

    public function scopeBajoStock(Builder $query): Builder
    {
        return $query->enAlerta()->where('stock_actual', '>', 0);
    }

    /** Búsqueda por nombre o código de barras. */
    public function scopeBuscar(Builder $query, ?string $termino): Builder
    {
        if (blank($termino)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($termino) {
            $q->where('nombre', 'like', "%{$termino}%")
                ->orWhere('codigoUnico', 'like', "%{$termino}%");
        });
    }

    /* ---------------------------------------------------------------------
     | Comportamiento de dominio
     * -------------------------------------------------------------------*/

    public function estaActivo(): bool
    {
        return $this->estado === EstadoProducto::ACTIVO->value;
    }

    public function tieneStock(int $cantidad): bool
    {
        return $this->stock_actual >= $cantidad;
    }

    public function estaEnAlerta(): bool
    {
        return $this->stock_actual <= $this->stock_minimo;
    }

    /** Tipo de alerta para la UI/API: `agotado`, `bajo_stock` o null. */
    public function tipoAlerta(): ?string
    {
        return match (true) {
            $this->stock_actual <= 0 => 'agotado',
            $this->estaEnAlerta() => 'bajo_stock',
            default => null,
        };
    }

    public function tieneHistorialDeVentas(): bool
    {
        return $this->detallesVenta()->exists();
    }

    /**
     * Fija un nuevo stock y sincroniza el estado:
     * llega a 0 → `agotado`; se repone estando agotado → `activo`.
     * Un producto `inactivo` conserva su estado (decisión del administrador).
     */
    public function establecerStock(int $nuevoStock): void
    {
        $this->stock_actual = $nuevoStock;

        if ($nuevoStock <= 0 && $this->estaActivo()) {
            $this->estado = EstadoProducto::AGOTADO->value;
        } elseif ($nuevoStock > 0 && $this->estado === EstadoProducto::AGOTADO->value) {
            $this->estado = EstadoProducto::ACTIVO->value;
        }
    }

    /** Alterna entre activo e inactivo (botón rápido del listado). */
    public function alternarEstado(): void
    {
        $this->estado = $this->estaActivo()
            ? EstadoProducto::INACTIVO->value
            : EstadoProducto::ACTIVO->value;
    }
}
