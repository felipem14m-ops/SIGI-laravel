<?php

namespace App\Models;

use App\Enums\Rol;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Usuario del sistema (Administrador o Empleado).
 *
 * La tabla usa columnas en español (`nombre`, `contrasena`); los accesores
 * `name` y `getAuthPassword()` mantienen la compatibilidad con Laravel Breeze.
 *
 * @property int $id_usuario
 * @property int $id_rol
 * @property string $nombre
 * @property string $email
 * @property bool $activo
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'id_rol',
        'nombre',
        'numeroIdentificacion',
        'email',
        'contrasena',
        'activo',
        'intentos_fallidos',
        'bloqueado_hasta',
    ];

    protected $hidden = [
        'contrasena',
        'remember_token',
    ];

    protected $attributes = [
        'activo' => true,
        'intentos_fallidos' => 0,
    ];

    protected function casts(): array
    {
        return [
            // El cast `hashed` cifra automáticamente; no usar Hash::make() antes.
            'contrasena' => 'hashed',
            'activo' => 'boolean',
            'bloqueado_hasta' => 'datetime',
        ];
    }

    public function getAuthPasswordName(): string
    {
        return 'contrasena';
    }

    public function getAuthPassword(): string
    {
        return $this->contrasena;
    }

    /* ---------------------------------------------------------------------
     | Compatibilidad con Breeze (`name`, `role_id`)
     * -------------------------------------------------------------------*/

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->nombre,
            set: fn (string $value) => ['nombre' => $value],
        );
    }

    protected function roleId(): Attribute
    {
        return Attribute::get(fn () => $this->id_rol);
    }

    /* ---------------------------------------------------------------------
     | Relaciones
     * -------------------------------------------------------------------*/

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'id_rol', 'id_rol');
    }

    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class, 'id_usuario', 'id_usuario');
    }

    /* ---------------------------------------------------------------------
     | Scopes
     * -------------------------------------------------------------------*/

    public function scopeBuscar(Builder $query, ?string $termino): Builder
    {
        if (blank($termino)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($termino) {
            $q->where('nombre', 'like', "%{$termino}%")
                ->orWhere('email', 'like', "%{$termino}%")
                ->orWhere('numeroIdentificacion', 'like', "%{$termino}%");
        });
    }

    /* ---------------------------------------------------------------------
     | Roles
     * -------------------------------------------------------------------*/

    public function rol(): Rol
    {
        return Rol::from((int) $this->id_rol);
    }

    public function tieneRol(Rol ...$roles): bool
    {
        return in_array($this->rol(), $roles, true);
    }

    public function esAdministrador(): bool
    {
        return $this->tieneRol(Rol::ADMINISTRADOR);
    }

    public function esEmpleado(): bool
    {
        return $this->tieneRol(Rol::EMPLEADO);
    }

    public function nombreRol(): string
    {
        return $this->role?->nombre ?? $this->rol()->etiqueta();
    }

    public function alternarEstado(): void
    {
        $this->activo = ! $this->activo;
    }
}
