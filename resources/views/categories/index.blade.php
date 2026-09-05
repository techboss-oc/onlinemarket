@extends('layouts.app')
@section('title', 'All Categories')

@section('content')
<div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-2">Browse All Categories</h1>
        <p class="text-slate-500">Find exactly what you're looking for across all our marketplace sectors.</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @foreach($categories as $cat)
            <a href="{{ route('categories.show', $cat->slug) }}" class="group bg-white rounded-2xl border border-slate-200 p-6 flex flex-col items-center justify-center gap-4 hover:shadow-hover hover:border-primary/30 transition-all">
                <div class="size-16 rounded-full bg-blue-50 text-primary flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-colors">
                    <span class="material-symbols-outlined text-3xl">{{ $cat->icon }}</span>
                </div>
                <div class="text-center">
                    <h3 class="font-bold text-slate-900 group-hover:text-primary transition-colors text-lg">{{ $cat->name }}</h3>
                    <p class="text-sm font-medium text-slate-500 mt-1">{{ number_format($cat->ads_count ?? 0) }} ads</p>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection
