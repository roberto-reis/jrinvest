<?php

use App\Domain\Ativo\Controllers\AtivoController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Domain\Operacao\Controllers\OperacaoController;
use App\Domain\Dashboard\Controllers\DashboardController;

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

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('operacoes')->group(function(){
    Route::get('/', [OperacaoController::class, 'index'])->name('operacoes.index');
    Route::post('/store', [OperacaoController::class, 'store'])->name('operacoes.store');
    Route::put('/update', [OperacaoController::class, 'update'])->name('operacoes.update');
    Route::delete('{id}/destroy', [OperacaoController::class, 'destroy'])->name('operacoes.destroy');
});

Route::prefix('ativos')->group(function(){
    Route::get('/', [AtivoController::class, 'index'])->name('ativos.index');
    Route::get('/create', [AtivoController::class, 'create'])->name('ativos.create');
    Route::post('/store', [AtivoController::class, 'store'])->name('ativos.store');
    // Route::put('/update', [AtivoController::class, 'update'])->name('ativos.update');
    // Route::delete('{id}/destroy', [AtivoController::class, 'destroy'])->name('ativos.destroy');
});

require __DIR__.'/auth.php';
