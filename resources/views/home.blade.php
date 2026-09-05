@extends('layouts.app')

@section('title', 'Home')

@section('content')
{{-- Hero Section --}}
<section class="relative bg-background-light pt-8 pb-12 lg:pt-16 lg:pb-24 px-4 sm:px-6 lg:px-8">
    <div class="absolute inset-0 z-0 overflow-hidden">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[500px] bg-gradient-to-b from-blue-50 to-transparent opacity-60"></div>
        <div class="absolute top-20 right-0 w-96 h-96 bg-primary/5 rounded-full blur-3xl"></div>
        <div class="absolute top-40 left-0 w-72 h-72 bg-purple-500/5 rounded-full blur-3xl"></div>
    </div>
    <div class="relative z-10 max-w-[960px] mx-auto text-center flex flex-col items-center">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-[#0e121b] tracking-tight mb-4">
            Find anything in <span class="text-primary">Nigeria</span>
        </h1>
        <p class="text-slate-500 text-lg md:text-xl max-w-2xl mb-10 leading-relaxed">
            The trusted marketplace to buy and sell everything from cars and property to jobs and services.
        </p>
        <form action="{{ route('ads.search') }}" method="GET" class="w-full bg-white p-2 rounded-2xl shadow-soft flex flex-col md:flex-row divide-y md:divide-y-0 md:divide-x divide-slate-100 border border-slate-200">
            <div class="flex-1 flex items-center px-4 py-3 md:py-2">
                <span class="material-symbols-outlined text-slate-400 mr-3">search</span>
                <div class="flex flex-col items-start w-full">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">What?</label>
                    <input name="q" class="w-full text-sm font-medium text-slate-800 placeholder:text-slate-400 border-none p-0 focus:ring-0 bg-transparent" placeholder="I am looking for..." type="text" />
                </div>
            </div>
            <div class="flex-1 flex items-center px-4 py-3 md:py-2">
                <span class="material-symbols-outlined text-slate-400 mr-3">grid_view</span>
                <div class="flex flex-col items-start w-full">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Category</label>
                    <select name="category" class="w-full text-sm font-medium text-slate-800 border-none p-0 focus:ring-0 bg-transparent cursor-pointer">
                        <option value="">All Categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex-1 flex items-center px-4 py-3 md:py-2">
                <span class="material-symbols-outlined text-slate-400 mr-3">location_on</span>
                <div class="flex flex-col items-start w-full">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Where?</label>
                    <select name="location" class="w-full text-sm font-medium text-slate-800 border-none p-0 focus:ring-0 bg-transparent cursor-pointer">
                        <option value="">Whole Nigeria</option>
                        <option value="lagos">Lagos</option>
                        <option value="abuja">Abuja</option>
                        <option value="rivers">Port Harcourt</option>
                    </select>
                </div>
            </div>
            <div class="p-2 md:pl-4">
                <button type="submit" class="w-full md:w-auto h-full min-h-[48px] px-8 bg-primary hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-primary/25 flex items-center justify-center gap-2">Search</button>
            </div>
        </form>
    </div>
</section>

{{-- Categories Grid --}}
<section class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-bold text-[#0e121b]">Browse Categories</h3>
        <a class="text-sm font-medium text-primary hover:underline" href="{{ route('categories.index') }}">View All</a>
    </div>
    <div class="grid grid-cols-4 sm:grid-cols-4 lg:grid-cols-8 gap-2 md:gap-4">
        @foreach ($categories as $cat)
            <a class="group flex flex-col items-center justify-center gap-1.5 md:gap-3 p-2 md:p-4 bg-white rounded-xl border border-slate-100 shadow-sm hover:shadow-hover hover:border-primary/30 transition-all duration-300" href="{{ route('categories.show', $cat->slug) }}">
                <div class="w-8 h-8 md:w-12 md:h-12 rounded-full bg-blue-50 text-primary flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-colors">
                    <span class="material-symbols-outlined text-[18px] md:text-[24px]">{{ $cat->icon }}</span>
                </div>
                <span class="text-[10px] md:text-sm font-semibold text-slate-700 group-hover:text-primary text-center truncate w-full">{{ $cat->name }}</span>
            </a>
        @endforeach
    </div>
</section>

{{-- Trending Ads --}}
<section class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full">
    <div class="flex items-center gap-2 mb-6">
        <span class="material-symbols-outlined text-orange-500">local_fire_department</span>
        <h3 class="text-xl font-bold text-[#0e121b]">Trending Ads</h3>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6">
        @foreach ($trendingAds as $item)
            @include('partials.ad-card-featured', ['item' => $item])
        @endforeach
    </div>
</section>

{{-- Fresh Recommendations --}}
<section class="flex-grow bg-white border-t border-slate-100 py-10">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-[#0e121b]">Fresh Recommendations</h3>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-3 md:gap-5">
            @foreach ($freshAds as $item)
                @include('partials.ad-card', ['item' => $item])
            @endforeach
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function toggleFavorite(btn, adId) {
        const icon = btn.querySelector('.material-symbols-outlined');
        const isActive = icon.classList.contains('text-red-500');
        icon.classList.toggle('text-red-500');

        fetch('{{ route("favorites.toggle") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ ad_id: adId })
        })
        .then(r => r.json())
        .then(data => {
            if (data.status !== 'success') {
                icon.classList.toggle('text-red-500');
            }
        })
        .catch(() => {
            window.location.href = '{{ route("login") }}';
        });
    }
</script>
@endpush
