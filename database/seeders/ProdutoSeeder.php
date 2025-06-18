<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Produto;

class ProdutoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Produto::firstOrCreate(['nome' => 'Smartphone X'], [
            'descricao' => 'Último modelo de smartphone, tela OLED.',
            'preco' => 2500.00,
            'estoque' => 100,
        ]);

        Produto::firstOrCreate(['nome' => 'Fones Bluetooth'], [
            'descricao' => 'Fones de ouvido sem fio com cancelamento de ruído.',
            'preco' => 350.50,
            'estoque' => 250,
        ]);

        Produto::firstOrCreate(['nome' => 'Carregador Portátil'], [
            'descricao' => 'Power bank de 10000mAh.',
            'preco' => 99.90,
            'estoque' => 300,
        ]);
    }
}