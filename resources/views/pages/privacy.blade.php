@extends('layouts.app')
@section('title', 'Privacy Policy')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-12 w-full">
    <div class="bg-white rounded-3xl border border-slate-200 p-8 md:p-12 shadow-soft">
        <h1 class="text-4xl font-black text-slate-900 mb-8 tracking-tight border-b border-slate-100 pb-6">Privacy Policy</h1>
        
        <div class="prose prose-slate max-w-none text-slate-600">
            <p class="lead text-lg mb-6 text-slate-800">Last updated: {{ date('F d, Y') }}</p>
            
            <h3 class="text-xl font-bold text-slate-900 mt-8 mb-4">1. Collection of Information</h3>
            <p class="mb-4">When you use Onlinemarket.ng, we collect information you provide directly to us, such as when you create or modify your account, post an ad, contact customer support, or otherwise communicate with us. This information may include your name, email address, phone number, profile picture, location, and any other information you choose to provide.</p>
            
            <h3 class="text-xl font-bold text-slate-900 mt-8 mb-4">2. Use of Information</h3>
            <p class="mb-4">We use the information we collect to provide, maintain, and improve our services, such as to facilitate transactions, customize your experience, send you technical notices, updates, security alerts, and support and administrative messages, and to respond to your comments, questions, and requests.</p>
            
            <h3 class="text-xl font-bold text-slate-900 mt-8 mb-4">3. Sharing of Information</h3>
            <p class="mb-4">We may share information about you if your profile and your ads are visible to the public. We will not share personal information with third parties for their direct marketing purposes without your consent. We may share information when required by law or to protect the rights, property, and safety of Onlinemarket.ng, our users, and others.</p>

            <h3 class="text-xl font-bold text-slate-900 mt-8 mb-4">4. Security</h3>
            <p class="mb-4">We take reasonable measures to help protect information about you from loss, theft, misuse, unauthorized access, disclosure, alteration, and destruction. However, the internet is not 100% secure, and we cannot guarantee absolute security of your personal information.</p>
            
            <hr class="my-8 border-slate-100">
            <p class="text-sm">For any privacy-related questions, please <a href="{{ route('contact') }}" class="text-primary hover:underline">contact our support team</a>.</p>
        </div>
    </div>
</div>
@endsection
