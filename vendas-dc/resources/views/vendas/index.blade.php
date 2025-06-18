{{-- resources/views/vendas/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Vendas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

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

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Listagem de Vendas</h3>
                        <a href="{{ route('vendas.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Nova Venda') }}
                        </a>
                    </div>

                    <form action="{{ route('vendas.index') }}" method="GET" class="mb-6 p-4 border rounded-md bg-gray-50 grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="cliente_id" class="block text-sm font-medium text-gray-700">Cliente</label>
                            <select name="cliente_id" id="cliente_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">-- Todos os Clientes --</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" {{ (request('cliente_id') == $cliente->id) ? 'selected' : '' }}>
                                        {{ $cliente->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="vendedor_id" class="block text-sm font-medium text-gray-700">Vendedor</label>
                            <select name="vendedor_id" id="vendedor_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">-- Todos os Vendedores --</option>
                                @foreach($vendedores as $vendedor)
                                    <option value="{{ $vendedor->id }}" {{ (request('vendedor_id') == $vendedor->id) ? 'selected' : '' }}>
                                        {{ $vendedor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="data_inicio" class="block text-sm font-medium text-gray-700">Data Início</label>
                            <input type="date" name="data_inicio" id="data_inicio" value="{{ request('data_inicio') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="data_fim" class="block text-sm font-medium text-gray-700">Data Fim</label>
                            <input type="date" name="data_fim" id="data_fim" value="{{ request('data_fim') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div class="md:col-span-4 flex justify-end space-x-2 mt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Filtrar
                            </button>
                            <a href="{{ route('vendas.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                Limpar Filtros
                            </a>
                        </div>
                    </form>


                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendedor</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($vendas as $venda)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $venda->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $venda->cliente->nome ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $venda->vendedor->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">R$ {{ number_format($venda->total, 2, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $venda->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('vendas.show', $venda->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Ver</a>
                                            <a href="{{ route('vendas.edit', $venda->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Editar</a>
                                            <form action="{{ route('vendas.destroy', $venda->id) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir esta venda?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Excluir</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                            Nenhuma venda encontrada.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $vendas->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>