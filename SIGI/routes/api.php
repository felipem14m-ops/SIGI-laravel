<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes - SIGI Sistema de Inventario y Gestión Integrado
|--------------------------------------------------------------------------
| Todas las rutas aquí declaradas tienen el prefijo automático "/api".
| Compatibles con Flutter y pruebas en Postman.
*/

// =========================================================================
// 1. RUTAS PÚBLICAS (Sin token)
// =========================================================================
Route::post('/login',    [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// =========================================================================
// 2. RUTAS PROTEGIDAS (Requieren Bearer Token con Laravel Sanctum)
// =========================================================================
Route::middleware('auth:sanctum')->group(function () {

    // ── Autenticación y Perfil ──────────────────────────────────────────
    Route::get('/me',     [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user()->load('role');
    });

    // ── Dashboard & Métricas Móviles ────────────────────────────────────
    Route::get('/dashboard', [ApiController::class, 'dashboard']);

    // ── Productos ───────────────────────────────────────────────────────
    // NOTA: La ruta barcode va ANTES de {id} para evitar colisión de parámetros
    Route::get('/productos/barcode/{codigo}', [ApiController::class, 'productoPorCodigo']);
    Route::get('/productos',                  [ApiController::class, 'productos']);
    Route::get('/productos/{id}',              [ApiController::class, 'producto']);
    Route::post('/productos',                 [ApiController::class, 'guardarProducto']);
    Route::post('/productos/{id}',            [ApiController::class, 'actualizarProducto']); // Soporte multipart/form-data con imagen
    Route::put('/productos/{id}',             [ApiController::class, 'actualizarProducto']);
    Route::delete('/productos/{id}',          [ApiController::class, 'eliminarProducto']);

    // ── Categorías ──────────────────────────────────────────────────────
    Route::get('/categorias',          [ApiController::class, 'categorias']);
    Route::get('/categorias/{id}',      [ApiController::class, 'categoria']);
    Route::post('/categorias',         [ApiController::class, 'guardarCategoria']);
    Route::post('/categorias/{id}',    [ApiController::class, 'actualizarCategoria']); // Soporte multipart/form-data con imagen
    Route::put('/categorias/{id}',     [ApiController::class, 'actualizarCategoria']);
    Route::delete('/categorias/{id}',  [ApiController::class, 'eliminarCategoria']);

    // ── Ventas (Terminal POS Móvil) ─────────────────────────────────────
    Route::get('/ventas',      [ApiController::class, 'ventas']);
    Route::get('/ventas/{id}', [ApiController::class, 'venta']);
    Route::post('/ventas',     [ApiController::class, 'guardarVenta']);

    // ── Métodos de Pago ─────────────────────────────────────────────────
    Route::get('/metodos-pago', [ApiController::class, 'metodosPago']);

    // ── Alertas de Stock ────────────────────────────────────────────────
    Route::get('/alertas', [ApiController::class, 'alertas']);

    // ── Movimientos de Inventario (Kardex) ───────────────────────────────
    Route::get('/movimientos',  [ApiController::class, 'movimientos']);
    Route::post('/movimientos', [ApiController::class, 'guardarMovimiento']);

    // ── Proveedores ─────────────────────────────────────────────────────
    Route::get('/proveedores', [ApiController::class, 'proveedores']);
});
