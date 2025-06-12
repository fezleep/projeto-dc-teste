<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'price_at_sale', // Preço do produto no momento da venda
    ];

    // Relação: Um item de venda pertence a uma venda
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    // Relação: Um item de venda pertence a um produto
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}