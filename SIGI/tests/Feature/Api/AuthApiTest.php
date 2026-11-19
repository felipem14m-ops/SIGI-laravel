<?php

namespace Tests\Feature\Api;

use App\Enums\Rol;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_registro_publico_siempre_crea_empleado_aunque_se_envie_id_rol(): void
    {
        $this->postJson('/api/register', [
            'nombre' => 'Intruso',
            'numeroIdentificacion' => '999',
            'email' => 'intruso@test.com',
            'password' => 'secreto123',
            'id_rol' => Rol::ADMINISTRADOR->value,
        ])->assertCreated()->assertJsonPath('user.id_rol', Rol::EMPLEADO->value);
    }

    public function test_login_devuelve_token_y_acepta_campo_contrasena(): void
    {
        $usuario = User::factory()->create();

        $this->postJson('/api/login', ['email' => $usuario->email, 'contrasena' => 'password'])
            ->assertOk()
            ->assertJsonStructure(['success', 'access_token', 'token_type', 'user' => ['id_usuario', 'rol_nombre']]);
    }

    public function test_login_rechaza_usuario_inactivo(): void
    {
        $usuario = User::factory()->inactivo()->create();

        $this->postJson('/api/login', ['email' => $usuario->email, 'password' => 'password'])->assertForbidden();
    }

    public function test_empleado_lee_productos_pero_no_puede_crearlos(): void
    {
        Producto::factory()->count(2)->create();
        Sanctum::actingAs(User::factory()->empleado()->create());

        $this->getJson('/api/productos')->assertOk()->assertJsonCount(2, 'data');
        $this->postJson('/api/productos', [])->assertForbidden();
    }

    public function test_producto_inexistente_devuelve_404_uniforme(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->getJson('/api/productos/9999')->assertNotFound()->assertJson(['success' => false]);
    }
}
