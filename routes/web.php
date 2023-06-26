<?php

use App\Http\Controllers\Auth\HomeController;
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

Route::get('/entrar', [LoginController::class, 'index'])->name('login.index');
Route::post('/auth/entrar', [LoginController::class, 'login'])->name('login.auth');

Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home.index');
    Route::get('/importacoes', [HomeController::class, 'getImports'])->name('home.getImports');
    Route::post('/importar', [HomeController::class, 'importExcel'])->name('home.importExcel');
    Route::delete('/deletarArquivo/{id}', [HomeController::class, 'deleteFile'])->name('home.deleteFile');
});
