<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListingPromotion extends Model
{
    protected $fillable = [
        'ad_id', 'user_id', 'package_id', 'promotion_type',
        'transaction_id', 'status', 'starts_at', 'expires_at'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];
    
    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function package()
    {
        return $this->belongsTo(MonetizationPackage::class, 'package_id');
    }
    
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
