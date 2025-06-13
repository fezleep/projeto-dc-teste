<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Venda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                {{-- Exibir erros de validação --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('sales.update', $sale) }}" method="POST" id="sale-edit-form">
                    @csrf
                    @method('PUT') {{-- Indica que é uma requisição de atualização --}}

                    <div class="mb-3">
                        <label for="client_id" class="form-label">Cliente (Opcional):</label>
                        <select class="form-select" id="client_id" name="client_id">
                            <option value="">Selecione um Cliente</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id', $sale->client_id) == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Forma de Pagamento:</label>
                        <select class="form-select" id="payment_method" name="payment_method">
                            <option value="Dinheiro" {{ old('payment_method', $sale->payment_method) == 'Dinheiro' ? 'selected' : '' }}>Dinheiro</option>
                            <option value="Cartao de Crédito" {{ old('payment_method', $sale->payment_method) == 'Cartao de Crédito' ? 'selected' : '' }}>Cartão de Crédito</option>
                            <option value="Cartao de Débito" {{ old('payment_method', $sale->payment_method) == 'Cartao de Débito' ? 'selected' : '' }}>Cartão de Débito</option>
                            <option value="Pix" {{ old('payment_method', $sale->payment_method) == 'Pix' ? 'selected' : '' }}>Pix</option>
                            <option value="Parcelado" {{ old('payment_method', $sale->payment_method) == 'Parcelado' ? 'selected' : '' }}>Parcelado</option>
                        </select>
                        @error('payment_method')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>
                    <h2>Itens da Venda</h2>
                    <div id="sale-items-container">
                        @foreach($sale->items as $index => $item)
                            <div class="row mb-3 sale-item">
                                <div class="col-md-5">
                                    <label class="form-label">Produto:</label>
                                    <select class="form-select product-select" name="items[{{ $index }}][product_id]">
                                        <option value="">Selecione um Produto</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-price="{{ $product->price }}" {{ old("items.$index.product_id", $item->product_id) == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }} (R$ {{ number_format($product->price, 2, ',', '.') }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error("items.$index.product_id")
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Quantidade:</label>
                                    <input type="number" class="form-control quantity-input" name="items[{{ $index }}][quantity]" value="{{ old("items.$index.quantity", $item->quantity) }}" min="1">
                                    @error("items.$index.quantity")
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Preço Unitário:</label>
                                    <input type="text" class="form-control price-at-sale-input" name="items[{{ $index }}][price_at_sale]" value="{{ number_format(old("items.$index.price_at_sale", $item->price_at_sale), 2, ',', '.') }}" readonly>
                                    @error("items.$index.price_at_sale")
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger remove-item-btn" @if(count($sale->items) === 1) style="display:none;" @endif>Remover</button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div id="sale-item-template" style="display:none;">
                        <div class="row mb-3 sale-item">
                            <div class="col-md-5">
                                <label class="form-label">Produto:</label>
                                <select class="form-select product-select" data-name="items[INDEX][product_id]">
                                    <option value="">Selecione um Produto</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }} (R$ {{ number_format($product->price, 2, ',', '.') }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Quantidade:</label>
                                <input type="number" class="form-control quantity-input" data-name="items[INDEX][quantity]" value="1" min="1">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Preço Unitário:</label>
                                <input type="text" class="form-control price-at-sale-input" data-name="items[INDEX][price_at_sale]" readonly>
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-danger remove-item-btn">Remover</button>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="add-item-btn" class="btn btn-secondary mb-3">Adicionar Item</button>

                    <div class="mb-3">
                        <label for="total" class="form-label">Total da Venda:</label>
                        <input type="text" class="form-control" id="total" name="total" value="{{ number_format(old('total', $sale->total), 2, ',', '.') }}" readonly>
                        @error('total')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-dark mt-3">Atualizar Venda</button>
                    <a href="{{ route('sales.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
                </form>
            </div>
        </div>
    </div>

    {{-- Script JavaScript para a lógica de itens de venda e cálculo do total --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const saleItemsContainer = document.getElementById('sale-items-container');
            const addItemBtn = document.getElementById('add-item-btn');
            const saleItemTemplate = document.getElementById('sale-item-template');
            const totalInput = document.getElementById('total');
            const saleEditForm = document.getElementById('sale-edit-form'); // Referência ao formulário de edição

            // Encontra o maior índice existente para começar a adicionar novos itens a partir dele
            let itemIndex = Array.from(saleItemsContainer.querySelectorAll('.sale-item')).reduce((maxIndex, item) => {
                const nameAttr = item.querySelector('.product-select').getAttribute('name');
                const match = nameAttr ? nameAttr.match(/items\[(\d+)\]/) : null;
                return match ? Math.max(maxIndex, parseInt(match[1])) : maxIndex;
            }, -1);


            function calculateTotal() {
                let total = 0;
                saleItemsContainer.querySelectorAll('.sale-item').forEach(function(item) {
                    const priceInput = item.querySelector('.price-at-sale-input');
                    const quantityInput = item.querySelector('.quantity-input');

                    const price = parseFloat(priceInput.value.replace('.', '').replace(',', '.')) || 0;
                    const quantity = parseInt(quantityInput.value) || 0;

                    total += price * quantity;
                });
                totalInput.value = total.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

            function initializeItemListeners(itemElement) {
                const productSelect = itemElement.querySelector('.product-select');
                const quantityInput = itemElement.querySelector('.quantity-input');
                const priceAtSaleInput = itemElement.querySelector('.price-at-sale-input');
                const removeItemBtn = itemElement.querySelector('.remove-item-btn');

                productSelect.addEventListener('change', function() {
                    const selectedOption = productSelect.options[productSelect.selectedIndex];
                    const price = selectedOption.dataset.price;
                    priceAtSaleInput.value = (price ? parseFloat(price).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '0,00');
                    calculateTotal();
                });

                quantityInput.addEventListener('input', calculateTotal);

                removeItemBtn.addEventListener('click', function() {
                    itemElement.remove();
                    if (saleItemsContainer.querySelectorAll('.sale-item').length === 1) {
                         saleItemsContainer.querySelector('.sale-item .remove-item-btn').style.display = 'none';
                    }
                    calculateTotal();
                });

                 // Dispara o 'change' inicial para preencher o preço_unitario e recalcular o total
                productSelect.dispatchEvent(new Event('change'));
            }

            // Inicializa listeners para todos os itens de venda existentes ao carregar a página
            saleItemsContainer.querySelectorAll('.sale-item').forEach(initializeItemListeners);

            // Garante que o botão remover do PRIMEIRO item esteja visível se mais de um item existir
            if (saleItemsContainer.querySelectorAll('.sale-item').length > 1) {
                saleItemsContainer.querySelector('.sale-item .remove-item-btn').style.display = 'inline-block';
            }


            addItemBtn.addEventListener('click', function() {
                const newItem = saleItemTemplate.firstElementChild.cloneNode(true);
                itemIndex++;

                newItem.querySelectorAll('[data-name*="INDEX"]').forEach(function(element) {
                    element.name = element.dataset.name.replace('INDEX', itemIndex);
                    element.removeAttribute('data-name');
                });

                newItem.querySelector('.product-select').value = "";
                newItem.querySelector('.quantity-input').value = "1";
                newItem.querySelector('.price-at-sale-input').value = "0,00";

                newItem.querySelector('.remove-item-btn').style.display = 'inline-block';

                saleItemsContainer.appendChild(newItem);
                initializeItemListeners(newItem);
                calculateTotal(); // Recalcula o total após adicionar um novo item
            });

            // Lógica para reindexar e formatar números ANTES do envio do formulário
            saleEditForm.addEventListener('submit', function(event) {
                saleItemsContainer.querySelectorAll('.sale-item').forEach(function(item, idx) {
                    item.querySelectorAll('[name*="items["]').forEach(function(element) {
                        element.name = element.name.replace(/items\[\d+\]/, `items[${idx}]`);
                    });
                });

                totalInput.value = totalInput.value.replace('.', '').replace(',', '.');

                saleItemsContainer.querySelectorAll('.price-at-sale-input').forEach(function(input) {
                    input.value = input.value.replace('.', '').replace(',', '.');
                });
            });
        });
    </script>
</x-app-layout>