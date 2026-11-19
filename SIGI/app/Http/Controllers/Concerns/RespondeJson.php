<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\AbstractPaginator;
use Symfony\Component\HttpFoundation\Response;

/**
 * Formato único de respuesta de la API: { success, message?, data? }.
 */
trait RespondeJson
{
    protected function exito(mixed $data = null, ?string $mensaje = null, int $status = Response::HTTP_OK, array $extra = []): JsonResponse
    {
        if ($data instanceof JsonResource) {
            // Colecciones paginadas: se conservan data/links/meta dentro de `data`.
            $data = $data->resource instanceof AbstractPaginator
                ? $data->response()->getData(true)
                : $data->resolve(request());
        }

        return response()->json(array_filter([
            'success' => true,
            'message' => $mensaje,
            ...$extra,
            'data' => $data,
        ], fn ($v) => $v !== null), $status);
    }

    protected function creado(mixed $data, string $mensaje): JsonResponse
    {
        return $this->exito($data, $mensaje, Response::HTTP_CREATED);
    }

    protected function error(string $mensaje, int $status = Response::HTTP_UNPROCESSABLE_ENTITY): JsonResponse
    {
        return response()->json(['success' => false, 'message' => $mensaje], $status);
    }
}
