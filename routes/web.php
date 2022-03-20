<?php

use App\Domain\Ativo\Controllers\AtivoController;
use App\Domain\ClasseAtivo\Controllers\ClasseAtivoController;
use Illuminate\Support\Facades\Route;
use App\Domain\Operacao\Controllers\OperacaoController;
use App\Domain\Dashboard\Controllers\DashboardController;
use App\Domain\Rebalanceamento\Controllers\RebalanceamentoController;

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

Route::get('/', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->prefix('operacoes')->group(function(){
    Route::get('/', [OperacaoController::class, 'index'])->name('operacoes.index');
    Route::post('/store', [OperacaoController::class, 'store'])->name('operacoes.store');
    Route::put('/update', [OperacaoController::class, 'update'])->name('operacoes.update');
    Route::delete('{id}/destroy', [OperacaoController::class, 'destroy'])->name('operacoes.destroy');
    Route::get('export', [OperacaoController::class, 'export'])->name('operacoes.export');
});

Route::middleware(['auth', 'verified'])->prefix('ativos')->group(function(){
    Route::get('/', [AtivoController::class, 'index'])->name('ativos.index');
    Route::get('/create', [AtivoController::class, 'create'])->name('ativos.create');
    Route::post('/store', [AtivoController::class, 'store'])->name('ativos.store');
    Route::get('/{id}/edit', [AtivoController::class, 'edit'])->name('ativos.edit');
    Route::put('/update', [AtivoController::class, 'update'])->name('ativos.update');
    Route::delete('{id}/destroy', [AtivoController::class, 'destroy'])->name('ativos.destroy');
});

Route::middleware(['auth', 'verified'])->prefix('classe_ativo')->group(function(){
    Route::get('/', [ClasseAtivoController::class, 'index'])->name('classe_ativo.index');
    Route::post('/store', [ClasseAtivoController::class, 'store'])->name('classe_ativo.store');
    Route::put('/update', [ClasseAtivoController::class, 'update'])->name('classe_ativo.update');
    Route::delete('{id}/destroy', [ClasseAtivoController::class, 'destroy'])->name('classe_ativo.destroy');
});

Route::middleware(['auth', 'verified'])->prefix('rebalanceamento')->group(function(){
    // Rebalanceamento por Classe de Ativo
    Route::get('/', [RebalanceamentoController::class, 'index'])->name('rebalanceamento.index');
    Route::post('rebalanceamento-classe/store', [RebalanceamentoController::class, 'percentualClasseStore'])->name('rebalanceamento.percentualClasseStore');
    Route::put('rebalanceamento-classe/update', [RebalanceamentoController::class, 'percentualClasseUpdate'])->name('rebalanceamento.percentualClasseUpdate');
    Route::delete('rebalanceamento-classe/{id}/destroy', [RebalanceamentoController::class, 'percentualClasseDestroy'])->name('rebalanceamento.percentualClasseDestroy');

    // Rebalanceamento por Ativo
    Route::post('rebalanceamento-ativo/store', [RebalanceamentoController::class, 'percentualAtivoStore'])->name('rebalanceamento.percentualAtivoStore');
    Route::put('rebalanceamento-ativo/update', [RebalanceamentoController::class, 'percentualAtivoUpdate'])->name('rebalanceamento.percentualAtivoUpdate');
    Route::delete('rebalanceamento-ativo/{id}/destroy', [RebalanceamentoController::class, 'percentualAtivoDestroy'])->name('rebalanceamento.percentualAtivoDestroy');
});

require __DIR__.'/auth.php';
