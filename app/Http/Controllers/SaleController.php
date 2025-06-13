<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Client;
use App\Models\Product;
use App\Models\SaleItem;      // Necessário para criar SaleItem diretamente
use App\Models\Installment;   // Necessário para criar Installment diretamente
use Illuminate\Support\Facades\Auth; // Para Auth::id()
use Illuminate\Support\Facades\DB;   // Para DB::transaction
use Illuminate\Validation\Rule;      // Pode ser útil para validações futuras, mas não essencial aqui
use Carbon\Carbon;                   // Para manipulação de datas

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['client', 'seller', 'items.product'])->orderBy('created_at', 'desc')->paginate(10); // Adicionei paginate para melhor performance
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        return view('sales.create', compact('clients', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'payment_method' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price_at_sale' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'num_installments' => 'nullable|integer|min:1|required_if:payment_method,Parcelado',
            'first_installment_date' => 'nullable|date|required_if:payment_method,Parcelado',
        ]);

        DB::beginTransaction(); // Inicia a transação manualmente para ter um bloco try-catch explícito

        try {
            $sale = Sale::create([
                'client_id' => $request->client_id,
                'seller_client_id' => Auth::id(),
                'payment_method' => $request->payment_method,
                'total' => $request->total,
            ]);

            foreach ($request->items as $item) {
                // Nenhuma verificação ou manipulação de estoque. O item é apenas criado.
                $sale->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price_at_sale' => $item['price_at_sale'],
                ]);
            }

            // Lógica para criar parcelas (Installments)
            if ($request->payment_method === 'Parcelado') {
                $numInstallments = $request->num_installments;
                $totalSale = $sale->total;
                $installmentAmount = round($totalSale / $numInstallments, 2);

                $firstInstallmentDate = Carbon::parse($request->first_installment_date);

                for ($i = 0; $i < $numInstallments; $i++) {
                    $dueDate = $firstInstallmentDate->copy()->addMonths($i);

                    $currentInstallmentAmount = $installmentAmount;
                    if ($i === $numInstallments - 1) {
                        // Ajusta a última parcela para cobrir a diferença de arredondamento
                        $currentInstallmentAmount = $totalSale - ($installmentAmount * ($numInstallments - 1));
                    }

                    $sale->installments()->create([
                        'amount' => $currentInstallmentAmount,
                        'due_date' => $dueDate,
                        'status' => 'pending',
                    ]);
                }
            }

            DB::commit(); // Confirma a transação

            return redirect()->route('sales.index')->with('success', 'Venda registrada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack(); // Reverte a transação em caso de erro
            // Retorna a mensagem de erro para o usuário
            return back()->withInput()->withErrors(['error' => 'Erro ao realizar a venda: ' . $e->getMessage()]);
        }
    }

    public function show(Sale $sale)
    {
        $sale->load(['client', 'seller', 'items.product', 'installments']);
        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        $sale->load('items.product');
        $clients = Client::all();
        $products = Product::all();
        return view('sales.edit', compact('sale', 'clients', 'products'));
    }

    public function update(Request $request, Sale $sale)
    {
        $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'payment_method' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price_at_sale' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'num_installments' => 'nullable|integer|min:1|required_if:payment_method,Parcelado',
            'first_installment_date' => 'nullable|date|required_if:payment_method,Parcelado',
        ]);

        DB::beginTransaction();

        try {
            $sale->update([
                'client_id' => $request->client_id,
                'payment_method' => $request->payment_method,
                'total' => $request->total,
            ]);

            // Exclui todos os itens de venda antigos para esta venda
            $sale->items()->delete();

            // Recria os itens de venda com base nos dados do request
            foreach ($request->items as $itemData) {
                // Nenhuma verificação ou manipulação de estoque. Item é apenas criado.
                $sale->items()->create([
                    'product_id' => $itemData['product_id'],
                    'quantity' => $itemData['quantity'],
                    'price_at_sale' => $itemData['price_at_sale'],
                ]);
            }

            // Lógica para atualizar/regerar parcelas
            // Deleta parcelas existentes e recria se o método for 'Parcelado'
            $sale->installments()->delete(); // Sempre exclui as parcelas para recriar ou deixar vazio

            if ($request->payment_method === 'Parcelado') {
                $numInstallments = $request->num_installments;
                $totalSale = $sale->total;
                $installmentAmount = round($totalSale / $numInstallments, 2);

                $firstInstallmentDate = Carbon::parse($request->first_installment_date);

                for ($i = 0; $i < $numInstallments; $i++) {
                    $dueDate = $firstInstallmentDate->copy()->addMonths($i);

                    $currentInstallmentAmount = $installmentAmount;
                    if ($i === $numInstallments - 1) {
                        $currentInstallmentAmount = $totalSale - ($installmentAmount * ($numInstallments - 1));
                    }

                    $sale->installments()->create([
                        'amount' => $currentInstallmentAmount,
                        'due_date' => $dueDate,
                        'status' => 'pending',
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('sales.index')->with('success', 'Venda atualizada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Erro ao atualizar a venda: ' . $e->getMessage()]);
        }
    }

    public function destroy(Sale $sale)
    {
        DB::beginTransaction();
        try {
            // Nenhuma devolução de estoque. Apenas exclui os itens e as parcelas.
            $sale->items()->delete();
            $sale->installments()->delete();
            $sale->delete();
            DB::commit();
            return redirect()->route('sales.index')->with('success', 'Venda excluída com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Erro ao excluir a venda: ' . $e->getMessage()]);
        }
    }
}