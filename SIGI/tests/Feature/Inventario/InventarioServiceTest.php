<?php

namespace Tests\Feature\Inventario;

use App\DTOs\RegistrarMovimientoData;
use App\Enums\TipoMovimientoCodigo;
use App\Exceptions\StockInsuficienteException;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\User;
use App\Services\InventarioService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventarioServiceTest extends TestCase
{
    use RefreshDatabase;

    private InventarioService $servicio;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->servicio = app(InventarioService::class);
        $this->admin = User::factory()->administrador()->create();
    }

    private function movimiento(Producto $producto, TipoMovimientoCodigo $tipo, int $cantidad): RegistrarMovimientoData
    {
        return new RegistrarMovimientoData($producto->id_producto, $this->admin->id_usuario, $tipo, $cantidad, 'Prueba');
    }

    public function test_entrada_suma_stock(): void
    {
        $producto = Producto::factory()->conStock(5)->create();

        $mov = $this->servicio->registrarMovimiento($this->movimiento($producto, TipoMovimientoCodigo::ENTRADA, 10));

        $this->assertSame(15, $producto->refresh()->stock_actual);
        $this->assertSame([5, 15], [$mov->stock_anterior, $mov->stock_resultante]);
    }

    public function test_ajuste_fija_el_conteo_fisico(): void
    {
        $producto = Producto::factory()->conStock(40)->create();

        $this->servicio->registrarMovimiento($this->movimiento($producto, TipoMovimientoCodigo::AJUSTE, 12));

        $this->assertSame(12, $producto->refresh()->stock_actual);
    }

    public function test_salida_sin_stock_lanza_excepcion_de_dominio(): void
    {
        $producto = Producto::factory()->conStock(2)->create();

        $this->expectException(StockInsuficienteException::class);

        $this->servicio->registrarMovimiento($this->movimiento($producto, TipoMovimientoCodigo::SALIDA, 3));
    }

    public function test_reponer_un_producto_agotado_lo_reactiva(): void
    {
        $producto = Producto::factory()->conStock(0)->create(['estado' => 'agotado']);

        $this->servicio->registrarMovimiento($this->movimiento($producto, TipoMovimientoCodigo::ENTRADA, 4));

        $this->assertSame('activo', $producto->refresh()->estado);
    }

    public function test_crear_producto_con_stock_inicial_genera_entrada_en_kardex(): void
    {
        $this->actingAs($this->admin)->post(route('productos.store'), [
            'codigo' => '7701234567890',
            'nombre' => 'Arroz Diana 500g',
            'id_categoria' => Categoria::factory()->create()->id_categoria,
            'precio_venta' => 3200,
            'alerta_minima' => 5,
            'stock_inicial' => 24,
        ])->assertRedirect(route('productos.index'));

        $producto = Producto::where('codigoUnico', '7701234567890')->firstOrFail();
        $this->assertSame(24, $producto->stock_actual);
        $this->assertSame(1, $producto->movimientos()->count());
    }
}
