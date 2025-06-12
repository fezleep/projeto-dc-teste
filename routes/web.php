<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController; // Adicione esta linha

Route::get('/', function () {
    return view('welcome'); // Ou sua própria view inicial
});

// Rotas de recurso para Vendas (vai criar index, create, store, show, edit, update, destroy)
Route::resource('sales', SaleController::class);