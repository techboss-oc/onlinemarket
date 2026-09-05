@extends('layouts.dashboard')
@section('title', 'Manage Users')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">space_dashboard</span>Overview</a>
    <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm bg-primary/10 text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">group</span>Users</a>
    <a href="{{ route('admin.ads') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">list_alt</span>Ads</a>
    <a href="{{ route('admin.categories') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">category</span>Categories</a>
    <div class="border-t border-slate-100 my-2"></div>
    <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">storefront</span>View Site</a>
@endsection

@section('mobile-nav')
    <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center gap-0.5 text-slate-500 text-xs"><span class="material-symbols-outlined text-[22px]">dashboard</span>Admin</a>
    <a href="{{ route('admin.users') }}" class="flex flex-col items-center gap-0.5 text-primary text-xs font-medium"><span class="material-symbols-outlined text-[22px]">group</span>Users</a>
    <a href="{{ route('admin.ads') }}" class="flex flex-col items-center gap-0.5 text-slate-500 text-xs"><span class="material-symbols-outlined text-[22px]">list_alt</span>Ads</a>
    <a href="{{ route('home') }}" class="flex flex-col items-center gap-0.5 text-slate-500 text-xs"><span class="material-symbols-outlined text-[22px]">home</span>Site</a>
@endsection

@section('content')
<div>
    <h2 class="text-2xl font-black text-slate-900 mb-6">Manage Users</h2>
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="text-left px-5 py-3 text-slate-500 font-semibold text-xs uppercase tracking-wider">User</th>
                        <th class="text-left px-5 py-3 text-slate-500 font-semibold text-xs uppercase tracking-wider hidden md:table-cell">Email</th>
                        <th class="text-center px-5 py-3 text-slate-500 font-semibold text-xs uppercase tracking-wider">Role</th>
                        <th class="text-center px-5 py-3 text-slate-500 font-semibold text-xs uppercase tracking-wider hidden md:table-cell">Verified</th>
                        <th class="text-center px-5 py-3 text-slate-500 font-semibold text-xs uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($users as $u)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="size-8 rounded-full bg-primary/10 text-primary text-sm font-bold flex items-center justify-center flex-shrink-0">{{ strtoupper(substr($u->username, 0, 1)) }}</div>
                                    <span class="font-semibold text-slate-900">{{ $u->username }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-slate-500 hidden md:table-cell">{{ $u->email }}</td>
                            <td class="px-5 py-3 text-center">
                                <form action="{{ route('admin.users.role', $u->id) }}" method="POST" class="inline">
                                    @csrf
                                    <select name="role" onchange="this.form.submit()" class="text-xs px-2 py-1 rounded-lg border border-slate-200 focus:ring-0 bg-white">
                                        <option value="buyer"  {{ $u->role === 'buyer'  ? 'selected' : '' }}>Buyer</option>
                                        <option value="seller" {{ $u->role === 'seller' ? 'selected' : '' }}>Seller</option>
                                        <option value="admin"  {{ $u->role === 'admin'  ? 'selected' : '' }}>Admin</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-5 py-3 text-center hidden md:table-cell">
                                <form action="{{ route('admin.users.verify', $u->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $u->is_verified ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }} transition-colors">
                                        {{ $u->is_verified ? 'Verified' : 'Unverified' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-5 py-3 text-center">
                                @if($u->id !== auth()->id())
                                    <form action="{{ route('admin.users.delete', $u->id) }}" method="POST" onsubmit="return confirm('Delete this user and all their data?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 transition-colors" title="Delete user">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-slate-300 text-xs">You</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t border-slate-100">{{ $users->links() }}</div>
    </div>
</div>
@endsection
