<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table      = 'venta';
    protected $primaryKey = 'id_venta';

    protected $fillable = [
        'id_usuario',
        'id_metodo',
        'fecha_venta',
        'total',
        'monto_recibido',
        'cambio',
        'estado',
    ];

    protected $casts = [
        'fecha_venta'    => 'datetime',
        'total'          => 'decimal:2',
        'monto_recibido' => 'decimal:2',
        'cambio'         => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    /** Cajero que procesó la venta */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    /** Método de pago utilizado */
    public function metodo()
    {
        return $this->belongsTo(MetodoPago::class, 'id_metodo', 'id_metodo');
    }

    /** Líneas de detalle de la venta */
    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class, 'id_venta', 'id_venta');
    }
}
