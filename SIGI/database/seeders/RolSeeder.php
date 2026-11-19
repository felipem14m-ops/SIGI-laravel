<?php

namespace Database\Seeders;

use App\Enums\Rol;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Rol::cases() as $rol) {
            Role::updateOrCreate(
                ['id_rol' => $rol->value],
                ['nombre' => $rol->etiqueta(), 'descripcion' => "Rol de {$rol->etiqueta()}"],
            );
        }
    }
}
