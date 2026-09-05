@extends('layouts.app')
@section('title', 'Notifications')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-8 w-full">
    <h1 class="text-2xl font-black text-slate-900 mb-6">Notifications</h1>
    <div class="bg-white rounded-2xl border border-slate-200 p-10 text-center">
        <span class="material-symbols-outlined text-6xl text-slate-200 mb-4 block">notifications_none</span>
        <h3 class="text-lg font-bold text-slate-700">You're all caught up!</h3>
        <p class="text-slate-400 mt-2">No new notifications at this time.</p>
    </div>
</div>
@endsection
