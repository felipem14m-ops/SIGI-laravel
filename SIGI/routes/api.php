<?php

use App\Http\Controllers\Api;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API REST - SIGI (prefijo automático /api)
|--------------------------------------------------------------------------
| Consumida por la app Flutter y documentada en postman_collection_SIGI.json.
| Lectura y POS: cualquier usuario autenticado. Escritura de catálogo e
| inventario: solo Administrador (`role:admin`).
*/

// ── Públicas (con límite de intentos) ──────────────────────────────────
Route::middleware('throttle:10,1')->group(function () {
    Route::post('/login', [Api\AuthController::class, 'login']);
    Route::post('/register', [Api\AuthController::class, 'register']);
});

// ── Protegidas (Bearer token Sanctum) ──────────────────────────────────
Route::middleware(['auth:sanctum', 'activo'])->group(function () {

    Route::get('/me', [Api\AuthController::class, 'me']);
    Route::get('/user', [Api\AuthController::class, 'me']);
    Route::post('/logout', [Api\AuthController::class, 'logout']);

    Route::get('/dashboard', Api\DashboardController::class);

    // Productos (la ruta barcode va antes de {producto} para evitar colisión)
    Route::get('/productos/barcode/{codigo}', [Api\ProductoController::class, 'porCodigo']);
    Route::get('/productos', [Api\ProductoController::class, 'index']);
    Route::get('/productos/{producto}', [Api\ProductoController::class, 'show']);

    Route::get('/categorias', [Api\CategoriaController::class, 'index']);
    Route::get('/categorias/{categoria}', [Api\CategoriaController::class, 'show']);

    Route::get('/ventas', [Api\VentaController::class, 'index']);
    Route::get('/ventas/{venta}', [Api\VentaController::class, 'show']);
    Route::post('/ventas', [Api\VentaController::class, 'store']);

    Route::get('/metodos-pago', [Api\CatalogoController::class, 'metodosPago']);
    Route::get('/alertas', Api\AlertaController::class);

    // ── Solo Administrador ──────────────────────────────────────────────
    Route::middleware('role:admin')->group(function () {
        Route::post('/productos', [Api\ProductoController::class, 'store']);
        // POST con {id} permite multipart/form-data con imagen desde Flutter
        Route::match(['post', 'put', 'patch'], '/productos/{producto}', [Api\ProductoController::class, 'update']);
        Route::delete('/productos/{producto}', [Api\ProductoController::class, 'destroy']);

        Route::post('/categorias', [Api\CategoriaController::class, 'store']);
        Route::match(['post', 'put', 'patch'], '/categorias/{categoria}', [Api\CategoriaController::class, 'update']);
        Route::delete('/categorias/{categoria}', [Api\CategoriaController::class, 'destroy']);

        Route::get('/movimientos', [Api\MovimientoController::class, 'index']);
        Route::post('/movimientos', [Api\MovimientoController::class, 'store']);

        Route::get('/proveedores', [Api\CatalogoController::class, 'proveedores']);
    });
});
