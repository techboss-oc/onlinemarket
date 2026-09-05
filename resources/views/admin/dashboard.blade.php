@extends('layouts.dashboard')
@section('title', 'Admin Dashboard')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm {{ request()->routeIs('admin.dashboard') ? 'bg-primary/10 text-primary' : 'text-slate-600 hover:bg-slate-50 hover:text-primary' }} transition-colors"><span class="material-symbols-outlined text-[20px]">space_dashboard</span>Overview</a>
    <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm {{ request()->routeIs('admin.users') ? 'bg-primary/10 text-primary' : 'text-slate-600 hover:bg-slate-50 hover:text-primary' }} transition-colors"><span class="material-symbols-outlined text-[20px]">group</span>Users</a>
    <a href="{{ route('admin.ads') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm {{ request()->routeIs('admin.ads') ? 'bg-primary/10 text-primary' : 'text-slate-600 hover:bg-slate-50 hover:text-primary' }} transition-colors"><span class="material-symbols-outlined text-[20px]">list_alt</span>Ads</a>
    <a href="{{ route('admin.categories') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">category</span>Categories</a>
    <a href="{{ route('admin.monetization.packages') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">sell</span>Packages</a>
    <a href="{{ route('admin.settings.payments') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">payments</span>Payment Settings</a>
    <div class="border-t border-slate-100 my-2"></div>
    <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">storefront</span>View Site</a>
@endsection

@section('mobile-nav')
    <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center gap-0.5 text-primary text-xs"><span class="material-symbols-outlined text-[22px]">dashboard</span>Admin</a>
    <a href="{{ route('admin.users') }}" class="flex flex-col items-center gap-0.5 text-slate-500 text-xs"><span class="material-symbols-outlined text-[22px]">group</span>Users</a>
    <a href="{{ route('admin.ads') }}" class="flex flex-col items-center gap-0.5 text-slate-500 text-xs"><span class="material-symbols-outlined text-[22px]">list_alt</span>Ads</a>
    <a href="{{ route('home') }}" class="flex flex-col items-center gap-0.5 text-slate-500 text-xs"><span class="material-symbols-outlined text-[22px]">home</span>Site</a>
@endsection

@section('content')
<div>
    <h2 class="text-2xl font-black text-slate-900 mb-2">Admin Overview</h2>
    <p class="text-slate-500 mb-8">Platform-wide statistics and activity.</p>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
        @foreach([
            ['label' => 'Total Users',   'value' => $stats['total_users'],   'icon' => 'group',        'color' => 'blue'],
            ['label' => 'Total Ads',     'value' => $stats['total_ads'],     'icon' => 'article',      'color' => 'purple'],
            ['label' => 'Active Ads',    'value' => $stats['active_ads'],    'icon' => 'check_circle', 'color' => 'green'],
            ['label' => 'Pending Ads',   'value' => $stats['pending_ads'],   'icon' => 'pending',      'color' => 'orange'],
            ['label' => 'Sellers',       'value' => $stats['total_sellers'], 'icon' => 'storefront',   'color' => 'indigo'],
            ['label' => 'Buyers',        'value' => $stats['total_buyers'],  'icon' => 'shopping_bag', 'color' => 'pink'],
        ] as $stat)
            <div class="bg-white rounded-2xl p-5 border border-slate-200 flex flex-col gap-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-widest text-slate-400">{{ $stat['label'] }}</span>
                    <div class="p-2 rounded-xl bg-{{ $stat['color'] }}-50 text-{{ $stat['color'] }}-500"><span class="material-symbols-outlined text-[18px]">{{ $stat['icon'] }}</span></div>
                </div>
                <div class="text-3xl font-black text-slate-900">{{ number_format($stat['value']) }}</div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent Users --}}
        <div class="bg-white rounded-2xl border border-slate-200">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-900">Recent Users</h3>
                <a href="{{ route('admin.users') }}" class="text-sm text-primary font-medium hover:underline">View All</a>
            </div>
            <div class="divide-y divide-slate-100">
                @foreach($recentUsers as $u)
                    <div class="flex items-center gap-3 px-6 py-3">
                        <div class="size-8 rounded-full bg-primary/10 text-primary text-sm font-bold flex items-center justify-center">{{ strtoupper(substr($u->username, 0, 1)) }}</div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-sm text-slate-900 truncate">{{ $u->username }}</p>
                            <p class="text-xs text-slate-500 truncate">{{ $u->email }}</p>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $u->role === 'admin' ? 'bg-red-100 text-red-700' : ($u->role === 'seller' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-600') }}">{{ ucfirst($u->role) }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Recent Ads --}}
        <div class="bg-white rounded-2xl border border-slate-200">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-900">Recent Ads</h3>
                <a href="{{ route('admin.ads') }}" class="text-sm text-primary font-medium hover:underline">View All</a>
            </div>
            <div class="divide-y divide-slate-100">
                @foreach($recentAds as $ad)
                    <div class="flex items-center gap-3 px-6 py-3">
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-sm text-slate-900 truncate">{{ $ad->title }}</p>
                            <p class="text-xs text-slate-500">by {{ $ad->user->username }} • {{ $ad->category->name }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-primary">{{ $ad->formatted_price }}</p>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $ad->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600' }}">{{ ucfirst($ad->status) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
