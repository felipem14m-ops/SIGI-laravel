<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\EliminacionNoPermitidaException;
use App\Exceptions\OperacionNoPermitidaException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Usuario\ActualizarUsuarioRequest;
use App\Http\Requests\Usuario\GuardarUsuarioRequest;
use App\Models\Movimiento;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UsuarioController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $roleFilter = $request->input('role_id');

        $usuarios = User::with('role')
            ->buscar($search)
            ->when($roleFilter, fn ($q) => $q->where('id_rol', $roleFilter))
            ->orderBy('nombre')
            ->get();

        return view('Admin.Usuarios.ListasdeUsuarios', [
            'usuarios' => $usuarios,
            'roles' => Role::all(),
            'search' => $search,
            'roleFilter' => $roleFilter,
        ]);
    }

    public function store(GuardarUsuarioRequest $request): RedirectResponse
    {
        User::create([...$request->datos(), 'activo' => true]);

        return to_route('usuarios.index')->with('success', '¡Usuario registrado exitosamente!');
    }

    public function update(ActualizarUsuarioRequest $request, User $usuario): RedirectResponse
    {
        $usuario->update($request->datos());

        return to_route('usuarios.index')->with('success', '¡Usuario actualizado con éxito!');
    }

    public function alternarEstado(Request $request, User $usuario): RedirectResponse
    {
        $this->impedirAutoGestion($request, $usuario, 'desactivar');

        $usuario->alternarEstado();
        $usuario->save();
        $texto = $usuario->activo ? 'activado' : 'desactivado';

        return to_route('usuarios.index')->with('success', "Usuario {$texto} con éxito.");
    }

    public function destroy(Request $request, User $usuario): RedirectResponse
    {
        $this->impedirAutoGestion($request, $usuario, 'eliminar');

        if ($usuario->ventas()->exists() || Movimiento::where('id_usuario', $usuario->id_usuario)->exists()) {
            throw EliminacionNoPermitidaException::porDependencias(
                "el usuario \"{$usuario->nombre}\"",
                'tiene ventas o movimientos registrados. Desactívalo en su lugar.',
            );
        }

        $usuario->tokens()->delete();
        $usuario->delete();

        return to_route('usuarios.index')->with('success', 'Usuario eliminado exitosamente.');
    }

    /** Un administrador no puede desactivarse ni eliminarse a sí mismo. */
    private function impedirAutoGestion(Request $request, User $usuario, string $accion): void
    {
        if ($request->user()->is($usuario)) {
            throw new OperacionNoPermitidaException("No puedes {$accion}te a ti mismo.");
        }
    }
}
