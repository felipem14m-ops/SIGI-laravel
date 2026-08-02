<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table      = 'venta';
    protected $primaryKey = 'id_venta';

    protected $fillable = [
        'total',
        'id_usuario',
        'id_metodo',
        'fecha_venta',
        'total',
        'estado'
    ];

    protected $casts = [
        'fecha_venta' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor', 'id_proveedor');
    }
}
