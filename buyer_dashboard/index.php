<?php
require_once '../core/init.php';

if (!isLoggedIn() || $_SESSION['role'] !== 'buyer') {
    redirect('../login_page_-_onlinemarket.ng/');
}

$user = getCurrentUser();
?>
<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Buyer Dashboard - Onlinemarket.ng</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#195de6",
                        "background-light": "#f6f6f8",
                        "background-dark": "#111621",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "2xl": "1rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <style>
        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .dark .glass-panel {
            background: rgba(17, 22, 33, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-slate-100 antialiased overflow-hidden">
    <div class="flex h-screen w-full">
        <!-- Sidebar -->
        <aside class="w-64 h-full hidden lg:flex flex-col justify-between border-r border-slate-200 dark:border-slate-800 bg-white dark:bg-[#111621] z-20 transition-all duration-300">
            <div class="p-6">
                <!-- Logo -->
                <a href="../home_page_-_onlinemarket.ng/" class="flex items-center gap-3 mb-10">
                    <div class="size-8 rounded-lg bg-primary flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                        <span class="material-symbols-outlined text-xl">shopping_bag</span>
                    </div>
                    <h1 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">Onlinemarket.ng</h1>
                </a>
                <!-- Navigation -->
                <nav class="flex flex-col gap-2">
                    <a class="flex items-center gap-3 px-4 py-3 rounded-xl bg-primary/10 text-primary font-medium transition-all group" href="index.php">
                        <span class="material-symbols-outlined text-[24px]" style="font-variation-settings: 'FILL' 1;">dashboard</span>
                        <span>Dashboard</span>
                    </a>
                    <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 hover:text-primary hover:bg-slate-50 dark:hover:bg-slate-800 dark:text-slate-400 transition-all group" href="../saved_ads_/_favorites_page/index.php">
                        <span class="material-symbols-outlined text-[24px]">favorite</span>
                        <span>Saved Items</span>
                    </a>
                    <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 hover:text-primary hover:bg-slate-50 dark:hover:bg-slate-800 dark:text-slate-400 transition-all group" href="../buyer_chat_/_messaging_page/index.php">
                        <span class="material-symbols-outlined text-[24px]">chat_bubble</span>
                        <div class="flex flex-1 justify-between items-center">
                            <span>Messages</span>
                            <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">0</span>
                        </div>
                    </a>
                    <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 hover:text-primary hover:bg-slate-50 dark:hover:bg-slate-800 dark:text-slate-400 transition-all group" href="#">
                        <span class="material-symbols-outlined text-[24px]">notifications</span>
                        <span>Notifications</span>
                    </a>
                    <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 hover:text-primary hover:bg-slate-50 dark:hover:bg-slate-800 dark:text-slate-400 transition-all group" href="../buyer_profile_&_settings/index.php">
                        <span class="material-symbols-outlined text-[24px]">settings</span>
                        <span>Settings</span>
                    </a>
                    <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 hover:text-red-600 hover:bg-slate-50 dark:hover:bg-slate-800 dark:text-slate-400 transition-all group" href="../core/logout.php">
                        <span class="material-symbols-outlined text-[24px]">logout</span>
                        <span>Logout</span>
                    </a>
                </nav>
            </div>
            <!-- User Profile Snippet in Sidebar -->
            <div class="p-6 border-t border-slate-100 dark:border-slate-800">
                <a href="../buyer_profile_&_settings/index.php" class="flex items-center gap-3 p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer transition-colors">
                    <div class="size-10 rounded-full bg-cover bg-center bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-lg" style='background-image: url("<?php echo $user['profile_image'] ?? ''; ?>");'>
                        <?php if (empty($user['profile_image'])) echo strtoupper(substr($user['username'], 0, 1)); ?>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm font-semibold text-slate-900 dark:text-white"><?php echo sanitize($user['username']); ?></span>
                        <span class="text-xs text-slate-500 dark:text-slate-400">View Profile</span>
                    </div>
                </a>
            </div>
        </aside>

        <!-- Mobile Sidebar -->
        <aside id="mobile-sidebar" class="fixed inset-y-0 left-0 w-64 bg-white dark:bg-[#111621] z-40 transform -translate-x-full transition-transform duration-300 lg:hidden flex flex-col shadow-2xl">
            <div class="h-16 flex items-center justify-between px-6 border-b border-slate-100 dark:border-slate-800">
                <span class="text-lg font-bold text-slate-900 dark:text-white">Menu</span>
                <button id="close-mobile-menu" class="text-slate-500 hover:text-red-500">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <!-- Navigation (Cloned) -->
            <nav class="flex-1 overflow-y-auto py-6 px-3 flex flex-col gap-1">
                <a class="flex items-center gap-3 px-4 py-3 rounded-xl bg-primary/10 text-primary font-medium transition-all group" href="index.php">
                    <span class="material-symbols-outlined text-[24px]" style="font-variation-settings: 'FILL' 1;">dashboard</span>
                    <span>Dashboard</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 hover:text-primary hover:bg-slate-50 dark:hover:bg-slate-800 dark:text-slate-400 transition-all group" href="../saved_ads_/_favorites_page/index.php">
                    <span class="material-symbols-outlined text-[24px]">favorite</span>
                    <span>Saved Items</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 hover:text-primary hover:bg-slate-50 dark:hover:bg-slate-800 dark:text-slate-400 transition-all group" href="../buyer_chat_/_messaging_page/index.php">
                    <span class="material-symbols-outlined text-[24px]">chat_bubble</span>
                    <div class="flex flex-1 justify-between items-center">
                        <span>Messages</span>
                        <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">0</span>
                    </div>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 hover:text-primary hover:bg-slate-50 dark:hover:bg-slate-800 dark:text-slate-400 transition-all group" href="../buyer_profile_&_settings/index.php">
                    <span class="material-symbols-outlined text-[24px]">settings</span>
                    <span>Settings</span>
                </a>
                <div class="border-t border-slate-100 dark:border-slate-800 my-2"></div>
                <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-red-600 hover:bg-red-50 dark:hover:bg-slate-800 transition-all group" href="../core/logout.php">
                    <span class="material-symbols-outlined text-[24px]">logout</span>
                    <span>Logout</span>
                </a>
            </nav>
        </aside>

        <!-- Overlay -->
        <div id="mobile-menu-overlay" class="fixed inset-0 bg-black/50 z-30 hidden transition-opacity opacity-0"></div>

        <!-- Main Content Wrapper -->
        <main class="flex-1 flex flex-col h-full overflow-hidden relative">
            <!-- Glass Header -->
            <header class="h-20 w-full glass-panel sticky top-0 z-30 flex items-center justify-between px-6 lg:px-8 border-b border-slate-200/50 dark:border-slate-700/50">
                <!-- Mobile Menu Button (Hidden on Desktop) -->
                <button id="mobile-menu-btn" class="lg:hidden p-2 text-slate-600 dark:text-slate-200">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <!-- Search Bar -->
                <div class="hidden md:flex flex-1 max-w-lg mx-4">
                    <form action="../search_results_page_-_onlinemarket.ng/" class="relative w-full group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-slate-400 group-focus-within:text-primary transition-colors">search</span>
                        </div>
                        <input name="q" class="block w-full pl-10 pr-3 py-2.5 border-none rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary/50 transition-all" placeholder="Search cars, phones, and more..." type="text" />
                    </form>
                </div>
                <!-- Right Actions -->
                <div class="flex items-center gap-4">
                    <a href="../post_new_ad_form/" class="hidden sm:flex items-center gap-2 bg-primary hover:bg-blue-600 text-white px-5 py-2.5 rounded-xl font-medium transition-all shadow-lg shadow-blue-500/20 active:scale-95">
                        <span class="material-symbols-outlined text-[20px]">add_circle</span>
                        <span>Post Ad</span>
                    </a>
                    <button class="relative p-2.5 rounded-xl bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition-colors">
                        <span class="material-symbols-outlined">notifications</span>
                        <span class="absolute top-2 right-2 size-2 bg-red-500 rounded-full border-2 border-white dark:border-slate-800"></span>
                    </button>
                    <!-- Mobile Search Trigger -->
                    <button class="md:hidden p-2.5 rounded-xl bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                        <span class="material-symbols-outlined">search</span>
                    </button>
                </div>
            </header>
            <!-- Scrollable Body -->
            <div class="flex-1 overflow-y-auto overflow-x-hidden p-6 lg:p-10 scroll-smooth">
                <div class="max-w-7xl mx-auto flex flex-col gap-10 pb-20">
                    <!-- Welcome & Stats Section -->
                    <section class="flex flex-col gap-6">
                        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                            <div>
                                <h2 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">Welcome back, <?php echo sanitize($user['username']); ?>! 👋</h2>
                                <p class="text-slate-500 dark:text-slate-400 mt-1">Here's what's happening with your account today.</p>
                            </div>
                            <div class="flex gap-2">
                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-medium border border-green-200">Verified Buyer</span>
                            </div>
                        </div>
                        <!-- Stats Grid (Static for now) -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Stat Card 1 -->
                            <div class="bg-white dark:bg-[#1A202C] p-6 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-md transition-shadow group cursor-pointer relative overflow-hidden">
                                <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                    <span class="material-symbols-outlined text-8xl text-primary">bookmark</span>
                                </div>
                                <div class="flex flex-col gap-4 relative z-10">
                                    <div class="size-12 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-primary">
                                        <span class="material-symbols-outlined">bookmark</span>
                                    </div>
                                    <div>
                                        <p class="text-4xl font-bold text-slate-900 dark:text-white">0</p>
                                        <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Saved Items</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Stat Card 2 -->
                            <div class="bg-white dark:bg-[#1A202C] p-6 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-md transition-shadow group cursor-pointer relative overflow-hidden">
                                <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                    <span class="material-symbols-outlined text-8xl text-purple-500">mail</span>
                                </div>
                                <div class="flex flex-col gap-4 relative z-10">
                                    <div class="size-12 rounded-full bg-purple-50 dark:bg-purple-900/30 flex items-center justify-center text-purple-600">
                                        <span class="material-symbols-outlined">mail</span>
                                    </div>
                                    <div>
                                        <p class="text-4xl font-bold text-slate-900 dark:text-white">0</p>
                                        <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Unread Messages</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Stat Card 3 -->
                            <div class="bg-white dark:bg-[#1A202C] p-6 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-md transition-shadow group cursor-pointer relative overflow-hidden">
                                <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                    <span class="material-symbols-outlined text-8xl text-green-500">trending_down</span>
                                </div>
                                <div class="flex flex-col gap-4 relative z-10">
                                    <div class="size-12 rounded-full bg-green-50 dark:bg-green-900/30 flex items-center justify-center text-green-600">
                                        <span class="material-symbols-outlined">trending_down</span>
                                    </div>
                                    <div>
                                        <p class="text-4xl font-bold text-slate-900 dark:text-white">0</p>
                                        <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Price Drops</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- Rest of the dashboard content (Recently Viewed, Recommended) preserved as static for now -->
                </div>
            </div>
        </main>
        <!-- Sticky Bottom Mobile Navigation -->
        <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white dark:bg-[#1a202c] border-t border-slate-200 dark:border-slate-800 px-4 py-2 z-50 flex justify-between items-center shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
            <a href="index.php" class="flex flex-col items-center gap-1 text-primary">
                <span class="material-symbols-outlined text-[24px]">dashboard</span>
                <span class="text-[10px] font-medium">Home</span>
            </a>
            <a href="../search_results_page_-_onlinemarket.ng/index.php" class="flex flex-col items-center gap-1 text-slate-500 dark:text-slate-400 hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-[24px]">search</span>
                <span class="text-[10px] font-medium">Search</span>
            </a>
            <a href="../post_new_ad_form/" class="flex flex-col items-center gap-1 text-slate-500 hover:text-primary transition-colors -mt-6">
                <div class="bg-primary text-white rounded-full p-3 shadow-lg shadow-primary/30 border-4 border-white dark:border-[#1a202c]">
                    <span class="material-symbols-outlined text-[24px]">add</span>
                </div>
                <span class="text-[10px] font-medium">Sell</span>
            </a>
            <a href="../buyer_chat_/_messaging_page/index.php" class="flex flex-col items-center gap-1 text-slate-500 dark:text-slate-400 hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-[24px]">chat</span>
                <span class="text-[10px] font-medium">Chat</span>
            </a>
            <a href="../buyer_profile_&_settings/index.php" class="flex flex-col items-center gap-1 text-slate-500 dark:text-slate-400 hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-[24px]">person</span>
                <span class="text-[10px] font-medium">Profile</span>
            </a>
        </div>
    </div>
    <script>
        // Mobile Menu Logic
        const mobileBtn = document.getElementById('mobile-menu-btn');
        const closeBtn = document.getElementById('close-mobile-menu');
        const sidebar = document.getElementById('mobile-sidebar');
        const overlay = document.getElementById('mobile-menu-overlay');

        function toggleMenu() {
            const isClosed = sidebar.classList.contains('-translate-x-full');
            if (isClosed) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.remove('opacity-0'), 10);
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('opacity-0');
                setTimeout(() => overlay.classList.add('hidden'), 300);
            }
        }

        if (mobileBtn) mobileBtn.addEventListener('click', toggleMenu);
        if (closeBtn) closeBtn.addEventListener('click', toggleMenu);
        if (overlay) overlay.addEventListener('click', toggleMenu);
    </script>
</body>

</html>