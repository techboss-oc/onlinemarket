<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Favorite::with(['ad.primaryImage', 'ad.location', 'ad.category'])
            ->where('user_id', auth()->id())
            ->latest('favorites.created_at')
            ->get();

        return view('buyer.favorites', compact('favorites'));
    }

    public function toggle(Request $request)
    {
        $request->validate(['ad_id' => 'required|exists:ads,id']);

        $existing = Favorite::where('user_id', auth()->id())
            ->where('ad_id', $request->ad_id)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['status' => 'success', 'action' => 'removed']);
        }

        Favorite::create(['user_id' => auth()->id(), 'ad_id' => $request->ad_id]);
        return response()->json(['status' => 'success', 'action' => 'added']);
    }
}
