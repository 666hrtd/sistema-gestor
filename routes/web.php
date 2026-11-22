<?php

use App\Http\Controllers\LoginController;
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
    ->middleware('signed')
    ->name('verificacion');

Route::post('/verificacion', [VerificacionController::class, 'verificar'])
    ->middleware('signed');