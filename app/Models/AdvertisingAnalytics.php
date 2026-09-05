<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvertisingAnalytics extends Model
{
    public $timestamps = false; // We use created_at manually in migration

    protected $fillable = [
        'campaign_id', 'ad_id', 'type', 'ip_address', 
        'session_id', 'cost', 'created_at'
    ];

    protected $casts = [
        'cost' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function campaign()
    {
        return $this->belongsTo(AdvertisingCampaign::class, 'campaign_id');
    }
    
    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }
}
