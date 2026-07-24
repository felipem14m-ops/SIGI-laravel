<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';

    protected $fillable = [
        'id_proveedor',
        'nombre',
        'telefono',
        'email',
        'empresa',
        'activo',
        'fecha_registro',
        'activo'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    public function users()
    {
        return $this->hasMany(User::class, 'id_rol');
    }
}
