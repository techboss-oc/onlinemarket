<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Category;
use App\Models\Favorite;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories   = Category::whereNull('parent_id')->get();
        $trendingAds  = Ad::with(['primaryImage', 'location', 'category'])
                          ->active()
                          ->orderBy('views_count', 'desc')
                          ->take(4)
                          ->get();
        $freshAds     = Ad::with(['primaryImage', 'location', 'category'])
                          ->active()
                          ->orderBy('is_top_ad', 'desc')
                          ->orderBy('is_featured', 'desc')
                          ->orderBy('last_boosted_at', 'desc')
                          ->latest()
                          ->take(8)
                          ->get();

        $savedAdIds = [];
        if (auth()->check()) {
            $savedAdIds = Favorite::where('user_id', auth()->id())->pluck('ad_id')->toArray();
        }

        return view('home', compact('categories', 'trendingAds', 'freshAds', 'savedAdIds'));
    }
}
