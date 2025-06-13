<?php

namespace App\Http\Controllers;

use App\Models\Product; // Importa o modelo Product
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Importa Rule para validação unique (se for usar nome único, por exemplo)

class ProductController extends Controller
{
    /**
     * Display a listing of the resource (Listagem de Produtos).
     */
    public function index()
    {
        $products = Product::all(); // Pega todos os produtos do banco de dados
        return view('products.index', compact('products')); // Passa os produtos para a view
    }

    /**
     * Show the form for creating a new resource (Exibe o formulário de criação).
     */
    public function create()
    {
        return view('products.create'); // Retorna a view do formulário de criação
    }

    /**
     * Store a newly created resource in storage (Salva um novo produto).
     */
    public function store(Request $request)
    {
        // Validação dos dados do formulário
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000', // Descrição é opcional
            'price' => 'required|numeric|min:0', // Preço deve ser numérico e não negativo
            'stock' => 'required|integer|min:0', // Estoque deve ser inteiro e não negativo
        ]);

        // Cria o produto no banco de dados
        Product::create($validatedData);

        // Redireciona para a listagem de produtos com uma mensagem de sucesso
        return redirect()->route('products.index')->with('success', 'Produto cadastrado com sucesso!');
    }

    /**
     * Display the specified resource (Exibe os detalhes de um produto específico).
     */
    public function show(Product $product)
    {
        // Retorna a view 'products.show' passando o objeto produto
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource (Exibe o formulário de edição).
     */
    public function edit(Product $product)
    {
        // Retorna a view 'products.edit' passando o objeto produto para preencher o formulário
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage (Atualiza um produto existente).
     */
    public function update(Request $request, Product $product)
    {
        // Validação dos dados do formulário de edição
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        // Atualiza o produto no banco de dados
        $product->update($validatedData);

        // Redireciona para a listagem de produtos com uma mensagem de sucesso
        return redirect()->route('products.index')->with('success', 'Produto atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage (Exclui um produto).
     */
    public function destroy(Product $product)
    {
        // Exclui o produto do banco de dados
        $product->delete();

        // Redireciona para a listagem de produtos com uma mensagem de sucesso
        return redirect()->route('products.index')->with('success', 'Produto excluído com sucesso!');
    }
}