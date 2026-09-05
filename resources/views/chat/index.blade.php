@extends('layouts.dashboard')
@section('title', 'Messages')

@section('sidebar-nav')
    <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">home</span>Browse</a>
    <a href="{{ route('buyer.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">dashboard</span>Dashboard</a>
    <a href="{{ route('chat.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm bg-primary/10 text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">chat</span>Messages</a>
    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">manage_accounts</span>Profile</a>
@endsection

@section('mobile-nav')
    <a href="{{ route('home') }}" class="flex flex-col items-center gap-0.5 text-slate-500 text-xs"><span class="material-symbols-outlined text-[22px]">home</span>Home</a>
    <a href="{{ route('favorites.index') }}" class="flex flex-col items-center gap-0.5 text-slate-500 text-xs"><span class="material-symbols-outlined text-[22px]">favorite</span>Saved</a>
    <a href="{{ route('ads.create') }}" class="flex flex-col items-center gap-0.5 -mt-4 text-xs"><div class="bg-primary text-white rounded-full p-3 shadow-lg border-4 border-white"><span class="material-symbols-outlined text-[24px]">add</span></div><span class="text-slate-500">Post</span></a>
    <a href="{{ route('chat.index') }}" class="flex flex-col items-center gap-0.5 text-primary text-xs font-medium"><span class="material-symbols-outlined text-[22px]">chat</span>Messages</a>
    <a href="{{ route('profile.edit') }}" class="flex flex-col items-center gap-0.5 text-slate-500 text-xs"><span class="material-symbols-outlined text-[22px]">person</span>Profile</a>
@endsection

@section('content')
<div>
    <h2 class="text-2xl font-black text-slate-900 mb-6">Messages</h2>

    @if($chats->count() > 0)
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden divide-y divide-slate-100">
            @foreach($chats as $chat)
                @php
                    $other = $chat->buyer_id === $userId ? $chat->seller : $chat->buyer;
                    $unread = $chat->unreadCount($userId);
                @endphp
                <a href="{{ route('chat.show', $chat->id) }}" class="flex items-center gap-4 p-5 hover:bg-slate-50 transition-colors">
                    <div class="size-12 flex-shrink-0 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-lg">
                        {{ strtoupper(substr($other->username, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <p class="font-semibold text-slate-900 text-sm">{{ $other->username }}</p>
                            @if($unread > 0)
                                <span class="bg-primary text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $unread }}</span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 truncate">{{ $chat->lastMessage?->message ?? 'No messages yet' }}</p>
                        @if($chat->ad)
                            <p class="text-[10px] text-blue-500 mt-0.5 truncate">Re: {{ $chat->ad->title }}</p>
                        @endif
                    </div>
                    <div class="flex-shrink-0 text-right">
                        <p class="text-[11px] text-slate-400">{{ $chat->lastMessage?->created_at?->diffForHumans() }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="text-center py-24">
            <span class="material-symbols-outlined text-8xl text-slate-200 block mb-4">chat</span>
            <h3 class="text-xl font-bold text-slate-600 mb-2">No conversations yet</h3>
            <p class="text-slate-400 mb-6">Browse ads and click "Chat with Seller" to start a conversation.</p>
            <a href="{{ route('home') }}" class="bg-primary text-white px-6 py-3 rounded-xl font-semibold">Browse Ads</a>
        </div>
    @endif
</div>
@endsection
