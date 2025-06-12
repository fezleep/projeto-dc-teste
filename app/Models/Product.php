<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        // Adicione os campos da sua tabela products aqui (ex: 'name', 'price', 'description')
    ];
    // Um produto pode estar em muitos itens de venda
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
}