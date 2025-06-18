{{-- resources/views/vendas/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalhes da Venda') }} #{{ $venda->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <p class="text-sm text-gray-600">ID da Venda:</p>
                            <p class="text-lg font-medium text-gray-900">{{ $venda->id }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Data da Venda:</p>
                            <p class="text-lg font-medium text-gray-900">{{ $venda->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Cliente:</p>
                            <p class="text-lg font-medium text-gray-900">{{ $venda->cliente->nome ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Vendedor:</p>
                            <p class="text-lg font-medium text-gray-900">{{ $venda->vendedor->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Forma de Pagamento:</p>
                            <p class="text-lg font-medium text-gray-900">{{ $venda->formaPagamento->descricao ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total da Venda:</p>
                            <p class="text-2xl font-bold text-gray-900">R$ {{ number_format($venda->total, 2, ',', '.') }}</p>
                        </div>
                    </div>

                    <h3 class="text-xl font-semibold text-gray-800 mt-8 mb-4">Itens da Venda</h3>
                    <div class="overflow-x-auto mb-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produto</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantidade</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Preço Unitário</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($venda->itensVenda as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->produto->nome ?? 'Produto Removido' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->quantidade }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">R$ {{ number_format($item->preco, 2, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">R$ {{ number_format($item->quantidade * $item->preco, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <h3 class="text-xl font-semibold text-gray-800 mt-8 mb-4">Parcelas</h3>
                    <div class="overflow-x-auto mb-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Número</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vencimento</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($venda->parcelas->sortBy('numero') as $parcela)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $parcela->numero }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">R$ {{ number_format($parcela->valor, 2, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $parcela->vencimento->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap capitalize">{{ $parcela->status }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-start mt-8 space-x-4">
                        <a href="{{ route('vendas.edit', $venda->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Editar Venda') }}
                        </a>
                        <a href="{{ route('vendas.exportPdf', $venda->id) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 active:bg-purple-900 focus:outline-none focus:border-purple-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150" target="_blank">
                            {{ __('Gerar PDF') }}
                        </a>
                        <form action="{{ route('vendas.destroy', $venda->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta venda? Esta ação é irreversível.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Excluir Venda') }}
                            </button>
                        </form>
                        <a href="{{ route('vendas.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Voltar para Vendas') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>