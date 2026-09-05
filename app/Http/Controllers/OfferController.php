<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Ad;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'ad_id' => 'required|exists:ads,id',
            'amount' => 'required|numeric|min:1',
            'message' => 'nullable|string|max:1000'
        ]);

        $ad = Ad::findOrFail($request->ad_id);

        if ($ad->user_id === auth()->id()) {
            return back()->with('error', 'You cannot make an offer on your own ad.');
        }

        Offer::create([
            'buyer_id' => auth()->id(),
            'seller_id' => $ad->user_id,
            'ad_id' => $ad->id,
            'amount' => $request->amount,
            'message' => $request->message,
        ]);

        return back()->with('success', 'Your offer has been sent to the seller!');
    }
}
