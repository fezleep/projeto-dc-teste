{{-- resources/views/vendas/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Nova Venda') }}
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

                    <form action="{{ route('vendas.store') }}" method="POST" x-data="vendaForm()">
                        @csrf

                        {{-- Informações Básicas da Venda --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="cliente_id" class="block text-sm font-medium text-gray-700">Cliente (Opcional)</label>
                                <select id="cliente_id" name="cliente_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="">-- Selecione um Cliente --</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>{{ $cliente->nome }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="forma_pagamento_id" class="block text-sm font-medium text-gray-700">Forma de Pagamento <span class="text-red-500">*</span></label>
                                <select id="forma_pagamento_id" name="forma_pagamento_id" x-model="selectedFormaPagamentoId" @change="updateFormaPagamento" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="">-- Selecione uma Forma de Pagamento --</option>
                                    @foreach($formasPagamento as $forma)
                                        <option value="{{ $forma->id }}" data-parcelas="{{ $forma->quantidade_parcelas }}" {{ old('forma_pagamento_id') == $forma->id ? 'selected' : '' }}>
                                            {{ $forma->descricao }} ({{ $forma->quantidade_parcelas }}x)
                                        </option>
                                    @endforeach
                                </select>
                                @error('forma_pagamento_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Itens da Venda --}}
                        <h2 class="text-xl font-semibold mb-4 text-gray-800">Itens da Venda <span class="text-red-500">*</span></h2>
                        <div id="itens-venda-container">
                            <template x-for="(item, index) in itens" :key="index">
                                <div class="flex items-center space-x-4 mb-4 bg-gray-50 p-4 rounded-md border border-gray-200">
                                    <div class="flex-grow grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label :for="`produto_${index}`" class="block text-xs font-medium text-gray-600">Produto</label>
                                            <select :id="`produto_${index}`" :name="`produtos[${index}][id]`" x-model="item.produto_id" @change="updateProdutoPrice(index)" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                                <option value="">Selecione um Produto</option>
                                                @foreach($produtos as $produto)
                                                    <option value="{{ $produto->id }}" data-preco="{{ $produto->preco }}">{{ $produto->nome }} (R$ {{ number_format($produto->preco, 2, ',', '.') }})</option>
                                                @endforeach
                                            </select>
                                            {{-- Removido: @error('produtos.' . $index . '.id') --}}
                                        </div>
                                        <div>
                                            <label :for="`quantidade_${index}`" class="block text-xs font-medium text-gray-600">Quantidade</label>
                                            <input type="number" :id="`quantidade_${index}`" :name="`produtos[${index}][quantidade]`" x-model.number="item.quantidade" @input="calculateTotal" min="1" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            {{-- Removido: @error('produtos.' . $index . '.quantidade') --}}
                                        </div>
                                        <div>
                                            <label :for="`preco_${index}`" class="block text-xs font-medium text-gray-600">Preço Unitário</label>
                                            <input type="text" :id="`preco_${index}`" x-model="formatCurrency(item.preco_unitario)" readonly class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-100 cursor-not-allowed">
                                        </div>
                                    </div>
                                    <button type="button" @click="removeItem(index)" class="p-2 text-red-600 hover:text-red-800 focus:outline-none" x-show="itens.length > 1">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </template>
                            @error('produtos') {{-- Erro geral se não houver nenhum item ou se os itens forem inválidos --}}
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="button" @click="addItem" class="flex items-center px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mt-4">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m0 0H6"></path></svg>
                            Adicionar Produto
                        </button>

                        <hr class="my-8 border-t border-gray-300">

                        {{-- Resumo da Venda --}}
                        <div class="flex justify-between items-center text-2xl font-bold text-gray-800 mb-6">
                            <span>Total da Venda:</span>
                            <span x-text="formatCurrency(totalVenda)">R$ 0,00</span>
                            <input type="hidden" name="total_enviado" x-model="totalVenda"> {{-- Campo oculto para enviar o total para o backend --}}
                            @error('total_enviado')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Parcelas --}}
                        <h2 class="text-xl font-semibold mb-4 text-gray-800">Parcelas</h2>
                        <div x-show="quantidadeParcelas > 0" class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200 rounded-md">
                                <thead>
                                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                        <th class="py-3 px-6 text-left">Parcela</th>
                                        <th class="py-3 px-6 text-left">Valor</th>
                                        <th class="py-3 px-6 text-left">Vencimento (Estimado)</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 text-sm font-light">
                                    <template x-for="i in quantidadeParcelas" :key="i">
                                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                                            <td class="py-3 px-6 text-left whitespace-nowrap" x-text="i"></td>
                                            <td class="py-3 px-6 text-left" x-text="formatCurrency(totalVenda / quantidadeParcelas)"></td>
                                            <td class="py-3 px-6 text-left" x-text="formatDate(addDays(new Date(), 30 * i))"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        <div x-show="quantidadeParcelas === 0" class="p-4 bg-gray-50 text-gray-500 border border-gray-200 rounded-md text-center">
                            Selecione uma forma de pagamento para ver as parcelas.
                        </div>


                        {{-- Botões de Ação --}}
                        <div class="flex justify-end mt-8 space-x-4">
                            <a href="{{ route('vendas.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Cancelar</a>
                            <button type="submit" class="px-6 py-3 bg-green-600 text-white font-semibold rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">Registrar Venda</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function vendaForm() {
                return {
                    clientes: @json($clientes),
                    produtos: @json($produtos),
                    formasPagamento: @json($formasPagamento),
                    itens: [],
                    totalVenda: 0,
                    selectedFormaPagamentoId: '{{ old('forma_pagamento_id') }}', // Inicia com old value
                    quantidadeParcelas: 0,

                    init() {
                        // Carregar itens antigos se a validação falhou
                        @if(old('produtos'))
                            this.itens = @json(old('produtos')).map(item => {
                                const produto = this.produtos.find(p => p.id == item.id);
                                return {
                                    produto_id: item.id,
                                    quantidade: parseInt(item.quantidade),
                                    preco_unitario: produto ? parseFloat(produto.preco) : 0
                                };
                            });
                        @else
                            this.addItem(); // Adiciona um item vazio se não houver old data
                        @endif

                        this.calculateTotal();
                        this.updateFormaPagamento(); // Atualiza as parcelas com base na forma de pagamento selecionada (old ou padrão)
                    },

                    addItem() {
                        this.itens.push({
                            produto_id: '',
                            quantidade: 1,
                            preco_unitario: 0
                        });
                        this.calculateTotal();
                    },

                    removeItem(index) {
                        this.itens.splice(index, 1);
                        this.calculateTotal();
                    },

                    updateProdutoPrice(index) {
                        const selectedProdutoId = this.itens[index].produto_id;
                        const produto = this.produtos.find(p => p.id == selectedProdutoId);
                        if (produto) {
                            this.itens[index].preco_unitario = parseFloat(produto.preco);
                        } else {
                            this.itens[index].preco_unitario = 0;
                        }
                        this.calculateTotal();
                    },

                    calculateTotal() {
                        let total = 0;
                        this.itens.forEach(item => {
                            total += (item.preco_unitario || 0) * (item.quantidade || 0); // Garante que é numérico
                        });
                        this.totalVenda = total;
                    },

                    updateFormaPagamento() {
                        const selectedForma = this.formasPagamento.find(f => f.id == this.selectedFormaPagamentoId);
                        if (selectedForma) {
                            this.quantidadeParcelas = selectedForma.quantidade_parcelas;
                        } else {
                            this.quantidadeParcelas = 0;
                        }
                    },

                    formatCurrency(value) {
                        return new Intl.NumberFormat('pt-BR', {
                            style: 'currency',
                            currency: 'BRL'
                        }).format(value);
                    },

                    // Função para adicionar dias a uma data
                    addDays(date, days) {
                        const result = new Date(date);
                        result.setDate(result.getDate() + days);
                        return result;
                    },

                    // Função para formatar a data (dd/mm/yyyy)
                    formatDate(date) {
                        const options = { year: 'numeric', month: '2-digit', day: '2-digit' };
                        return date.toLocaleDateString('pt-BR', options);
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>