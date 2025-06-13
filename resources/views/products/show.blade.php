<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalhes do Produto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="mb-4">{{ $product->name }}</h3>

                <div class="mb-3">
                    <strong>ID:</strong> {{ $product->id }}
                </div>
                <div class="mb-3">
                    <strong>Descrição:</strong> {{ $product->description ?? 'N/A' }}
                </div>
                <div class="mb-3">
                    <strong>Preço:</strong> R$ {{ number_format($product->price, 2, ',', '.') }}
                </div>
                <div class="mb-3">
                    <strong>Estoque:</strong> {{ $product->stock }}
                </div>
                <div class="mb-3">
                    <strong>Cadastrado em:</strong> {{ $product->created_at->format('d/m/Y H:i') }}
                </div>
                <div class="mb-3">
                    <strong>Última atualização:</strong> {{ $product->updated_at->format('d/m/Y H:i') }}
                </div>

                <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">Editar</a>
                <a href="{{ route('products.index') }}" class="btn btn-secondary ms-2">Voltar para a Lista</a>
            </div>
        </div>
    </div>
</x-app-layout>