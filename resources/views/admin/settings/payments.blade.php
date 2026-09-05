@extends('layouts.dashboard')
@section('title', 'Payment Settings')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">space_dashboard</span>Overview</a>
    <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">group</span>Users</a>
    <a href="{{ route('admin.ads') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">list_alt</span>Ads</a>
    <a href="{{ route('admin.categories') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">category</span>Categories</a>
    <a href="{{ route('admin.monetization.packages') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">sell</span>Packages</a>
    <a href="{{ route('admin.settings.payments') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm bg-primary/10 text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">payments</span>Payment Settings</a>
    <div class="border-t border-slate-100 my-2"></div>
    <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">storefront</span>View Site</a>
@endsection

@section('mobile-nav')
    <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center gap-0.5 text-slate-500 text-xs"><span class="material-symbols-outlined text-[22px]">dashboard</span>Admin</a>
    <a href="{{ route('admin.monetization.packages') }}" class="flex flex-col items-center gap-0.5 text-slate-500 text-xs"><span class="material-symbols-outlined text-[22px]">sell</span>Packages</a>
    <a href="{{ route('admin.settings.payments') }}" class="flex flex-col items-center gap-0.5 text-primary text-xs"><span class="material-symbols-outlined text-[22px]">payments</span>Settings</a>
@endsection

@section('content')
<div>
    <h2 class="text-2xl font-black text-slate-900 mb-2">Payment Settings</h2>
    <p class="text-slate-500 mb-8">Configure your payment gateway credentials and environments securely.</p>

    @if(session('success'))
        <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 border border-green-200 flex items-center justify-between">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        {{-- Paystack Settings --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 flex flex-col gap-6">
            <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                <div class="size-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined">account_balance_wallet</span>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900">Paystack Settings</h3>
                    <p class="text-xs text-slate-500">Enable Paystack for Nigerian users.</p>
                </div>
            </div>

            <form action="{{ route('admin.settings.payments.update') }}" method="POST" class="flex flex-col gap-4">
                @csrf
                <input type="hidden" name="provider" value="paystack">
                
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" id="paystack_active" value="1" {{ $paystack->is_active ? 'checked' : '' }} class="rounded border-slate-300 text-primary focus:ring-primary h-4 w-4">
                    <label for="paystack_active" class="text-sm font-medium text-slate-900">Enable Paystack Gateway</label>
                </div>

                <div class="flex items-center gap-3 mb-2">
                    <input type="checkbox" name="is_test_mode" id="paystack_test" value="1" {{ $paystack->is_test_mode ? 'checked' : '' }} class="rounded border-slate-300 text-primary focus:ring-primary h-4 w-4">
                    <label for="paystack_test" class="text-sm font-medium text-slate-900">Enable Test Mode</label>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Public Key</label>
                    <input type="text" name="public_key" value="{{ old('public_key', $paystack->public_key) }}" class="w-full text-sm rounded-xl border-slate-200 focus:border-primary focus:ring-primary" placeholder="pk_test_...">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Secret Key (Hidden)</label>
                    <input type="password" name="secret_key" placeholder="{{ $paystack->secret_key ? '••••••••••••••••••••••••' : 'sk_test_...' }}" class="w-full text-sm rounded-xl border-slate-200 focus:border-primary focus:ring-primary">
                    <p class="text-xs text-slate-400 mt-1">Leave empty to keep existing key.</p>
                </div>

                <button type="submit" class="mt-2 bg-slate-900 text-white font-medium text-sm rounded-xl px-5 py-2.5 text-center hover:bg-slate-800 transition-colors w-full">Save Paystack Settings</button>
            </form>
        </div>

        {{-- Flutterwave Settings --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 flex flex-col gap-6">
            <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                <div class="size-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined">payments</span>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900">Flutterwave Settings</h3>
                    <p class="text-xs text-slate-500">Enable Flutterwave for cross-border limits.</p>
                </div>
            </div>

            <form action="{{ route('admin.settings.payments.update') }}" method="POST" class="flex flex-col gap-4">
                @csrf
                <input type="hidden" name="provider" value="flutterwave">
                
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" id="flw_active" value="1" {{ $flutterwave->is_active ? 'checked' : '' }} class="rounded border-slate-300 text-primary focus:ring-primary h-4 w-4">
                    <label for="flw_active" class="text-sm font-medium text-slate-900">Enable Flutterwave Gateway</label>
                </div>

                <div class="flex items-center gap-3 mb-2">
                    <input type="checkbox" name="is_test_mode" id="flw_test" value="1" {{ $flutterwave->is_test_mode ? 'checked' : '' }} class="rounded border-slate-300 text-primary focus:ring-primary h-4 w-4">
                    <label for="flw_test" class="text-sm font-medium text-slate-900">Enable Test Mode</label>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Public Key</label>
                    <input type="text" name="public_key" value="{{ old('public_key', $flutterwave->public_key) }}" class="w-full text-sm rounded-xl border-slate-200 focus:border-primary focus:ring-primary" placeholder="FLWPUBK_TEST-...">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Secret Key (Hidden)</label>
                    <input type="password" name="secret_key" placeholder="{{ $flutterwave->secret_key ? '••••••••••••••••••••••••' : 'FLWSECK_TEST-...' }}" class="w-full text-sm rounded-xl border-slate-200 focus:border-primary focus:ring-primary">
                    <p class="text-xs text-slate-400 mt-1">Leave empty to keep existing key.</p>
                </div>

                <button type="submit" class="mt-2 bg-slate-900 text-white font-medium text-sm rounded-xl px-5 py-2.5 text-center hover:bg-slate-800 transition-colors w-full">Save Flutterwave Settings</button>
            </form>
        </div>

    </div>
</div>
@endsection
