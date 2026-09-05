@extends('layouts.app')
@section('title', 'Billing Policy')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-12 w-full">
    <div class="bg-white rounded-3xl border border-slate-200 p-8 md:p-12 shadow-soft text-center">
        <span class="material-symbols-outlined text-6xl text-slate-200 mb-6 block">payments</span>
        <h1 class="text-4xl font-black text-slate-900 mb-4 tracking-tight border-b border-slate-100 pb-6 inline-block">Billing Policy</h1>
        
        <div class="prose prose-slate max-w-none text-slate-600 text-left mt-8">
            <p class="mb-4">At Onlinemarket.ng, our core platform for posting and browsing ads is entirely free to use.</p>
            
            <h3 class="text-xl font-bold text-slate-900 mt-8 mb-4">Premium Promotions</h3>
            <p class="mb-4">We offer optional premium promotional packages (e.g., placing ads at the top of search results or marking them as "Urgent") for a fee. All payments for these services are final and non-refundable unless the ad is removed due to a technical error on our part.</p>
            
            <h3 class="text-xl font-bold text-slate-900 mt-8 mb-4">Payment Methods</h3>
            <p class="mb-4">We accept major credit/debit cards, bank transfers, and local payment gateways processed securely through our partners. We do not store your full card details on our servers.</p>
            
            <h3 class="text-xl font-bold text-slate-900 mt-8 mb-4">Subscription Services</h3>
            <p class="mb-4">Any recurring subscription services for professional sellers will be billed automatically. You may cancel your subscription at any time from your dashboard, which will halt future billing but will not refund past charges.</p>
        </div>
    </div>
</div>
@endsection
