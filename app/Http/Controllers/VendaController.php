<?php

namespace App\Http\Controllers;

use App\Models\Venda;
use App\Models\Cliente; // Certifique-se de que este use existe
use App\Models\Produto; // Para o método create/edit
use App\Models\FormaPagamento; // Para o método create/edit
use App\Models\User; // Certifique-se de que este use existe (para Vendedor)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ItemVenda; // Certifique-se de que este use existe
use App\Models\Parcela; // Certifique-se de que este use existe
use PDF; // Certifique-se de que este use existe se você usa o Facade do barryvdh/laravel-dompdf


class VendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Venda::query();

        // Carrega os relacionamentos para evitar N+1 query problem
        $query->with(['cliente', 'vendedor']);

        // Filtro por Cliente
        if ($request->filled('cliente_id')) {
            $query->where('cliente_id', $request->cliente_id);
        }

        // Filtro por Vendedor
        if ($request->filled('vendedor_id')) {
            $query->where('vendedor_id', $request->vendedor_id);
        }

        // Filtro por Data de Início
        if ($request->filled('data_inicio')) {
            $query->whereDate('created_at', '>=', $request->data_inicio);
        }

        // Filtro por Data de Fim
        if ($request->filled('data_fim')) {
            $query->whereDate('created_at', '<=', $request->data_fim);
        }

        // Ordena pela data de criação (mais recente primeiro) e pagina
        $vendas = $query->orderByDesc('created_at')->paginate(10);

        // Para os dropdowns de filtro, pegamos todos os clientes e vendedores
        $clientes = Cliente::orderBy('nome')->get();
        $vendedores = User::orderBy('name')->get(); // Assumindo que 'User' são os vendedores

        return view('vendas.index', compact('vendas', 'request', 'clientes', 'vendedores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientes = Cliente::orderBy('nome')->get();
        $produtos = Produto::orderBy('nome')->get();
        $formasPagamento = FormaPagamento::all(); // Ou orderBy('descricao')->get();
        return view('vendas.create', compact('clientes', 'produtos', 'formasPagamento'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'forma_pagamento_id' => 'required|exists:forma_pagamentos,id',
            'produtos' => 'required|array|min:1',
            'produtos.*.id' => 'required|exists:produtos,id',
            'produtos.*.quantidade' => 'required|integer|min:1',
            'total_enviado' => 'required|numeric|min:0', // Valida o total enviado pelo JS
        ], [
            'produtos.required' => 'A venda deve ter pelo menos um item.',
            'produtos.array' => 'Os itens da venda devem ser uma lista.',
            'produtos.min' => 'A venda deve ter pelo menos um item.',
            'produtos.*.id.required' => 'O ID do produto é obrigatório.',
            'produtos.*.id.exists' => 'O produto selecionado não existe.',
            'produtos.*.quantidade.required' => 'A quantidade do produto é obrigatória.',
            'produtos.*.quantidade.integer' => 'A quantidade deve ser um número inteiro.',
            'produtos.*.quantidade.min' => 'A quantidade do produto deve ser no mínimo 1.',
            'total_enviado.required' => 'O total da venda é obrigatório.',
            'total_enviado.numeric' => 'O total da venda deve ser um número.',
            'total_enviado.min' => 'O total da venda não pode ser negativo.',
        ]);

        DB::beginTransaction();
        try {
            // Cria a Venda
            $venda = new Venda();
            $venda->cliente_id = $request->cliente_id;
            $venda->vendedor_id = Auth::id(); // Usuário logado
            $venda->forma_pagamento_id = $request->forma_pagamento_id;
            $venda->total = $request->total_enviado; // Usa o total enviado pelo JS
            $venda->save();

            // Adiciona os Itens da Venda
            foreach ($request->produtos as $produtoData) {
                $produto = Produto::find($produtoData['id']);
                if (!$produto) {
                    throw new \Exception('Produto não encontrado.');
                }
                $itemVenda = new ItemVenda();
                $itemVenda->venda_id = $venda->id;
                $itemVenda->produto_id = $produto->id;
                $itemVenda->quantidade = $produtoData['quantidade'];
                $itemVenda->preco = $produto->preco; // Pega o preço do BD para garantir integridade
                $itemVenda->save();
            }

            // Geração de Parcelas
            $formaPagamento = FormaPagamento::find($request->forma_pagamento_id);
            $quantidadeParcelas = $formaPagamento->quantidade_parcelas;
            $valorParcela = $venda->total / $quantidadeParcelas;

            for ($i = 1; $i <= $quantidadeParcelas; $i++) {
                $parcela = new Parcela();
                $parcela->venda_id = $venda->id;
                $parcela->numero = $i;
                $parcela->valor = $valorParcela;
                // Vencimento: Exemplo - 30 dias para a primeira, 60 para a segunda, etc.
                // Ajuste esta lógica conforme a necessidade do negócio
                $parcela->vencimento = now()->addDays(30 * $i);
                $parcela->status = 'pendente'; // Ou 'aberto', 'a_receber'
                $parcela->save();
            }

            DB::commit();
            return redirect()->route('vendas.index')->with('success', 'Venda cadastrada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            // Log do erro para depuração
            \Log::error('Erro ao cadastrar venda: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Erro ao cadastrar venda: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Venda $venda)
    {
        // Carrega os relacionamentos para a view de detalhes
        $venda->load('cliente', 'vendedor', 'formaPagamento', 'itensVenda.produto', 'parcelas');
        return view('vendas.show', compact('venda'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Venda $venda)
    {
        $venda->load('itensVenda'); // Carrega os itens da venda para a edição
        $clientes = Cliente::orderBy('nome')->get();
        $produtos = Produto::orderBy('nome')->get(); // Todos os produtos para o dropdown
        $formasPagamento = FormaPagamento::all();
        return view('vendas.edit', compact('venda', 'clientes', 'produtos', 'formasPagamento'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Venda $venda)
    {
        $request->validate([
            'forma_pagamento_id' => 'required|exists:forma_pagamentos,id',
            'produtos' => 'required|array|min:1',
            'produtos.*.id' => 'required|exists:produtos,id',
            'produtos.*.quantidade' => 'required|integer|min:1',
            'total_enviado' => 'required|numeric|min:0',
        ], [
            'produtos.required' => 'A venda deve ter pelo menos um item.',
            'produtos.min' => 'A venda deve ter pelo menos um item.',
            'produtos.*.id.required' => 'O ID do produto é obrigatório.',
            'produtos.*.id.exists' => 'O produto selecionado não existe.',
            'produtos.*.quantidade.required' => 'A quantidade do produto é obrigatória.',
            'produtos.*.quantidade.integer' => 'A quantidade deve ser um número inteiro.',
            'produtos.*.quantidade.min' => 'A quantidade do produto deve ser no mínimo 1.',
            'total_enviado.required' => 'O total da venda é obrigatório.',
            'total_enviado.numeric' => 'O total da venda deve ser um número.',
            'total_enviado.min' => 'O total da venda não pode ser negativo.',
        ]);

        DB::beginTransaction();
        try {
            $venda->cliente_id = $request->cliente_id;
            // O vendedor não muda na edição, pois ele é quem criou a venda
            $venda->forma_pagamento_id = $request->forma_pagamento_id;
            $venda->total = $request->total_enviado;
            $venda->save();

            // Atualiza Itens da Venda
            // Remove itens antigos que não estão na nova requisição
            $oldItemIds = $venda->itensVenda->pluck('id')->toArray();
            $newItemIds = [];

            foreach ($request->produtos as $produtoData) {
                // Tenta encontrar o item de venda existente pelo produto_id e venda_id
                $item = ItemVenda::where('venda_id', $venda->id)
                                  ->where('produto_id', $produtoData['id'])
                                  ->first();

                if ($item) {
                    // Se o item existe, atualiza a quantidade e o preço
                    $item->quantidade = $produtoData['quantidade'];
                    $item->preco = Produto::find($produtoData['id'])->preco; // Atualiza preço baseando-se no produto atual
                    $item->save();
                    $newItemIds[] = $item->id;
                } else {
                    // Se não existe, cria um novo item
                    $produto = Produto::find($produtoData['id']);
                    if (!$produto) {
                        throw new \Exception('Produto não encontrado.');
                    }
                    $itemVenda = new ItemVenda();
                    $itemVenda->venda_id = $venda->id;
                    $itemVenda->produto_id = $produto->id;
                    $itemVenda->quantidade = $produtoData['quantidade'];
                    $itemVenda->preco = $produto->preco;
                    $itemVenda->save();
                    $newItemIds[] = $itemVenda->id;
                }
            }
            // Remove itens que foram removidos da requisição
            ItemVenda::where('venda_id', $venda->id)
                      ->whereNotIn('id', $newItemIds)
                      ->delete();


            // Recria Parcelas (método mais simples para atualização, pode ser otimizado)
            // Remove as parcelas antigas e gera novas com base na nova forma de pagamento e total
            $venda->parcelas()->delete(); // Exclui todas as parcelas antigas

            $formaPagamento = FormaPagamento::find($request->forma_pagamento_id);
            $quantidadeParcelas = $formaPagamento->quantidade_parcelas;
            $valorParcela = $venda->total / $quantidadeParcelas;

            for ($i = 1; $i <= $quantidadeParcelas; $i++) {
                $parcela = new Parcela();
                $parcela->venda_id = $venda->id;
                $parcela->numero = $i;
                $parcela->valor = $valorParcela;
                $parcela->vencimento = now()->addDays(30 * $i); // Ajustar vencimento se necessário
                $parcela->status = 'pendente';
                $parcela->save();
            }

            DB::commit();
            return redirect()->route('vendas.index')->with('success', 'Venda atualizada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erro ao atualizar venda: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Erro ao atualizar venda: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Venda $venda)
    {
        DB::beginTransaction();
        try {
            // Exclui os itens de venda e as parcelas primeiro (se você usa onDelete('cascade') nas migrations, isso é automático)
            $venda->itensVenda()->delete();
            $venda->parcelas()->delete();
            $venda->delete();

            DB::commit();
            return redirect()->route('vendas.index')->with('success', 'Venda excluída com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erro ao excluir venda: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao excluir venda: ' . $e->getMessage());
        }
    }

    /**
     * Exporta o resumo da venda para PDF.
     */
    public function exportPdf(Venda $venda)
    {
        $venda->load('cliente', 'vendedor', 'formaPagamento', 'itensVenda.produto', 'parcelas');
        $pdf = PDF::loadView('vendas.pdf', compact('venda')); // 'vendas.pdf' é a view que você criará para o PDF

        return $pdf->download('resumo-venda-' . $venda->id . '.pdf');
    }
}