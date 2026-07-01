<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

    return match ((int) $user->id_rol) {
        1       => view('Admin.dashboard'),
        default => view('Empleado.dashboard'),
    };
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
