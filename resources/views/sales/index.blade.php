<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Listagem de Vendas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                {{-- Alerta de sucesso --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <a href="{{ route('sales.create') }}" class="btn btn-dark mb-3">Registrar Nova Venda</a>

                @if($sales->isEmpty())
                    <p class="text-center">Nenhuma venda encontrada.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Vendedor</th>
                                    <th>Total</th>
                                    <th>Método Pagamento</th>
                                    <th>Data Venda</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sales as $sale)
                                    <tr>
                                        <td>{{ $sale->id }}</td>
                                        {{-- Exibe o nome do cliente comprador ou 'Cliente Avulso' se não tiver cliente_id --}}
                                        <td>{{ $sale->client ? $sale->client->name : 'Cliente Avulso' }}</td>
                                        {{-- Exibe o nome do vendedor (cliente que registrou a venda) --}}
                                        <td>{{ $sale->seller ? $sale->seller->name : 'N/A' }}</td>
                                        <td>R$ {{ number_format($sale->total, 2, ',', '.') }}</td>
                                        <td>{{ $sale->payment_method }}</td>
                                        <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('sales.show', $sale) }}" class="btn btn-info btn-sm me-1">Ver</a>
                                            <a href="{{ route('sales.edit', $sale) }}" class="btn btn-warning btn-sm me-1">Editar</a>
                                            <form action="{{ route('sales.destroy', $sale) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir esta venda e todos os seus itens/parcelas? Esta ação é irreversível!')">Excluir</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>