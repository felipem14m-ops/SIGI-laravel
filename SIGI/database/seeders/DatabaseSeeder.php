<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Orden: primero los catálogos de los que dependen las demás tablas.
     */
    public function run(): void
    {
        $this->call([
            CatalogosBaseSeeder::class,
            MetodoPagoSeeder::class,
            UsuarioSeeder::class,
        ]);
    }
}
