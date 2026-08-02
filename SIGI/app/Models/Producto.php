<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table      = 'productos';
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

    protected $casts = [
        'fechaVencimiento' => 'date',
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
