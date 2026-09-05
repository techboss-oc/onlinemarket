@extends('layouts.dashboard')
@section('title', 'Saved Ads')

@section('sidebar-nav')
    <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">home</span>Browse</a>
    <a href="{{ route('buyer.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">dashboard</span>Dashboard</a>
    <a href="{{ route('favorites.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm bg-primary/10 text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">favorite</span>Saved Ads</a>
    <a href="{{ route('chat.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">chat</span>Messages</a>
    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">manage_accounts</span>Profile</a>
@endsection

@section('mobile-nav')
    <a href="{{ route('home') }}" class="flex flex-col items-center gap-0.5 text-slate-500 text-xs"><span class="material-symbols-outlined text-[22px]">home</span>Home</a>
    <a href="{{ route('favorites.index') }}" class="flex flex-col items-center gap-0.5 text-primary text-xs font-medium"><span class="material-symbols-outlined text-[22px]">favorite</span>Saved</a>
    <a href="{{ route('ads.create') }}" class="flex flex-col items-center gap-0.5 -mt-4 text-xs"><div class="bg-primary text-white rounded-full p-3 shadow-lg border-4 border-white"><span class="material-symbols-outlined text-[24px]">add</span></div><span class="text-slate-500">Post</span></a>
    <a href="{{ route('chat.index') }}" class="flex flex-col items-center gap-0.5 text-slate-500 text-xs"><span class="material-symbols-outlined text-[22px]">chat</span>Messages</a>
    <a href="{{ route('profile.edit') }}" class="flex flex-col items-center gap-0.5 text-slate-500 text-xs"><span class="material-symbols-outlined text-[22px]">person</span>Profile</a>
@endsection

@section('content')
<div>
    <h2 class="text-2xl font-black text-slate-900 mb-6">Saved Ads</h2>

    @if($favorites->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($favorites as $fav)
                @if($fav->ad)
                    @include('partials.ad-card', ['item' => $fav->ad, 'savedAdIds' => [$fav->ad_id]])
                @endif
            @endforeach
        </div>
    @else
        <div class="text-center py-24">
            <span class="material-symbols-outlined text-8xl text-slate-200 mb-4 block">favorite</span>
            <h3 class="text-xl font-bold text-slate-600 mb-2">No saved ads yet</h3>
            <p class="text-slate-400 mb-6">Click the heart icon on any ad to save it here.</p>
            <a href="{{ route('home') }}" class="bg-primary text-white px-6 py-3 rounded-xl font-semibold">Browse Ads</a>
        </div>
    @endif
</div>
@endsection
