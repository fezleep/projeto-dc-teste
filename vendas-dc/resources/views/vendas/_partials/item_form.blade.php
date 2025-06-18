{{-- resources/views/vendas/_partials/item_form.blade.php --}}
@php
    // Define um índice único para o item, usado para os nomes e IDs dos campos.
    // Se 'index' não for fornecido (para um novo item via JS), usa 'new_X' onde X é um número aleatório ou incremental no JS.
    // Para itens existentes (em edição), 'index' será o índice do array ou a chave do item.
    $itemIndex = $index ?? 'new_'.uniqid(); // Usar uniqid para evitar colisões ao adicionar via JS
    $oldProduct = $oldProduct ?? []; // Garante que $oldProduct é um array, mesmo que vazio
    $selectedProductId = $oldProduct['id'] ?? ($item->produto_id ?? null);
    $selectedQuantity = $oldProduct['quantidade'] ?? ($item->quantidade ?? 1);
    $selectedPrice = $oldProduct['preco'] ?? ($item->preco ?? 0);
@endphp

<div class="item-row mb-4 p-4 border rounded-md bg-gray-50 relative">
    <button type="button" class="remove-item absolute top-2 right-2 text-red-500 hover:text-red-700 text-lg">&times;</button>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label for="produto_id_{{ $itemIndex }}" class="block text-sm font-medium text-gray-700">Produto</label>
            <select name="produtos[{{ $itemIndex }}][id]" id="produto_id_{{ $itemIndex }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm product-select" required>
                <option value="">Selecione um Produto</option>
                @foreach($produtos as $produto)
                    <option value="{{ $produto->id }}" data-preco="{{ $produto->preco }}" {{ $selectedProductId == $produto->id ? 'selected' : '' }}>
                        {{ $produto->nome }} (R$ {{ number_format($produto->preco, 2, ',', '.') }})
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="quantidade_{{ $itemIndex }}" class="block text-sm font-medium text-gray-700">Quantidade</label>
            <input type="number" name="produtos[{{ $itemIndex }}][quantidade]" id="quantidade_{{ $itemIndex }}" value="{{ $selectedQuantity }}" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm quantity-input" required>
        </div>
        <div>
            <label for="preco_{{ $itemIndex }}" class="block text-sm font-medium text-gray-700">Preço Unitário</label>
            {{-- O campo de preço é apenas para exibição e é preenchido via JS com o preço do produto do BD --}}
            <input type="number" name="produtos[{{ $itemIndex }}][preco]" id="preco_{{ $itemIndex }}" value="{{ number_format($selectedPrice, 2, '.', '') }}" step="0.01" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm price-input" readonly required>
        </div>
    </div>
</div>

{{-- resources/views/vendas/_partials/item_form.blade.php --}}
<div class="item-row grid grid-cols-1 md:grid-cols-4 gap-4 items-center mb-4 p-3 border rounded-md bg-white shadow-sm">
    <div class="col-span-1 md:col-span-2">
        <label for="produtos[{{ $index }}][id]" class="block text-sm font-medium text-gray-700">Produto <span class="text-red-500">*</span></label>
        <select name="produtos[{{ $index }}][id]" id="produtos[{{ $index }}][id]" class="item-produto mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
            <option value="">-- Selecione um Produto --</option>
            @foreach($produtos as $produto)
                <option value="{{ $produto->id }}" data-preco="{{ $produto->preco }}" {{ (isset($item) && $item->id == $produto->id) || (isset($selectedProdutoId) && $selectedProdutoId == $produto->id) ? 'selected' : '' }}>
                    {{ $produto->nome }} (R$ {{ number_format($produto->preco, 2, ',', '.') }})
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="produtos[{{ $index }}][quantidade]" class="block text-sm font-medium text-gray-700">Quantidade <span class="text-red-500">*</span></label>
        <input type="number" name="produtos[{{ $index }}][quantidade]" id="produtos[{{ $index }}][quantidade]" class="item-quantidade mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ (isset($item) && $item->quantidade) ? $item->quantidade : (isset($quantidade) ? $quantidade : 1) }}" min="1" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Preço Unitário</label>
        <p class="mt-1 text-gray-900 text-lg font-semibold"><span class="item-preco-display">R$ {{ number_format((isset($preco) && $preco) ? $preco : 0, 2, ',', '.') }}</span></p>
        <input type="hidden" class="item-preco-hidden" value="{{ (isset($preco) && $preco) ? $preco : 0 }}">
    </div>
    <div class="flex justify-end items-center mt-4 md:mt-0">
        @if($index > 0 || old('produtos') ) {{-- Só mostra o botão de remover se não for o primeiro item ou se tiver itens via old() --}}
            <button type="button" class="remove-item inline-flex items-center px-3 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:border-red-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                Remover
            </button>
        @endif
    </div>
</div>