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
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\AlertasController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\ConfiguracionesController;
use App\Http\Controllers\EmpleadoVentaController;
use App\Http\Controllers\EmpleadoConsultaController;
use App\Http\Controllers\EmpleadoMisVentasController;

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
    Route::get('/categorias', [Categoriascontroller::class, 'index'])->name('categorias.index');
    Route::post('/categorias', [Categoriascontroller::class, 'store'])->name('categorias.store');
    Route::put('/categorias/{id}', [Categoriascontroller::class, 'update'])->name('categorias.update');
    Route::delete('/categorias/{id}', [Categoriascontroller::class, 'destroy'])->name('categorias.destroy');

    //Rutas de Gestion de Productos
    Route::get('/productos', [Productoscontroller::class, 'index'])->name('productos.index');
    Route::post('/productos', [Productoscontroller::class, 'store'])->name('productos.store');
    Route::put('/productos/{id}', [Productoscontroller::class, 'update'])->name('productos.update');
    Route::delete('/productos/{id}', [Productoscontroller::class, 'destroy'])->name('productos.destroy');

    //Rutas de Gestion de Ventas
    Route::get('/ventas',                 [Ventacontroller::class, 'index'])->name('ventas.index');     // Terminal POS
    Route::get('/ventas/historial',       [HistorialVentasController::class, 'index'])->name('ventas.historial');  // Historial de Ventas
    Route::get('/ventas/historial/{id}',  [HistorialVentasController::class, 'show'])->name('ventas.show');       // Detalle de Venta
    Route::get('/ventas/factura/{id}',    [HistorialVentasController::class, 'factura'])->name('ventas.factura');    // Factura POS Ticket
    Route::post('/ventas',                [Ventacontroller::class, 'store'])->name('ventas.store');      // Registrar nueva venta

    //Rutas de Gestion de Movimientos
    Route::get('/movimientos', [MovimientoController::class, 'index'])->name('movimientos.index');
    Route::post('/movimientos', [MovimientoController::class, 'store'])->name('movimientos.store');

    //Rutas de Alertas de Stock
    Route::get('/alertas', [AlertasController::class, 'index'])->name('alertas.index');

    //Rutas de Reportes
    Route::get('/reportes', [ReportesController::class, 'index'])->name('reportes.index');

    //Rutas de Configuraciones
    Route::get('/configuraciones',                [ConfiguracionesController::class, 'index'])->name('configuraciones.index');
    Route::get('/configuraciones/crear',          [ConfiguracionesController::class, 'create'])->name('configuraciones.create');
    Route::post('/configuraciones/metodos',       [ConfiguracionesController::class, 'store'])->name('configuraciones.store');
    Route::put('/configuraciones/metodos/{id}',   [ConfiguracionesController::class, 'update'])->name('configuraciones.update');
    Route::delete('/configuraciones/metodos/{id}', [ConfiguracionesController::class, 'destroy'])->name('configuraciones.destroy');

    //Rutas de Gestion de Ventas Empleado
    Route::get('/empleado/ventas',     [EmpleadoVentaController::class, 'index'])->name('empleado.ventas.index');
    Route::get('/empleado/consultas',  [EmpleadoConsultaController::class, 'index'])->name('empleado.consultas.index');
    Route::get('/empleado/mis-ventas', [EmpleadoMisVentasController::class, 'index'])->name('empleado.misventas.index');
});

require __DIR__ . '/auth.php';
