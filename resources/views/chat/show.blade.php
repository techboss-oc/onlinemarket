@extends('layouts.dashboard')
@section('title', 'Chat')

@section('sidebar-nav')
    <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">home</span>Browse</a>
    <a href="{{ route('chat.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm bg-primary/10 text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">chat</span>Messages</a>
    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">manage_accounts</span>Profile</a>
@endsection

@section('mobile-nav')
    <a href="{{ route('chat.index') }}" class="flex flex-col items-center gap-0.5 text-slate-500 text-xs"><span class="material-symbols-outlined text-[22px]">arrow_back</span>Back</a>
    <a href="{{ route('home') }}" class="flex flex-col items-center gap-0.5 text-slate-500 text-xs"><span class="material-symbols-outlined text-[22px]">home</span>Home</a>
@endsection

@section('content')
<div class="bg-white rounded-2xl border border-slate-200 flex flex-col h-[70vh]">
    {{-- Chat Header --}}
    <div class="flex items-center gap-3 px-5 py-4 border-b border-slate-100">
        <a href="{{ route('chat.index') }}" class="text-slate-400 hover:text-primary"><span class="material-symbols-outlined">arrow_back</span></a>
        @php $other = $chat->buyer_id === $userId ? $chat->seller : $chat->buyer; @endphp
        <div class="size-10 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold">
            {{ strtoupper(substr($other->username, 0, 1)) }}
        </div>
        <div>
            <p class="font-bold text-slate-900 text-sm">{{ $other->username }}</p>
            @if($chat->ad)
                <p class="text-xs text-blue-500 truncate max-w-[200px]">Re: {{ $chat->ad->title }}</p>
            @endif
        </div>
    </div>

    {{-- Messages --}}
    <div id="messages-container" class="flex-1 overflow-y-auto p-5 flex flex-col gap-3">
        @foreach($messages as $msg)
            @if($msg->sender_id === $userId)
                <div class="flex justify-end">
                    <div class="max-w-xs md:max-w-md bg-primary text-white px-4 py-2.5 rounded-2xl rounded-tr-sm text-sm leading-snug">
                        <p>{{ $msg->message }}</p>
                        <p class="text-blue-200 text-[10px] text-right mt-1">{{ $msg->created_at->format('H:i') }}</p>
                    </div>
                </div>
            @else
                <div class="flex justify-start gap-2">
                    <div class="size-7 rounded-full bg-slate-200 flex-shrink-0 flex items-center justify-center text-xs font-bold text-slate-600">{{ strtoupper(substr($other->username, 0, 1)) }}</div>
                    <div class="max-w-xs md:max-w-md bg-slate-100 px-4 py-2.5 rounded-2xl rounded-tl-sm text-sm leading-snug">
                        <p class="text-slate-900">{{ $msg->message }}</p>
                        <p class="text-slate-400 text-[10px] mt-1">{{ $msg->created_at->format('H:i') }}</p>
                    </div>
                </div>
            @endif
        @endforeach
        <div id="messages-end"></div>
    </div>

    {{-- Message Input --}}
    <div class="border-t border-slate-100 px-4 py-3">
        <form id="message-form" action="{{ route('chat.send', $chat->id) }}" method="POST" class="flex items-center gap-3">
            @csrf
            <input type="text" name="message" id="message-input" placeholder="Type a message..." required
                   class="flex-1 h-10 px-4 rounded-full bg-slate-100 border-none text-sm focus:outline-none focus:ring-2 focus:ring-primary/30" />
            <button type="submit" class="size-10 bg-primary text-white rounded-full flex items-center justify-center shadow-lg shadow-primary/25 hover:bg-blue-700 transition-colors flex-shrink-0">
                <span class="material-symbols-outlined text-[20px]">send</span>
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('messages-end').scrollIntoView();

    document.getElementById('message-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const input = document.getElementById('message-input');
        if (!input.value.trim()) return;
        fetch(this.action, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: JSON.stringify({ message: input.value })
        })
        .then(r => r.json())
        .then(data => {
            const container = document.getElementById('messages-container');
            const msgEl = document.createElement('div');
            msgEl.className = 'flex justify-end';
            msgEl.innerHTML = `<div class="max-w-xs md:max-w-md bg-primary text-white px-4 py-2.5 rounded-2xl rounded-tr-sm text-sm"><p>${input.value}</p></div>`;
            container.insertBefore(msgEl, document.getElementById('messages-end'));
            input.value = '';
            document.getElementById('messages-end').scrollIntoView({ behavior: 'smooth' });
        });
    });
</script>
@endpush
