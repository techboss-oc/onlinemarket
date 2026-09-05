<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>@yield('title', 'Onlinemarket.ng') - Onlinemarket.ng</title>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#195de6",
                        "background-light": "#f6f6f8",
                        "background-dark": "#111621",
                        "surface": "#ffffff",
                    },
                    fontFamily: { "display": ["Inter", "sans-serif"] },
                    borderRadius: {
                        "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem",
                        "2xl": "1rem", "full": "9999px"
                    },
                    boxShadow: {
                        'soft': '0 4px 20px -2px rgba(0,0,0,0.05)',
                        'hover': '0 10px 25px -5px rgba(25,93,230,0.15)',
                    }
                },
            },
        }
    </script>
    @stack('styles')
</head>
<body class="bg-background-light text-[#0e121b] font-display antialiased overflow-x-hidden selection:bg-primary/20 selection:text-primary">

    {{-- Navbar --}}
    <header class="sticky top-0 z-50 w-full backdrop-blur-md bg-white/90 border-b border-[#e7ebf3] transition-all duration-300">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('home') }}" class="flex items-center gap-2 cursor-pointer group">
                    <div class="size-8 rounded-lg bg-primary text-white flex items-center justify-center shadow-lg shadow-primary/30 transition-transform group-hover:scale-105">
                        <span class="material-symbols-outlined text-[20px]">shopping_bag</span>
                    </div>
                    <h2 class="text-[#0e121b] text-xl font-bold tracking-tight">Onlinemarket<span class="text-primary">.ng</span></h2>
                </a>
                <div class="hidden md:flex items-center gap-6">
                    <div class="h-6 w-px bg-slate-200"></div>
                    @auth
                        <a class="text-slate-600 text-sm font-medium hover:text-primary transition-colors" href="{{ route('buyer.dashboard') }}">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-slate-600 text-sm font-medium hover:text-primary transition-colors">Logout</button>
                        </form>
                    @else
                        <a class="text-slate-600 text-sm font-medium hover:text-primary transition-colors" href="{{ route('login') }}">Login</a>
                        <a class="text-slate-600 text-sm font-medium hover:text-primary transition-colors" href="{{ route('register') }}">Register</a>
                    @endauth
                    <a href="{{ route('ads.create') }}" class="flex items-center gap-2 bg-primary hover:bg-blue-700 text-white text-sm font-bold px-5 py-2.5 rounded-lg shadow-lg shadow-primary/20 transition-all hover:-translate-y-0.5">
                        <span class="material-symbols-outlined text-[20px]">add_circle</span>
                        <span>Post Ad</span>
                    </a>
                </div>
                <button id="mobile-menu-btn" class="md:hidden p-2 text-slate-600 hover:text-primary">
                    <span class="material-symbols-outlined">menu</span>
                </button>
            </div>
        </div>
    </header>

    {{-- Mobile Overlay --}}
    <div id="mobile-menu-overlay" class="fixed inset-0 bg-black/50 z-30 hidden transition-opacity opacity-0"></div>
    {{-- Mobile Sidebar --}}
    <aside id="mobile-sidebar" class="fixed inset-y-0 right-0 w-64 bg-white z-40 transform translate-x-full transition-transform duration-300 md:hidden flex flex-col shadow-2xl">
        <div class="h-16 flex items-center justify-between px-6 border-b border-slate-100">
            <span class="text-lg font-bold text-slate-800">Menu</span>
            <button id="close-mobile-menu" class="text-slate-500 hover:text-red-500">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <nav class="flex-1 overflow-y-auto p-4 flex flex-col gap-4">
            @auth
                <div class="flex items-center gap-3 px-2 py-2 bg-slate-50 rounded-lg">
                    <div class="size-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold">
                        {{ strtoupper(substr(auth()->user()->username, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800">{{ auth()->user()->username }}</p>
                        <p class="text-xs text-slate-500">Logged in</p>
                    </div>
                </div>
                <a class="flex items-center gap-3 px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-primary" href="{{ route('buyer.dashboard') }}">
                    <span class="material-symbols-outlined">dashboard</span> Dashboard
                </a>
            @else
                <a class="flex items-center gap-3 px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-primary" href="{{ route('login') }}">
                    <span class="material-symbols-outlined">login</span> Login
                </a>
                <a class="flex items-center gap-3 px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-primary" href="{{ route('register') }}">
                    <span class="material-symbols-outlined">person_add</span> Register
                </a>
            @endauth
            <div class="border-t border-slate-100 my-1"></div>
            <a class="flex items-center gap-3 px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-primary" href="{{ route('ads.create') }}">
                <span class="material-symbols-outlined">add_circle</span> Post Ad
            </a>
            <a class="flex items-center gap-3 px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-primary" href="{{ route('categories.index') }}">
                <span class="material-symbols-outlined">category</span> Categories
            </a>
            @auth
                <div class="border-t border-slate-100 my-1"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 rounded-lg text-red-600 hover:bg-red-50">
                        <span class="material-symbols-outlined">logout</span> Logout
                    </button>
                </form>
            @endauth
        </nav>
    </aside>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="max-w-[1200px] mx-auto px-4 pt-4">
            <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px]">check_circle</span>
                {{ session('success') }}
            </div>
        </div>
    @endif

    {{-- Page Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-[#111621] text-slate-300 py-12 border-t border-slate-800 mb-16 md:mb-0">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div class="col-span-1 md:col-span-1">
                    <div class="flex items-center gap-2 mb-4 text-white">
                        <div class="size-6 rounded bg-primary flex items-center justify-center">
                            <span class="material-symbols-outlined text-[16px]">shopping_bag</span>
                        </div>
                        <h2 class="text-lg font-bold">Onlinemarket.ng</h2>
                    </div>
                    <p class="text-sm text-slate-400 mb-6 leading-relaxed">Nigeria's fastest-growing online marketplace.</p>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">About Us</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a class="hover:text-primary transition-colors" href="{{ route('about') }}">About Onlinemarket.ng</a></li>
                        <li><a class="hover:text-primary transition-colors" href="{{ route('terms') }}">Terms & Conditions</a></li>
                        <li><a class="hover:text-primary transition-colors" href="{{ route('privacy') }}">Privacy Policy</a></li>
                        <li><a class="hover:text-primary transition-colors" href="{{ route('billing') }}">Billing Policy</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">Support</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a class="hover:text-primary transition-colors" href="{{ route('safety-tips') }}">Safety Tips</a></li>
                        <li><a class="hover:text-primary transition-colors" href="{{ route('contact') }}">Contact Us</a></li>
                        <li><a class="hover:text-primary transition-colors" href="{{ route('faq') }}">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">Get the App</h4>
                    <div class="flex flex-col gap-3">
                        <button class="flex items-center gap-3 bg-slate-800 hover:bg-slate-700 px-4 py-2 rounded-lg border border-slate-700">
                            <span class="material-symbols-outlined text-[24px]">android</span>
                            <div class="text-left"><div class="text-[10px] uppercase">Get it on</div><div class="text-sm font-bold text-white">Google Play</div></div>
                        </button>
                    </div>
                </div>
            </div>
            <div class="border-t border-slate-800 pt-8 text-center">
                <p class="text-xs text-slate-500">© {{ date('Y') }} Onlinemarket.ng. All rights reserved.</p>
            </div>
        </div>
    </footer>

    {{-- Mobile Bottom Nav --}}
    <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 px-4 py-2 z-50 flex justify-between items-center shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
        <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('home') ? 'text-primary' : 'text-slate-500 hover:text-primary' }}">
            <span class="material-symbols-outlined text-[24px]">home</span>
            <span class="text-[10px] font-medium">Home</span>
        </a>
        <a href="{{ route('categories.index') }}" class="flex flex-col items-center gap-1 text-slate-500 hover:text-primary">
            <span class="material-symbols-outlined text-[24px]">grid_view</span>
            <span class="text-[10px] font-medium">Categories</span>
        </a>
        <a href="{{ route('ads.create') }}" class="flex flex-col items-center gap-1 text-slate-500 hover:text-primary -mt-6">
            <div class="bg-primary text-white rounded-full p-3 shadow-lg shadow-primary/30 border-4 border-white">
                <span class="material-symbols-outlined text-[24px]">add</span>
            </div>
            <span class="text-[10px] font-medium">Sell</span>
        </a>
        <a href="{{ auth()->check() ? route('favorites.index') : route('login') }}" class="flex flex-col items-center gap-1 text-slate-500 hover:text-primary">
            <span class="material-symbols-outlined text-[24px]">favorite</span>
            <span class="text-[10px] font-medium">Saved</span>
        </a>
        <a href="{{ auth()->check() ? route('buyer.dashboard') : route('login') }}" class="flex flex-col items-center gap-1 text-slate-500 hover:text-primary">
            <span class="material-symbols-outlined text-[24px]">person</span>
            <span class="text-[10px] font-medium">Profile</span>
        </a>
    </div>

    <script>
        const mobileBtn = document.getElementById('mobile-menu-btn');
        const closeBtn  = document.getElementById('close-mobile-menu');
        const sidebar   = document.getElementById('mobile-sidebar');
        const overlay   = document.getElementById('mobile-menu-overlay');
        function toggleMenu() {
            const closed = sidebar.classList.contains('translate-x-full');
            if (closed) {
                sidebar.classList.remove('translate-x-full');
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.remove('opacity-0'), 10);
            } else {
                sidebar.classList.add('translate-x-full');
                overlay.classList.add('opacity-0');
                setTimeout(() => overlay.classList.add('hidden'), 300);
            }
        }
        if (mobileBtn) mobileBtn.addEventListener('click', toggleMenu);
        if (closeBtn)  closeBtn.addEventListener('click', toggleMenu);
        if (overlay)   overlay.addEventListener('click', toggleMenu);
    </script>

    @stack('scripts')
</body>
</html>
