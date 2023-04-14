<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/acerca', [MainController::class, 'acerca_de'])->name('acerca');
Route::get('/', [PostController::class, 'index'])->name('home');
Route::get('/categoria/{categoria:descripcion}', [PostController::class, 'porCategoria'])->name('por-categoria');
Route::get('/{post:descripcion}', [PostController::class, 'show'])->name('view');