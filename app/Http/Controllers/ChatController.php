<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\Ad;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $chats = Chat::with(['buyer', 'seller', 'ad', 'lastMessage'])
            ->where('buyer_id', $userId)
            ->orWhere('seller_id', $userId)
            ->latest('updated_at')
            ->get();

        return view('chat.index', compact('chats', 'userId'));
    }

    public function show(int $chatId)
    {
        $userId = auth()->id();
        $chat = Chat::with(['buyer', 'seller', 'ad'])->findOrFail($chatId);

        // Authorization: only participants can view
        abort_unless($chat->buyer_id === $userId || $chat->seller_id === $userId, 403);

        $messages = Message::where('chat_id', $chatId)->orderBy('created_at')->get();

        // Mark messages as read
        Message::where('chat_id', $chatId)
            ->where('sender_id', '!=', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('chat.show', compact('chat', 'messages', 'userId'));
    }

    public function start(Request $request)
    {
        $request->validate([
            'seller_id' => 'required|exists:users,id',
            'ad_id'     => 'required|exists:ads,id',
        ]);

        $buyerId  = auth()->id();
        $sellerId = $request->seller_id;
        $adId     = $request->ad_id;

        abort_if($buyerId === $sellerId, 422, 'You cannot chat with yourself.');

        $chat = Chat::firstOrCreate(
            ['buyer_id' => $buyerId, 'seller_id' => $sellerId, 'ad_id' => $adId]
        );

        return redirect()->route('chat.show', $chat->id);
    }

    public function send(Request $request, int $chatId)
    {
        $userId = auth()->id();
        $chat = Chat::findOrFail($chatId);
        abort_unless($chat->buyer_id === $userId || $chat->seller_id === $userId, 403);

        $request->validate(['message' => 'required|string|max:5000']);

        $message = Message::create([
            'chat_id'   => $chatId,
            'sender_id' => $userId,
            'message'   => $request->message,
        ]);

        $chat->touch();

        if ($request->expectsJson()) {
            return response()->json(['status' => 'success', 'message' => $message]);
        }

        return back();
    }
}
