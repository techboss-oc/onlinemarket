@extends('layouts.app')
@section('title', 'Terms and Conditions')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-12 w-full">
    <div class="bg-white rounded-3xl border border-slate-200 p-8 md:p-12 shadow-soft">
        <h1 class="text-4xl font-black text-slate-900 mb-8 tracking-tight border-b border-slate-100 pb-6">Terms and Conditions</h1>
        
        <div class="prose prose-slate max-w-none text-slate-600">
            <p class="lead text-lg mb-6 text-slate-800">Last updated: {{ date('F d, Y') }}</p>
            
            <h3 class="text-xl font-bold text-slate-900 mt-8 mb-4">1. Acceptance of Terms</h3>
            <p class="mb-4">By accessing and using Onlinemarket.ng, you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by these terms, please do not use our service.</p>
            
            <h3 class="text-xl font-bold text-slate-900 mt-8 mb-4">2. User Account</h3>
            <p class="mb-4">To use certain features of the platform, you must register for an account. You are responsible for maintaining the confidentiality of your account information and password. You must notify us immediately of any unauthorized use of your account.</p>
            
            <h3 class="text-xl font-bold text-slate-900 mt-8 mb-4">3. Prohibited Content</h3>
            <p class="mb-4">You agree not to post any items or content that are illegal, offensive, infringe on intellectual property rights, promote violence, or are otherwise deemed unacceptable by our moderation team. We reserve the right to remove any ad or ban any account without prior notice.</p>

            <h3 class="text-xl font-bold text-slate-900 mt-8 mb-4">4. Liability</h3>
            <p class="mb-4">Onlinemarket.ng acts only as a platform connecting buyers and sellers. We do not own the items listed, nor do we guarantee the quality, safety, or legality of the items advertised. All transactions happen directly between users, and we are not liable for any damages or losses resulting from these interactions.</p>
            
            <hr class="my-8 border-slate-100">
            <p class="text-sm">For further clarification on our terms, please <a href="{{ route('contact') }}" class="text-primary hover:underline">contact us</a>.</p>
        </div>
    </div>
</div>
@endsection
