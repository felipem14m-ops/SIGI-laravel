<?php

namespace App\Contracts;

use Illuminate\Http\UploadedFile;

/**
 * Abstracción del almacenamiento de imágenes (productos, categorías).
 *
 * Los servicios dependen de esta interfaz y no del disco concreto
 * (Principio de Inversión de Dependencias): mañana puede ser S3 o
 * Cloudinary cambiando solo el binding en AppServiceProvider.
 */
interface AlmacenImagenes
{
    /** Guarda el archivo y devuelve la ruta relativa almacenada. */
    public function guardar(UploadedFile $archivo, string $carpeta): string;

    /** Elimina el archivo si existe. Ignora rutas vacías. */
    public function eliminar(?string $ruta): void;

    /** Guarda el nuevo archivo, elimina el anterior y devuelve la nueva ruta. */
    public function reemplazar(?string $rutaAnterior, UploadedFile $archivo, string $carpeta): string;
}
