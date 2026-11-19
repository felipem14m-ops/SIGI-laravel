<?php

namespace App\Models;

use App\Enums\TipoMovimientoCodigo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoMovimiento extends Model
{
    protected $table = 'tipos_movimiento';

    protected $primaryKey = 'id_tipo';

    protected $fillable = [
        'codigo',
        'nombre',
        'signo',
    ];

    public function movimientos(): HasMany
    {
        return $this->hasMany(Movimiento::class, 'id_tipo_movimiento', 'id_tipo');
    }

    /**
     * Obtiene (o crea si falta el catálogo) el registro de un tipo de movimiento.
     */
    public static function para(TipoMovimientoCodigo $codigo): self
    {
        return static::firstOrCreate(
            ['codigo' => $codigo->value],
            ['nombre' => $codigo->nombre(), 'signo' => $codigo->signo()],
        );
    }

    public function codigoEnum(): TipoMovimientoCodigo
    {
        return TipoMovimientoCodigo::from($this->codigo);
    }
}
