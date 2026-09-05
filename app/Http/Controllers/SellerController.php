<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\MonetizationPackage;
use App\Models\ListingPromotion;
use App\Models\AdvertisingCampaign;
use App\Models\Transaction;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function dashboard()
    {
        $user  = auth()->user();

        $stats = [
            'total_ads'   => Ad::where('user_id', $user->id)->count(),
            'active_ads'  => Ad::where('user_id', $user->id)->where('status', 'active')->count(),
            'total_views' => Ad::where('user_id', $user->id)->sum('views_count'),
            'expired_ads' => Ad::where('user_id', $user->id)->where('status', 'expired')->count(),
        ];

        $recentAds = Ad::with(['primaryImage', 'category'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('seller.dashboard', compact('user', 'stats', 'recentAds'));
    }

    public function myAds()
    {
        $ads = Ad::with(['primaryImage', 'category', 'location'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(12);

        return view('seller.my-ads', compact('ads'));
    }

    public function promote(int $id)
    {
        $ad = Ad::where('user_id', auth()->id())->findOrFail($id);
        
        $packages = MonetizationPackage::where('is_active', true)
            ->whereIn('type', ['top_ad', 'boost', 'featured'])
            ->orderBy('sort_order')
            ->get();
            
        // Group by type for UI
        $topAds = $packages->where('type', 'top_ad');
        $boosts = $packages->where('type', 'boost');
        $featured = $packages->where('type', 'featured');

        return view('seller.promote', compact('ad', 'topAds', 'boosts', 'featured'));
    }

    public function initPromotion(Request $request, int $id)
    {
        $request->validate([
            'package_id' => 'required|exists:monetization_packages,id',
            'gateway' => 'required|in:paystack,flutterwave'
        ]);

        $ad = Ad::where('user_id', auth()->id())->findOrFail($id);
        $package = MonetizationPackage::findOrFail($request->package_id);

        if ($package->price <= 0) {
            return back()->with('error', 'Free packages not supported via this gateway.');
        }

        // 1. Create Pending Transaction
        $transaction = Transaction::create([
            'user_id' => auth()->id(),
            'listing_id' => $ad->id,
            'amount' => $package->price,
            'status' => 'pending',
            'payment_type' => 'listing_promotion',
            'currency' => $package->currency ?? 'NGN',
            'product_purchased' => clone $package->id, // just storing ID for reference
            'reference' => 'OMNG-' . time() . '-' . rand(100,999) // Temp ref, PaymentController forces update
        ]);

        // 2. Create Pending ListingPromotion
        ListingPromotion::create([
            'ad_id' => $ad->id,
            'user_id' => auth()->id(),
            'package_id' => $package->id,
            'promotion_type' => $package->type,
            'transaction_id' => $transaction->id,
            'status' => 'pending',
        ]);

        // 3. Hand off to generic payment checkout by faking a request or redirecting with post using form (or just session/cache). Building a POST form redirect view is safest across browsers, but for now we can just redirect to an internal route if we change PaymentController to accept GET, or fast-forward using internal call.
        // Let's use a standard View returning an auto-submitting form to the checkout route.
        return view('payments.redirect', ['transaction_id' => $transaction->id, 'gateway' => $request->gateway]);
    }

    public function campaigns()
    {
        $ads = Ad::where('user_id', auth()->id())->active()->get();
        $campaigns = AdvertisingCampaign::with('ad')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(15);
            
        $analytics = \App\Models\AdvertisingAnalytics::whereHas('campaign', function($q) {
            $q->where('user_id', auth()->id());
        })->selectRaw('type, count(*) as total, sum(cost) as total_cost')
          ->groupBy('type')
          ->get();
            
        return view('seller.campaigns', compact('ads', 'campaigns', 'analytics'));
    }

    public function storeCampaign(Request $request)
    {
        $request->validate([
            'ad_id' => 'required|exists:ads,id',
            'budget' => 'required|numeric|min:1000',
            'cost_per_click' => 'required|numeric|min:10',
            'gateway' => 'required|in:paystack,flutterwave'
        ]);

        $ad = Ad::where('user_id', auth()->id())->findOrFail($request->ad_id);

        $transaction = Transaction::create([
            'user_id' => auth()->id(),
            'listing_id' => $ad->id,
            'amount' => $request->budget,
            'status' => 'pending',
            'payment_type' => 'advertising_campaign',
            'currency' => 'NGN',
            'reference' => 'OMNG-PPC-' . time() . '-' . rand(100,999)
        ]);

        $campaign = AdvertisingCampaign::create([
            'user_id' => auth()->id(),
            'ad_id' => $ad->id,
            'budget' => $request->budget,
            'remaining_budget' => $request->budget,
            'cost_per_click' => $request->cost_per_click,
            'status' => 'pending',
        ]);
        
        $transaction->update(['product_purchased' => $campaign->id]);

        return view('payments.redirect', ['transaction_id' => $transaction->id, 'gateway' => $request->gateway]);
    }
}
