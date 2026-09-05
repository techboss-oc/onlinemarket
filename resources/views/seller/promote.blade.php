@extends('layouts.dashboard')
@section('title', 'Promote Ad')

@include('seller.partials.nav')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-900 flex items-center gap-2">
                <span class="material-symbols-outlined text-orange-500 text-3xl">rocket_launch</span>
                Promote Your Ad
            </h2>
            <p class="text-slate-500 mt-1">Get up to 10x more visibility and sell faster!</p>
        </div>
        <a href="{{ route('seller.ads') }}" class="text-sm font-medium text-primary hover:underline">Back to My Ads</a>
    </div>

    @if(session('error'))
        <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6 border border-red-200">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6 border border-red-200">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 p-4 flex gap-4 md:items-center mb-8 shadow-sm">
        <div class="size-20 shrink-0 bg-slate-100 rounded-xl overflow-hidden {{ !$ad->primaryImage ? 'flex items-center justify-center' : '' }}">
            @if($ad->primaryImage)
                <img src="{{ $ad->primaryImage->image_url }}" class="w-full h-full object-cover">
            @else
                <span class="material-symbols-outlined text-slate-400">image</span>
            @endif
        </div>
        <div>
            <h3 class="font-bold text-slate-900 line-clamp-1">{{ $ad->title }}</h3>
            <p class="text-primary font-black mt-1">{{ $ad->formatted_price }}</p>
            <div class="flex items-center gap-2 mt-2">
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $ad->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600' }}">{{ ucfirst($ad->status) }}</span>
                <span class="text-xs text-slate-500">Posted on {{ $ad->created_at->format('M d, Y') }}</span>
            </div>
        </div>
    </div>

    <form action="{{ route('seller.promote.init', $ad->id) }}" method="POST" id="promotion-form">
        @csrf
        
        <h3 class="text-lg font-black text-slate-800 mb-4">Choose a Promotion Package</h3>
        
        <div class="space-y-6 mb-8">
            {{-- Boost Packages --}}
            @if($boosts->count() > 0)
            <div>
                <h4 class="font-bold text-slate-700 mb-3 flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-500">trending_up</span> 
                    Boost Packages
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($boosts as $pkg)
                        <label class="relative block cursor-pointer group">
                            <input type="radio" name="package_id" value="{{ $pkg->id }}" class="peer sr-only">
                            <div class="bg-white rounded-2xl border-2 border-slate-200 p-5 transition-all peer-checked:border-primary peer-checked:bg-primary/5 hover:border-slate-300">
                                <h5 class="font-bold text-slate-900 group-hover:text-primary transition-colors">{{ $pkg->name }}</h5>
                                <p class="text-xs text-slate-500 my-2">{{ $pkg->description }}</p>
                                <div class="font-black text-slate-900 text-lg">{{ $pkg->currency ?? 'NGN' }} {{ number_format($pkg->price) }}</div>
                                
                                <div class="absolute top-4 right-4 text-slate-300 peer-checked:text-primary transition-colors">
                                    <span class="material-symbols-outlined peer-checked:text-[24px]">check_circle</span>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Top Ad Packages --}}
            @if($topAds->count() > 0)
            <div>
                <h4 class="font-bold text-slate-700 mb-3 flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-500">vertical_align_top</span> 
                    Top Ads (Pin to Top)
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($topAds as $pkg)
                        <label class="relative block cursor-pointer group">
                            <input type="radio" name="package_id" value="{{ $pkg->id }}" class="peer sr-only">
                            <div class="bg-white rounded-2xl border-2 border-slate-200 p-5 transition-all peer-checked:border-primary peer-checked:bg-primary/5 hover:border-slate-300">
                                <h5 class="font-bold text-slate-900 group-hover:text-primary transition-colors">{{ $pkg->name }}</h5>
                                <p class="text-xs text-slate-500 my-2">{{ $pkg->description }}</p>
                                <div class="font-black text-slate-900 text-lg">{{ $pkg->currency ?? 'NGN' }} {{ number_format($pkg->price) }}</div>
                                
                                <div class="absolute top-4 right-4 text-slate-300 peer-checked:text-primary transition-colors">
                                    <span class="material-symbols-outlined peer-checked:text-[24px]">check_circle</span>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Featured Packages --}}
            @if($featured->count() > 0)
            <div>
                <h4 class="font-bold text-slate-700 mb-3 flex items-center gap-2">
                    <span class="material-symbols-outlined text-purple-500">stars</span> 
                    Homepage VIP
                </h4>
                <div class="grid grid-cols-1 gap-4">
                    @foreach($featured as $pkg)
                        <label class="relative block cursor-pointer group">
                            <input type="radio" name="package_id" value="{{ $pkg->id }}" class="peer sr-only">
                            <div class="bg-gradient-to-br from-purple-600 to-indigo-700 rounded-2xl border-2 border-transparent p-5 transition-all peer-checked:ring-4 peer-checked:ring-purple-300 text-white hover:opacity-95 shadow-xl">
                                <div class="flex items-center justify-between border-b border-purple-400/50 pb-3 mb-3">
                                    <h5 class="font-black text-xl">{{ $pkg->name }}</h5>
                                    <div class="absolute top-4 right-4 text-white/50 peer-checked:text-white transition-colors">
                                        <span class="material-symbols-outlined peer-checked:text-[24px]">check_circle</span>
                                    </div>
                                </div>
                                <p class="text-sm font-medium text-purple-100 mb-3">{{ $pkg->description }}</p>
                                <div class="font-black text-2xl">{{ $pkg->currency ?? 'NGN' }} {{ number_format($pkg->price) }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm mb-6">
            <h4 class="font-bold text-slate-900 mb-4">Payment Method</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="flex items-center p-4 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 group">
                    <input type="radio" name="gateway" value="paystack" required class="text-primary focus:ring-primary h-5 w-5 border-slate-300">
                    <div class="ml-3 flex-1 flex items-center justify-between">
                        <span class="font-bold text-slate-700 group-hover:text-primary">Paystack</span>
                        <span class="material-symbols-outlined text-slate-400">credit_card</span>
                    </div>
                </label>
                <label class="flex items-center p-4 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 group">
                    <input type="radio" name="gateway" value="flutterwave" required class="text-primary focus:ring-primary h-5 w-5 border-slate-300">
                    <div class="ml-3 flex-1 flex items-center justify-between">
                        <span class="font-bold text-slate-700 group-hover:text-primary">Flutterwave</span>
                        <span class="material-symbols-outlined text-slate-400">public</span>
                    </div>
                </label>
            </div>
        </div>

        <button type="submit" class="w-full bg-primary text-white font-bold text-lg rounded-xl py-4 shadow-xl hover:bg-primary-dark transition-all transform hover:-translate-y-0.5">
            Proceed to Payment <span class="material-symbols-outlined align-middle ml-2">arrow_forward</span>
        </button>

    </form>
</div>
@endsection
