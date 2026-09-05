<?php

namespace App\Http\Controllers;

use App\Models\Block;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'blocked_user_id' => 'required|exists:users,id',
        ]);
        
        $userId = auth()->id();
        
        if ($userId == $request->blocked_user_id) {
             return back()->with('error', 'You cannot block yourself.');
        }

        Block::firstOrCreate([
            'user_id' => $userId,
            'blocked_user_id' => $request->blocked_user_id,
        ]);

        return back()->with('success', 'User blocked successfully.');
    }
}
