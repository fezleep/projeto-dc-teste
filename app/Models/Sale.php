<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',        // O cliente que comprou (pode ser nullable se for 'Cliente Avulso')
        'seller_client_id', // O cliente (usuário logado) que realizou a venda
        'payment_method',
        'total',
    ];

    /**
     * Get the client (buyer) that owns the Sale.
     */
    public function client(): BelongsTo
    {
        // Uma venda pertence a um cliente (o comprador).
        // client_id na tabela sales referencia id na tabela clients.
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Get the seller (client user) that handled the Sale.
     */
    public function seller(): BelongsTo
    {
        // Uma venda é realizada por um vendedor (que é um cliente autenticado).
        // seller_client_id na tabela sales referencia id na tabela clients.
        return $this->belongsTo(Client::class, 'seller_client_id');
    }

    /**
     * Get the sale items for the Sale.
     */
    public function items(): HasMany
    {
        // Uma venda tem muitos itens de venda.
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Get the installments for the Sale.
     * Será usado futuramente para parcelas.
     */
    public function installments(): HasMany
    {
        // Uma venda pode ter muitas parcelas.
        return $this->hasMany(Installment::class);
    }
}