<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VendaController; // Importe o VendaController
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotas para Vendas (usando Route::resource para conveniência)
    Route::resource('vendas', VendaController::class);

    // Rota para exportar PDF
    Route::get('/vendas/{venda}/pdf', [VendaController::class, 'exportPdf'])->name('vendas.exportPdf');
});

require __DIR__.'/auth.php';