<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $table = 'roles';

    protected $primaryKey = 'id_rol';

    // La PK es un tinyint asignado manualmente (1 = Admin, 2 = Empleado).
    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'id_rol',
        'nombre',
        'descripcion',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'id_rol', 'id_rol');
    }
}
