<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'wallet_id', 'amount', 'type', 'description', 'reference', 'status',
        'user_id', 'listing_id', 'payment_provider', 'provider_transaction_id',
        'currency', 'payment_type', 'product_purchased', 'metadata', 'paid_at', 'updated_at'
    ];
    
    protected $casts = [
        'amount' => 'decimal:2', 
        'created_at' => 'datetime',
        'paid_at' => 'datetime',
        'updated_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ad()
    {
        return $this->belongsTo(Ad::class, 'listing_id');
    }
}
