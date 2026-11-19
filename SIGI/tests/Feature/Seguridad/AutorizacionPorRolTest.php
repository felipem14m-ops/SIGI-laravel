<?php

namespace Tests\Feature\Seguridad;

use App\Models\MetodoPago;
use App\Models\User;
use App\Models\Venta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AutorizacionPorRolTest extends TestCase
{
    use RefreshDatabase;

    public static function modulosAdministrativos(): array
    {
        return [
            ['usuarios.index'], ['proveedores.index'], ['categorias.index'], ['productos.index'],
            ['ventas.index'], ['ventas.historial'], ['movimientos.index'], ['alertas.index'],
            ['reportes.index'], ['configuraciones.index'],
        ];
    }

    #[DataProvider('modulosAdministrativos')]
    public function test_empleado_no_accede_a_modulos_de_administracion(string $ruta): void
    {
        $this->actingAs(User::factory()->empleado()->create())
            ->get(route($ruta))
            ->assertForbidden();
    }

    #[DataProvider('modulosAdministrativos')]
    public function test_administrador_accede_a_sus_modulos(string $ruta): void
    {
        $this->actingAs(User::factory()->administrador()->create())
            ->get(route($ruta))
            ->assertOk();
    }

    public function test_cada_rol_ve_su_propio_dashboard(): void
    {
        $this->actingAs(User::factory()->administrador()->create())->get(route('dashboard'))
            ->assertOk()->assertViewIs('Admin.dashboard');

        $this->actingAs(User::factory()->empleado()->create())->get(route('dashboard'))
            ->assertOk()->assertViewIs('Empleado.dashboard');
    }

    public function test_empleado_accede_a_sus_modulos(): void
    {
        $empleado = User::factory()->empleado()->create();

        foreach (['empleado.ventas.index', 'empleado.consultas.index', 'empleado.misventas.index'] as $ruta) {
            $this->actingAs($empleado)->get(route($ruta))->assertOk();
        }
    }

    public function test_empleado_no_puede_ver_ventas_de_otro_empleado(): void
    {
        $metodo = MetodoPago::factory()->create();
        [$ana, $luis] = User::factory()->empleado()->count(2)->create();

        $ventaDeLuis = Venta::create(['id_usuario' => $luis->id_usuario, 'id_metodo' => $metodo->id_metodo, 'fecha_venta' => now(), 'total' => 1000]);

        $this->actingAs($ana)->getJson(route('ventas.show', $ventaDeLuis))->assertForbidden();
        $this->actingAs($luis)->getJson(route('ventas.show', $ventaDeLuis))->assertOk();
    }

    public function test_usuario_inactivo_no_puede_iniciar_sesion(): void
    {
        $usuario = User::factory()->inactivo()->create();

        $this->post('/login', ['email' => $usuario->email, 'password' => 'password']);

        $this->assertGuest();
    }
}
