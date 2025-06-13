<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Pode ser que você precise desta linha

class Installment extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'amount',
        'due_date',
        'paid_at', // Certifique-se que estas estão no $fillable
        'status',  // Certifique-se que estas estão no $fillable
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at' => 'datetime', // Certifique-se que esta está no $casts
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }
}