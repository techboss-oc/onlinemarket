@extends('layouts.app')
@section('title', 'Safety Tips')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-12 w-full">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-black text-slate-900 mb-4 tracking-tight">Stay Safe on Onlinemarket.ng</h1>
        <p class="text-xl text-slate-500 max-w-2xl mx-auto">Your safety is our priority. Follow these simple rules to ensure a secure trading experience.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="bg-red-50 rounded-2xl p-6 border border-red-100 text-center">
            <div class="size-16 rounded-full bg-red-100 text-red-500 flex items-center justify-center mx-auto mb-4"><span class="material-symbols-outlined text-3xl">money_off</span></div>
            <h3 class="font-bold text-slate-900 mb-2">Never pay in advance</h3>
            <p class="text-sm text-slate-600">Do not send money before receiving the item. Always pay upon delivery and inspection.</p>
        </div>
        <div class="bg-blue-50 rounded-2xl p-6 border border-blue-100 text-center">
            <div class="size-16 rounded-full bg-blue-100 text-blue-500 flex items-center justify-center mx-auto mb-4"><span class="material-symbols-outlined text-3xl">location_city</span></div>
            <h3 class="font-bold text-slate-900 mb-2">Meet in public</h3>
            <p class="text-sm text-slate-600">Always arrange to meet the buyer or seller in a safe, well-lit, public place like a mall or cafe.</p>
        </div>
        <div class="bg-green-50 rounded-2xl p-6 border border-green-100 text-center">
            <div class="size-16 rounded-full bg-green-100 text-green-500 flex items-center justify-center mx-auto mb-4"><span class="material-symbols-outlined text-3xl">search_check</span></div>
            <h3 class="font-bold text-slate-900 mb-2">Inspect thoroughly</h3>
            <p class="text-sm text-slate-600">Check the item completely to ensure it matches the description before handing over any money.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 p-8 md:p-10 shadow-soft">
        <h2 class="text-2xl font-bold text-slate-900 mb-6">Red Flags to Watch Out For</h2>
        <ul class="space-y-4">
            <li class="flex items-start gap-3">
                <span class="material-symbols-outlined text-red-500 mt-1">flag</span>
                <div>
                    <strong class="text-slate-800">The price is too good to be true.</strong>
                    <p class="text-slate-600 text-sm">Scammers often use incredibly low prices to lure victims.</p>
                </div>
            </li>
            <li class="flex items-start gap-3">
                <span class="material-symbols-outlined text-red-500 mt-1">flag</span>
                <div>
                    <strong class="text-slate-800">Requests for bank transfers or crypto.</strong>
                    <p class="text-slate-600 text-sm">Avoid irreversible payment methods before seeing the item.</p>
                </div>
            </li>
            <li class="flex items-start gap-3">
                <span class="material-symbols-outlined text-red-500 mt-1">flag</span>
                <div>
                    <strong class="text-slate-800">Seller refuses to meet in person.</strong>
                    <p class="text-slate-600 text-sm">If a seller creates excuses for why they cannot meet you, walk away.</p>
                </div>
            </li>
            <li class="flex items-start gap-3">
                <span class="material-symbols-outlined text-red-500 mt-1">flag</span>
                <div>
                    <strong class="text-slate-800">Communication outside the platform.</strong>
                    <p class="text-slate-600 text-sm">Always use our built-in chat system for your protection.</p>
                </div>
            </li>
        </ul>
        
        <div class="mt-10 p-5 bg-slate-50 rounded-xl border border-slate-100">
            <h3 class="font-bold text-slate-900 mb-2">Spotted a scammer?</h3>
            <p class="text-slate-600 text-sm mb-4">Help us keep the community safe by reporting suspicious activity immediately.</p>
            <a href="{{ route('contact') }}" class="text-primary font-semibold hover:underline">Report suspicious ad</a>
        </div>
    </div>
</div>
@endsection
