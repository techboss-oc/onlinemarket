@extends('layouts.app')
@section('title', 'Frequently Asked Questions')

@section('content')
<div class="max-w-[800px] mx-auto px-4 sm:px-6 lg:px-8 py-12 w-full">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-black text-slate-900 mb-4 tracking-tight">Frequently Asked Questions</h1>
        <p class="text-xl text-slate-500">Everything you need to know about using Onlinemarket.ng.</p>
    </div>

    <div class="space-y-4">
        @php
            $faqs = [
                ['q' => 'How do I post an ad?', 'a' => 'Click on the "Post Ad" button on the top right corner. You will need to log in or register if you haven\'t already. Fill in the details about your item, add clear photos, set a price, and hit publish!'],
                ['q' => 'Is it free to post ads?', 'a' => 'Yes, posting basic ads on Onlinemarket.ng is completely free. We also offer premium promotional packages to give your ads more visibility if you want to sell faster.'],
                ['q' => 'How can I edit or delete my ad?', 'a' => 'Go to your Dashboard, navigate to "My Ads", and you will see buttons to Edit or Delete your active listings.'],
                ['q' => 'How do I contact a seller?', 'a' => 'When viewing an ad, click the "Chat with Seller" button on the right side. This will open a direct, secure messaging channel with the seller right inside the platform.'],
                ['q' => 'How do I stay safe from scams?', 'a' => 'Never pay for an item before seeing it and inspecting it in person. Always meet sellers in safe, public places. Read our comprehensive Safety Tips guide for more information.'],
            ];
        @endphp

        @foreach($faqs as $index => $faq)
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm" x-data="{ open: {{ $index === 0 ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full px-6 py-4 flex items-center justify-between font-bold text-slate-900 bg-slate-50/50 hover:bg-slate-50 transition-colors text-left focus:outline-none">
                    <span class="text-lg">{{ $faq['q'] }}</span>
                    <span class="material-symbols-outlined text-slate-400 transition-transform duration-300" :class="{'rotate-180': open}">expand_more</span>
                </button>
                <div x-show="open" x-collapse id="faq-{{$index}}" style="display: {{ $index === 0 ? 'block' : 'none' }}">
                    <div class="px-6 py-4 text-slate-600 leading-relaxed border-t border-slate-100 bg-white">
                        {{ $faq['a'] }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-12 bg-blue-50 rounded-2xl p-8 text-center border border-blue-100">
        <h3 class="text-xl font-bold text-slate-900 mb-2">Still have questions?</h3>
        <p class="text-slate-600 mb-6">Can't find the answer you're looking for? Please chat to our friendly team.</p>
        <a href="{{ route('contact') }}" class="inline-block bg-primary text-white font-bold px-6 py-3 rounded-xl shadow-lg shadow-primary/20 hover:bg-blue-700 transition">Get in touch</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection
