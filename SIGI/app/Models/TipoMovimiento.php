<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoMovimiento extends Model
{
    protected $table      = 'tipos_movimiento';
    protected $primaryKey = 'id_tipo';

    protected $fillable = [
        'codigo',
        'nombre',
        'signo',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */
    public function movimientos()
    {
        return $this->hasMany(Movimiento::class, 'id_tipo_movimiento', 'id_tipo');
    }
}
