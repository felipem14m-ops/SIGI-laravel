<?php

namespace Tests\Feature;

use App\Models\MetodoPago;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Verifica que todas las pantallas rendericen con datos reales
 * (producto, proveedor, venta y movimiento en el Kardex).
 */
class SmokeVistasTest extends TestCase
{
    use RefreshDatabase;

    public function test_pantallas_renderizan_con_datos(): void
    {
        $admin = User::factory()->administrador()->create();
        $empleado = User::factory()->empleado()->create();
        $metodo = MetodoPago::factory()->create(['nombre' => 'Efectivo']);
        Proveedor::factory()->create();
        $producto = Producto::factory()->conStock(3, 5)->create(['precio_venta' => 1500]);

        $venta = $this->actingAs($empleado)->postJson(route('ventas.store'), [
            'items' => [['id' => $producto->id_producto, 'cantidad' => 1]],
            'metodo_pago_id' => $metodo->id_metodo,
            'monto_recibido' => 2000,
        ])->assertOk()->json('venta_id');

        foreach (['dashboard', 'usuarios.index', 'proveedores.index', 'categorias.index', 'productos.index', 'ventas.index',
            'ventas.historial', 'movimientos.index', 'alertas.index', 'reportes.index', 'configuraciones.index'] as $ruta) {
            $this->actingAs($admin)->get(route($ruta))->assertOk();
        }
        $this->actingAs($admin)->get(route('ventas.factura', $venta))->assertOk();

        foreach (['dashboard', 'empleado.ventas.index', 'empleado.consultas.index', 'empleado.misventas.index'] as $ruta) {
            $this->actingAs($empleado)->get(route($ruta))->assertOk();
        }
        $this->actingAs($empleado)->get(route('ventas.factura', $venta))->assertOk();
        $this->actingAs($empleado)->getJson(route('ventas.show', $venta))->assertOk()->assertJsonPath('venta.id_venta', $venta);
    }

    public function test_acciones_crud_del_administrador(): void
    {
        $admin = User::factory()->administrador()->create();
        $producto = Producto::factory()->create();
        $proveedor = Proveedor::factory()->create();
        $metodo = MetodoPago::factory()->create();

        $this->actingAs($admin)->patch(route('productos.estado', $producto))->assertRedirect();
        $this->assertSame('inactivo', $producto->refresh()->estado);

        $this->actingAs($admin)->put(route('productos.update', $producto), [
            'codigo' => $producto->codigoUnico, 'nombre' => 'Nuevo nombre', 'id_categoria' => $producto->id_categoria,
            'precio_venta' => 999, 'precio_costo' => '', 'stock_minimo' => '', 'stock_actual' => 80, 'estado' => 'activo',
        ])->assertRedirect(route('productos.index'))->assertSessionHasNoErrors();
        $this->assertSame(80, $producto->refresh()->stock_actual);
        $this->assertSame(1, $producto->movimientos()->count(), 'El cambio de stock debe quedar en el Kardex');

        $this->actingAs($admin)->patch(route('proveedores.estado', $proveedor))->assertRedirect();
        $this->assertFalse($proveedor->refresh()->activo);
        $this->actingAs($admin)->delete(route('proveedores.destroy', $proveedor))->assertRedirect();
        $this->assertModelMissing($proveedor);

        $this->actingAs($admin)->patch(route('configuraciones.estado', $metodo))->assertRedirect();
        $this->assertFalse($metodo->refresh()->activo);

        $this->actingAs($admin)->post(route('usuarios.store'), [
            'nombre' => 'Nuevo', 'email' => 'nuevo@sigi.test', 'id_rol' => 2, 'contrasena' => 'secreto123', 'numeroIdentificacion' => '555',
        ])->assertSessionHasNoErrors();
        $this->assertTrue(User::where('email', 'nuevo@sigi.test')->firstOrFail()->esEmpleado());

        $this->actingAs($admin)->delete(route('usuarios.destroy', $admin))->assertSessionHas('error');
    }
}
