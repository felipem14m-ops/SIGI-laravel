<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;

class EmpleadoConsultaController extends Controller
{
    /**
     * Muestra el catálogo de consulta de productos para empleados.
     */
    public function index()
    {
        $productos = Producto::with('categoria')
            ->where('estado', 'activo')
            ->get();

        $categorias = Categoria::where('activa', 1)->get();

        return view('Empleado.Consultas.consultas', compact('productos', 'categorias'));
    }
}
