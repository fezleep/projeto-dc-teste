{{-- resources/views/vendas/pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>Resumo da Venda #{{ $venda->id }}</title>
    <style>
        body { font-family: sans-serif; margin: 20px; font-size: 10pt; }
        h1, h2, h3 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .footer { text-align: center; margin-top: 30px; font-size: 8pt; color: #555; }
    </style>
</head>
<body>
    <h1>Resumo da Venda #{{ $venda->id }}</h1>

    <h2>Informações da Venda</h2>
    <table>
        <tr>
            <th>Cliente:</th><td>{{ $venda->cliente->nome ?? 'N/A' }}</td>
            <th>Vendedor:</th><td>{{ $venda->vendedor->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Forma de Pagamento:</th><td>{{ $venda->formaPagamento->descricao ?? 'N/A' }}</td>
            <th>Data da Venda:</th><td>{{ $venda->created_at->format('d/m/Y H:i:s') }}</td>
        </tr>
        <tr>
            <th>Total da Venda:</th><td colspan="3">R$ {{ number_format($venda->total, 2, ',', '.') }}</td>
        </tr>
    </table>

    <h2>Itens da Venda</h2>
    @if($venda->itensVenda->isEmpty())
        <p>Nenhum item nesta venda.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Preço Unitário</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($venda->itensVenda as $item)
                    <tr>
                        <td>{{ $item->produto->nome ?? 'Produto Removido' }}</td>
                        <td>{{ $item->quantidade }}</td>
                        <td>R$ {{ number_format($item->preco, 2, ',', '.') }}</td>
                        <td>R$ {{ number_format($item->quantidade * $item->preco, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <h2>Parcelas</h2>
    @if($venda->parcelas->isEmpty())
        <p>Nenhuma parcela para esta venda.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Valor</th>
                    <th>Vencimento</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($venda->parcelas as $parcela)
                    <tr>
                        <td>{{ $parcela->numero }}</td>
                        <td>R$ {{ number_format($parcela->valor, 2, ',', '.') }}</td>
                        <td>{{ $parcela->vencimento->format('d/m/Y') }}</td>
                        <td>{{ ucfirst($parcela->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        Gerado em {{ now()->format('d/m/Y H:i:s') }}
        <p>DC Tecnologia - Teste para vaga de desenvolvedor</p>
    </div>
</body>
</html>