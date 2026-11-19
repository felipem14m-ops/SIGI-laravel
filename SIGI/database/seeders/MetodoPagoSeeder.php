<?php

namespace Database\Seeders;

use App\Models\MetodoPago;
use Illuminate\Database\Seeder;

class MetodoPagoSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Efectivo', 'Tarjeta débito/crédito', 'Nequi', 'Daviplata', 'Transferencia'] as $nombre) {
            MetodoPago::firstOrCreate(['nombre' => $nombre], ['activo' => true]);
        }
    }
}
