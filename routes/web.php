<?php

use App\Http\Controllers\Auth\InicioController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/entrar', [LoginController::class, 'index'])->name('entrar.index');
Route::post('/auth/entrar', [LoginController::class, 'entrar'])->name('entrar.auth');


Route::get('/', [InicioController::class, 'index'])->name('inicio.index');
