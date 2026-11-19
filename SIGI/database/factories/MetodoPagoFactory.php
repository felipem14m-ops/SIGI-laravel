<?php

namespace Database\Factories;

use App\Models\MetodoPago;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MetodoPago>
 */
class MetodoPagoFactory extends Factory
{
    protected $model = MetodoPago::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->unique()->randomElement(['Efectivo', 'Tarjeta', 'Nequi', 'Daviplata', 'Transferencia']),
            'activo' => true,
        ];
    }
}
