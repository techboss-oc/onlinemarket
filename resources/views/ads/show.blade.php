@extends('layouts.app')

@section('title', $ad->title . ' - OnlineMarket.ng')

@push('styles')
    <meta name="description" content="{{ Str::limit($ad->description, 150) }}">
    <meta property="og:title" content="{{ $ad->title }}">
    <meta property="og:description" content="{{ Str::limit($ad->description, 150) }}">
    <meta property="og:image" content="{{ url($ad->primaryImage?->image_url ?? '') }}">
    <link rel="canonical" href="{{ route('ads.show', $ad->id) }}">
    <style>
        .lightbox { display: none; }
        .lightbox.active { display: flex; }
    </style>
@endpush

@section('content')
<div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-4 w-full">

    {{-- Breadcrumbs --}}
    <nav class="flex text-sm text-slate-500 mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('home') }}" class="inline-flex items-center hover:text-primary transition-colors"><span class="material-symbols-outlined text-[16px] mr-1">home</span>Home</a>
            </li>
            <li>
                <div class="flex items-center">
                    <span class="material-symbols-outlined text-[16px] text-slate-400 mx-1">chevron_right</span>
                    <a href="{{ route('categories.show', $ad->category->slug) }}" class="hover:text-primary transition-colors">{{ $ad->category->name }}</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <span class="material-symbols-outlined text-[16px] text-slate-400 mx-1">chevron_right</span>
                    <span class="text-slate-700 font-medium truncate max-w-[200px]">{{ $ad->title }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="flex flex-col lg:flex-row gap-8">
        {{-- Left: Images + Details --}}
        <div class="flex-1 min-w-0">
            
            {{-- Image Gallery --}}
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden mb-6 relative">
                <div class="absolute top-3 left-3 z-10 flex flex-col gap-2">
                    @if ($ad->is_top_ad)
                        <span class="bg-amber-500 text-white text-xs font-bold px-3 py-1 rounded-full uppercase shadow-md flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">star</span> Top Ad</span>
                    @endif
                    @if ($ad->is_featured)
                        <span class="bg-purple-500 text-white text-xs font-bold px-3 py-1 rounded-full uppercase shadow-md flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">rocket_launch</span> Featured</span>
                    @endif
                    @if ($ad->is_urgent)
                        <span class="bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full uppercase shadow-md flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">error</span> Urgent</span>
                    @endif
                </div>

                <div class="aspect-[16/9] bg-slate-900 relative cursor-zoom-in" onclick="openLightbox()">
                    <img id="main-image" alt="{{ $ad->title }}" class="w-full h-full object-contain"
                         src="{{ $ad->primaryImage?->image_url ?? '' }}" />
                         
                    <div class="absolute bottom-3 right-3 bg-black/50 text-white text-xs px-2 py-1 rounded flex items-center gap-1 pointer-events-none">
                        <span class="material-symbols-outlined text-[16px]">fullscreen</span> Click to enlarge
                    </div>
                </div>

                @if($ad->images->count() > 1)
                    <div class="flex gap-2 p-3 overflow-x-auto bg-slate-50">
                        @foreach($ad->images as $index => $img)
                            <button onclick="changeMainImage('{{ $img->image_url }}', {{ $index }})"
                                    class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden border-2 border-transparent focus:border-primary transition-colors gallery-thumb hover:opacity-80" data-img="{{ $img->image_url }}">
                                <img src="{{ $img->image_url }}" class="w-full h-full object-cover" />
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Details --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-6 mb-6">
                <div class="flex items-start justify-between flex-wrap gap-4 mb-4">
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold text-slate-900 mb-2">{{ $ad->title }}</h1>
                        <div class="flex items-center gap-4 text-sm text-slate-500 flex-wrap">
                            <span class="bg-slate-100 px-3 py-1 rounded text-slate-700 font-medium">Condition: {{ ucfirst($ad->condition_state) }}</span>
                            @if($ad->brand)
                                <span class="bg-slate-100 px-3 py-1 rounded text-slate-700 font-medium">Brand: {{ $ad->brand }}</span>
                            @endif
                            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">location_on</span>{{ $ad->location->name }}</span>
                            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">category</span>{{ $ad->category->name }}</span>
                            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">schedule</span>{{ $ad->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <div class="text-right w-full sm:w-auto">
                        <div class="text-3xl font-black text-primary">{{ $ad->formatted_price }}</div>
                        @if(auth()->check() && auth()->id() !== $ad->user_id)
                            <button onclick="document.getElementById('offer-modal').classList.remove('hidden')" class="mt-2 text-primary font-bold text-sm hover:underline flex items-center justify-end gap-1 w-full">
                                <span class="material-symbols-outlined text-[18px]">local_offer</span> Make an Offer
                            </button>
                        @endif
                    </div>
                </div>

                {{-- Action Bar --}}
                <div class="flex items-center justify-between border-y border-slate-100 py-3 mb-5">
                    <div class="flex items-center gap-4 text-sm text-slate-500">
                        <span class="flex items-center gap-1 text-slate-600 font-medium"><span class="material-symbols-outlined text-[18px]">visibility</span>{{ number_format($ad->views_count) }} views</span>
                        <button class="flex items-center gap-1 hover:text-primary transition-colors font-medium" onclick="shareAd()">
                            <span class="material-symbols-outlined text-[18px]">share</span> Share
                        </button>
                    </div>
                    <div class="flex items-center gap-3">
                        <button onclick="document.getElementById('report-modal').classList.remove('hidden')" class="text-slate-400 hover:text-red-500 flex items-center gap-1 text-sm font-medium transition-colors">
                            <span class="material-symbols-outlined text-[16px]">flag</span> Report Ad
                        </button>
                    </div>
                </div>

                {{-- Description with Expand/Collapse --}}
                <div>
                    <h3 class="font-bold text-slate-800 text-lg mb-3">Description</h3>
                    <div class="relative">
                        <div id="desc-content" class="text-slate-600 leading-relaxed whitespace-pre-line overflow-hidden relative max-h-48 transition-all duration-300">
                            {{ $ad->description }}
                            <div id="desc-fade" class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-white to-transparent"></div>
                        </div>
                        <button id="desc-toggle" class="text-primary font-bold mt-2 text-sm hover:underline hidden">Show More</button>
                    </div>
                </div>
            </div>
            
            {{-- More From Seller --}}
            @if(isset($sellerAds) && $sellerAds->count() > 0)
                <div class="mt-8 bg-white rounded-2xl border border-slate-200 p-6 overflow-hidden hidden lg:block">
                    <h3 class="text-xl font-bold text-slate-900 mb-5 flex items-center gap-2"><span class="material-symbols-outlined text-primary">storefront</span> More from {{ $ad->user->username }}</h3>
                    <div class="flex overflow-x-auto gap-4 pb-4 -mx-6 px-6 snap-x">
                        @foreach($sellerAds as $item)
                            <div class="w-48 flex-shrink-0 snap-start">
                                <a href="{{ route('ads.show', $item->id) }}" class="group block">
                                    <div class="aspect-square bg-slate-100 rounded-xl overflow-hidden mb-3">
                                        <img src="{{ $item->primaryImage?->image_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    </div>
                                    <h4 class="text-sm font-medium text-slate-800 truncate group-hover:text-primary transition-colors">{{ $item->title }}</h4>
                                    <div class="text-primary font-bold mt-1">{{ count(explode('.', $item->price)) ? '₦' . number_format((float)$item->price) : $item->formatted_price }}</div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Right: Seller + CTA --}}
        <div class="lg:w-80 flex flex-col gap-4">
            <div class="bg-white rounded-2xl border border-slate-200 p-6 sticky top-24">
                <h3 class="font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100 uppercase tracking-widest text-xs">Seller Information</h3>
                
                <div class="flex items-start gap-4 mb-4">
                    <div class="size-14 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-2xl flex-shrink-0">
                        {{ strtoupper(substr($ad->user->username, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-bold text-lg text-slate-900 leading-tight">
                            {{ $ad->user->username }}
                            @if($ad->user->is_verified)
                                <span class="material-symbols-outlined text-[16px] text-green-500 align-middle" title="Verified User">verified</span>
                            @endif
                        </p>
                        
                        <div class="flex items-center mt-1 text-sm text-slate-500">
                            <span class="font-bold text-yellow-500 mr-1">{{ number_format($sellerRating, 1) }}</span> 
                            <span class="material-symbols-outlined text-[14px] text-yellow-500 mr-1">star</span> 
                            <span>({{ $sellerReviewCount }} reviews)</span>
                        </div>
                        
                        <div class="text-xs text-slate-400 mt-2">
                            Member since {{ $ad->user->created_at->format('M Y') }}
                        </div>
                    </div>
                </div>

                {{-- Contact Buttons --}}
                <div class="space-y-3 mt-6">
                    @auth
                        @if (auth()->id() !== $ad->user_id)
                            <form action="{{ route('chat.start') }}" method="POST">
                                @csrf
                                <input type="hidden" name="seller_id" value="{{ $ad->user_id }}">
                                <input type="hidden" name="ad_id" value="{{ $ad->id }}">
                                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-primary hover:bg-blue-700 text-white py-3 rounded-xl font-bold transition-all shadow-md shadow-primary/20">
                                    <span class="material-symbols-outlined text-[20px]">chat</span>
                                    Chat with Seller
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="w-full flex items-center justify-center gap-2 bg-primary hover:bg-blue-700 text-white py-3 rounded-xl font-bold transition-all shadow-md shadow-primary/20">
                            <span class="material-symbols-outlined text-[20px]">chat</span>
                            Login to Chat
                        </a>
                    @endauth

                    <button id="show-phone-btn" class="w-full flex items-center justify-center gap-2 bg-slate-900 hover:bg-slate-800 text-white py-3 rounded-xl font-bold transition-all shadow-md" onclick="showPhone(this, '{{ $ad->user->phone ?? 'Not provided' }}')">
                        <span class="material-symbols-outlined text-[20px]">call</span>
                        Show Phone Number
                    </button>
                    
                    @if($ad->user->phone)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $ad->user->phone) }}?text=Hi, I am interested in your ad: {{ urlencode($ad->title) }}" target="_blank" class="w-full flex items-center justify-center gap-2 bg-[#25D366] hover:bg-[#20b858] text-white py-3 rounded-xl font-bold transition-all shadow-md">
                        <svg viewBox="0 0 24 24" class="w-5 h-5 fill-current"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.878-.788-1.47-1.761-1.643-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></svg>
                        WhatsApp
                    </a>
                    @endif

                    <button onclick="toggleFavorite(this, {{ $ad->id }})"
                            class="w-full flex items-center justify-center gap-2 border-2 {{ $isSaved ? 'border-red-500 text-red-500 bg-red-50' : 'border-slate-200 text-slate-700 hover:border-red-500 hover:text-red-500' }} py-3 rounded-xl font-bold transition-all mt-2">
                        <span class="material-symbols-outlined text-[20px]">{{ $isSaved ? 'favorite' : 'favorite_border' }}</span>
                        <span>{{ $isSaved ? 'Saved' : 'Save Ad' }}</span>
                    </button>
                    
                    @if(auth()->check() && auth()->id() !== $ad->user_id)
                        <button onclick="document.getElementById('review-modal').classList.remove('hidden')" class="w-full text-center text-sm text-slate-500 hover:text-primary mt-2 font-medium transition-colors">Leave a Review</button>
                        <hr class="border-slate-100 my-4">
                        <button onclick="document.getElementById('block-modal').classList.remove('hidden')" class="w-full flex items-center justify-center gap-1 text-slate-400 hover:text-red-500 text-sm transition-colors">
                            <span class="material-symbols-outlined text-[16px]">block</span> Block this Seller
                        </button>
                    @endif
                </div>

                <div class="mt-6 p-4 bg-amber-50 border border-amber-200 rounded-xl flex items-start gap-3">
                    <span class="material-symbols-outlined text-amber-500 text-[24px]">shield</span>
                    <div class="text-xs text-amber-800 leading-relaxed">
                        <strong class="block mb-1">Safety First</strong>
                        Never pay before inspecting the item. Do not send deposits or wire transfers. Read our <a href="{{ route('safety-tips') }}" class="font-bold underline hover:text-amber-900">Safety Tips</a>.
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- More From This Seller (Mobile specific) --}}
    @if(isset($sellerAds) && $sellerAds->count() > 0)
        <div class="mt-8 bg-white rounded-2xl border border-slate-200 p-6 overflow-hidden block lg:hidden">
            <h3 class="text-xl font-bold text-slate-900 mb-5 flex items-center gap-2"><span class="material-symbols-outlined text-primary">storefront</span> More from {{ $ad->user->username }}</h3>
            <div class="flex overflow-x-auto gap-4 pb-4 -mx-6 px-6 snap-x">
                @foreach($sellerAds as $item)
                    <div class="w-48 flex-shrink-0 snap-start">
                        <a href="{{ route('ads.show', $item->id) }}" class="group block">
                            <div class="aspect-square bg-slate-100 rounded-xl overflow-hidden mb-3">
                                <img src="{{ $item->primaryImage?->image_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                            <h4 class="text-sm font-medium text-slate-800 truncate group-hover:text-primary transition-colors">{{ $item->title }}</h4>
                            <div class="text-primary font-bold mt-1">{{ count(explode('.', $item->price)) ? '₦' . number_format((float)$item->price) : $item->formatted_price }}</div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Similar Ads block remains the same --}}
    @if($similarAds->count() > 0)
        <div class="mt-12">
            <h3 class="text-xl font-bold text-slate-900 mb-5">Similar Ads in {{ $ad->category->name }}</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($similarAds as $item)
                    @include('partials.ad-card', ['item' => $item, 'savedAdIds' => []])
                @endforeach
            </div>
        </div>
    @endif
