<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Venta\RegistrarVentaRequest;
use App\Http\Resources\VentaResource;
use App\Models\Venta;
use App\Services\VentaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class VentaController extends ApiController
{
    public function __construct(private readonly VentaService $ventas) {}

    /** Admin: todas las ventas (filtrables). Empleado: solo las propias. */
    public function index(Request $request): JsonResponse
    {
        $usuario = $request->user();
        $cajero = $usuario->esAdministrador() ? ($request->integer('id_usuario') ?: null) : $usuario;

        $ventas = Venta::with(['usuario:id_usuario,nombre,email', 'metodo:id_metodo,nombre', 'detalles.producto:id_producto,nombre,codigoUnico,imagen'])
            ->deCajero($cajero)
            ->when($request->filled('fecha'), fn ($q) => $q->delDia($request->fecha))
            ->when($request->filled('id_metodo'), fn ($q) => $q->where('id_metodo', $request->id_metodo))
            ->latest('id_venta')
            ->paginate($this->porPagina(15));

        return $this->exito(VentaResource::collection($ventas));
    }

    public function show(Venta $venta): JsonResponse
    {
        Gate::authorize('view', $venta);

        return $this->exito(new VentaResource($venta->load(['usuario', 'metodo', 'detalles.producto'])));
    }

    public function store(RegistrarVentaRequest $request): JsonResponse
    {
        $venta = $this->ventas->registrar($request->toData(canal: 'POS Móvil'));

        return $this->creado(new VentaResource($venta), '¡Venta registrada exitosamente!');
    }
}
