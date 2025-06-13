<x-app-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Painel de Controle') }} {{-- Um título mais clássico para o dashboard --}}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="mb-4 text-center text-dark">Bem-vindo(a) ao seu Sistema de Vendas!</h3>
                <p class="text-center text-secondary mb-5">Tenha uma visão geral e acesso rápido às funcionalidades essenciais.</p>

                {{-- Seção de Estatísticas Rápidas (Tema Alvinegro) --}}
                <h4 class="mb-4 text-secondary">Estatísticas Rápidas</h4>
                <div class="row g-4 mb-5"> {{-- 'g-4' para espaçamento entre colunas --}}
                    <div class="col-md-4">
                        <div class="card text-white bg-dark h-100 shadow-sm border border-secondary"> {{-- Card escuro --}}
                            <div class="card-body d-flex flex-column justify-content-between">
                                <h5 class="card-title fw-bold">Total de Clientes</h5>
                                <p class="card-text fs-1 mb-3">{{ \App\Models\Client::count() }}</p>
                                <a href="{{ route('clients.index') }}" class="btn btn-outline-light btn-sm mt-auto">Gerenciar Clientes <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-dark bg-light h-100 shadow-sm border border-secondary"> {{-- Card claro --}}
                            <div class="card-body d-flex flex-column justify-content-between">
                                <h5 class="card-title fw-bold">Produtos Disponíveis</h5>
                                <p class="card-text fs-1 mb-3">{{ \App\Models\Product::count() }}</p>
                                <a href="{{ route('products.index') }}" class="btn btn-outline-dark btn-sm mt-auto">Gerenciar Produtos <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-dark h-100 shadow-sm border border-secondary"> {{-- Card escuro --}}
                            <div class="card-body d-flex flex-column justify-content-between">
                                <h5 class="card-title fw-bold">Vendas Registradas</h5>
                                <p class="card-text fs-1 mb-3">{{ \App\Models\Sale::count() }}</p>
                                <a href="{{ route('sales.index') }}" class="btn btn-outline-light btn-sm mt-auto">Visualizar Vendas <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Seção de Ações Rápidas (Tema Alvinegro) --}}
                <h4 class="mb-4 text-secondary">Ações Rápidas</h4>
                <div class="d-grid gap-2 d-md-flex justify-content-md-center mb-5">
                    <a href="{{ route('sales.create') }}" class="btn btn-dark btn-lg me-md-2"> {{-- Botão escuro --}}
                        <i class="bi bi-plus-circle"></i> Registrar Nova Venda
                    </a>
                    <a href="{{ route('clients.create') }}" class="btn btn-outline-dark btn-lg me-md-2"> {{-- Botão com borda escura --}}
                        <i class="bi bi-person-plus"></i> Cadastrar Cliente
                    </a>
                    <a href="{{ route('products.create') }}" class="btn btn-outline-dark btn-lg"> {{-- Botão com borda escura --}}
                        <i class="bi bi-box"></i> Cadastrar Produto
                    </a>
                </div>

                {{-- Placeholder para Vendas Recentes --}}
                <h4 class="mb-4 text-secondary">Vendas Recentes</h4>
                <div class="alert alert-secondary text-center" role="alert"> {{-- Alerta secundário (cinza) --}}
                    As vendas mais recentes aparecerão aqui em breve. (Requer implementação no controller e modelo)
                </div>

                <p class="mt-5 text-center text-muted small">Mantenha seu sistema atualizado para uma gestão eficiente!</p>

            </div>
        </div>
    </div>
</x-app-layout>