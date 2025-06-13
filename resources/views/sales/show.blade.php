<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalhes da Venda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="mb-4">Venda #{{ $sale->id }}</h3>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Cliente:</strong> {{ $sale->client ? $sale->client->name : 'Cliente Avulso' }}
                        </div>
                        <div class="mb-3">
                            <strong>Vendedor:</strong> {{ $sale->seller ? $sale->seller->name : 'N/A' }}
                        </div>
                        <div class="mb-3">
                            <strong>Data da Venda:</strong> {{ $sale->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Total da Venda:</strong> R$ {{ number_format($sale->total, 2, ',', '.') }}
                        </div>
                        <div class="mb-3">
                            <strong>Forma de Pagamento:</strong> {{ $sale->payment_method }}
                        </div>
                        {{-- Futuramente, aqui podemos exibir os detalhes das parcelas --}}
                        {{-- <div class="mb-3">
                            <strong>Parcelas:</strong> (Será implementado)
                        </div> --}}
                    </div>
                </div>

                <hr class="my-4">

                <h4>Itens da Venda</h4>
                @if($sale->items->isEmpty())
                    <p>Nenhum item encontrado para esta venda.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Quantidade</th>
                                    <th>Preço Unitário</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sale->items as $item)
                                    <tr>
                                        <td>{{ $item->product->name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>R$ {{ number_format($item->price_at_sale, 2, ',', '.') }}</td>
                                        <td>R$ {{ number_format($item->quantity * $item->price_at_sale, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <div class="mt-4">
                    <a href="{{ route('sales.edit', $sale) }}" class="btn btn-warning">Editar Venda</a>
                    <a href="{{ route('sales.index') }}" class="btn btn-secondary ms-2">Voltar para a Lista</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>