</div>

{{-- Fullscreen Lightbox --}}
<div id="lightbox" class="lightbox fixed inset-0 z-[100] bg-black/95 flex-col w-full h-full pb-[calc(100vh-100%)]">
    <div class="absolute top-0 w-full p-4 flex justify-between items-center bg-gradient-to-b from-black/50 to-transparent z-10 text-white">
        <div class="font-medium truncate pr-4">{{ $ad->title }}</div>
        <button onclick="closeLightbox()" class="p-2 hover:bg-white/10 rounded-full transition-colors flex-shrink-0">
            <span class="material-symbols-outlined text-3xl">close</span>
        </button>
    </div>
    
    <div class="flex-1 relative flex items-center justify-center p-4">
        <button onclick="prevImage()" class="absolute left-4 p-3 rounded-full bg-black/50 text-white hover:bg-white/20 transition-colors z-10">
            <span class="material-symbols-outlined text-2xl">chevron_left</span>
        </button>
        
        <img id="lightbox-img" src="" class="max-w-full max-h-full object-contain transition-transform">
        
        <button onclick="nextImage()" class="absolute right-4 p-3 rounded-full bg-black/50 text-white hover:bg-white/20 transition-colors z-10">
            <span class="material-symbols-outlined text-2xl">chevron_right</span>
        </button>
    </div>
    
    @if($ad->images->count() > 1)
        <div class="h-24 bg-black/80 flex items-center gap-2 px-4 overflow-x-auto border-t border-white/10">
            @foreach($ad->images as $index => $img)
                <button onclick="lightboxChangeImage({{ $index }})" 
                        id="lb-thumb-{{ $index }}"
                        class="lb-thumb flex-shrink-0 w-16 h-16 rounded-md overflow-hidden border-2 border-transparent transition-colors opacity-50 hover:opacity-100">
                    <img src="{{ $img->image_url }}" class="w-full h-full object-cover" />
                </button>
            @endforeach
        </div>
    @endif
