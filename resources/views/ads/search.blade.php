@extends('layouts.app')
@section('title', 'Search Results')
@section('content')
<div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
    {{-- Search Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900 mb-1">
            @if($query) Results for "{{ $query }}" @else All Ads @endif
        </h1>
        <p class="text-slate-500">{{ $ads->total() }} ads found</p>
    </div>

    {{-- Filters --}}
    <form action="{{ route('ads.search') }}" method="GET" class="flex flex-wrap gap-3 mb-8">
        <input name="q" type="text" placeholder="Search term..." value="{{ $query }}" class="flex-1 min-w-[180px] h-10 px-4 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm" />
        <select name="category" class="h-10 px-4 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary/20 text-sm">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->slug }}" {{ $categorySlug === $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <select name="location" class="h-10 px-4 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary/20 text-sm">
            <option value="">All Locations</option>
            @foreach($locations as $loc)
                <option value="{{ $loc->slug }}" {{ $locationSlug === $loc->slug ? 'selected' : '' }}>{{ $loc->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="h-10 px-6 bg-primary text-white rounded-lg font-semibold text-sm">Search</button>
    </form>

    @if($ads->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($ads as $item)
                @include('partials.ad-card', ['item' => $item, 'savedAdIds' => []])
            @endforeach
        </div>
        <div class="mt-8">{{ $ads->withQueryString()->links() }}</div>
    @else
        <div class="text-center py-20">
            <span class="material-symbols-outlined text-6xl text-slate-300 mb-4 block">search_off</span>
            <h2 class="text-xl font-bold text-slate-600 mb-2">No ads found</h2>
            <p class="text-slate-400 mb-6">Try different keywords or browse our categories</p>
            <a href="{{ route('home') }}" class="bg-primary text-white px-6 py-3 rounded-lg font-semibold">Browse All</a>
        </div>
    @endif
</div>
@endsection
