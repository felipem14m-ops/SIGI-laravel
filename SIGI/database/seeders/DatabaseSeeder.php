<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed default roles
        \App\Models\Role::firstOrCreate(['id_rol' => 1], ['nombre' => 'Administrador', 'descripcion' => 'Rol de administrador']);
        \App\Models\Role::firstOrCreate(['id_rol' => 2], ['nombre' => 'Empleado', 'descripcion' => 'Rol de empleado']);

        User::firstOrCreate([
            'email' => 'test@example.com',
        ], [
            'id_rol' => 1,
            'nombre' => 'Test User',
            'numeroIdentificacion' => '1234567890',
            'contrasena' => \Illuminate\Support\Facades\Hash::make('password'),
            'activo' => 1,
            'intentos_fallidos' => 0,
        ]);

        // Usuario administrador principal
        User::firstOrCreate([
            'email' => 'felipem14m@gmail.com',
        ], [
            'id_rol' => 1,
            'nombre' => 'FELIPE',
            'numeroIdentificacion' => '1145824618',
            'contrasena' => \Illuminate\Support\Facades\Hash::make('password'),
            'activo' => 1,
            'intentos_fallidos' => 0,
        ]);
    }
}
