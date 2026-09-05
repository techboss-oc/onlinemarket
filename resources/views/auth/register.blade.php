@extends('layouts.app')
@section('title', 'Register')
@section('content')
<main class="flex-grow flex items-center justify-center p-4 sm:p-8">
    <div class="w-full max-w-lg bg-white rounded-2xl shadow-xl p-8 sm:p-12 border border-slate-100">
        <div class="mb-8">
            <h1 class="text-3xl font-black text-[#0e121b] tracking-tight mb-2">Create Account</h1>
            <p class="text-gray-500">Join Nigeria's fastest growing marketplace.</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form class="space-y-5" method="POST" action="{{ route('register') }}">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-800 mb-1.5">Username</label>
                <input name="username" class="w-full h-12 px-4 rounded-xl bg-gray-50 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" type="text" placeholder="Choose a username" required value="{{ old('username') }}" />
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-800 mb-1.5">Email</label>
                <input name="email" class="w-full h-12 px-4 rounded-xl bg-gray-50 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" type="email" placeholder="Enter your email" required value="{{ old('email') }}" />
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-800 mb-1.5">Password</label>
                <input name="password" class="w-full h-12 px-4 rounded-xl bg-gray-50 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" type="password" placeholder="Minimum 6 characters" required />
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-800 mb-1.5">Confirm Password</label>
                <input name="password_confirmation" class="w-full h-12 px-4 rounded-xl bg-gray-50 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" type="password" placeholder="Repeat your password" required />
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-800 mb-1.5">I want to</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-3 p-4 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-primary transition-colors has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                        <input type="radio" name="role" value="buyer" class="accent-primary" {{ old('role', 'buyer') === 'buyer' ? 'checked' : '' }} />
                        <div>
                            <span class="font-semibold text-slate-800 text-sm">Buy</span>
                            <p class="text-xs text-slate-500">Browse & buy ads</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 p-4 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-primary transition-colors has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                        <input type="radio" name="role" value="seller" class="accent-primary" {{ old('role') === 'seller' ? 'checked' : '' }} />
                        <div>
                            <span class="font-semibold text-slate-800 text-sm">Sell</span>
                            <p class="text-xs text-slate-500">Post & manage ads</p>
                        </div>
                    </label>
                </div>
            </div>
            <button type="submit" class="w-full h-12 bg-primary hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg transition-all">Create Account</button>
        </form>
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-500">Already have an account? <a class="font-bold text-primary" href="{{ route('login') }}">Login</a></p>
        </div>
    </div>
</main>
@endsection
