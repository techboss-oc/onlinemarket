@extends('layouts.dashboard')
@section('title', 'Manage Categories')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">space_dashboard</span>Overview</a>
    <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">group</span>Users</a>
    <a href="{{ route('admin.ads') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">list_alt</span>Ads</a>
    <a href="{{ route('admin.categories') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm bg-primary/10 text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">category</span>Categories</a>
    <a href="{{ route('admin.monetization.packages') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">sell</span>Packages</a>
    <a href="{{ route('admin.settings.payments') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">payments</span>Payment Settings</a>
    <div class="border-t border-slate-100 my-2"></div>
    <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">storefront</span>View Site</a>
@endsection

@section('mobile-nav')
    <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center gap-0.5 text-slate-500 text-xs"><span class="material-symbols-outlined text-[22px]">dashboard</span>Admin</a>
    <a href="{{ route('admin.users') }}" class="flex flex-col items-center gap-0.5 text-slate-500 text-xs"><span class="material-symbols-outlined text-[22px]">group</span>Users</a>
    <a href="{{ route('admin.ads') }}" class="flex flex-col items-center gap-0.5 text-slate-500 text-xs"><span class="material-symbols-outlined text-[22px]">list_alt</span>Ads</a>
    <a href="{{ route('home') }}" class="flex flex-col items-center gap-0.5 text-slate-500 text-xs"><span class="material-symbols-outlined text-[22px]">home</span>Site</a>
@endsection

@section('content')
<div>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-black text-slate-900">Manage Categories</h2>
        <button class="flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-xl font-semibold text-sm shadow-lg shadow-primary/20">
            <span class="material-symbols-outlined text-[18px]">add</span> Add Category
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="text-left px-5 py-3 text-slate-500 font-semibold text-xs uppercase tracking-wider w-16 text-center">Icon</th>
                    <th class="text-left px-5 py-3 text-slate-500 font-semibold text-xs uppercase tracking-wider">Name</th>
                    <th class="text-left px-5 py-3 text-slate-500 font-semibold text-xs uppercase tracking-wider">Slug</th>
                    <th class="text-center px-5 py-3 text-slate-500 font-semibold text-xs uppercase tracking-wider">Ads</th>
                    <th class="text-center px-5 py-3 text-slate-500 font-semibold text-xs uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($categories as $cat)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-5 py-3 text-center">
                            <span class="material-symbols-outlined text-slate-400">{{ $cat->icon }}</span>
                        </td>
                        <td class="px-5 py-3 font-semibold text-slate-900">{{ $cat->name }}</td>
                        <td class="px-5 py-3 text-slate-500">{{ $cat->slug }}</td>
                        <td class="px-5 py-3 text-center font-bold text-primary">{{ $cat->ads_count ?? 0 }}</td>
                        <td class="px-5 py-3 text-center">
                            <button class="text-slate-400 hover:text-blue-500 mx-1"><span class="material-symbols-outlined text-[18px]">edit</span></button>
                            <button class="text-slate-400 hover:text-red-500 mx-1"><span class="material-symbols-outlined text-[18px]">delete</span></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
