<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem de Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Listagem de Clientes</h1>

        {{-- Alerta de sucesso (se você usar sessões para mensagens) --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('clients.create') }}" class="btn btn-primary mb-3">Cadastrar Novo Cliente</a>

        @if($clients->isEmpty())
            <p>Nenhum cliente encontrado.</p>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Data de Criação</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clients as $client)
                    <tr>
                        <td>{{ $client->id }}</td>
                        <td>{{ $client->name }}</td>
                        <td>{{ $client->email }}</td>
                        <td>{{ $client->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('clients.show', $client) }}" class="btn btn-info btn-sm">Ver</a>
                            <a href="{{ route('clients.edit', $client) }}" class="btn btn-warning btn-sm">Editar</a>
                            <form action="{{ route('clients.destroy', $client) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este cliente?')">Excluir</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <a href="{{ url('/dashboard') }}" class="btn btn-secondary mt-3">Voltar para o Dashboard</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>