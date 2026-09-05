@extends('layouts.dashboard')
@section('title', 'Transactions & Revenue')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">space_dashboard</span>Overview</a>
    <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">group</span>Users</a>
    <a href="{{ route('admin.ads') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">list_alt</span>Ads</a>
    <a href="{{ route('admin.categories') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">category</span>Categories</a>
    <a href="{{ route('transactions') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm bg-primary/10 text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">receipt_long</span>Transactions</a>
    <a href="{{ route('admin.monetization.packages') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">sell</span>Packages</a>
    <a href="{{ route('admin.settings.payments') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">payments</span>Payment Settings</a>
    <div class="border-t border-slate-100 my-2"></div>
    <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">storefront</span>View Site</a>
@endsection

@section('content')
<div>
    <h2 class="text-2xl font-black text-slate-900 mb-2">Revenue & Transactions</h2>
    <p class="text-slate-500 mb-8">View all payments processed through the platform.</p>

    {{-- Stats View --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-5 border border-slate-200 flex flex-col gap-2 shadow-sm">
            <span class="text-xs font-bold uppercase tracking-widest text-green-500">Total Revenue (NGN)</span>
            <div class="text-3xl font-black text-slate-900">₦{{ number_format($totalRevenue) }}</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200 flex flex-col gap-2 shadow-sm">
            <span class="text-xs font-bold uppercase tracking-widest text-blue-500">Total Transactions</span>
            <div class="text-3xl font-black text-slate-900">{{ number_format($transactions->total()) }}</div>
        </div>
    </div>

    {{-- Transactions Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-900 font-bold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 whitespace-nowrap">ID / Ref</th>
                        <th class="px-6 py-4 whitespace-nowrap">Amount</th>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4 whitespace-nowrap">Type / Gateway</th>
                        <th class="px-6 py-4 whitespace-nowrap">Status</th>
                        <th class="px-6 py-4 whitespace-nowrap">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transactions as $txn)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-bold text-slate-900">#{{ $txn->id }}</span>
                                <div class="text-xs text-slate-400 mt-1 truncate max-w-[120px]">{{ $txn->reference }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-black text-slate-900">{{ $txn->currency ?? 'NGN' }} {{ number_format($txn->amount) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-slate-900">{{ optional($txn->user)->username ?? 'Guest' }}</span>
                                <div class="text-xs text-slate-500 mt-0.5">{{ optional($txn->user)->email ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-bold text-slate-700 capitalize">{{ str_replace('_', ' ', $txn->payment_type) ?: 'Wallet Topup' }}</span>
                                <div class="text-xs text-slate-400 mt-0.5 capitalize">{{ $txn->payment_provider ?? 'Unknown' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($txn->status === 'successful')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">Successful</span>
                                @elseif($txn->status === 'failed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700">Failed</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-orange-100 text-orange-700">Pending</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500">
                                {{ $txn->created_at ? $txn->created_at->format('M d, Y h:i A') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-500">No transactions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($transactions->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
