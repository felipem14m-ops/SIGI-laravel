<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table      = 'categorias';
    protected $primaryKey = 'id_categoria';

    protected $fillable = [
        'nombre',
        'descripcion',
        'imagen',
        'activa',   // ← columna real en la BD
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */
    public function productos()
    {
        return $this->hasMany(Producto::class, 'id_categoria', 'id_categoria');
    }
}