</div>

{{-- Mobile Sticky Bottom Bar --}}
<div class="lg:hidden fixed bottom-[60px] left-0 right-0 bg-white border-t border-slate-200 p-3 z-40 flex gap-2 shadow-[0_-10px_20px_-10px_rgba(0,0,0,0.1)] pb-safe">
    <button onclick="toggleFavorite(this, {{ $ad->id }})" class="flex-shrink-0 size-12 flex items-center justify-center rounded-xl border border-slate-200 text-slate-600 bg-slate-50 relative top-auto">
        <span class="material-symbols-outlined {{ $isSaved ? 'text-red-500 fill-current' : '' }}">{{ $isSaved ? 'favorite' : 'favorite_border' }}</span>
    </button>
    
    @auth
        @if (auth()->id() !== $ad->user_id)
            <button onclick="document.getElementById('offer-modal').classList.remove('hidden')" class="flex-1 h-12 bg-blue-100 text-primary font-bold rounded-xl flex items-center justify-center gap-1 border border-primary/20">
                <span class="material-symbols-outlined text-[18px]">local_offer</span> Offer
            </button>
            <form action="{{ route('chat.start') }}" method="POST" class="flex-1">
                @csrf
                <input type="hidden" name="seller_id" value="{{ $ad->user_id }}">
                <input type="hidden" name="ad_id" value="{{ $ad->id }}">
                <button type="submit" class="w-full h-12 bg-primary text-white font-bold rounded-xl flex items-center justify-center gap-1 shadow-md shadow-primary/20">
                    <span class="material-symbols-outlined text-[18px]">chat</span> Chat
                </button>
            </form>
        @else
            <div class="flex-1 flex items-center justify-center h-12 bg-slate-100 text-slate-400 font-bold rounded-xl border border-slate-200">
                Your Ad
            </div>
        @endif
    @else
        <button onclick="window.location='{{ route('login') }}'" class="flex-1 h-12 bg-blue-100 text-primary font-bold rounded-xl flex items-center justify-center gap-1 border border-primary/20">
            <span class="material-symbols-outlined text-[18px]">local_offer</span> Offer
        </button>
        <a href="{{ route('login') }}" class="flex-1 h-12 bg-primary text-white font-bold rounded-xl flex items-center justify-center gap-1 shadow-md shadow-primary/20">
            <span class="material-symbols-outlined text-[18px]">chat</span> Chat
        </a>
    @endauth
    
    @if($ad->user->phone)
        <a href="tel:{{ preg_replace('/[^0-9]/', '', $ad->user->phone) }}" class="flex-shrink-0 size-12 flex items-center justify-center rounded-xl bg-green-500 text-white shadow-md">
            <span class="material-symbols-outlined text-[18px]">call</span>
        </a>
    @endif
