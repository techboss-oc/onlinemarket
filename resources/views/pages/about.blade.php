@extends('layouts.app')
@section('title', 'About Us')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 w-full">
    <div class="text-center mb-16">
        <div class="inline-flex items-center justify-center size-20 rounded-full bg-primary/10 text-primary mb-6">
            <span class="material-symbols-outlined text-4xl">storefront</span>
        </div>
        <h1 class="text-4xl font-black text-slate-900 mb-4 tracking-tight">About Onlinemarket.ng</h1>
        <p class="text-xl text-slate-500 max-w-2xl mx-auto">Nigeria's most trusted online marketplace for buying and selling goods securely and conveniently.</p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-soft mb-12">
        <div class="aspect-video bg-slate-100 flex items-center justify-center relative">
            <div class="absolute inset-0 bg-primary/5"></div>
            <span class="material-symbols-outlined text-6xl text-primary/40 relative z-10">groups</span>
        </div>
        <div class="p-8 md:p-12">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">Our Mission</h2>
            <p class="text-slate-600 leading-relaxed mb-8">
                At Onlinemarket.ng, we believe that buying and selling in Nigeria should be an effortless, secure, and rewarding experience for everyone. Our mission is to connect millions of buyers and sellers across the country, providing a seamless platform to trade anything from cars and real estate to electronics, fashion, and services.
            </p>
            
            <h2 class="text-2xl font-bold text-slate-900 mb-4">Why Choose Us?</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-start gap-4">
                    <div class="size-10 rounded-full bg-blue-50 text-primary flex items-center justify-center flex-shrink-0"><span class="material-symbols-outlined text-[20px]">verified_user</span></div>
                    <div>
                        <h4 class="font-bold text-slate-900">Safety First</h4>
                        <p class="text-sm text-slate-500 mt-1">We implement strict moderation and review processes to keep scammers off our platform.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="size-10 rounded-full bg-blue-50 text-primary flex items-center justify-center flex-shrink-0"><span class="material-symbols-outlined text-[20px]">speed</span></div>
                    <div>
                        <h4 class="font-bold text-slate-900">Fast & Easy</h4>
                        <p class="text-sm text-slate-500 mt-1">Post an ad in under 2 minutes. Our streamlined process makes selling a breeze.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="size-10 rounded-full bg-blue-50 text-primary flex items-center justify-center flex-shrink-0"><span class="material-symbols-outlined text-[20px]">location_on</span></div>
                    <div>
                        <h4 class="font-bold text-slate-900">Local Focus</h4>
                        <p class="text-sm text-slate-500 mt-1">Connect with buyers and sellers in your local community, making transactions convenient.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="size-10 rounded-full bg-blue-50 text-primary flex items-center justify-center flex-shrink-0"><span class="material-symbols-outlined text-[20px]">support_agent</span></div>
                    <div>
                        <h4 class="font-bold text-slate-900">24/7 Support</h4>
                        <p class="text-sm text-slate-500 mt-1">Our dedicated support team is always ready to assist you with any issues or queries.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
