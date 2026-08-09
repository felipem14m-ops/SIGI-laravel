<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    protected $table      = 'movimiento_inventario';
    protected $primaryKey = 'id_movimiento';

    protected $fillable = [
        'id_producto',
        'id_usuario',
        'id_tipo_movimiento',
        'cantidad',
        'stock_anterior',
        'stock_resultante',
        'motivo',
        'fecha'
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    public function tipoMovimiento()
    {
        return $this->belongsTo(TipoMovimiento::class, 'id_tipo_movimiento', 'id_tipo');
    }
}
