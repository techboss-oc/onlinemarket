<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonetizationPackage extends Model
{
    protected $fillable = [
        'type', 'name', 'description', 'price', 'currency',
        'duration_days', 'boost_count', 'refresh_frequency_hours',
        'listing_limit', 'is_active', 'sort_order'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];
    
    public function listingPromotions()
    {
        return $this->hasMany(ListingPromotion::class, 'package_id');
    }
    
    public function userPackages()
    {
        return $this->hasMany(UserPackage::class, 'package_id');
    }
}
