<?php

namespace Database\Factories;

use App\Enums\EstadoProducto;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Producto>
 */
class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    public function definition(): array
    {
        $costo = fake()->numberBetween(500, 20000);

        return [
            'codigoUnico' => fake()->unique()->ean13(),
            'nombre' => ucfirst(fake()->unique()->words(3, true)),
            'descripcion' => fake()->sentence(),
            'id_categoria' => Categoria::factory(),
            'id_proveedor' => null,
            'precio_costo' => $costo,
            'precio_venta' => round($costo * 1.3),
            'stock_minimo' => 5,
            'stock_actual' => 50,
            'fechaCreacion' => now(),
            'estado' => EstadoProducto::ACTIVO->value,
        ];
    }

    public function conStock(int $stock, int $minimo = 5): static
    {
        return $this->state(fn () => ['stock_actual' => $stock, 'stock_minimo' => $minimo]);
    }

    public function inactivo(): static
    {
        return $this->state(fn () => ['estado' => EstadoProducto::INACTIVO->value]);
    }
}
