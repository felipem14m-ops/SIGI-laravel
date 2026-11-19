<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Movimiento\RegistrarMovimientoRequest;
use App\Http\Resources\MovimientoResource;
use App\Models\Movimiento;
use App\Services\InventarioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MovimientoController extends ApiController
{
    public function __construct(private readonly InventarioService $inventario) {}

    public function index(Request $request): JsonResponse
    {
        $movimientos = Movimiento::with(['producto:id_producto,nombre,codigoUnico', 'usuario:id_usuario,nombre', 'tipoMovimiento'])
            ->deProducto($request->integer('id_producto') ?: null)
            ->buscarProducto($request->search)
            ->deTipo($request->tipo)
            ->latest('id_movimiento')
            ->paginate($this->porPagina());

        return $this->exito(MovimientoResource::collection($movimientos));
    }

    public function store(RegistrarMovimientoRequest $request): JsonResponse
    {
        $movimiento = $this->inventario->registrarMovimiento($request->toData())
            ->load(['producto', 'usuario', 'tipoMovimiento']);

        return $this->creado(new MovimientoResource($movimiento), 'Movimiento registrado correctamente.');
    }
}
