<?php

namespace App\Services\Almacenamiento;

use App\Contracts\AlmacenImagenes;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Implementación sobre un disco de Laravel (por defecto `public`).
 */
final class AlmacenImagenesLocal implements AlmacenImagenes
{
    public function __construct(private readonly string $disco = 'public') {}

    public function guardar(UploadedFile $archivo, string $carpeta): string
    {
        return $archivo->store($carpeta, $this->disco);
    }

    public function eliminar(?string $ruta): void
    {
        if ($ruta && $this->filesystem()->exists($ruta)) {
            $this->filesystem()->delete($ruta);
        }
    }

    public function reemplazar(?string $rutaAnterior, UploadedFile $archivo, string $carpeta): string
    {
        $nuevaRuta = $this->guardar($archivo, $carpeta);
        $this->eliminar($rutaAnterior);

        return $nuevaRuta;
    }

    private function filesystem(): Filesystem
    {
        return Storage::disk($this->disco);
    }
}
