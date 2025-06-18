{{-- resources/views/vendas/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Venda') }} #{{ $venda->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Ops!</strong>
                            <span class="block sm:inline">Houve alguns problemas com sua submissão.</span>
                            <ul class="mt-3 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('vendas.update', $venda->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- Importante para o método UPDATE --}}

                        <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="cliente_id" class="block text-sm font-medium text-gray-700">Cliente (Opcional)</label>
                                <select name="cliente_id" id="cliente_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">-- Selecione um Cliente --</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" {{ (old('cliente_id', $venda->cliente_id) == $cliente->id) ? 'selected' : '' }}>
                                            {{ $cliente->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="forma_pagamento_id" class="block text-sm font-medium text-gray-700">Forma de Pagamento <span class="text-red-500">*</span></label>
                                <select name="forma_pagamento_id" id="forma_pagamento_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">-- Selecione uma Forma de Pagamento --</option>
                                    @foreach($formasPagamento as $forma)
                                        <option value="{{ $forma->id }}" data-parcelas="{{ $forma->quantidade_parcelas }}" {{ (old('forma_pagamento_id', $venda->forma_pagamento_id) == $forma->id) ? 'selected' : '' }}>
                                            {{ $forma->descricao }} ({{ $forma->quantidade_parcelas }}x)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-6 border p-4 rounded-md bg-gray-50">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Itens da Venda <span class="text-red-500">*</span></h3>
                            <div id="itens-venda-container">
                                @foreach($venda->itensVenda as $index => $item)
                                    @include('vendas._partials.item_form', [
                                        'index' => $index,
                                        'item' => $item,
                                        'produtos' => $produtos,
                                        'selectedProdutoId' => $item->produto_id,
                                        'quantidade' => $item->quantidade,
                                        'preco' => $item->preco, // Usa o preço salvo na venda
                                    ])
                                @endforeach
                                {{-- Se não houver itens na venda, ou se houver um erro de validação e old() estiver vazio --}}
                                @if($venda->itensVenda->isEmpty() && !old('produtos'))
                                    @include('vendas._partials.item_form', ['index' => 0, 'produtos' => $produtos, 'selectedProdutoId' => null, 'quantidade' => null, 'preco' => 0])
                                @endif
                                {{-- Lógica para re-popular com old() values em caso de erro de validação --}}
                                @if(old('produtos'))
                                    @foreach(old('produtos') as $index => $item)
                                        {{-- Evitar duplicar itens que já foram carregados de $venda->itensVenda --}}
                                        @php
                                            $isExistingItem = false;
                                            foreach($venda->itensVenda as $existingItem) {
                                                if ($existingItem->produto_id == ($item['id'] ?? null) && $existingItem->quantidade == ($item['quantidade'] ?? null)) {
                                                    $isExistingItem = true;
                                                    break;
                                                }
                                            }
                                        @endphp
                                        @unless($isExistingItem)
                                            @include('vendas._partials.item_form', [
                                                'index' => $index + $venda->itensVenda->count(), // Garante um índice único
                                                'item' => (object)$item,
                                                'produtos' => $produtos,
                                                'selectedProdutoId' => $item['id'] ?? null,
                                                'quantidade' => $item['quantidade'] ?? null,
                                                'preco' => $produtos->firstWhere('id', $item['id'])->preco ?? 0, // Recalcula preço
                                            ])
                                        @endunless
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" id="add-item" class="mt-4 inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:border-green-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Adicionar Produto
                            </button>
                        </div>

                        <div class="mb-6 p-4 border rounded-md bg-gray-50 text-right">
                            <p class="text-lg font-bold text-gray-900">Total da Venda: <span id="total-venda">R$ {{ number_format($venda->total, 2, ',', '.') }}</span></p>
                            <input type="hidden" name="total_enviado" id="total_enviado" value="{{ $venda->total }}">
                        </div>

                        <div class="mb-6 border p-4 rounded-md bg-gray-50">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Parcelas</h3>
                            <div id="parcelas-container">
                                {{-- As parcelas existentes serão sobreescritas pelo JS --}}
                                @if($venda->parcelas->isNotEmpty())
                                    <div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200"><thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parcela</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vencimento (Estimado)</th></tr></thead><tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($venda->parcelas->sortBy('numero') as $parcela)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $parcela->numero }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">R$ {{ number_format($parcela->valor, 2, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $parcela->vencimento->format('d/m/Y') }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody></table></div>
                                @else
                                    <p class="text-gray-600">Selecione uma forma de pagamento para gerar as parcelas.</p>
                                @endif
                            </div>
                        </div>


                        <div class="flex items-center justify-end mt-8">
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-4">
                                Atualizar Venda
                            </button>
                            <a href="{{ route('vendas.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-200 border border-gray-300 rounded-md font-semibold text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        let itemIndex = {{ old('produtos') ? count(old('produtos')) : ($venda->itensVenda->count() > 0 ? $venda->itensVenda->count() : 1) }}; // Inicia o índice para novos itens

        function formatCurrency(value) {
            return 'R$ ' + parseFloat(value).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function calculateTotal() {
            let total = 0;
            $('#itens-venda-container .item-row').each(function() {
                const quantidade = parseFloat($(this).find('.item-quantidade').val()) || 0;
                const preco = parseFloat($(this).find('.item-preco-hidden').val()) || 0;
                total += (quantidade * preco);
            });
            $('#total-venda').text(formatCurrency(total));
            $('#total_enviado').val(total.toFixed(2)); // Envia o total formatado para 2 casas decimais
        }

        function generateParcelas() {
            const total = parseFloat($('#total_enviado').val()) || 0;
            const formaPagamentoSelect = $('#forma_pagamento_id option:selected');
            const quantidadeParcelas = parseInt(formaPagamentoSelect.data('parcelas')) || 1;
            const parcelasContainer = $('#parcelas-container');
            parcelasContainer.empty(); // Limpa parcelas anteriores

            if (total === 0 || quantidadeParcelas === 0) {
                 parcelasContainer.html('<p class="text-gray-600">Selecione uma forma de pagamento e adicione itens para gerar as parcelas.</p>');
                 return;
            }

            const valorPorParcela = total / quantidadeParcelas;

            let tableHtml = '<div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200"><thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parcela</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vencimento (Estimado)</th></tr></thead><tbody class="bg-white divide-y divide-gray-200">';
            for (let i = 1; i <= quantidadeParcelas; i++) {
                const vencimento = new Date();
                vencimento.setMonth(vencimento.getMonth() + i); // Vencimento em X meses
                const vencimentoFormatado = vencimento.toLocaleDateString('pt-BR'); // Formato dd/mm/yyyy

                tableHtml += `<tr>
                                    <td class="px-6 py-4 whitespace-nowrap">${i}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">R$ ${valorPorParcela.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">${vencimentoFormatado}</td>
                                </tr>`;
            }
            tableHtml += '</tbody></table></div>';
            parcelasContainer.html(tableHtml);
        }

        $(document).ready(function() {
            // Re-calcula total e parcelas ao carregar a página (útil para old() values e para carregar edição)
            calculateTotal();
            generateParcelas();

            // Adicionar Item
            $('#add-item').on('click', function() {
                const newItemHtml = `@include('vendas._partials.item_form', ['index' => 'ITEM_INDEX_PLACEHOLDER', 'produtos' => $produtos, 'selectedProdutoId' => null, 'quantidade' => null, 'preco' => 0])`;
                $('#itens-venda-container').append(newItemHtml.replace(/ITEM_INDEX_PLACEHOLDER/g, itemIndex));
                itemIndex++;
                calculateTotal(); // Recalcula quando um novo item é adicionado
                generateParcelas(); // Recalcula parcelas também
            });

            // Remover Item (usa delegação de evento para elementos adicionados dinamicamente)
            $(document).on('click', '.remove-item', function() {
                $(this).closest('.item-row').remove();
                calculateTotal();
                generateParcelas();
            });

            // Reagir a mudanças no produto ou quantidade
            $(document).on('change', '.item-produto, .item-quantidade', function() {
                const itemRow = $(this).closest('.item-row');
                const produtoId = itemRow.find('.item-produto').val();
                const quantidade = itemRow.find('.item-quantidade').val();

                if (produtoId) {
                    // Pega o preço do produto selecionado (do array JS `produtosData`)
                    const produtoPreco = produtosData[produtoId] || 0; // Use o mapa de produtos
                    itemRow.find('.item-preco-hidden').val(produtoPreco);
                    itemRow.find('.item-preco-display').text(formatCurrency(produtoPreco));
                } else {
                    itemRow.find('.item-preco-hidden').val(0);
                    itemRow.find('.item-preco-display').text(formatCurrency(0));
                }
                calculateTotal();
                generateParcelas();
            });

            // Reagir a mudança na forma de pagamento
            $('#forma_pagamento_id').on('change', function() {
                generateParcelas();
            });

            // Inicializar produtosData do PHP para JavaScript (necessário para pegar preços)
            const produtosData = {};
            @foreach($produtos as $produto)
                produtosData[{{ $produto->id }}] = parseFloat({{ $produto->preco }});
            @endforeach
        });
    </script>
    @endpush
</x-app-layout>