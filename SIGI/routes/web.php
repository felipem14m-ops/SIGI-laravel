<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController as Usuariocontroller;

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
});

require __DIR__ . '/auth.php';
