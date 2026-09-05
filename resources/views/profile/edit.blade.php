@extends('layouts.dashboard')
@section('title', 'Edit Profile')

@section('sidebar-nav')
    <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">home</span>Browse</a>
    <a href="{{ route('buyer.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">dashboard</span>Dashboard</a>
    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm bg-primary/10 text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">manage_accounts</span>Profile</a>
    <a href="{{ route('favorites.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">favorite</span>Saved Ads</a>
    <a href="{{ route('chat.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">chat</span>Messages</a>
@endsection

@section('mobile-nav')
    <a href="{{ route('home') }}" class="flex flex-col items-center gap-0.5 text-slate-500 text-xs"><span class="material-symbols-outlined text-[22px]">home</span>Home</a>
    <a href="{{ route('favorites.index') }}" class="flex flex-col items-center gap-0.5 text-slate-500 text-xs"><span class="material-symbols-outlined text-[22px]">favorite</span>Saved</a>
    <a href="{{ route('ads.create') }}" class="flex flex-col items-center gap-0.5 -mt-4 text-xs"><div class="bg-primary text-white rounded-full p-3 shadow-lg border-4 border-white"><span class="material-symbols-outlined text-[24px]">add</span></div><span class="text-slate-500">Post</span></a>
    <a href="{{ route('chat.index') }}" class="flex flex-col items-center gap-0.5 text-slate-500 text-xs"><span class="material-symbols-outlined text-[22px]">chat</span>Messages</a>
    <a href="{{ route('profile.edit') }}" class="flex flex-col items-center gap-0.5 text-primary text-xs font-medium"><span class="material-symbols-outlined text-[22px]">person</span>Profile</a>
@endsection

@section('content')
<div class="max-w-2xl w-full">
    <h2 class="text-2xl font-black text-slate-900 mb-8">Edit Profile</h2>

    {{-- Profile Info Form --}}
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-slate-200 p-6 mb-6">
        @csrf
        <h3 class="font-bold text-slate-900 mb-5 pb-3 border-b border-slate-100">Personal Information</h3>

        {{-- Avatar --}}
        <div class="flex items-center gap-4 mb-6">
            <div class="size-20 rounded-full bg-primary/10 text-primary font-bold text-3xl flex items-center justify-center relative overflow-hidden cursor-pointer border-2 border-slate-200" onclick="document.getElementById('avatarInput').click()">
                @if($user->profile_image)
                    <img id="avatarPreview" src="{{ $user->profile_image }}" class="absolute inset-0 w-full h-full object-cover rounded-full" />
                @else
                    <span id="avatarInitial">{{ strtoupper(substr($user->username, 0, 1)) }}</span>
                @endif
                <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity rounded-full">
                    <span class="material-symbols-outlined text-white text-xl">photo_camera</span>
                </div>
            </div>
            <input type="file" id="avatarInput" name="avatar" accept="image/*" class="hidden" />
            <div>
                <p class="font-semibold text-slate-800 text-sm">{{ $user->username }}</p>
                <p class="text-slate-500 text-xs">{{ $user->email }}</p>
                <p class="text-xs text-primary mt-1 cursor-pointer" onclick="document.getElementById('avatarInput').click()">Change photo</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Username</label>
                <input name="username" value="{{ old('username', $user->username) }}" type="text" required
                       class="w-full h-11 px-4 rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm" />
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Phone</label>
                <input name="phone" value="{{ old('phone', $user->phone) }}" type="text" placeholder="+234..."
                       class="w-full h-11 px-4 rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/20 text-sm" />
            </div>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Location</label>
            <input name="location" value="{{ old('location', $user->location) }}" type="text" placeholder="e.g. Lagos, Nigeria"
                   class="w-full h-11 px-4 rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/20 text-sm" />
        </div>
        <div class="mb-5">
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Bio</label>
            <textarea name="bio" rows="3" placeholder="Tell buyers and sellers a bit about yourself..."
                      class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/20 text-sm">{{ old('bio', $user->bio) }}</textarea>
        </div>
        @if ($errors->any())
            <div class="bg-red-50 text-red-700 px-4 py-2 rounded-lg mb-4 text-sm">{{ $errors->first() }}</div>
        @endif
        <button type="submit" class="w-full h-11 bg-primary hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-primary/20 transition-all">Save Changes</button>
    </form>

    {{-- Change Password --}}
    <form action="{{ route('profile.password') }}" method="POST" class="bg-white rounded-2xl border border-slate-200 p-6">
        @csrf
        <h3 class="font-bold text-slate-900 mb-5 pb-3 border-b border-slate-100">Change Password</h3>
        <div class="flex flex-col gap-4">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Current Password</label>
                <input name="current_password" type="password" required class="w-full h-11 px-4 rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/20 text-sm" />
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">New Password</label>
                <input name="password" type="password" required class="w-full h-11 px-4 rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/20 text-sm" />
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Confirm New Password</label>
                <input name="password_confirmation" type="password" required class="w-full h-11 px-4 rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/20 text-sm" />
            </div>
        </div>
        <button type="submit" class="w-full h-11 mt-5 bg-slate-900 hover:bg-slate-700 text-white font-bold rounded-xl transition-all">Change Password</button>
    </form>

    {{-- Logout --}}
    <div class="mt-4 text-center">
        <form action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-semibold">Logout from Account</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('avatarInput').addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        const preview = document.getElementById('avatarPreview');
        const initial = document.getElementById('avatarInitial');
        if (initial) initial.style.display = 'none';
        if (preview) { preview.src = e.target.result; }
        else {
            const container = document.querySelector('.size-20.rounded-full');
            const img = document.createElement('img');
            img.id = 'avatarPreview';
            img.src = e.target.result;
            img.className = 'absolute inset-0 w-full h-full object-cover rounded-full';
            container.insertBefore(img, container.firstChild);
        }
    };
    reader.readAsDataURL(file);
});
</script>
@endpush
