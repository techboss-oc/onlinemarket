<div class="bg-white rounded-lg border border-slate-200 hover:shadow-lg transition-shadow duration-200 flex flex-col overflow-hidden">
    <a href="{{ route('ads.show', $item->id) }}" class="relative h-32 md:h-40 bg-slate-100 block">
        @if($item->primaryImage)
            <img alt="{{ $item->title }}" class="w-full h-full object-cover" src="{{ $item->primaryImage->image_url }}" />
        @else
            <div class="w-full h-full flex items-center justify-center text-slate-300">
                <span class="material-symbols-outlined text-5xl">image_not_supported</span>
            </div>
        @endif
        <div class="absolute bottom-2 left-2 bg-black/60 text-white text-[10px] px-1.5 py-0.5 rounded">{{ ucfirst($item->condition_state ?? 'Used') }}</div>
        <button onclick="toggleFavorite(this, {{ $item->id }})"
                class="absolute top-2 right-2 z-10 w-7 h-7 rounded-full bg-white/80 backdrop-blur-sm flex items-center justify-center hover:text-red-500 transition-colors {{ in_array($item->id, $savedAdIds ?? []) ? 'text-red-500' : 'text-slate-400' }}">
            <span class="material-symbols-outlined text-[18px]">favorite</span>
        </button>
    </a>
    <div class="p-2 md:p-3 flex flex-col flex-1">
        <a href="{{ route('ads.show', $item->id) }}">
            <h5 class="text-xs md:text-sm font-medium text-slate-900 line-clamp-2 mb-1 leading-snug">{{ $item->title }}</h5>
        </a>
        <div class="text-primary font-bold text-sm md:text-base mb-1 md:mb-2">{{ $item->formatted_price }}</div>
        <div class="mt-auto flex items-center text-[10px] md:text-[11px] text-slate-400 gap-1 truncate">
            <span class="material-symbols-outlined text-[12px]">location_on</span>
            <span class="truncate">{{ ucfirst($item->location->name ?? '') }}</span>
        </div>
    </div>
</div>
