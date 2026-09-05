@extends('layouts.app')
@section('title', $category->name . ' Ads')

@section('content')
<div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
    {{-- Header --}}
    <div class="flex items-center gap-4 mb-8">
        <div class="size-16 rounded-full bg-blue-50 text-primary flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-3xl">{{ $category->icon }}</span>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">{{ $category->name }}</h1>
            <p class="text-slate-500">{{ $ads->total() }} ads found in this category</p>
        </div>
    </div>

    {{-- Ads Grid --}}
    @if($ads->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($ads as $item)
                @include('partials.ad-card', ['item' => $item, 'savedAdIds' => []])
            @endforeach
        </div>
        <div class="mt-8">{{ $ads->links() }}</div>
    @else
        <div class="text-center py-20 bg-white rounded-2xl border border-slate-200">
            <span class="material-symbols-outlined text-6xl text-slate-300 mb-4 block">inventory_2</span>
            <h2 class="text-xl font-bold text-slate-600 mb-2">No ads in {{ $category->name }}</h2>
            <p class="text-slate-400 mb-6">Be the first to post an ad in this category!</p>
            <a href="{{ route('ads.create', ['category' => $category->id]) }}" class="bg-primary text-white px-6 py-3 rounded-xl font-semibold">Post Ad in {{ $category->name }}</a>
        </div>
    @endif
</div>
@endsection
