<?php

namespace Tests\Feature\Ventas;

use App\Models\MetodoPago;
use App\Models\Movimiento;
use App\Models\Producto;
use App\Models\User;
use App\Models\Venta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrarVentaTest extends TestCase
{
    use RefreshDatabase;

    private User $empleado;

    private MetodoPago $efectivo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->empleado = User::factory()->empleado()->create();
        $this->efectivo = MetodoPago::factory()->create(['nombre' => 'Efectivo']);
    }

    public function test_registra_venta_descuenta_stock_y_deja_rastro_en_kardex(): void
    {
        $producto = Producto::factory()->conStock(10)->create(['precio_venta' => 2500]);

        $respuesta = $this->actingAs($this->empleado)->postJson(route('ventas.store'), [
            'items' => [['id' => $producto->id_producto, 'cantidad' => 3]],
            'metodo_pago_id' => $this->efectivo->id_metodo,
            'monto_recibido' => 10000,
        ]);

        $respuesta->assertOk()->assertJson(['success' => true, 'cambio' => 2500]);

        $venta = Venta::firstOrFail();
        $this->assertEquals(7500, (float) $venta->total);
        $this->assertSame(7, $producto->refresh()->stock_actual);

        $movimiento = Movimiento::firstOrFail();
        $this->assertSame(10, $movimiento->stock_anterior);
        $this->assertSame(7, $movimiento->stock_resultante);
        $this->assertSame('automatico', $movimiento->origen);
    }

    public function test_lineas_repetidas_del_mismo_producto_se_agrupan(): void
    {
        $producto = Producto::factory()->conStock(5)->create(['precio_venta' => 1000]);

        $this->actingAs($this->empleado)->postJson(route('ventas.store'), [
            'items' => [
                ['id' => $producto->id_producto, 'cantidad' => 2],
                ['id' => $producto->id_producto, 'cantidad' => 2],
            ],
            'metodo_pago_id' => $this->efectivo->id_metodo,
        ])->assertOk();

        $this->assertSame(1, $producto->refresh()->stock_actual);
        $this->assertSame(1, Movimiento::count());
    }

    public function test_stock_insuficiente_revierte_toda_la_venta(): void
    {
        $conStock = Producto::factory()->conStock(10)->create();
        $sinStock = Producto::factory()->conStock(1)->create();

        $this->actingAs($this->empleado)->postJson(route('ventas.store'), [
            'items' => [
                ['id' => $conStock->id_producto, 'cantidad' => 2],
                ['id' => $sinStock->id_producto, 'cantidad' => 5],
            ],
            'metodo_pago_id' => $this->efectivo->id_metodo,
        ])->assertStatus(422)->assertJson(['success' => false]);

        $this->assertSame(0, Venta::count());
        $this->assertSame(10, $conStock->refresh()->stock_actual);
        $this->assertSame(0, Movimiento::count());
    }

    public function test_monto_recibido_menor_al_total_es_rechazado(): void
    {
        $producto = Producto::factory()->conStock(10)->create(['precio_venta' => 5000]);

        $this->actingAs($this->empleado)->postJson(route('ventas.store'), [
            'items' => [['id' => $producto->id_producto, 'cantidad' => 1]],
            'metodo_pago_id' => $this->efectivo->id_metodo,
            'monto_recibido' => 1000,
        ])->assertStatus(422);

        $this->assertSame(0, Venta::count());
    }

    public function test_no_se_vende_un_producto_inactivo(): void
    {
        $producto = Producto::factory()->conStock(10)->inactivo()->create();

        $this->actingAs($this->empleado)->postJson(route('ventas.store'), [
            'items' => [['id' => $producto->id_producto, 'cantidad' => 1]],
            'metodo_pago_id' => $this->efectivo->id_metodo,
        ])->assertStatus(422);
    }

    public function test_al_vender_todo_el_stock_el_producto_queda_agotado(): void
    {
        $producto = Producto::factory()->conStock(2)->create();

        $this->actingAs($this->empleado)->postJson(route('ventas.store'), [
            'items' => [['id' => $producto->id_producto, 'cantidad' => 2]],
            'metodo_pago_id' => $this->efectivo->id_metodo,
        ])->assertOk();

        $this->assertSame('agotado', $producto->refresh()->estado);
    }
}
