<?php

namespace App\Enums;

/**
 * Roles del sistema. El valor coincide con `roles.id_rol`.
 */
enum Rol: int
{
    case ADMINISTRADOR = 1;
    case EMPLEADO = 2;

    public function etiqueta(): string
    {
        return match ($this) {
            self::ADMINISTRADOR => 'Administrador',
            self::EMPLEADO => 'Empleado',
        };
    }

    /** Alias legibles usados en el middleware `role:admin,empleado`. */
    public static function desdeAlias(string $alias): self
    {
        return match (strtolower($alias)) {
            'admin', 'administrador', '1' => self::ADMINISTRADOR,
            'empleado', '2' => self::EMPLEADO,
            default => throw new \InvalidArgumentException("Rol desconocido: {$alias}"),
        };
    }
}
