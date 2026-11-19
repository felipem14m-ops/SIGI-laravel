<?php

namespace Tests;

use Database\Seeders\CatalogosBaseSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Con RefreshDatabase se siembran los catálogos base (roles y tipos de
     * movimiento), requeridos por las llaves foráneas de users y del Kardex.
     */
    protected bool $seed = true;

    protected string $seeder = CatalogosBaseSeeder::class;

    protected function setUp(): void
    {
        parent::setUp();

        // Las pruebas no dependen de que los assets de Vite estén compilados.
        $this->withoutVite();
    }
}
