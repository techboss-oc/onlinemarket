@extends('layouts.dashboard')
@section('title', 'Advertising Campaigns')

@include('seller.partials.nav')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-black text-slate-900 flex items-center gap-2">
            <span class="material-symbols-outlined text-purple-500 text-3xl">campaign</span>
            Pro Sales Campaigns (PPC)
        </h2>
        <p class="text-slate-500 mt-1">Drive guaranteed traffic to your listings through Pay-Per-Click advertising.</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 border border-green-200">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6 border border-red-200">{{ session('error') }}</div>
    @endif

    {{-- Analytics Overview --}}
    @php
        $clicks = clone $analytics;
        $totalClicks = $clicks->where('type', 'click')->first()->total ?? 0;
        $totalSpend = $clicks->where('type', 'click')->first()->total_cost ?? 0;
    @endphp
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="bg-orange-100 text-orange-600 rounded-xl p-4">
                <span class="material-symbols-outlined text-3xl">touch_app</span>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Clicks</p>
                <div class="text-3xl font-black text-slate-900 mt-1">{{ number_format($totalClicks) }}</div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="bg-green-100 text-green-600 rounded-xl p-4">
                <span class="material-symbols-outlined text-3xl">account_balance_wallet</span>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Ad Spend</p>
                <div class="text-3xl font-black text-slate-900 mt-1">₦{{ number_format($totalSpend) }}</div>
            </div>
        </div>
    </div>

    {{-- Grid Layout for Create Form & List --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Create Campaign Form --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm sticky top-6">
                <h3 class="text-lg font-black text-slate-900 mb-4">Launch New Campaign</h3>
                
                <form action="{{ route('seller.campaigns.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Select Ad</label>
                        <select name="ad_id" required class="w-full rounded-xl border border-slate-300 p-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                            <option value="">-- Choose your ad --</option>
                            @foreach($ads as $ad)
                                <option value="{{ $ad->id }}">{{ $ad->title }}</option>
                            @endforeach
                        </select>
                        @error('ad_id') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Budget (₦)</label>
                        <input type="number" name="budget" min="1000" step="500" placeholder="e.g. 5000" required class="w-full rounded-xl border border-slate-300 p-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-primary outline-none">
                        <span class="text-xs text-slate-500 mt-1 block">Minimum ₦1,000 required.</span>
                        @error('budget') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Cost Per Click / Bid (₦)</label>
                        <input type="number" name="cost_per_click" min="10" step="5" placeholder="e.g. 50" required class="w-full rounded-xl border border-slate-300 p-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-primary outline-none">
                        <span class="text-xs text-slate-500 mt-1 block">Higher bids place your ads more frequently in suggested spots!</span>
                        @error('cost_per_click') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Payment Method</label>
                        <select name="gateway" class="w-full rounded-xl border border-slate-300 p-3 bg-slate-50">
                            <option value="paystack">Paystack</option>
                            <option value="flutterwave">Flutterwave</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-primary text-white font-bold rounded-xl py-3 shadow-lg hover:bg-primary-dark transition-colors">Start Campaign</button>
                </form>
            </div>
        </div>
        
        {{-- List of Campaigns --}}
        <div class="lg:col-span-2 space-y-4">
            <h3 class="text-lg font-black text-slate-900 mb-4">Active & Past Campaigns</h3>
            
            @forelse($campaigns as $campaign)
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h4 class="font-bold text-slate-900 line-clamp-1">{{ optional($campaign->ad)->title ?? 'Unknown Ad' }}</h4>
                            <div class="text-xs text-slate-500 mt-1 flex items-center gap-3">
                                <span>Bid: ₦{{ number_format($campaign->cost_per_click) }}/click</span>
                                <span>&bull;</span>
                                <span>Created: {{ $campaign->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-1 rounded-full uppercase tracking-wider {{ $campaign->status === 'active' ? 'bg-green-100 text-green-700' : ($campaign->status === 'pending' ? 'bg-orange-100 text-orange-700' : 'bg-slate-100 text-slate-600') }}">
                            {{ $campaign->status }}
                        </span>
                    </div>

                    {{-- Budget Progress --}}
                    @php
                        $percentage = $campaign->budget > 0 ? (($campaign->budget - $campaign->remaining_budget) / $campaign->budget) * 100 : 0;
                    @endphp
                    <div class="mt-4">
                        <div class="flex justify-between text-xs font-bold text-slate-500 mb-1">
                            <span>Spend: ₦{{ number_format($campaign->budget - $campaign->remaining_budget) }}</span>
                            <span>Budget: ₦{{ number_format($campaign->budget) }}</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-primary h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-slate-50 rounded-2xl border border-slate-200 p-12 text-center">
                    <span class="material-symbols-outlined text-5xl text-slate-300 mb-3 block">data_thresholding</span>
                    <p class="font-bold text-slate-700">No campaigns yet</p>
                    <p class="text-sm text-slate-500 mt-1">Boost your views by launching your first Pay-Per-Click campaign!</p>
                </div>
            @endforelse

            <div class="mt-4">{{ $campaigns->links() }}</div>
        </div>
        
    </div>
</div>
@endsection
