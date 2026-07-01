<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'id_rol',
    'nombre',
    'numeroIdentificacion',
    'email',
    'contrasena',
    'activo',
    'intentos_fallidos',
    'bloqueado_hasta'
])]

#[Hidden([
    'contrasena',
    'remember_token'
])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_usuario';

    /**
     * Casts
     */
    protected function casts(): array
    {
        return [
            'contrasena' => 'hashed',
        ];
    }

    /**
     * Get the password for the user.
     */
    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    /**
     * Accessor for name to support Laravel's default features.
     */
    public function getNameAttribute()
    {
        return $this->nombre;
    }

    /**
     * Mutator for name.
     */
    public function setNameAttribute($value)
    {
        $this->nombre = $value;
    }

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    // Rol del usuario
    public function role()
    {
        return $this->belongsTo(Role::class, 'id_rol', 'id_rol');
    }
}
