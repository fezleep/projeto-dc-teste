<?php

namespace App\Http\Controllers;

use App\Models\Client; // Importa o modelo Client
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Importa Rule para validação unique

class ClientController extends Controller
{
    /**
     * Display a listing of the resource (Listagem de Clientes).
     */
    public function index()
    {
        $clients = Client::all(); // Pega todos os clientes do banco de dados
        return view('clients.index', compact('clients')); // Passa os clientes para a view
    }

    /**
     * Show the form for creating a new resource (Exibe o formulário de criação).
     */
    public function create()
    {
        return view('clients.create'); // Retorna a view do formulário de criação
    }

    /**
     * Store a newly created resource in storage (Salva um novo cliente).
     */
    public function store(Request $request)
    {
        // Validação dos dados do formulário
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('clients', 'email'), // Garante que o email seja único na tabela clients
            ],
            'phone' => 'nullable|string|max:20', // Telefone é opcional
            'address' => 'nullable|string|max:255', // Endereço é opcional
        ]);

        // Cria o cliente no banco de dados
        Client::create($validatedData);

        // Redireciona para a listagem de clientes com uma mensagem de sucesso
        return redirect()->route('clients.index')->with('success', 'Cliente cadastrado com sucesso!');
    }

    /**
     * Display the specified resource (Exibe os detalhes de um cliente específico).
     */
    public function show(Client $client)
    {
        // Retorna a view 'clients.show' passando o objeto cliente
        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource (Exibe o formulário de edição).
     */
    public function edit(Client $client)
    {
        // Retorna a view 'clients.edit' passando o objeto cliente para preencher o formulário
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage (Atualiza um cliente existente).
     */
    public function update(Request $request, Client $client)
    {
        // Validação dos dados do formulário de edição
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                // Garante que o email seja único, ignorando o email do cliente atual
                Rule::unique('clients', 'email')->ignore($client->id),
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        // Atualiza o cliente no banco de dados
        $client->update($validatedData);

        // Redireciona para a listagem de clientes com uma mensagem de sucesso
        return redirect()->route('clients.index')->with('success', 'Cliente atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage (Exclui um cliente).
     */
    public function destroy(Client $client)
    {
        // Exclui o cliente do banco de dados
        $client->delete();

        // Redireciona para a listagem de clientes com uma mensagem de sucesso
        return redirect()->route('clients.index')->with('success', 'Cliente excluído com sucesso!');
    }
}