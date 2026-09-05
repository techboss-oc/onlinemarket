@extends('layouts.dashboard')
@section('title', 'Seller Dashboard')

@include('seller.partials.nav')

@section('content')
    <div>
        <h2 class="text-2xl font-black text-slate-900 mb-1">Seller Dashboard</h2>
        <p class="text-slate-500 mb-8">Manage your listings and track performance.</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        @foreach([
            ['label' => 'Total Ads',   'value' => $stats['total_ads'],   'icon' => 'article',      'color' => 'blue'],
            ['label' => 'Active Ads',  'value' => $stats['active_ads'],  'icon' => 'check_circle', 'color' => 'green'],
            ['label' => 'Total Views', 'value' => number_format($stats['total_views']), 'icon' => 'visibility', 'color' => 'purple'],
            ['label' => 'Expired',     'value' => $stats['expired_ads'], 'icon' => 'schedule',     'color' => 'red'],
        ] as $stat)
            <div class="bg-white rounded-2xl p-5 border border-slate-200 flex flex-col gap-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-widest text-slate-400">{{ $stat['label'] }}</span>
                    <div class="p-2 rounded-xl bg-{{ $stat['color'] }}-50 text-{{ $stat['color'] }}-500"><span class="material-symbols-outlined text-[20px]">{{ $stat['icon'] }}</span></div>
                </div>
                <div class="text-3xl font-black text-slate-900">{{ $stat['value'] }}</div>
            </div>
        @endforeach
    </div>

    {{-- Recent Ads --}}
    <div class="bg-white rounded-2xl border border-slate-200">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h3 class="font-bold text-slate-900">Recent Listings</h3>
            <a href="{{ route('seller.ads') }}" class="text-sm text-primary font-medium hover:underline">View All</a>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($recentAds as $ad)
                <div class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50 transition-colors">
                    <div class="w-14 h-14 bg-slate-100 rounded-lg overflow-hidden flex-shrink-0">
                        @if($ad->primaryImage)
                            <img class="w-full h-full object-cover" src="{{ $ad->primaryImage->image_url }}" />
                        @else
                            <div class="w-full h-full flex items-center justify-center"><span class="material-symbols-outlined text-slate-300 text-2xl">image</span></div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-slate-900 truncate text-sm">{{ $ad->title }}</p>
                        <p class="text-xs text-slate-500">{{ $ad->category->name }} • {{ $ad->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="font-bold text-primary text-sm">{{ $ad->formatted_price }}</p>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $ad->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600' }}">{{ ucfirst($ad->status) }}</span>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-slate-400">No ads posted yet. <a href="{{ route('ads.create') }}" class="text-primary font-bold">Post your first ad!</a></div>
            @endforelse
        </div>
    </div>
@endsection
