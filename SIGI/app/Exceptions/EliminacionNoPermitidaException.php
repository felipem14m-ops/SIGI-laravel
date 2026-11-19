<?php

namespace App\Exceptions;

final class EliminacionNoPermitidaException extends ReglaNegocioException
{
    public static function porDependencias(string $entidad, string $detalle): self
    {
        return new self("No se puede eliminar {$entidad}: {$detalle}");
    }
}
