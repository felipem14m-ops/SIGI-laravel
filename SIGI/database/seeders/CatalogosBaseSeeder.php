<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Catálogos mínimos para que el sistema funcione (roles y tipos de Kardex).
 * Se usa en DatabaseSeeder y en las pruebas automatizadas.
 */
class CatalogosBaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([RolSeeder::class, TipoMovimientoSeeder::class]);
    }
}
