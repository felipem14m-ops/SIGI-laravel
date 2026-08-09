<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetodoPago extends Model
{
    protected $table      = 'metodos_pago';
    protected $primaryKey = 'id_metodo';

    protected $fillable = [
        'nombre',
        'activo',
    ];

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'id_metodo', 'id_metodo');
    }
}
