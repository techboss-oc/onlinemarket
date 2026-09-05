@extends('layouts.dashboard')
@section('title', 'Manage Ads')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">space_dashboard</span>Overview</a>
    <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">group</span>Users</a>
    <a href="{{ route('admin.ads') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm bg-primary/10 text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">list_alt</span>Ads</a>
    <a href="{{ route('admin.categories') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">category</span>Categories</a>
    <a href="{{ route('admin.monetization.packages') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">sell</span>Packages</a>
    <a href="{{ route('admin.settings.payments') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">payments</span>Payment Settings</a>
    <div class="border-t border-slate-100 my-2"></div>
    <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">storefront</span>View Site</a>
@endsection

@section('mobile-nav')
    <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center gap-0.5 text-slate-500 text-xs"><span class="material-symbols-outlined text-[22px]">dashboard</span>Admin</a>
    <a href="{{ route('admin.users') }}" class="flex flex-col items-center gap-0.5 text-slate-500 text-xs"><span class="material-symbols-outlined text-[22px]">group</span>Users</a>
    <a href="{{ route('admin.ads') }}" class="flex flex-col items-center gap-0.5 text-primary text-xs font-medium"><span class="material-symbols-outlined text-[22px]">list_alt</span>Ads</a>
    <a href="{{ route('home') }}" class="flex flex-col items-center gap-0.5 text-slate-500 text-xs"><span class="material-symbols-outlined text-[22px]">home</span>Site</a>
@endsection

@section('content')
<div>
    <h2 class="text-2xl font-black text-slate-900 mb-6">Manage Ads</h2>
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="text-left px-5 py-3 text-slate-500 font-semibold text-xs uppercase tracking-wider">Ad</th>
                        <th class="text-left px-5 py-3 text-slate-500 font-semibold text-xs uppercase tracking-wider hidden md:table-cell">Seller</th>
                        <th class="text-right px-5 py-3 text-slate-500 font-semibold text-xs uppercase tracking-wider hidden md:table-cell">Price</th>
                        <th class="text-center px-5 py-3 text-slate-500 font-semibold text-xs uppercase tracking-wider">Status</th>
                        <th class="text-center px-5 py-3 text-slate-500 font-semibold text-xs uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($ads as $ad)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-slate-100 overflow-hidden flex-shrink-0">
                                        @if($ad->primaryImage)
                                            <img class="w-full h-full object-cover" src="{{ $ad->primaryImage->image_url }}" />
                                        @else
                                            <div class="w-full h-full flex items-center justify-center"><span class="material-symbols-outlined text-slate-300 text-base">image</span></div>
                                        @endif
                                    </div>
                                    <p class="font-semibold text-slate-900 text-xs line-clamp-2 max-w-[150px]">{{ $ad->title }}</p>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-slate-500 text-xs hidden md:table-cell">{{ $ad->user->username }}</td>
                            <td class="px-5 py-3 text-right font-bold text-primary text-xs hidden md:table-cell">{{ $ad->formatted_price }}</td>
                            <td class="px-5 py-3 text-center">
                                <form action="{{ route('admin.ads.status', $ad->id) }}" method="POST" class="inline">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()" class="text-[10px] px-2 py-0.5 rounded-full border border-slate-200 focus:ring-0 bg-white font-bold {{ $ad->status === 'active' ? 'text-green-700 border-green-200 bg-green-50' : 'text-slate-600' }}">
                                        @foreach(['pending','active','rejected','expired','sold'] as $s)
                                            <option value="{{ $s }}" {{ $ad->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <a href="{{ route('ads.show', $ad->id) }}" class="text-slate-400 hover:text-primary transition-colors" title="View">
                                    <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t border-slate-100">{{ $ads->links() }}</div>
    </div>
</div>
@endsection
