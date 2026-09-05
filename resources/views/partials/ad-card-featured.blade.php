<div class="group bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-hover transition-all duration-300 flex flex-col h-full relative">
    @if($item->is_featured)
        <div class="absolute top-2 left-2 z-10 bg-yellow-400 text-yellow-900 text-[10px] font-bold px-1.5 py-0.5 rounded uppercase tracking-wide">Promoted</div>
    @endif
    <button onclick="toggleFavorite(this, {{ $item->id }})"
            class="absolute top-2 right-2 z-10 w-7 h-7 rounded-full bg-white/80 backdrop-blur-sm flex items-center justify-center hover:text-red-500 transition-colors {{ in_array($item->id, $savedAdIds ?? []) ? 'text-red-500' : 'text-slate-400' }}">
        <span class="material-symbols-outlined text-[18px]">favorite</span>
    </button>
    <a href="{{ route('ads.show', $item->id) }}" class="relative aspect-[4/3] overflow-hidden bg-slate-100 block">
        @if($item->primaryImage)
            <img alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" src="{{ $item->primaryImage->image_url }}" />
        @else
            <div class="w-full h-full flex items-center justify-center text-slate-300">
                <span class="material-symbols-outlined text-6xl">image_not_supported</span>
            </div>
        @endif
    </a>
    <div class="p-3 flex flex-col flex-1">
        <a href="{{ route('ads.show', $item->id) }}">
            <h4 class="font-semibold text-sm md:text-base text-slate-800 line-clamp-2 group-hover:text-primary transition-colors leading-snug mb-1">{{ $item->title }}</h4>
        </a>
        <div class="text-primary font-bold text-base md:text-lg mb-2">{{ $item->formatted_price }}</div>
        <div class="mt-auto flex items-center justify-between text-[10px] md:text-xs text-slate-500 pt-2 border-t border-slate-100">
            <div class="flex items-center gap-1 truncate max-w-[60%]">
                <span class="material-symbols-outlined text-[12px] md:text-[14px]">location_on</span>
                <span class="truncate">{{ ucfirst($item->location->name ?? '') }}</span>
            </div>
            <span class="whitespace-nowrap">{{ $item->created_at->diffForHumans() }}</span>
        </div>
    </div>
</div>
