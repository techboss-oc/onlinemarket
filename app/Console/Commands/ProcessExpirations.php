<?php

namespace App\Console\Commands;

use App\Models\ListingPromotion;
use App\Models\AdvertisingCampaign;
use App\Models\Ad;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('monetization:process-expirations')]
#[Description('Expire old promotions and campaigns')]
class ProcessExpirations extends Command
{
    public function handle()
    {
        $expiredPromotions = ListingPromotion::where('status', 'active')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->get();
            
        foreach ($expiredPromotions as $promo) {
            $promo->update(['status' => 'expired']);
            $ad = Ad::find($promo->ad_id);
            if ($ad) {
                if ($promo->promotion_type === 'top_ad') {
                    $ad->update(['is_top_ad' => false]);
                } elseif ($promo->promotion_type === 'featured') {
                    $ad->update(['is_featured' => false]);
                }
            }
        }
        
        $expiredCampaigns = AdvertisingCampaign::where('status', 'active')
            ->where(function($q) {
                $q->where('ends_at', '<', now())
                  ->orWhere('remaining_budget', '<=', 0);
            })->get();
            
        foreach ($expiredCampaigns as $campaign) {
            $campaign->update(['status' => 'expired']);
        }
        
        $this->info("Processed " . $expiredPromotions->count() . " promotions and " . $expiredCampaigns->count() . " campaigns.");
    }
}
