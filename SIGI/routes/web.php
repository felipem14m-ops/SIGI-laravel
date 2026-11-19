<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Empleado;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Ventas\ComprobanteVentaController;
use App\Http\Controllers\Ventas\RegistrarVentaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas Web - SIGI
|--------------------------------------------------------------------------
| Organización por rol:
|   · Compartidas (Administrador y Empleado): dashboard, perfil, registro y
|     comprobantes de venta (VentaPolicy limita al empleado a sus ventas).
|   · role:admin    → módulos de gestión.
|   · role:empleado → POS, consultas y "mis ventas" del empleado.
*/

Route::view('/', 'welcome');

Route::middleware(['auth', 'activo'])->group(function () {

    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');
    });

    // ── Ventas (ambos roles) ────────────────────────────────────────────
    Route::post('/ventas', RegistrarVentaController::class)->name('ventas.store');
    Route::get('/ventas/historial/{venta}', [ComprobanteVentaController::class, 'show'])->name('ventas.show');
    Route::get('/ventas/factura/{venta}', [ComprobanteVentaController::class, 'factura'])->name('ventas.factura');

    // ── Administrador ───────────────────────────────────────────────────
    Route::middleware('role:admin')->group(function () {

        Route::resource('usuarios', Admin\UsuarioController::class)
            ->only(['index', 'store', 'update', 'destroy'])
            ->parameters(['usuarios' => 'usuario']);
        Route::patch('/usuarios/{usuario}/estado', [Admin\UsuarioController::class, 'alternarEstado'])->name('usuarios.estado');

        Route::resource('proveedores', Admin\ProveedorController::class)
            ->only(['index', 'store', 'update', 'destroy'])
            ->parameters(['proveedores' => 'proveedor']);
        Route::patch('/proveedores/{proveedor}/estado', [Admin\ProveedorController::class, 'alternarEstado'])->name('proveedores.estado');

        Route::resource('categorias', Admin\CategoriaController::class)
            ->only(['index', 'store', 'update', 'destroy'])
            ->parameters(['categorias' => 'categoria']);
        Route::patch('/categorias/{categoria}/estado', [Admin\CategoriaController::class, 'alternarEstado'])->name('categorias.estado');

        Route::resource('productos', Admin\ProductoController::class)
            ->only(['index', 'store', 'update', 'destroy']);
        Route::patch('/productos/{producto}/estado', [Admin\ProductoController::class, 'alternarEstado'])->name('productos.estado');

        Route::get('/ventas', Admin\PuntoVentaController::class)->name('ventas.index');
        Route::get('/ventas/historial', Admin\HistorialVentaController::class)->name('ventas.historial');

        Route::get('/movimientos', [Admin\MovimientoController::class, 'index'])->name('movimientos.index');
        Route::post('/movimientos', [Admin\MovimientoController::class, 'store'])->name('movimientos.store');

        Route::get('/alertas', Admin\AlertaController::class)->name('alertas.index');
        Route::get('/reportes', Admin\ReporteController::class)->name('reportes.index');

        Route::controller(Admin\MetodoPagoController::class)->prefix('configuraciones')->name('configuraciones.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/metodos', 'store')->name('store');
            Route::put('/metodos/{metodo}', 'update')->name('update');
            Route::patch('/metodos/{metodo}/estado', 'alternarEstado')->name('estado');
            Route::delete('/metodos/{metodo}', 'destroy')->name('destroy');
        });
    });

    // ── Empleado ────────────────────────────────────────────────────────
    Route::middleware('role:empleado')->prefix('empleado')->name('empleado.')->group(function () {
        Route::get('/ventas', Empleado\PuntoVentaController::class)->name('ventas.index');
        Route::get('/consultas', Empleado\ConsultaProductoController::class)->name('consultas.index');
        Route::get('/mis-ventas', Empleado\MisVentasController::class)->name('misventas.index');
    });
});

require __DIR__.'/auth.php';
