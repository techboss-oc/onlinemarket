<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\AdImage;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdController extends Controller
{
    public function show(int $id)
    {
        $ad = Ad::with(['user', 'category', 'location', 'images'])->findOrFail($id);
        $ad->incrementViews();

        $activeCampaign = \App\Models\AdvertisingCampaign::where('ad_id', $ad->id)
            ->where('status', 'active')
            ->where('remaining_budget', '>', 0)
            ->first();

        // Prevent sellers clicking their own ads to deplete budget
        if ($activeCampaign && auth()->id() !== $ad->user_id) {
            $cost = $activeCampaign->cost_per_click;
            
            // Check for previous impressions by this IP within the last x mins? Optional complexity.
            // Keeping it simple and just recording the basic click cost
            \App\Models\AdvertisingAnalytics::create([
                'campaign_id' => $activeCampaign->id,
                'ad_id' => $ad->id,
                'type' => 'click',
                'ip_address' => request()->ip(),
                'session_id' => session()->getId(),
                'cost' => $cost
            ]);
            
            // Raw decrement does not use eloquent events fully, but decrementing directly is fast. We MUST refresh or recalculate manually if we check the status update
            $activeCampaign->remaining_budget -= $cost;
            if ($activeCampaign->remaining_budget <= 0) {
                $activeCampaign->remaining_budget = 0;
                $activeCampaign->status = 'expired';
            }
            $activeCampaign->save();
        }

        $similarAds = Ad::with(['primaryImage', 'location', 'category'])
            ->active()
            ->where('category_id', $ad->category_id)
            ->where('id', '!=', $ad->id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        $isSaved = false;
        if (auth()->check()) {
            $isSaved = Favorite::where('user_id', auth()->id())->where('ad_id', $id)->exists();
        }

        $sellerAds = Ad::with(['primaryImage', 'location', 'category'])
            ->active()
            ->where('user_id', $ad->user_id)
            ->where('id', '!=', $ad->id)
            ->latest()
            ->take(4)
            ->get();
            
        $sellerRating = \App\Models\Review::where('seller_id', $ad->user_id)->avg('rating') ?: 0;
        $sellerReviewCount = \App\Models\Review::where('seller_id', $ad->user_id)->count();

        return view('ads.show', compact('ad', 'similarAds', 'isSaved', 'sellerAds', 'sellerRating', 'sellerReviewCount'));
    }

    public function search(Request $request)
    {
        $query        = $request->input('q', '');
        $categorySlug = $request->input('category', '');
        $locationSlug = $request->input('location', '');

        $ads = Ad::with(['primaryImage', 'location', 'category'])
            ->active()
            ->when($query, fn($q) => $q->where(function ($q2) use ($query) {
                $q2->where('title', 'like', "%{$query}%")
                   ->orWhere('description', 'like', "%{$query}%");
            }))
            ->when($categorySlug, fn($q) => $q->whereHas('category', fn($q2) => $q2->where('slug', $categorySlug)))
            ->when($locationSlug, fn($q) => $q->whereHas('location', fn($q2) => $q2->where('slug', $locationSlug)))
            ->orderBy('is_top_ad', 'desc')
            ->orderBy('is_featured', 'desc')
            ->orderBy('last_boosted_at', 'desc')
            ->latest()
            ->paginate(20);

        $categories = Category::whereNull('parent_id')->get();
        $locations  = Location::all();

        return view('ads.search', compact('ads', 'query', 'categorySlug', 'locationSlug', 'categories', 'locations'));
    }

    public function create()
    {
        $categories = Category::whereNull('parent_id')->get();
        $locations  = Location::all();
        return view('ads.create', compact('categories', 'locations'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'required|string',
            'price'           => 'required|numeric|min:0',
            'category_id'     => 'required|exists:categories,id',
            'location_id'     => 'required|exists:locations,id',
            'condition_state' => 'required|in:new,used,refurbished',
            'brand'           => 'nullable|string|max:100',
            'images'          => 'nullable|array|max:6',
            'images.*'        => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $data['user_id'] = auth()->id();
        $data['status']  = 'active';

        $ad = Ad::create($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('ads', 'public');
                AdImage::create([
                    'ad_id'      => $ad->id,
                    'image_url'  => Storage::url($path),
                    'is_primary' => $index === 0,
                ]);
            }
        }

        return redirect()->route('ads.show', $ad->id)->with('success', 'Ad posted successfully!');
    }

    public function destroy(int $id)
    {
        $ad = Ad::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $ad->delete();
        return back()->with('success', 'Ad deleted.');
    }
}
