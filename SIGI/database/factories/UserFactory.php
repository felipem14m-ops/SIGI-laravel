<?php

namespace Database\Factories;

use App\Enums\Rol;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'id_rol' => Rol::EMPLEADO->value,
            'nombre' => fake()->name(),
            'numeroIdentificacion' => (string) fake()->unique()->numerify('##########'),
            'email' => fake()->unique()->safeEmail(),
            'contrasena' => 'password', // el cast `hashed` del modelo la cifra
            'activo' => true,
            'intentos_fallidos' => 0,
            'remember_token' => Str::random(10),
        ];
    }

    public function administrador(): static
    {
        return $this->state(fn () => ['id_rol' => Rol::ADMINISTRADOR->value]);
    }

    public function empleado(): static
    {
        return $this->state(fn () => ['id_rol' => Rol::EMPLEADO->value]);
    }

    public function inactivo(): static
    {
        return $this->state(fn () => ['activo' => false]);
    }

    /** Compatibilidad con los tests de Breeze. */
    public function unverified(): static
    {
        return $this;
    }
}