</div>

{{-- Modals container --}}
@auth
    @if(auth()->id() !== $ad->user_id)
        {{-- Offer Modal --}}
        <div id="offer-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
            <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl animate-scale">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-slate-900">Make an Offer</h3>
                    <button type="button" onclick="document.getElementById('offer-modal').classList.add('hidden')" class="text-slate-400 hover:text-red-500"><span class="material-symbols-outlined">close</span></button>
                </div>
                <form action="{{ route('offers.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="ad_id" value="{{ $ad->id }}">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Your Price (₦)</label>
                        <input type="number" name="amount" min="1" required class="w-full border border-slate-300 rounded-lg p-3 focus:ring-primary focus:border-primary text-lg font-bold">
                        <p class="text-xs text-slate-500 mt-1">Current price: {{ $ad->formatted_price }}</p>
                    </div>
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Message (Optional)</label>
                        <textarea name="message" rows="3" class="w-full border border-slate-300 rounded-lg p-3 focus:ring-primary focus:border-primary" placeholder="E.g. I can pick it up today."></textarea>
                    </div>
                    <button type="submit" class="w-full bg-primary hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-primary/20">Send Offer</button>
                </form>
            </div>
        </div>

        {{-- Review Modal --}}
        <div id="review-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
            <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl animate-scale">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-slate-900">Review Seller</h3>
                    <button type="button" onclick="document.getElementById('review-modal').classList.add('hidden')" class="text-slate-400 hover:text-red-500"><span class="material-symbols-outlined">close</span></button>
                </div>
                <form action="{{ route('reviews.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="seller_id" value="{{ $ad->user_id }}">
                    <input type="hidden" name="ad_id" value="{{ $ad->id }}">
                    <div class="mb-4 text-center">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Rating</label>
                        <select name="rating" required class="w-full border border-slate-300 rounded-lg p-3 text-center text-lg font-bold focus:ring-primary focus:border-primary bg-white">
                            <option value="5">⭐⭐⭐⭐⭐ (5 - Excellent)</option>
                            <option value="4">⭐⭐⭐⭐ (4 - Good)</option>
                            <option value="3">⭐⭐⭐ (3 - Average)</option>
                            <option value="2">⭐⭐ (2 - Poor)</option>
                            <option value="1">⭐ (1 - Terrible)</option>
                        </select>
                    </div>
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Comment</label>
                        <textarea name="comment" rows="3" class="w-full border border-slate-300 rounded-lg p-3 focus:ring-primary focus:border-primary" placeholder="How was your experience?"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl shadow-lg">Submit Review</button>
                </form>
            </div>
        </div>

        {{-- Report Modal --}}
        <div id="report-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
            <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl animate-scale">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-slate-900">Report Ad</h3>
                    <button type="button" onclick="document.getElementById('report-modal').classList.add('hidden')" class="text-slate-400 hover:text-red-500"><span class="material-symbols-outlined">close</span></button>
                </div>
                <form action="{{ route('reports.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="ad_id" value="{{ $ad->id }}">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Reason</label>
                        <select name="reason" required class="w-full border border-slate-300 rounded-lg p-3 focus:ring-primary focus:border-primary bg-white">
                            <option value="">Select a reason...</option>
                            <option value="fraud">Fraud / Scam</option>
                            <option value="unavailable">Item no longer available</option>
                            <option value="spam">Spam or misleading</option>
                            <option value="inappropriate">Inappropriate content</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Additional Details</label>
                        <textarea name="description" rows="3" class="w-full border border-slate-300 rounded-lg p-3 focus:ring-primary focus:border-primary" placeholder="Please provide more information..."></textarea>
                    </div>
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl shadow-lg">Submit Report</button>
                </form>
            </div>
        </div>
        
        {{-- Block Modal --}}
        <div id="block-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
            <div class="bg-white rounded-2xl p-6 w-full max-w-sm shadow-2xl text-center animate-scale">
                <div class="size-16 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-3xl">block</span>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Block Seller?</h3>
                <p class="text-slate-600 text-sm mb-6">You will no longer see ads or messages from this seller.</p>
                <form action="{{ route('blocks.store') }}" method="POST" class="flex gap-3">
                    @csrf
                    <input type="hidden" name="blocked_user_id" value="{{ $ad->user_id }}">
                    <button type="button" onclick="document.getElementById('block-modal').classList.add('hidden')" class="flex-1 py-3 rounded-xl font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors">Cancel</button>
                    <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl shadow-lg transition-colors">Block</button>
                </form>
            </div>
        </div>
    @endif
