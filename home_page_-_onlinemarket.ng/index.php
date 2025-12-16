<?php
require_once '../core/init.php';

$category = new Category();
$ad = new Ad();

$categories = $category->getAll();
$trending_ads = $ad->getTrending(4);
$fresh_ads = $ad->getLatest(8);
?>
<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Home Page - Onlinemarket.ng</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
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
                    boxShadow: {
                        'soft': '0 4px 20px -2px rgba(0, 0, 0, 0.05)',
                        'hover': '0 10px 25px -5px rgba(25, 93, 230, 0.15)',
                    }
                },
            },
        }
    </script>
    <style>
        /* Custom scrollbar for horizontal scrolling if needed */
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="bg-background-light text-[#0e121b] font-display antialiased overflow-x-hidden selection:bg-primary/20 selection:text-primary">
    <!-- Sticky Navbar with Glassmorphism -->
    <header class="sticky top-0 z-50 w-full backdrop-blur-md bg-white/90 border-b border-[#e7ebf3] transition-all duration-300">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="../home_page_-_onlinemarket.ng/" class="flex items-center gap-2 cursor-pointer group">
                    <div class="size-8 rounded-lg bg-primary text-white flex items-center justify-center shadow-lg shadow-primary/30 transition-transform group-hover:scale-105">
                        <span class="material-symbols-outlined text-[20px]">shopping_bag</span>
                    </div>
                    <h2 class="text-[#0e121b] text-xl font-bold tracking-tight">Onlinemarket<span class="text-primary">.ng</span></h2>
                </a>
                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center gap-6">
                    <div class="flex items-center gap-1 text-sm font-medium text-slate-600 hover:text-primary transition-colors cursor-pointer bg-slate-100 px-3 py-1.5 rounded-full">
                        <span class="material-symbols-outlined text-[18px]">location_on</span>
                        <span>Lagos, NG</span>
                        <span class="material-symbols-outlined text-[16px]">expand_more</span>
                    </div>
                    <div class="h-6 w-px bg-slate-200"></div>
                    <?php if (isLoggedIn()): ?>
                        <a class="text-slate-600 text-sm font-medium hover:text-primary transition-colors" href="../buyer_dashboard/">Dashboard</a>
                        <a class="text-slate-600 text-sm font-medium hover:text-primary transition-colors" href="../core/logout.php">Logout</a>
                    <?php else: ?>
                        <a class="text-slate-600 text-sm font-medium hover:text-primary transition-colors" href="../login_page_-_onlinemarket.ng/">Login</a>
                        <a class="text-slate-600 text-sm font-medium hover:text-primary transition-colors" href="../register_page_-_onlinemarket.ng/">Register</a>
                    <?php endif; ?>
                    <a href="../post_new_ad_form/" class="flex items-center gap-2 bg-primary hover:bg-blue-700 text-white text-sm font-bold px-5 py-2.5 rounded-lg shadow-lg shadow-primary/20 transition-all hover:-translate-y-0.5 active:translate-y-0">
                        <span class="material-symbols-outlined text-[20px]">add_circle</span>
                        <span>Post Ad</span>
                    </a>
                </div>
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="md:hidden p-2 text-slate-600 hover:text-primary">
                    <span class="material-symbols-outlined">menu</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Mobile Menu Sidebar Overlay (Hidden by default) -->
    <div id="mobile-menu-overlay" class="fixed inset-0 bg-black/50 z-30 hidden transition-opacity opacity-0"></div>

    <!-- Mobile Sidebar -->
    <aside id="mobile-sidebar" class="fixed inset-y-0 right-0 w-64 bg-white z-40 transform translate-x-full transition-transform duration-300 md:hidden flex flex-col shadow-2xl">
        <div class="h-16 flex items-center justify-between px-6 border-b border-slate-100">
            <span class="text-lg font-bold text-slate-800">Menu</span>
            <button id="close-mobile-menu" class="text-slate-500 hover:text-red-500">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <nav class="flex-1 overflow-y-auto p-4 flex flex-col gap-4">
            <?php if (isLoggedIn()): ?>
                <div class="flex items-center gap-3 px-2 py-2 bg-slate-50 rounded-lg">
                    <div class="size-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold">
                        <?php echo strtoupper(substr($_SESSION['username'] ?? 'U', 0, 1)); ?>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800"><?php echo sanitize($_SESSION['username'] ?? 'User'); ?></p>
                        <p class="text-xs text-slate-500">Logged in</p>
                    </div>
                </div>
                <a class="flex items-center gap-3 px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors" href="../buyer_dashboard/">
                    <span class="material-symbols-outlined">dashboard</span> Dashboard
                </a>
            <?php else: ?>
                <a class="flex items-center gap-3 px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors" href="../login_page_-_onlinemarket.ng/">
                    <span class="material-symbols-outlined">login</span> Login
                </a>
                <a class="flex items-center gap-3 px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors" href="../register_page_-_onlinemarket.ng/">
                    <span class="material-symbols-outlined">person_add</span> Register
                </a>
            <?php endif; ?>

            <div class="border-t border-slate-100 my-1"></div>

            <a class="flex items-center gap-3 px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors" href="../post_new_ad_form/">
                <span class="material-symbols-outlined">add_circle</span> Post Ad
            </a>
            <a class="flex items-center gap-3 px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors" href="../category_listing_page_-_onlinemarket.ng/">
                <span class="material-symbols-outlined">category</span> Categories
            </a>

            <?php if (isLoggedIn()): ?>
                <div class="border-t border-slate-100 my-1"></div>
                <a class="flex items-center gap-3 px-4 py-2 rounded-lg text-red-600 hover:bg-red-50 transition-colors" href="../core/logout.php">
                    <span class="material-symbols-outlined">logout</span> Logout
                </a>
            <?php endif; ?>
        </nav>
    </aside>

    <script>
        // Mobile Menu Logic
        const mobileBtn = document.getElementById('mobile-menu-btn');
        const closeBtn = document.getElementById('close-mobile-menu');
        const sidebar = document.getElementById('mobile-sidebar');
        const overlay = document.getElementById('mobile-menu-overlay');

        function toggleMenu() {
            const isClosed = sidebar.classList.contains('translate-x-full');
            if (isClosed) {
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
        if (closeBtn) closeBtn.addEventListener('click', toggleMenu);
        if (overlay) overlay.addEventListener('click', toggleMenu);
    </script>
    <main class="flex flex-col min-h-screen">
        <!-- Hero Section -->
        <section class="relative bg-background-light pt-8 pb-12 lg:pt-16 lg:pb-24 px-4 sm:px-6 lg:px-8">
            <!-- Background Decoration -->
            <div class="absolute inset-0 z-0 overflow-hidden">
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[500px] bg-gradient-to-b from-blue-50 to-transparent opacity-60"></div>
                <div class="absolute top-20 right-0 w-96 h-96 bg-primary/5 rounded-full blur-3xl"></div>
                <div class="absolute top-40 left-0 w-72 h-72 bg-purple-500/5 rounded-full blur-3xl"></div>
            </div>
            <div class="relative z-10 max-w-[960px] mx-auto text-center flex flex-col items-center">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-[#0e121b] tracking-tight mb-4">
                    Find anything in <span class="text-primary relative inline-block">Nigeria
                        <svg class="absolute w-full h-3 -bottom-1 left-0 text-primary/20" preserveaspectratio="none" viewbox="0 0 100 10">
                            <path d="M0 5 Q 50 10 100 5" fill="none" stroke="currentColor" stroke-width="8"></path>
                        </svg>
                    </span>
                </h1>
                <p class="text-slate-500 text-lg md:text-xl max-w-2xl mb-10 leading-relaxed">
                    The trusted marketplace to buy and sell everything from cars and property to jobs and services.
                </p>
                <!-- Complex Search Bar -->
                <form action="../search_results_page_-_onlinemarket.ng/" method="GET" class="w-full bg-white p-2 rounded-2xl shadow-soft flex flex-col md:flex-row divide-y md:divide-y-0 md:divide-x divide-slate-100 border border-slate-200">
                    <!-- Keyword -->
                    <div class="flex-1 flex items-center px-4 py-3 md:py-2">
                        <span class="material-symbols-outlined text-slate-400 mr-3">search</span>
                        <div class="flex flex-col items-start w-full">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">What?</label>
                            <input name="q" class="w-full text-sm font-medium text-slate-800 placeholder:text-slate-400 border-none p-0 focus:ring-0 bg-transparent" placeholder="I am looking for..." type="text" />
                        </div>
                    </div>
                    <!-- Category -->
                    <div class="flex-1 flex items-center px-4 py-3 md:py-2">
                        <span class="material-symbols-outlined text-slate-400 mr-3">grid_view</span>
                        <div class="flex flex-col items-start w-full">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Category</label>
                            <select name="category" class="w-full text-sm font-medium text-slate-800 border-none p-0 focus:ring-0 bg-transparent cursor-pointer">
                                <option value="">All Categories</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['slug']; ?>"><?php echo $cat['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <!-- Location -->
                    <div class="flex-1 flex items-center px-4 py-3 md:py-2">
                        <span class="material-symbols-outlined text-slate-400 mr-3">location_on</span>
                        <div class="flex flex-col items-start w-full">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Where?</label>
                            <select name="location" class="w-full text-sm font-medium text-slate-800 border-none p-0 focus:ring-0 bg-transparent cursor-pointer">
                                <option value="">Whole Nigeria</option>
                                <option value="lagos">Lagos</option>
                                <option value="abuja">Abuja</option>
                                <option value="rivers">Port Harcourt</option>
                            </select>
                        </div>
                    </div>
                    <!-- Button -->
                    <div class="p-2 md:pl-4">
                        <button type="submit" class="w-full md:w-auto h-full min-h-[48px] px-8 bg-primary hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-primary/25 transition-all flex items-center justify-center gap-2">
                            Search
                        </button>
                    </div>
                </form>
                <!-- Popular Tags -->
                <div class="mt-6 flex flex-wrap justify-center gap-2">
                    <span class="text-sm text-slate-500 font-medium mr-1">Popular:</span>
                    <a class="px-3 py-1 bg-white border border-slate-200 rounded-full text-xs font-medium text-slate-600 hover:border-primary hover:text-primary transition-colors" href="#">iPhone 14</a>
                    <a class="px-3 py-1 bg-white border border-slate-200 rounded-full text-xs font-medium text-slate-600 hover:border-primary hover:text-primary transition-colors" href="#">Toyota Camry</a>
                    <a class="px-3 py-1 bg-white border border-slate-200 rounded-full text-xs font-medium text-slate-600 hover:border-primary hover:text-primary transition-colors" href="#">Lekki Apartments</a>
                    <a class="px-3 py-1 bg-white border border-slate-200 rounded-full text-xs font-medium text-slate-600 hover:border-primary hover:text-primary transition-colors" href="#">Generators</a>
                </div>
            </div>
        </section>
        <!-- Categories Grid -->
        <section class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-[#0e121b]">Browse Categories</h3>
                <a class="text-sm font-medium text-primary hover:underline" href="../category_listing_page_-_onlinemarket.ng/">View All</a>
            </div>
            <div class="grid grid-cols-4 sm:grid-cols-4 lg:grid-cols-8 gap-2 md:gap-4">
                <!-- Category Items -->
                <?php foreach ($categories as $cat): ?>
                    <a class="group flex flex-col items-center justify-center gap-1.5 md:gap-3 p-2 md:p-4 bg-white rounded-xl border border-slate-100 shadow-sm hover:shadow-hover hover:border-primary/30 transition-all duration-300" href="../category_listing_page_-_onlinemarket.ng/?cat=<?php echo $cat['slug']; ?>">
                        <div class="w-8 h-8 md:w-12 md:h-12 rounded-full bg-blue-50 text-primary flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-colors">
                            <span class="material-symbols-outlined text-[18px] md:text-[24px]"><?php echo $cat['icon']; ?></span>
                        </div>
                        <span class="text-[10px] md:text-sm font-semibold text-slate-700 group-hover:text-primary text-center truncate w-full"><?php echo $cat['name']; ?></span>
                    </a>
                <?php endforeach; ?>
                <a class="group flex flex-col items-center justify-center gap-1.5 md:gap-3 p-2 md:p-4 bg-white rounded-xl border border-slate-100 shadow-sm hover:shadow-hover hover:border-primary/30 transition-all duration-300" href="../category_listing_page_-_onlinemarket.ng/">
                    <div class="w-8 h-8 md:w-12 md:h-12 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center group-hover:bg-slate-600 group-hover:text-white transition-colors">
                        <span class="material-symbols-outlined text-[18px] md:text-[24px]">more_horiz</span>
                    </div>
                    <span class="text-[10px] md:text-sm font-semibold text-slate-700 group-hover:text-slate-600 text-center">More</span>
                </a>
            </div>
        </section>
        <!-- Featured/Trending Ads -->
        <section class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full">
            <div class="flex items-center gap-2 mb-6">
                <span class="material-symbols-outlined text-orange-500">local_fire_department</span>
                <h3 class="text-xl font-bold text-[#0e121b]">Trending Ads</h3>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6">
                <?php foreach ($trending_ads as $item): ?>
                    <!-- Ad Card -->
                    <div class="group bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-hover transition-all duration-300 flex flex-col h-full relative">
                        <div class="absolute top-2 left-2 z-10 bg-yellow-400 text-yellow-900 text-[10px] font-bold px-1.5 py-0.5 rounded uppercase tracking-wide">Promoted</div>
                        <button class="absolute top-2 right-2 z-10 w-7 h-7 rounded-full bg-white/80 backdrop-blur-sm flex items-center justify-center text-slate-400 hover:text-red-500 transition-colors">
                            <span class="material-symbols-outlined text-[18px]">favorite</span>
                        </button>
                        <a href="../single_ad_view_page_-_onlinemarket.ng/?id=<?php echo $item['id']; ?>" class="relative aspect-[4/3] overflow-hidden bg-slate-100 block">
                            <img alt="<?php echo sanitize($item['title']); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" src="<?php echo $item['image_url']; ?>" />
                        </a>
                        <div class="p-3 flex flex-col flex-1">
                            <div class="flex justify-between items-start mb-1">
                                <a href="../single_ad_view_page_-_onlinemarket.ng/?id=<?php echo $item['id']; ?>">
                                    <h4 class="font-semibold text-sm md:text-base text-slate-800 line-clamp-2 group-hover:text-primary transition-colors leading-snug"><?php echo sanitize($item['title']); ?></h4>
                                </a>
                            </div>
                            <div class="text-primary font-bold text-base md:text-lg mb-2">₦ <?php echo number_format($item['price']); ?></div>
                            <div class="mt-auto flex items-center justify-between text-[10px] md:text-xs text-slate-500 pt-2 border-t border-slate-100">
                                <div class="flex items-center gap-1 truncate max-w-[60%]">
                                    <span class="material-symbols-outlined text-[12px] md:text-[14px]">location_on</span>
                                    <span class="truncate"><?php echo ucfirst($item['location_name']); ?></span>
                                </div>
                                <span class="whitespace-nowrap"><?php echo time_elapsed_string($item['created_at']); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <!-- Fresh Recommendations -->
        <section class="flex-grow bg-white border-t border-slate-100 py-10">
            <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 w-full">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-[#0e121b]">Fresh Recommendations</h3>
                    <div class="flex gap-2">
                        <button class="p-2 rounded-full border border-slate-200 hover:bg-slate-50 text-slate-500 disabled:opacity-50">
                            <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                        </button>
                        <button class="p-2 rounded-full border border-slate-200 hover:bg-slate-50 text-slate-500">
                            <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                        </button>
                    </div>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-3 md:gap-5">
                    <?php foreach ($fresh_ads as $item): ?>
                        <!-- Standard Card -->
                        <div class="bg-white rounded-lg border border-slate-200 hover:shadow-lg transition-shadow duration-200 flex flex-col overflow-hidden">
                            <a href="../single_ad_view_page_-_onlinemarket.ng/?id=<?php echo $item['id']; ?>" class="relative h-32 md:h-40 bg-slate-100 block">
                                <img alt="<?php echo sanitize($item['title']); ?>" class="w-full h-full object-cover" src="<?php echo $item['image_url']; ?>" />
                                <div class="absolute bottom-2 left-2 bg-black/60 text-white text-[10px] px-1.5 py-0.5 rounded"><?php echo ucfirst($item['condition_state'] ?? 'Used'); ?></div>
                            </a>
                            <div class="p-2 md:p-3 flex flex-col flex-1">
                                <a href="../single_ad_view_page_-_onlinemarket.ng/?id=<?php echo $item['id']; ?>">
                                    <h5 class="text-xs md:text-sm font-medium text-slate-900 line-clamp-2 mb-1 leading-snug"><?php echo sanitize($item['title']); ?></h5>
                                </a>
                                <div class="text-primary font-bold text-sm md:text-base mb-1 md:mb-2">₦ <?php echo number_format($item['price']); ?></div>
                                <div class="mt-auto flex items-center text-[10px] md:text-[11px] text-slate-400 gap-1 truncate">
                                    <span class="material-symbols-outlined text-[12px]">location_on</span>
                                    <span class="truncate"><?php echo ucfirst($item['location_name']); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="mt-10 flex justify-center">
                    <button class="border border-primary text-primary hover:bg-primary hover:text-white font-semibold py-3 px-8 rounded-lg transition-colors duration-200">
                        Load More Ads
                    </button>
                </div>
            </div>
        </section>
        <!-- Sticky Bottom Mobile Navigation -->
        <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 px-4 py-2 z-50 flex justify-between items-center shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
            <a href="../home_page_-_onlinemarket.ng/" class="flex flex-col items-center gap-1 text-primary">
                <span class="material-symbols-outlined text-[24px]">home</span>
                <span class="text-[10px] font-medium">Home</span>
            </a>
            <a href="../category_listing_page_-_onlinemarket.ng/" class="flex flex-col items-center gap-1 text-slate-500 hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-[24px]">grid_view</span>
                <span class="text-[10px] font-medium">Categories</span>
            </a>
            <a href="../post_new_ad_form/" class="flex flex-col items-center gap-1 text-slate-500 hover:text-primary transition-colors -mt-6">
                <div class="bg-primary text-white rounded-full p-3 shadow-lg shadow-primary/30 border-4 border-white">
                    <span class="material-symbols-outlined text-[24px]">add</span>
                </div>
                <span class="text-[10px] font-medium">Sell</span>
            </a>
            <a href="<?php echo isLoggedIn() ? '../saved_ads_/_favorites_page/' : '../login_page_-_onlinemarket.ng/'; ?>" class="flex flex-col items-center gap-1 text-slate-500 hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-[24px]">favorite</span>
                <span class="text-[10px] font-medium">Saved</span>
            </a>
            <a href="<?php echo isLoggedIn() ? '../buyer_dashboard/' : '../login_page_-_onlinemarket.ng/'; ?>" class="flex flex-col items-center gap-1 text-slate-500 hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-[24px]">person</span>
                <span class="text-[10px] font-medium">Profile</span>
            </a>
        </div>
    </main>
    <!-- Footer -->
    <footer class="bg-[#111621] text-slate-300 py-12 border-t border-slate-800 mb-16 md:mb-0">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <!-- Col 1 -->
                <div class="col-span-1 md:col-span-1">
                    <div class="flex items-center gap-2 mb-4 text-white">
                        <div class="size-6 rounded bg-primary flex items-center justify-center">
                            <span class="material-symbols-outlined text-[16px]">shopping_bag</span>
                        </div>
                        <h2 class="text-lg font-bold">Onlinemarket.ng</h2>
                    </div>
                    <p class="text-sm text-slate-400 mb-6 leading-relaxed">
                        Nigeria's fastest-growing online marketplace. Buy and sell easily, safely, and quickly.
                    </p>
                    <div class="flex gap-4">
                        <a class="text-slate-400 hover:text-white transition-colors" href="#"><span class="material-symbols-outlined">thumb_up</span></a>
                        <a class="text-slate-400 hover:text-white transition-colors" href="#"><span class="material-symbols-outlined">photo_camera</span></a>
                        <a class="text-slate-400 hover:text-white transition-colors" href="#"><span class="material-symbols-outlined">share</span></a>
                    </div>
                </div>
                <!-- Col 2 -->
                <div>
                    <h4 class="text-white font-bold mb-4">About Us</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a class="hover:text-primary transition-colors" href="#">About Onlinemarket.ng</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">Terms &amp; Conditions</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">Privacy Policy</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">Billing Policy</a></li>
                    </ul>
                </div>
                <!-- Col 3 -->
                <div>
                    <h4 class="text-white font-bold mb-4">Support</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a class="hover:text-primary transition-colors" href="#">Safety Tips</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">Contact Us</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">FAQ</a></li>
                    </ul>
                </div>
                <!-- Col 4 -->
                <div>
                    <h4 class="text-white font-bold mb-4">Download Our App</h4>
                    <div class="flex flex-col gap-3">
                        <button class="flex items-center gap-3 bg-slate-800 hover:bg-slate-700 px-4 py-2 rounded-lg transition-colors border border-slate-700">
                            <span class="material-symbols-outlined text-[24px]">android</span>
                            <div class="text-left">
                                <div class="text-[10px] uppercase">Get it on</div>
                                <div class="text-sm font-bold text-white">Google Play</div>
                            </div>
                        </button>
                        <button class="flex items-center gap-3 bg-slate-800 hover:bg-slate-700 px-4 py-2 rounded-lg transition-colors border border-slate-700">
                            <span class="material-symbols-outlined text-[24px]">ios</span>
                            <div class="text-left">
                                <div class="text-[10px] uppercase">Download on the</div>
                                <div class="text-sm font-bold text-white">App Store</div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
            <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-xs text-slate-500 text-center md:text-left">
                    © 2023 Onlinemarket.ng. All rights reserved.
                </p>
                <div class="flex gap-4 text-xs text-slate-500">
                    <a class="hover:text-white" href="#">Sitemap</a>
                    <a class="hover:text-white" href="#">Cookie Settings</a>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>