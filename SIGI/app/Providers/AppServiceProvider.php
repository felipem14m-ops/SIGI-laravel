<?php

namespace App\Providers;

use App\Contracts\AlmacenImagenes;
use App\Services\Almacenamiento\AlmacenImagenesLocal;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Contenedor de dependencias: interfaces → implementaciones concretas.
     */
    public array $singletons = [
        AlmacenImagenes::class => AlmacenImagenesLocal::class,
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Los Resources se envuelven en { success, data } desde RespondeJson.
        JsonResource::withoutWrapping();

        Paginator::useTailwind();
    }
}
