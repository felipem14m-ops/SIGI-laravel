<?php

namespace App\Services;

use App\Contracts\AlmacenImagenes;
use App\Exceptions\EliminacionNoPermitidaException;
use App\Models\Categoria;
use Illuminate\Http\UploadedFile;

/**
 * Gestión de categorías del catálogo.
 */
class CategoriaService
{
    private const CARPETA_IMAGENES = 'categorias';

    public function __construct(private readonly AlmacenImagenes $imagenes) {}

    /** @param array<string, mixed> $datos */
    public function crear(array $datos, ?UploadedFile $imagen): Categoria
    {
        $categoria = new Categoria($datos);

        if ($imagen) {
            $categoria->imagen = $this->imagenes->guardar($imagen, self::CARPETA_IMAGENES);
        }

        $categoria->save();

        return $categoria;
    }

    /** @param array<string, mixed> $datos */
    public function actualizar(Categoria $categoria, array $datos, ?UploadedFile $imagen): Categoria
    {
        $categoria->fill($datos);

        if ($imagen) {
            $categoria->imagen = $this->imagenes->reemplazar($categoria->imagen, $imagen, self::CARPETA_IMAGENES);
        }

        $categoria->save();

        return $categoria;
    }

    public function alternarEstado(Categoria $categoria): Categoria
    {
        $categoria->alternarEstado();
        $categoria->save();

        return $categoria;
    }

    /**
     * @throws EliminacionNoPermitidaException si tiene productos asignados
     */
    public function eliminar(Categoria $categoria): void
    {
        if ($categoria->productos()->exists()) {
            throw EliminacionNoPermitidaException::porDependencias(
                "la categoría \"{$categoria->nombre}\"",
                'tiene productos asignados. Desactívala o reasigna sus productos.',
            );
        }

        $this->imagenes->eliminar($categoria->imagen);
        $categoria->delete();
    }
}
