<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Certifique-se de que o UserSeeder (se você tiver um) e outros seeders padrões do Laravel estão aqui.
        // Se você não tiver um UserSeeder ou não estiver usando autenticação (Breeze/Jetstream),
        // pode comentar a linha abaixo ou criar um UserSeeder se necessário para ter um usuário para logar.
        // \App\Models\User::factory(10)->create();

        // Isso é para criar um usuário de teste se você ainda não tem:
        \App\Models\User::firstOrCreate(
            ['email' => 'teste@example.com'],
            ['name' => 'Usuário Teste', 'password' => bcrypt('password')] // Senha 'password'
        );


        // Chame seus novos seeders aqui:
        $this->call([
            ClienteSeeder::class,
            ProdutoSeeder::class,
            FormaPagamentoSeeder::class,
            // Adicione outros seeders aqui, se tiver
        ]);
    }
}