@endauth

@endsection

@push('scripts')
<script>
// Gallery Data
const images = [
    @foreach($ad->images as $img)
        "{{ $img->image_url }}",
    @endforeach
];
let currentImageIndex = 0;

function changeMainImage(url, index) {
    document.getElementById('main-image').src = url;
    currentImageIndex = index;
    document.querySelectorAll('.gallery-thumb').forEach((el, i) => {
        if(i === index) el.classList.add('border-primary');
        else el.classList.remove('border-primary');
    });
}

// Lightbox logic
function openLightbox() {
    document.getElementById('lightbox').classList.add('active');
    document.body.style.overflow = 'hidden';
    updateLightbox();
}

function closeLightbox() {
    document.getElementById('lightbox').classList.remove('active');
    document.body.style.overflow = 'auto';
}

function updateLightbox() {
    if(images.length === 0) return;
    document.getElementById('lightbox-img').src = images[currentImageIndex];
    document.querySelectorAll('.lb-thumb').forEach((el, i) => {
        if(i === currentImageIndex) el.classList.add('border-white', 'opacity-100');
        else {
            el.classList.remove('border-white', 'opacity-100');
            el.classList.add('border-transparent', 'opacity-50');
        }
    });
}

function nextImage() {
    currentImageIndex = (currentImageIndex + 1) % images.length;
    updateLightbox();
}

