@extends('layouts.dashboard')
@section('title', 'Manage Packages')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">space_dashboard</span>Overview</a>
    <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">group</span>Users</a>
    <a href="{{ route('admin.ads') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">list_alt</span>Ads</a>
    <a href="{{ route('admin.categories') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">category</span>Categories</a>
    <a href="{{ route('admin.monetization.packages') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm bg-primary/10 text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">sell</span>Packages</a>
    <a href="{{ route('admin.settings.payments') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">payments</span>Payment Settings</a>
    <div class="border-t border-slate-100 my-2"></div>
    <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">storefront</span>View Site</a>
@endsection

@section('mobile-nav')
    <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center gap-0.5 text-slate-500 text-xs"><span class="material-symbols-outlined text-[22px]">dashboard</span>Admin</a>
    <a href="{{ route('admin.monetization.packages') }}" class="flex flex-col items-center gap-0.5 text-primary text-xs"><span class="material-symbols-outlined text-[22px]">sell</span>Packages</a>
    <a href="{{ route('admin.settings.payments') }}" class="flex flex-col items-center gap-0.5 text-slate-500 text-xs"><span class="material-symbols-outlined text-[22px]">payments</span>Settings</a>
@endsection

@section('content')
<div x-data="{ addModal: false }">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-black text-slate-900">Monetization Packages</h2>
            <p class="text-slate-500">Manage pricing for Seller packages, Top Ads, and Boosts.</p>
        </div>
        <button @click="addModal = true" class="bg-primary text-white px-5 py-2.5 rounded-xl font-medium text-sm flex items-center gap-2 hover:bg-primary-dark transition-colors shadow-sm">
            <span class="material-symbols-outlined text-[20px]">add</span>
            New Package
        </button>
    </div>

    @if(session('success'))
        <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 border border-green-200">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6 border border-red-200">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Packages Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-900 font-bold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 whitespace-nowrap">ID / Order</th>
                        <th class="px-6 py-4">Name & Description</th>
                        <th class="px-6 py-4 whitespace-nowrap">Type / Duration</th>
                        <th class="px-6 py-4 whitespace-nowrap">Price</th>
                        <th class="px-6 py-4 whitespace-nowrap">Status</th>
                        <th class="px-6 py-4 whitespace-nowrap text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($packages as $pkg)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-bold text-slate-900 text-lg">#{{ $pkg->id }}</span>
                                <div class="text-[11px] font-medium text-slate-400 mt-1 uppercase">Sort: {{ $pkg->sort_order }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">{{ $pkg->name }}</div>
                                <div class="text-xs text-slate-500 mt-1 md:max-w-xs truncate" title="{{ $pkg->description }}">{{ $pkg->description ?: 'No description' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-semibold text-slate-700 uppercase">{{ str_replace('_', ' ', $pkg->type) }}</div>
                                <div class="text-xs text-slate-500 mt-1">{{ $pkg->duration_days > 0 ? $pkg->duration_days . ' Days' : 'Unlimited' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-black text-slate-900">
                                {{ $pkg->currency }} {{ number_format($pkg->price) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($pkg->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600">
                                        Disabled
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <form action="{{ route('admin.monetization.packages.toggle', $pkg->id) }}" method="POST">
                                        @csrf
                                        <button class="size-8 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center hover:bg-orange-100 transition-colors" title="Toggle Status">
                                            <span class="material-symbols-outlined text-[18px]">power_settings_new</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Add Modal --}}
    <div x-show="addModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm px-4" x-transition.opacity style="display: none;">
        <div @click.outside="addModal = false" class="bg-white rounded-3xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white/95 backdrop-blur z-10 rounded-t-3xl">
                <h3 class="font-black text-lg text-slate-900">Create New Package</h3>
                <button @click="addModal = false" class="text-slate-400 hover:text-slate-600"><span class="material-symbols-outlined">close</span></button>
            </div>
            
            <form action="{{ route('admin.monetization.packages.store') }}" method="POST" class="p-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Package Type *</label>
                        <select name="type" required class="w-full text-sm rounded-xl border-slate-200 focus:border-primary focus:ring-primary shadow-sm">
                            <option value="top_ad">Top Ad</option>
                            <option value="boost">Boost</option>
                            <option value="featured">Featured Ad</option>
                            <option value="seller_package">Business / Seller Package</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Name *</label>
                        <input type="text" name="name" required class="w-full text-sm rounded-xl border-slate-200 focus:border-primary focus:ring-primary shadow-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                        <textarea name="description" rows="2" class="w-full text-sm rounded-xl border-slate-200 focus:border-primary focus:ring-primary shadow-sm"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Price (NGN) *</label>
                        <input type="number" name="price" required class="w-full text-sm rounded-xl border-slate-200 focus:border-primary focus:ring-primary shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Duration In Days *</label>
                        <input type="number" name="duration_days" required value="7" class="w-full text-sm rounded-xl border-slate-200 focus:border-primary focus:ring-primary shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Listing Limit (Seller Packages)</label>
                        <input type="number" name="listing_limit" value="0" class="w-full text-sm rounded-xl border-slate-200 focus:border-primary focus:ring-primary shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" value="10" class="w-full text-sm rounded-xl border-slate-200 focus:border-primary focus:ring-primary shadow-sm">
                    </div>
                </div>

                <div class="flex items-center gap-3 mb-6 bg-slate-50 p-4 rounded-xl border border-slate-100">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked class="rounded border-slate-300 text-primary focus:ring-primary h-5 w-5 cursor-pointer">
                    <label for="is_active" class="text-sm font-bold text-slate-900 cursor-pointer">Automatically activate this package</label>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="addModal = false" class="px-5 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 border border-slate-200 transition-colors">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl font-medium text-sm bg-primary text-white hover:bg-primary-dark shadow-sm transition-colors">Create Package</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
