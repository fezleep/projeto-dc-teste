<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Nova Venda') }}
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

                <form action="{{ route('sales.store') }}" method="POST" id="sale-form">
                    @csrf

                    <div class="mb-3">
                        <label for="client_id" class="form-label">Cliente (Opcional):</label>
                        <select class="form-select" id="client_id" name="client_id">
                            <option value="">Selecione um Cliente</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Forma de Pagamento:</label>
                        <select class="form-select" id="payment_method" name="payment_method" required>
                            <option value="">Selecione uma Forma de Pagamento</option>
                            <option value="Dinheiro">Dinheiro</option>
                            <option value="Cartao de Crédito">Cartão de Crédito</option>
                            <option value="Cartao de Débito">Cartão de Débito</option>
                            <option value="Pix">Pix</option>
                            <option value="Parcelado">Parcelado</option>
                        </select>
                        @error('payment_method')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- INÍCIO: Seção para Parcelamento (inicialmente oculta) --}}
                    <div id="installment_fields" class="mt-4" style="display: none;">
                        <h3>Detalhes do Parcelamento</h3>
                        <div class="mb-3">
                            <label for="num_installments" class="form-label">Número de Parcelas:</label>
                            <input type="number" class="form-control" id="num_installments" name="num_installments" min="1">
                            @error('num_installments')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="first_installment_date" class="form-label">Data da Primeira Parcela:</label>
                            <input type="date" class="form-control" id="first_installment_date" name="first_installment_date">
                            @error('first_installment_date')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    {{-- FIM: Seção para Parcelamento --}}

                    <hr>
                    <h2>Itens da Venda</h2>
                    <div id="sale-items-container">
                        <div class="row mb-3 sale-item">
                            <div class="col-md-5">
                                <label class="form-label">Produto:</label>
                                <select class="form-select product-select" name="items[0][product_id]">
                                    <option value="">Selecione um Produto</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }} (R$ {{ number_format($product->price, 2, ',', '.') }})</option>
                                    @endforeach
                                </select>
                                @error('items.0.product_id')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Quantidade:</label>
                                <input type="number" class="form-control quantity-input" name="items[0][quantity]" value="1" min="1">
                                @error('items.0.quantity')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Preço Unitário:</label>
                                <input type="text" class="form-control price-at-sale-input" name="items[0][price_at_sale]" readonly>
                                @error('items.0.price_at_sale')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-danger remove-item-btn" style="display:none;">Remover</button>
                            </div>
                        </div>
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
                        <input type="text" class="form-control" id="total" name="total" value="0,00" readonly>
                        @error('total')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-dark mt-3">Salvar Venda</button>
                    <a href="{{ route('sales.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
                </form>
            </div>
        </div>
    </div>

    {{-- Script JavaScript para a lógica de itens de venda, cálculo do total E AGORA O PARCELAMENTO --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Lógica existente para Itens da Venda e Total
            const saleItemsContainer = document.getElementById('sale-items-container');
            const addItemBtn = document.getElementById('add-item-btn');
            const saleItemTemplate = document.getElementById('sale-item-template');
            const totalInput = document.getElementById('total');
            const saleForm = document.getElementById('sale-form');

            let itemIndex = 0;

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
                           saleItemsContainer.querySelector('.remove-item-btn').style.display = 'none';
                    }
                    calculateTotal();
                });
            }

            initializeItemListeners(saleItemsContainer.querySelector('.sale-item'));
            saleItemsContainer.querySelector('.product-select').dispatchEvent(new Event('change'));


            addItemBtn.addEventListener('click', function() {
                const newItem = saleItemTemplate.firstElementChild.cloneNode(true);
                itemIndex++;

                newItem.querySelectorAll('[data-name*="INDEX"]').forEach(function(element) {
                    element.name = element.name || element.dataset.name.replace('INDEX', itemIndex);
                    element.removeAttribute('data-name');
                });

                newItem.querySelector('.product-select').value = "";
                newItem.querySelector('.quantity-input').value = "1";
                newItem.querySelector('.price-at-sale-input').value = "0,00";

                newItem.querySelector('.remove-item-btn').style.display = 'inline-block';

                saleItemsContainer.appendChild(newItem);
                initializeItemListeners(newItem);

                if (saleItemsContainer.querySelectorAll('.sale-item').length > 1) {
                    saleItemsContainer.querySelector('.sale-item .remove-item-btn').style.display = 'inline-block';
                }
                calculateTotal();
            });

            saleForm.addEventListener('submit', function(event) {
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


            // INÍCIO: Nova lógica para Parcelamento
            const paymentMethodSelect = document.getElementById('payment_method');
            const installmentFields = document.getElementById('installment_fields');
            const numInstallmentsInput = document.getElementById('num_installments');
            const firstInstallmentDateInput = document.getElementById('first_installment_date');

            function toggleInstallmentFields() {
                if (paymentMethodSelect.value === 'Parcelado') {
                    installmentFields.style.display = 'block'; // Mostra a seção
                    numInstallmentsInput.setAttribute('required', 'required'); // Torna os campos obrigatórios
                    firstInstallmentDateInput.setAttribute('required', 'required');
                } else {
                    installmentFields.style.display = 'none'; // Esconde a seção
                    numInstallmentsInput.removeAttribute('required'); // Remove a obrigatoriedade
                    firstInstallmentDateInput.removeAttribute('required');
                    numInstallmentsInput.value = ''; // Limpa o valor
                    firstInstallmentDateInput.value = ''; // Limpa o valor
                }
            }

            // Adiciona um listener para quando a seleção da forma de pagamento mudar
            paymentMethodSelect.addEventListener('change', toggleInstallmentFields);

            // Chama a função uma vez para configurar o estado inicial (caso já venha com algum valor selecionado)
            toggleInstallmentFields();
            // FIM: Nova lógica para Parcelamento
        });
    </script>
</x-app-layout>