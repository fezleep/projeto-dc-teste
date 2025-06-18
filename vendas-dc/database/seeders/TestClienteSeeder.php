<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Importar o Facade DB

class TestClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tenta inserir dados diretamente na tabela 'clientes'
        // sem usar o Model Cliente
        DB::table('clientes')->insert([
            'nome' => 'Cliente Teste Direto',
            'endereco' => 'Endereço Teste, 100',
            'telefone' => '11912345678',
            'email' => 'teste.direto@example.com',
            'created_at' => now(), // Adiciona timestamps
            'updated_at' => now(), // Adiciona timestamps
        ]);

        // Se o primeiro funcionar, tente o second:
        DB::table('clientes')->insert([
            'nome' => 'Cliente Teste Direto 2',
            'endereco' => 'Endereço Teste, 200',
            'telefone' => '11912345679',
            'email' => 'teste.direto2@example.com',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('TestClienteSeeder: Inserção direta tentada na tabela clientes.');
    }
}