function prevImage() {
    currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
    updateLightbox();
}

function lightboxChangeImage(index) {
    currentImageIndex = index;
    updateLightbox();
}

// Favorite logic
function toggleFavorite(btn, adId) {
    @if(!auth()->check())
        window.location.href = '{{ route("login") }}';
        return;
    @endif
    
    const buttons = document.querySelectorAll(`button[onclick="toggleFavorite(this, ${adId})"]`);
    
    fetch('{{ route("favorites.toggle") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ ad_id: adId })
    }).then(r => r.json()).then(data => {
        buttons.forEach(b => {
             const icon = b.querySelector('.material-symbols-outlined');
             const text = b.querySelector('span:not(.material-symbols-outlined)');
             
             if (data.action === 'added') {
                 b.classList.add('border-red-500', 'text-red-500', 'bg-red-50');
                 b.classList.remove('border-slate-200', 'text-slate-700');
                 if(icon) { icon.classList.add('fill-current'); icon.textContent = 'favorite'; }
                 if(text && text.textContent.includes('Save')) text.textContent = 'Saved';
             } else {
                 b.classList.remove('border-red-500', 'text-red-500', 'bg-red-50');
                 b.classList.add('border-slate-200', 'text-slate-700');
                 if(icon) { icon.classList.remove('fill-current'); icon.textContent = 'favorite_border'; }
                 if(text && text.textContent.includes('Save')) text.textContent = 'Save Ad';
             }
        });
    });
}

