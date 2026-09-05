@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('sidebar-nav')
    @php
        $navItems = [
            ['route' => 'home',           'icon' => 'home',               'label' => 'Browse'],
            ['route' => 'buyer.dashboard','icon' => 'dashboard',          'label' => 'Dashboard'],
            ['route' => 'favorites.index','icon' => 'favorite',           'label' => 'Saved Ads'],
            ['route' => 'chat.index',     'icon' => 'chat',               'label' => 'Messages'],
            ['route' => 'ads.create',     'icon' => 'add_circle',         'label' => 'Post Ad'],
            ['route' => 'profile.edit',   'icon' => 'manage_accounts',    'label' => 'Profile'],
        ];
    @endphp
    @foreach($navItems as $item)
        <a href="{{ route($item['route']) }}"
           class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm {{ request()->routeIs($item['route']) ? 'bg-primary/10 text-primary' : 'text-slate-600 hover:bg-slate-50 hover:text-primary' }} transition-colors">
            <span class="material-symbols-outlined text-[20px]">{{ $item['icon'] }}</span>
            {{ $item['label'] }}
        </a>
    @endforeach
@endsection

@section('mobile-nav')
    <a href="{{ route('home') }}" class="flex flex-col items-center gap-0.5 text-slate-500 text-xs"><span class="material-symbols-outlined text-[22px]">home</span>Home</a>
    <a href="{{ route('favorites.index') }}" class="flex flex-col items-center gap-0.5 text-slate-500 text-xs"><span class="material-symbols-outlined text-[22px]">favorite</span>Saved</a>
    <a href="{{ route('ads.create') }}" class="flex flex-col items-center gap-0.5 -mt-4 text-xs">
        <div class="bg-primary text-white rounded-full p-3 shadow-lg shadow-primary/30 border-4 border-white"><span class="material-symbols-outlined text-[24px]">add</span></div>
        <span class="text-slate-500">Post</span>
    </a>
    <a href="{{ route('chat.index') }}" class="flex flex-col items-center gap-0.5 text-slate-500 text-xs"><span class="material-symbols-outlined text-[22px]">chat</span>Messages</a>
    <a href="{{ route('profile.edit') }}" class="flex flex-col items-center gap-0.5 text-primary text-xs font-medium"><span class="material-symbols-outlined text-[22px]">person</span>Profile</a>
@endsection

@section('content')
    {{-- Welcome --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-black text-slate-900 dark:text-white">Welcome, {{ $user->username }} 👋</h2>
            <p class="text-slate-500 mt-1">Here's a quick overview of your account.</p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 flex flex-col gap-2">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-widest text-slate-400">Saved Ads</span>
                <div class="p-2 rounded-xl bg-blue-50 text-primary"><span class="material-symbols-outlined text-[20px]">favorite</span></div>
            </div>
            <div class="text-3xl font-black text-slate-900 dark:text-white">{{ $savedCount }}</div>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 flex flex-col gap-2">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-widest text-slate-400">Unread Msgs</span>
                <div class="p-2 rounded-xl bg-purple-50 text-purple-500"><span class="material-symbols-outlined text-[20px]">chat</span></div>
            </div>
            <div class="text-3xl font-black text-slate-900 dark:text-white">{{ $unreadCount }}</div>
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('ads.create') }}" class="group bg-gradient-to-br from-primary to-blue-700 text-white rounded-2xl p-6 flex items-center gap-4 shadow-lg shadow-primary/25 hover:shadow-hover transition-all">
            <div class="p-3 bg-white/20 rounded-xl"><span class="material-symbols-outlined text-[28px]">add_circle</span></div>
            <div><div class="font-bold text-lg">Post an Ad</div><div class="text-blue-100 text-sm">Sell something today</div></div>
        </a>
        <a href="{{ route('favorites.index') }}" class="group bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 flex items-center gap-4 hover:shadow-hover transition-all">
            <div class="p-3 bg-red-50 rounded-xl text-red-500"><span class="material-symbols-outlined text-[28px]">favorite</span></div>
            <div><div class="font-bold text-lg text-slate-900 dark:text-white">Saved Ads</div><div class="text-slate-500 text-sm">{{ $savedCount }} saved items</div></div>
        </a>
        <a href="{{ route('chat.index') }}" class="group bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 flex items-center gap-4 hover:shadow-hover transition-all">
            <div class="p-3 bg-green-50 rounded-xl text-green-500"><span class="material-symbols-outlined text-[28px]">chat</span></div>
            <div><div class="font-bold text-lg text-slate-900 dark:text-white">Messages</div><div class="text-slate-500 text-sm">{{ $unreadCount > 0 ? $unreadCount . ' unread' : 'No unread' }}</div></div>
        </a>
    </div>
@endsection
