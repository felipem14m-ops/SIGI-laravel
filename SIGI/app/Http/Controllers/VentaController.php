<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;


class VentaController extends Controller
{
    /**
     * Terminal Punto de Venta (POS).
     */
    public function index()
    {
        $productos  = \App\Models\Producto::with('categoria')
                        ->where('estado', 'activo')
                        ->where('stock_actual', '>', 0)
                        ->get();

        $categorias = \App\Models\Categoria::where('activa', 1)->get();

        return view('Admin.Venta.ListasdeVentas', compact('productos', 'categorias'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
    }
}
