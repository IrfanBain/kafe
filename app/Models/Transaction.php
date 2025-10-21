<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'order_id',
        'transaction_id',
        'payment_method',
        'amount',
        'amount_paid',
        'change_amount',
        'status',
        'midtrans_response',
        'snap_token',
        'paid_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'midtrans_response' => 'array',
        'paid_at' => 'datetime'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
