<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Ad;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'seller_id' => 'required|exists:users,id',
            'ad_id' => 'nullable|exists:ads,id',
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);
        
        $userId = auth()->id();

        if ($userId == $request->seller_id) {
            return back()->with('error', 'You cannot review yourself.');
        }

        Review::updateOrCreate(
            [
                'buyer_id' => $userId, 
                'seller_id' => $request->seller_id, 
                'ad_id' => $request->ad_id
            ],
            [
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]
        );

        return back()->with('success', 'Review submitted successfully!');
    }
}
