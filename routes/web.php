<?php

use App\Http\Controllers\EquipoController;
use App\Http\Controllers\ReservacionController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificacionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.store');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::get('/verificacion', [VerificacionController::class, 'mostrar'])
    ->middleware(['signed', 'pending2fa'])
    ->name('verificacion');

Route::middleware(['auth', 'role:admin,empleado'])->group(function () {
    Route::resource('users', UserController::class)->except(['destroy']);
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::delete('/users/{id}/force', [UserController::class, 'forceDestroy'])->name('users.force');
    Route::get('/papelera/usuarios', [UserController::class, 'trashed'])->name('users.trashed');
    Route::patch('/papelera/usuarios/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
});

Route::middleware(['auth', 'role:profesor'])->group(function () {
    Route::resource('alumnos', AlumnoController::class)->except(['show']);
});

Route::get('/mi-informacion', [AlumnoController::class, 'miInformacion'])
    ->middleware(['auth', 'role:alumno'])
    ->name('alumnos.mi');

    Route::post('/verificacion', [VerificacionController::class, 'verificar'])
    ->middleware(['signed', 'pending2fa']);

    Route::get('/productos', [ProductoController::class, 'index'])
    ->middleware(['auth', 'role:admin,empleado'])
    ->name('productos.index');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('productos', ProductoController::class)->except(['index', 'show']);
});

Route::middleware(['auth', 'role:admin,estudiante'])->group(function () {
    Route::get('/equipos', [EquipoController::class, 'index'])->name('equipos.index');
    Route::get('/reservaciones', [ReservacionController::class, 'index'])->name('reservaciones.index');
    Route::delete('/reservaciones/{reservacion}', [ReservacionController::class, 'destroy'])->name('reservaciones.destroy');

    Route::post('/equipos/{equipo}/reservar', [ReservacionController::class, 'store'])
        ->middleware('role:estudiante')
        ->name('reservaciones.store');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('equipos', EquipoController::class)->except(['index', 'show']);
});