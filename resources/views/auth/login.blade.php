@extends('layouts.app')
@section('title', 'Login')
@section('content')
<main class="flex-grow flex items-center justify-center p-4 sm:p-8 relative overflow-hidden">
    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-primary/5 blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-purple-500/5 blur-[120px] pointer-events-none"></div>
    <div class="w-full max-w-5xl bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col lg:flex-row border border-white/50 relative z-10 min-h-[600px]">
        <div class="w-full lg:w-1/2 p-8 sm:p-12 lg:p-16 flex flex-col justify-center">
            <div class="mb-10">
                <h1 class="text-3xl sm:text-4xl font-black text-[#0e121b] tracking-tight mb-3">Welcome Back</h1>
                <p class="text-gray-500 text-base leading-relaxed">Log in to access your marketplace dashboard.</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ $errors->first() }}
                </div>
            @endif

            <form class="space-y-6" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-[#0e121b]" for="email">Email</label>
                    <div class="relative group">
                        <input name="email" class="w-full h-12 px-4 pl-11 rounded-xl bg-gray-50 border border-gray-200 text-[#0e121b] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" id="email" placeholder="Enter your email" type="email" required value="{{ old('email') }}" />
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-primary transition-colors text-[20px]">mail</span>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="block text-sm font-semibold text-[#0e121b]" for="password">Password</label>
                    </div>
                    <div class="relative group">
                        <input name="password" class="w-full h-12 px-4 pl-11 pr-11 rounded-xl bg-gray-50 border border-gray-200 text-[#0e121b] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" id="password" placeholder="Enter your password" type="password" required />
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-[20px]">lock</span>
                    </div>
                </div>
                <button type="submit" class="w-full h-12 bg-primary hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-primary/30 transition-all flex items-center justify-center gap-2">
                    <span>Log In</span>
                    <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                </button>
            </form>
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-500">Don't have an account? <a class="font-bold text-primary" href="{{ route('register') }}">Register</a></p>
            </div>
        </div>
        <div class="hidden lg:block w-1/2 relative bg-primary">
            <div class="absolute inset-0 bg-gradient-to-br from-primary/90 to-blue-900"></div>
            <div class="absolute bottom-0 left-0 w-full p-12 text-white">
                <div class="inline-flex items-center justify-center p-3 bg-white/20 backdrop-blur-md rounded-xl mb-6">
                    <span class="material-symbols-outlined text-3xl">storefront</span>
                </div>
                <h2 class="text-4xl font-bold mb-4 leading-tight">Start Buying & Selling<br/>With Confidence</h2>
                <p class="text-white/90 text-lg leading-relaxed max-w-md">Join over 2 million users on Nigeria's fastest growing online marketplace.</p>
                <div class="flex flex-wrap gap-3 mt-6">
                    <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium border border-white/10">Verified Sellers</span>
                    <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium border border-white/10">Instant Chat</span>
                    <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium border border-white/10">Secure</span>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
