<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::prefix('operacoes')->group(function(){
    Route::get('/', [OperacaoController::class, 'index'])->name('operacoes.index');
    Route::post('/store', [OperacaoController::class, 'store'])->name('operacoes.store');
    Route::put('/update', [OperacaoController::class, 'update'])->name('operacoes.update');
    Route::delete('/{id}', [OperacaoController::class, 'destroy'])->name('operacoes.destroy');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