// Show Phone
function showPhone(btn, phoneStr) {
    btn.innerHTML = `<span class="material-symbols-outlined text-[20px]">phone</span> ${phoneStr}`;
    btn.classList.remove('bg-slate-900', 'hover:bg-slate-800', 'text-white');
    btn.classList.add('bg-white', 'text-slate-900', 'border', 'border-slate-300');
}

// Share Native
function shareAd() {
    if (navigator.share) {
        navigator.share({
            title: '{!! addslashes($ad->title) !!}',
            text: 'Check out this ad on OnlineMarket.ng',
            url: window.location.href,
        }).catch(console.error);
    } else {
        navigator.clipboard.writeText(window.location.href);
        alert('Link copied to clipboard!');
    }
}

// Initialize Expand/Collapse
document.addEventListener('DOMContentLoaded', () => {
    const desc = document.getElementById('desc-content');
    const toggle = document.getElementById('desc-toggle');
    const fade = document.getElementById('desc-fade');
    
    if(images.length > 0) {
        changeMainImage(images[0], 0);
    }
    
    if (desc && desc.scrollHeight > desc.clientHeight) {
        toggle.classList.remove('hidden');
        toggle.addEventListener('click', () => {
            if (desc.style.maxHeight) {
                desc.style.maxHeight = null;
                toggle.textContent = 'Show More';
                fade.classList.remove('hidden');
            } else {
                desc.style.maxHeight = desc.scrollHeight + 'px';
                toggle.textContent = 'Show Less';
                fade.classList.add('hidden');
            }
        });
    } else if (fade) {
        fade.classList.add('hidden');
    }
    
    document.addEventListener('keydown', (e) => {
        if(document.getElementById('lightbox').classList.contains('active')) {
            if(e.key === 'Escape') closeLightbox();
            if(e.key === 'ArrowRight') nextImage();
            if(e.key === 'ArrowLeft') prevImage();
        }
    });

    // Close modals on escape or click outside
    const modals = ['offer-modal', 'review-modal', 'report-modal', 'block-modal'];
    document.addEventListener('keydown', (e) => {
        if(e.key === 'Escape') {
            modals.forEach(id => {
                const modal = document.getElementById(id);
                if(modal && !modal.classList.contains('hidden')) modal.classList.add('hidden');
            });
        }
    });
    
    modals.forEach(id => {
        const modal = document.getElementById(id);
        if(modal) {
            modal.addEventListener('click', function(e) {
                if(e.target === this) this.classList.add('hidden');
            });
        }
    });
});
</script>
<style>
    .pb-safe { padding-bottom: env(safe-area-inset-bottom, 16px); }
    .animate-scale { animation: scaleIn 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes scaleIn { from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }
</style>
@endpush
