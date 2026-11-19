<?php

namespace Database\Seeders;

use App\Enums\TipoMovimientoCodigo;
use App\Models\TipoMovimiento;
use Illuminate\Database\Seeder;

/**
 * Catálogo obligatorio del Kardex. Sin él, el POS no podía registrar ventas
 * en una instalación nueva (firstOrFail sobre "SALIDA").
 */
class TipoMovimientoSeeder extends Seeder
{
    public function run(): void
    {
        foreach (TipoMovimientoCodigo::cases() as $tipo) {
            TipoMovimiento::updateOrCreate(
                ['codigo' => $tipo->value],
                ['nombre' => $tipo->nombre(), 'signo' => $tipo->signo()],
            );
        }
    }
}
