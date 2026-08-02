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
        'origen',
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
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }
}
