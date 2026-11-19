<?php

namespace Database\Seeders;

use App\Enums\Rol;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Usuarios iniciales. Cambia las contraseñas después de la primera instalación.
 */
class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = [
            [
                'email' => 'felipem14m@gmail.com',
                'nombre' => 'FELIPE',
                'numeroIdentificacion' => '1145824618',
                'id_rol' => Rol::ADMINISTRADOR,
            ],
            [
                'email' => 'test@example.com',
                'nombre' => 'Test User',
                'numeroIdentificacion' => '1234567890',
                'id_rol' => Rol::ADMINISTRADOR,
            ],
            [
                'email' => 'empleado@sigi.test',
                'nombre' => 'Empleado Demo',
                'numeroIdentificacion' => '1000000002',
                'id_rol' => Rol::EMPLEADO,
            ],
        ];

        foreach ($usuarios as $datos) {
            User::firstOrCreate(['email' => $datos['email']], [
                'nombre' => $datos['nombre'],
                'numeroIdentificacion' => $datos['numeroIdentificacion'],
                'id_rol' => $datos['id_rol']->value,
                'contrasena' => 'password', // cifrada por el cast `hashed`
                'activo' => true,
            ]);
        }
    }
}
