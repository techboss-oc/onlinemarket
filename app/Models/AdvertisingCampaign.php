<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvertisingCampaign extends Model
{
    protected $fillable = [
        'user_id', 'ad_id', 'budget', 'remaining_budget', 
        'cost_per_click', 'status', 'starts_at', 'ends_at'
    ];

    protected $casts = [
        'budget' => 'decimal:2',
        'remaining_budget' => 'decimal:2',
        'cost_per_click' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }
    
    public function analytics()
    {
        return $this->hasMany(AdvertisingAnalytics::class, 'campaign_id');
    }
}
