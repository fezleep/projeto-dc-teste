<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cliente;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cliente::firstOrCreate(['nome' => 'Cliente Padrão'], [
            'endereco' => 'Rua Exemplo, 123',
            'telefone' => '11987654321',
            'email' => 'cliente.padrao@example.com',
        ]);

        Cliente::firstOrCreate(['nome' => 'Cliente Premium'], [
            'endereco' => 'Av. Teste, 456',
            'telefone' => '11998765432',
            'email' => 'cliente.premium@example.com',
        ]);

        Cliente::firstOrCreate(['nome' => 'Cliente Básico'], [
            'endereco' => 'Praça Central, 789',
            'telefone' => '11976543210',
            'email' => 'cliente.basico@example.com',
        ]);
        
    }
}