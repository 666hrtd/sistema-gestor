<?php

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