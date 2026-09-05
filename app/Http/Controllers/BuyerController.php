<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Favorite;
use App\Models\Chat;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $savedCount   = Favorite::where('user_id', $user->id)->count();
        $unreadCount  = 0;
        $chats = Chat::where('buyer_id', $user->id)->orWhere('seller_id', $user->id)->get();
        foreach ($chats as $chat) {
            $unreadCount += $chat->unreadCount($user->id);
        }

        return view('buyer.dashboard', compact('user', 'savedCount', 'unreadCount'));
    }
}
