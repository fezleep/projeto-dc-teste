<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalhes do Cliente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="mb-4">{{ $client->name }}</h3>

                <div class="mb-3">
                    <strong>ID:</strong> {{ $client->id }}
                </div>
                <div class="mb-3">
                    <strong>Email:</strong> {{ $client->email }}
                </div>
                <div class="mb-3">
                    <strong>Telefone:</strong> {{ $client->phone ?? 'N/A' }} {{-- Exibe N/A se o telefone for nulo --}}
                </div>
                <div class="mb-3">
                    <strong>Endereço:</strong> {{ $client->address ?? 'N/A' }} {{-- Exibe N/A se o endereço for nulo --}}
                </div>
                <div class="mb-3">
                    <strong>Cadastrado em:</strong> {{ $client->created_at->format('d/m/Y H:i') }}
                </div>
                <div class="mb-3">
                    <strong>Última atualização:</strong> {{ $client->updated_at->format('d/m/Y H:i') }}
                </div>

                <a href="{{ route('clients.edit', $client) }}" class="btn btn-warning">Editar</a>
                <a href="{{ route('clients.index') }}" class="btn btn-secondary ms-2">Voltar para a Lista</a>
            </div>
        </div>
    </div>
</x-app-layout>