<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>@yield('title', 'Dashboard') - Onlinemarket.ng</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: { extend: { colors: { "primary": "#195de6", "background-light": "#f6f6f8", "background-dark": "#111621" }, fontFamily: { "display": ["Inter", "sans-serif"] }, borderRadius: { "DEFAULT": "0.25rem","lg": "0.5rem","xl": "0.75rem","2xl": "1rem","full": "9999px" } } },
        }
    </script>
    <style>
        .glass-panel { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.5); }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    @stack('styles')
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-slate-100 antialiased overflow-hidden">
    <div class="flex h-screen w-full">

        {{-- Desktop Sidebar --}}
        <aside class="w-64 h-full hidden lg:flex flex-col justify-between border-r border-slate-200 dark:border-slate-800 bg-white dark:bg-[#111621] z-20">
            <div class="p-6">
                <a href="{{ route('home') }}" class="flex items-center gap-3 mb-10">
                    <div class="size-8 rounded-lg bg-primary flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                        <span class="material-symbols-outlined text-xl">shopping_bag</span>
                    </div>
                    <h1 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">Onlinemarket.ng</h1>
                </a>
                <nav class="flex flex-col gap-2">
                    @yield('sidebar-nav')
                </nav>
            </div>
            <div class="p-6 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer">
                    <div class="size-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-lg">
                        @if(auth()->user()->profile_image)
                            <img src="{{ auth()->user()->profile_image }}" class="w-full h-full rounded-full object-cover" alt="">
                        @else
                            {{ auth()->user()->initial }}
                        @endif
                    </div>
                    <div class="flex flex-col flex-1">
                        <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ auth()->user()->username }}</span>
                        <span class="text-xs text-slate-500">View Profile</span>
                    </div>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-xl text-sm font-bold bg-red-50 text-red-600 hover:bg-red-100 transition-colors border border-red-100">
                        <span class="material-symbols-outlined text-[18px]">logout</span> Log Out
                    </button>
                </form>
            </div>
        </aside>

        {{-- Mobile Sidebar --}}
        <aside id="mobile-sidebar" class="fixed inset-y-0 left-0 w-64 bg-white dark:bg-[#111621] z-40 transform -translate-x-full transition-transform duration-300 lg:hidden flex flex-col shadow-2xl">
            <div class="h-16 flex items-center justify-between px-6 border-b border-slate-100 dark:border-slate-800">
                <span class="text-lg font-bold">Menu</span>
                <button id="close-mobile-menu" class="text-slate-500 hover:text-red-500">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <nav class="flex-1 overflow-y-auto py-6 px-3 flex flex-col gap-1">
                @yield('sidebar-nav')
            </nav>
            <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-xl text-sm font-bold bg-red-50 text-red-600 hover:bg-red-100 border border-red-100 transition-colors">
                        <span class="material-symbols-outlined text-[18px]">logout</span> Log Out
                    </button>
                </form>
            </div>
        </aside>
        <div id="mobile-menu-overlay" class="fixed inset-0 bg-black/50 z-30 hidden transition-opacity opacity-0"></div>

        {{-- Main Content --}}
        <main class="flex-1 flex flex-col h-full overflow-hidden relative">
            {{-- Glass Header --}}
            <header class="h-20 w-full glass-panel sticky top-0 z-30 flex items-center justify-between px-6 lg:px-8 border-b border-slate-200/50 dark:border-slate-700/50">
                <button id="mobile-menu-btn" class="lg:hidden p-2 text-slate-600 dark:text-slate-200">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <div class="hidden md:flex flex-1 max-w-lg mx-4">
                    <form action="{{ route('ads.search') }}" class="relative w-full group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-slate-400">search</span>
                        </div>
                        <input name="q" class="block w-full pl-10 pr-3 py-2.5 border-none rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary/50" placeholder="Search ads..." type="text" />
                    </form>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('ads.create') }}" class="hidden sm:flex items-center gap-2 bg-primary hover:bg-blue-600 text-white px-5 py-2.5 rounded-xl font-medium shadow-lg shadow-blue-500/20">
                        <span class="material-symbols-outlined text-[20px]">add_circle</span>
                        <span>Post Ad</span>
                    </a>
                    <a href="{{ route('notifications') }}" class="relative p-2.5 rounded-xl bg-white dark:bg-slate-800 text-slate-600 border border-slate-200 dark:border-slate-700">
                        <span class="material-symbols-outlined">notifications</span>
                    </a>
                </div>
            </header>

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="mx-6 mt-4 bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">check_circle</span>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Scrollable Body --}}
            <div class="flex-1 overflow-y-auto overflow-x-hidden p-6 lg:p-10 scroll-smooth">
                <div class="max-w-7xl mx-auto flex flex-col gap-10 pb-20">
                    @yield('content')
                </div>
            </div>

            {{-- Mobile Bottom Nav --}}
            <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white dark:bg-[#1a202c] border-t border-slate-200 dark:border-slate-800 px-4 py-2 z-50 flex justify-between items-center">
                @yield('mobile-nav')
            </div>
        </main>
    </div>

    <script>
        const mobileBtn = document.getElementById('mobile-menu-btn');
        const closeBtn  = document.getElementById('close-mobile-menu');
        const sidebar   = document.getElementById('mobile-sidebar');
        const overlay   = document.getElementById('mobile-menu-overlay');
        function toggleMenu(){
            const closed = sidebar.classList.contains('-translate-x-full');
            if(closed){ sidebar.classList.remove('-translate-x-full'); overlay.classList.remove('hidden'); setTimeout(()=>overlay.classList.remove('opacity-0'),10); }
            else { sidebar.classList.add('-translate-x-full'); overlay.classList.add('opacity-0'); setTimeout(()=>overlay.classList.add('hidden'),300); }
        }
        if(mobileBtn) mobileBtn.addEventListener('click', toggleMenu);
        if(closeBtn)  closeBtn.addEventListener('click', toggleMenu);
        if(overlay)   overlay.addEventListener('click', toggleMenu);
    </script>
    @stack('scripts')
</body>
</html>
