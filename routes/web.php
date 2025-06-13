<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController; // Importe o ClientController
use App\Http\Controllers\ProductController; // Importe o ProductController
use App\Http\Controllers\SaleController; // Importe o SaleController

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

// Rota da Página Inicial (/)
// Se o usuário não estiver logado, redireciona para a página de login.
// Se o usuário estiver logado, redireciona para o dashboard.
Route::get('/', function () {
    return redirect()->route('dashboard'); // Redireciona para o dashboard se logado
})->middleware(['auth']); // Protege a rota, exigindo login

// Se o usuário não estiver logado, o middleware 'auth' do '/' acima
// vai redirecionar para a página de login.

// Rota do Dashboard (já existente e configurada pelo Laravel Breeze/Auth)
// Acessível apenas para usuários autenticados e verificados.
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Agrupamento de rotas que exigem autenticação
Route::middleware('auth')->group(function () {
    // Rotas para o perfil do usuário (padrão do Laravel Breeze/Auth)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotas de Recursos (CRUD) para Clientes, Produtos e Vendas
    // Laravel automaticamente cria as rotas: index, create, store, show, edit, update, destroy
    Route::resource('clients', ClientController::class);
    Route::resource('products', ProductController::class);
    Route::resource('sales', SaleController::class);

    // Se você precisar de rotas adicionais específicas, pode adicioná-las aqui:
    // Exemplo: Route::get('/sales/{sale}/invoice', [SaleController::class, 'generateInvoice'])->name('sales.invoice');
});

// Inclui as rotas de autenticação (login, registro, etc.) geradas pelo Laravel Breeze/Auth
require __DIR__.'/auth.php';