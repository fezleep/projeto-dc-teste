<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    // Define os campos que podem ser preenchidos em massa
    protected $fillable = [
        'client_id',
        'user_id',
        'payment_method',
        'total',
    ];

    // Relação: Uma venda pertence a um cliente
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Relação: Uma venda pertence a um usuário/vendedor (Client, neste caso)
    public function user()
    {
        return $this->belongsTo(Client::class, 'user_id'); // 'user_id' é a FK, 'Client::class' é o Model referenciado
    }

    // Relação: Uma venda tem muitos itens de venda
    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    // Relação: Uma venda tem muitas parcelas
    public function installments()
    {
        return $this->hasMany(Installment::class);
    }
}