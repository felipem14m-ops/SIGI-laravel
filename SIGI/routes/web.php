<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController as Usuariocontroller;
use App\Http\Controllers\ProveedorController as Proveedorcontroller;
use App\Http\Controllers\CategoriasController as Categoriascontroller;
use App\Http\Controllers\HistorialVentasController;
use App\Http\Controllers\ProductosController as Productoscontroller;
use App\Http\Controllers\VentaController as Ventacontroller;
use App\Http\Controllers\MovimientoController as Movimientocontroller;
use App\Http\Controllers\AlertasController;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Dashboard con redirección por Rol
|--------------------------------------------------------------------------
| Rol 1 → Administrador → admin/dashboard
| Rol 2 → Empleado     → empleado/dashboard
*/
Route::get('/dashboard', function () {
    /** @var \App\Models\User $user */
    $user = Auth::user();
    $role = $user->role_id;
    if ($role == 1) {
        return view('admin.dashboard');
    } elseif ($role == 2) {
        return view('empleado.dashboard');
    }
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rutas de Gestión de Usuarios
    Route::get('/usuarios', [Usuariocontroller::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/crear', [Usuariocontroller::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [Usuariocontroller::class, 'store'])->name('usuarios.store');
    Route::put('/usuarios/{id}', [Usuariocontroller::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{id}', [Usuariocontroller::class, 'destroy'])->name('usuarios.destroy');

    //Rutas de Gestion de Proveedores 
    Route::get('/proveedores', [Proveedorcontroller::class, 'index'])->name('proveedores.index');
    Route::post('/proveedores', [Proveedorcontroller::class, 'store'])->name('proveedores.store');
    Route::put('/proveedores/{id}', [Proveedorcontroller::class, 'update'])->name('proveedores.update');
    Route::delete('/proveedores/{id}', [Proveedorcontroller::class, 'destroy'])->name('proveedores.destroy');

    //Rutas de Gestion de Categorias
    Route::get('/categorias', [CategoriasController::class, 'index'])->name('categorias.index');
    Route::post('/categorias', [CategoriasController::class, 'store'])->name('categorias.store');
    Route::put('/categorias/{id}', [CategoriasController::class, 'update'])->name('categorias.update');
    Route::delete('/categorias/{id}', [CategoriasController::class, 'destroy'])->name('categorias.destroy');

    //Rutas de Gestion de Productos
    Route::get('/productos', [ProductosController::class, 'index'])->name('productos.index');
    Route::post('/productos', [ProductosController::class, 'store'])->name('productos.store');
    Route::put('/productos/{id}', [ProductosController::class, 'update'])->name('productos.update');
    Route::delete('/productos/{id}', [ProductosController::class, 'destroy'])->name('productos.destroy');

    //Rutas de Gestion de Ventas
    Route::get('/ventas',           [Ventacontroller::class, 'index']    )->name('ventas.index');     // Terminal POS
    Route::get('/ventas/historial', [HistorialVentasController::class, 'index'])->name('ventas.historial');  // Historial de Ventas
    Route::post('/ventas',          [Ventacontroller::class, 'store']    )->name('ventas.store');      // Registrar nueva venta

    //Rutas de Gestion de Movimientos
    Route::get('/movimientos', [MovimientoController::class, 'index'])->name('movimientos.index');
    Route::post('/movimientos', [MovimientoController::class, 'store'])->name('movimientos.store');

    //Rutas de Alertas de Stock
    Route::get('/alertas', [AlertasController::class, 'index'])->name('alertas.index');
    
    
});

require __DIR__ . '/auth.php';
