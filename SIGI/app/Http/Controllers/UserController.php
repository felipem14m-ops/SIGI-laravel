<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $roleFilter = $request->input('role_id');

        $query = User::with('role');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('numeroIdentificacion', 'like', "%{$search}%");
            });
        }

        if (!empty($roleFilter)) {
            $query->where('id_rol', $roleFilter);
        }

        $usuarios = $query->get();
        $roles = Role::all();

        return view('admin.Usuarios.ListasdeUsuarios', compact('usuarios', 'roles', 'search', 'roleFilter'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // El formulario está integrado en el modal de ListasdeUsuarios
        return redirect()->route('usuarios.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'               => ['required', 'string', 'max:255'],
            'email'                => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'id_rol'               => ['required', 'integer', 'exists:roles,id_rol'],
            'contrasena'           => ['required', 'string', 'min:8'],
            'numeroIdentificacion' => ['required', 'string', 'max:20', 'unique:users,numeroIdentificacion'],
        ]);

        $usuario = new User();

        $usuario->id_rol               = $request->post('id_rol');
        $usuario->nombre               = $request->post('nombre');
        $usuario->email                = $request->post('email');
        $usuario->contrasena           = Hash::make($request->post('contrasena'));
        $usuario->numeroIdentificacion = $request->post('numeroIdentificacion');
        $usuario->activo               = 1; // Activo por defecto

        $usuario->save();

        return redirect()
            ->route('usuarios.index')
            ->with('success', '¡Registro exitoso!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $usuario = User::findOrFail($id);

        // Check if this is a toggle status request
        if ($request->has('toggle_status')) {
            $usuario->activo = $usuario->activo ? 0 : 1;
            $usuario->save();

            $statusText = $usuario->activo ? 'activado' : 'desactivado';
            return redirect()->route('usuarios.index')->with('success', "Usuario {$statusText} con éxito.");
        }

        $request->validate([
            'nombre'               => ['required', 'string', 'max:255'],
            'email'                => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id . ',id_usuario'],
            'id_rol'               => ['required', 'integer', 'exists:roles,id_rol'],
            'activo'               => ['required', 'integer', 'in:0,1'],
            'contrasena'           => ['nullable', 'string', 'min:8'],
            'numeroIdentificacion' => ['required', 'string', 'max:20', 'unique:users,numeroIdentificacion,' . $id . ',id_usuario'],
        ]);

        $usuario->id_rol               = $request->post('id_rol');
        $usuario->nombre               = $request->post('nombre');
        $usuario->email                = $request->post('email');
        $usuario->numeroIdentificacion = $request->post('numeroIdentificacion');
        $usuario->activo               = $request->post('activo');

        if ($request->filled('contrasena')) {
            $usuario->contrasena = Hash::make($request->post('contrasena'));
        }

        $usuario->save();

        return redirect()
            ->route('usuarios.index')
            ->with('success', '¡Usuario actualizado con éxito!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $usuario = User::findOrFail($id);
        $usuario->delete();

        return back()->with('success', 'Usuario eliminado exitosamente');
    }
}
