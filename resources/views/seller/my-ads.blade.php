@extends('layouts.dashboard')
@section('title', 'My Ads')

@include('seller.partials.nav')

@section('content')
<div>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-black text-slate-900">My Ads</h2>
        <a href="{{ route('ads.create') }}" class="flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl font-semibold text-sm shadow-lg shadow-primary/20">
            <span class="material-symbols-outlined text-[18px]">add</span> Post New Ad
        </a>
    </div>

    @if($ads->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($ads as $ad)
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg transition-shadow">
                <div class="h-36 bg-slate-100 relative">
                    @if($ad->primaryImage)
                        <img src="{{ $ad->primaryImage->image_url }}" class="w-full h-full object-cover" />
                    @else
                        <div class="w-full h-full flex items-center justify-center"><span class="material-symbols-outlined text-slate-300 text-4xl">image</span></div>
                    @endif
                    <span class="absolute top-2 left-2 px-2 py-1 rounded-full text-[10px] font-bold {{ $ad->status === 'active' ? 'bg-green-500 text-white' : 'bg-slate-500 text-white' }}">{{ ucfirst($ad->status) }}</span>
                </div>
                <div class="p-4">
                    <h4 class="font-bold text-slate-900 text-sm line-clamp-2 mb-1">{{ $ad->title }}</h4>
                    <p class="text-primary font-black text-base mb-2">{{ $ad->formatted_price }}</p>
                    <div class="flex items-center justify-between text-xs text-slate-500">
                        <span>{{ $ad->views_count }} views</span>
                        <span>{{ $ad->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex gap-2 mt-3">
                        <a href="{{ route('ads.show', $ad->id) }}" class="flex-1 text-center border border-slate-200 py-2 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50">View</a>
                        <a href="{{ route('seller.promote', $ad->id) }}" class="flex-1 text-center bg-orange-100 border border-orange-200 text-orange-600 py-2 rounded-lg text-xs font-bold hover:bg-orange-200 transition-colors">🚀 Promote</a>
                        <form action="{{ route('ads.destroy', $ad->id) }}" method="POST" onsubmit="return confirm('Delete this ad?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-3 py-2 rounded-lg border border-red-200 text-red-500 text-xs font-semibold hover:bg-red-50">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $ads->links() }}</div>
    @else
        <div class="text-center py-24">
            <span class="material-symbols-outlined text-8xl text-slate-200 block mb-4">article</span>
            <h3 class="text-xl font-bold text-slate-600 mb-2">No ads posted yet</h3>
            <a href="{{ route('ads.create') }}" class="inline-block bg-primary text-white px-6 py-3 rounded-xl font-semibold mt-2">Post Your First Ad</a>
        </div>
    @endif
</div>
@endsection
