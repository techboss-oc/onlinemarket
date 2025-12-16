<?php
require_once '../core/init.php';

$adModel = new Ad();
$categoryModel = new Category();
$locationModel = new Location();

$query = $_GET['q'] ?? '';
$cat_slug = $_GET['category'] ?? '';
$loc_slug = $_GET['location'] ?? '';

$ads = $adModel->search($query, $cat_slug, $loc_slug);
$categories = $categoryModel->getAll();
$locations = $locationModel->getAll();

// Filter display logic
$display_query = $query ? sanitize($query) : 'All Items';
$display_location = $loc_slug ? ucfirst($loc_slug) : 'Nigeria';
?>
<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Search Results - Onlinemarket.ng</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet" />
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
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
</head>

<body class="bg-background-light dark:bg-background-dark text-[#0e121b] dark:text-white font-display min-h-screen flex flex-col">
    <!-- Top Navigation Bar -->
    <header class="sticky top-0 z-50 w-full bg-white dark:bg-[#1a202c] border-b border-[#e7ebf3] dark:border-[#2d3748] shadow-sm backdrop-blur-md bg-opacity-90">
        <div class="px-4 md:px-10 py-3 flex items-center justify-between gap-4">
            <!-- Logo & Search -->
            <div class="flex items-center gap-8 flex-1">
                <a href="../home_page_-_onlinemarket.ng/" class="flex items-center gap-2 text-primary">
                    <div class="size-8 flex items-center justify-center rounded-lg bg-primary/10">
                        <span class="material-symbols-outlined text-primary">shopping_bag</span>
                    </div>
                    <h2 class="text-[#0e121b] dark:text-white text-xl font-bold leading-tight tracking-[-0.015em]">Onlinemarket.ng</h2>
                </a>
                <!-- Search Bar -->
                <div class="hidden md:flex max-w-[500px] w-full items-center">
                    <form action="" method="GET" class="flex w-full items-center rounded-lg bg-[#e7ebf3] dark:bg-[#2d3748] h-10 overflow-hidden focus-within:ring-2 focus-within:ring-primary/50 transition-all">
                        <div class="pl-3 flex items-center justify-center text-[#4e6797] dark:text-gray-400">
                            <span class="material-symbols-outlined text-[20px]">search</span>
                        </div>
                        <input name="q" class="w-full bg-transparent border-none text-sm text-[#0e121b] dark:text-white placeholder:text-[#4e6797] focus:ring-0 h-full px-3" placeholder="Search for anything..." value="<?php echo sanitize($query); ?>" />
                        <div class="h-6 w-px bg-gray-300 dark:bg-gray-600 mx-2"></div>
                        <div class="pl-2 flex items-center justify-center text-[#4e6797] dark:text-gray-400">
                            <span class="material-symbols-outlined text-[20px]">location_on</span>
                        </div>
                        <select name="location" class="w-1/3 bg-transparent border-none text-sm text-[#0e121b] dark:text-white placeholder:text-[#4e6797] focus:ring-0 h-full px-3 cursor-pointer">
                            <option value="">Anywhere</option>
                            <?php foreach ($locations as $loc): ?>
                                <option value="<?php echo $loc['slug']; ?>" <?php echo ($loc_slug == $loc['slug']) ? 'selected' : ''; ?>><?php echo $loc['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="bg-primary hover:bg-blue-700 text-white h-full px-6 text-sm font-medium transition-colors">
                            Search
                        </button>
                    </form>
                </div>
            </div>
            <!-- Right Actions -->
            <div class="flex items-center gap-4 md:gap-6">
                <div class="hidden lg:flex items-center gap-6">
                    <?php if (isLoggedIn()): ?>
                        <a class="text-[#0e121b] dark:text-gray-200 text-sm font-medium hover:text-primary transition-colors flex flex-col items-center gap-1" href="../buyer_chat_/_messaging_page/">
                            <span class="material-symbols-outlined">chat</span>
                            <span class="text-[10px] uppercase tracking-wide">Messages</span>
                        </a>
                        <a class="text-[#0e121b] dark:text-gray-200 text-sm font-medium hover:text-primary transition-colors flex flex-col items-center gap-1" href="../saved_ads_/_favorites_page/">
                            <span class="material-symbols-outlined">favorite</span>
                            <span class="text-[10px] uppercase tracking-wide">Saved</span>
                        </a>
                        <a class="text-[#0e121b] dark:text-gray-200 text-sm font-medium hover:text-primary transition-colors flex flex-col items-center gap-1" href="../buyer_dashboard/">
                            <span class="material-symbols-outlined">person</span>
                            <span class="text-[10px] uppercase tracking-wide">Profile</span>
                        </a>
                    <?php else: ?>
                        <a class="text-[#0e121b] dark:text-gray-200 text-sm font-medium hover:text-primary transition-colors" href="../login_page_-_onlinemarket.ng/">Login</a>
                    <?php endif; ?>
                </div>
                <a href="../post_new_ad_form/" class="flex items-center justify-center rounded-lg h-10 px-5 bg-primary hover:bg-blue-700 text-white text-sm font-bold shadow-md hover:shadow-lg transition-all transform active:scale-95">
                    Post Ad
                </a>
            </div>
        </div>
    </header>
    <!-- Main Content -->
    <main class="flex-grow w-full max-w-[1440px] mx-auto px-4 md:px-6 lg:px-8 py-6">
        <!-- Breadcrumbs -->
        <div class="flex flex-wrap items-center gap-2 text-sm text-[#4e6797] mb-6">
            <a class="hover:text-primary transition-colors" href="../home_page_-_onlinemarket.ng/">Home</a>
            <span class="material-symbols-outlined text-[16px]">chevron_right</span>
            <span class="text-[#0e121b] dark:text-white font-medium">Search Results</span>
        </div>
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Filters -->
            <aside class="w-full lg:w-64 flex-shrink-0 space-y-8 hidden lg:block">
                <!-- Categories -->
                <div class="bg-white dark:bg-[#1a202c] p-5 rounded-xl border border-[#e7ebf3] dark:border-[#2d3748] shadow-sm">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-[#4e6797] mb-4 flex items-center justify-between">
                        Categories
                        <span class="material-symbols-outlined text-[20px] cursor-pointer">expand_less</span>
                    </h3>
                    <ul class="space-y-3">
                        <?php foreach ($categories as $cat): ?>
                            <li>
                                <a class="flex items-center justify-between text-[#0e121b] dark:text-gray-300 text-sm hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800 p-2 rounded-lg transition-colors <?php echo ($cat_slug == $cat['slug']) ? 'bg-primary/5 text-primary' : ''; ?>" href="?category=<?php echo $cat['slug']; ?>&q=<?php echo urlencode($query); ?>&location=<?php echo urlencode($loc_slug); ?>">
                                    <span><?php echo $cat['name']; ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <!-- Banner Ad (Sidebar) -->
                <div class="relative overflow-hidden rounded-xl h-64 shadow-md group cursor-pointer">
                    <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBjOMwu7vlN4SvtFyTYsxSiuSZnT3bMx5XucapbLTL6BEdL9A0hqmpQXZdM_GYOCg32V6T25N7yTo5sT9-dJ8_cvPwcRhPiiME1M-wjZP3-SHXFaCd2qxBVqa7aCcps3szEF7Z49BkaBSL-yootxAlUgyb9FStRUPoslrmOWz0wlJGB2renAIQxyR1CCnqfseN9FggGRzBANzltuqOW5-SlDMtS3R_ecp-76z6nEXXd3BKF0JRC6bEJ10AAJyNXGPGA5MDn-oJztYA" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex flex-col justify-end p-5">
                        <span class="bg-yellow-400 text-black text-[10px] font-bold px-2 py-0.5 rounded uppercase w-fit mb-2">Ad</span>
                        <h4 class="text-white font-bold text-lg leading-tight">Boost your sales today!</h4>
                        <p class="text-gray-200 text-xs mt-1">Get 5x more visibility with Premium.</p>
                    </div>
                </div>
            </aside>
            <!-- Results Column -->
            <div class="flex-1 flex flex-col min-w-0">
                <!-- Page Header & Controls -->
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
                    <div>
                        <h1 class="text-[#0e121b] dark:text-white text-2xl md:text-3xl font-bold tracking-tight mb-2">
                            <?php echo $display_query; ?>
                        </h1>
                        <p class="text-[#4e6797] text-sm"><?php echo count($ads); ?> results found in <?php echo $display_location; ?></p>
                    </div>
                </div>

                <!-- Product Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">

                    <?php if (empty($ads)): ?>
                        <div class="col-span-full text-center py-20 bg-white rounded-xl border border-gray-100">
                            <span class="material-symbols-outlined text-6xl text-gray-300 mb-4">search_off</span>
                            <h3 class="text-xl font-bold text-gray-700">No results found</h3>
                            <p class="text-gray-500 mt-2">Try adjusting your search filters.</p>
                            <a href="../search_results_page_-_onlinemarket.ng/" class="mt-4 inline-block text-primary font-bold hover:underline">Clear Filters</a>
                        </div>
                    <?php else: ?>
                        <?php foreach ($ads as $item): ?>
                            <!-- Card -->
                            <div class="group bg-white dark:bg-[#1a202c] rounded-xl border border-[#e7ebf3] dark:border-[#2d3748] overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-300 relative">
                                <?php if ($item['is_featured']): ?>
                                    <div class="absolute top-3 left-3 z-10">
                                        <span class="bg-yellow-400 text-[#0e121b] text-[10px] font-bold px-2 py-1 rounded shadow-sm uppercase tracking-wide">Promoted</span>
                                    </div>
                                <?php endif; ?>
                                <button class="absolute top-3 right-3 z-10 p-2 rounded-full bg-white/80 dark:bg-black/50 hover:bg-white text-gray-400 hover:text-red-500 transition-colors backdrop-blur-sm">
                                    <span class="material-symbols-outlined text-[20px] block">favorite</span>
                                </button>
                                <a href="../single_ad_view_page_-_onlinemarket.ng/?id=<?php echo $item['id']; ?>" class="relative aspect-[4/3] overflow-hidden bg-gray-100 block">
                                    <img alt="<?php echo sanitize($item['title']); ?>" class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500" src="<?php echo $item['image_url']; ?>" />
                                </a>
                                <div class="p-4">
                                    <div class="flex justify-between items-start mb-1">
                                        <a href="../single_ad_view_page_-_onlinemarket.ng/?id=<?php echo $item['id']; ?>">
                                            <h3 class="text-[#0e121b] dark:text-white font-semibold text-base line-clamp-1 group-hover:text-primary transition-colors"><?php echo sanitize($item['title']); ?></h3>
                                        </a>
                                    </div>
                                    <p class="text-primary font-bold text-lg mb-3">₦<?php echo number_format($item['price']); ?></p>
                                    <div class="flex items-center gap-4 text-xs text-[#4e6797] dark:text-gray-400 border-t border-gray-100 dark:border-gray-800 pt-3">
                                        <div class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[16px]">location_on</span>
                                            <?php echo ucfirst($item['location_name']); ?>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[16px]">schedule</span>
                                            <?php echo time_elapsed_string($item['created_at']); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>

                <!-- Pagination (Static for demo) -->
                <?php if (!empty($ads) && count($ads) > 20): ?>
                    <div class="flex justify-center items-center gap-2 mb-10">
                        <button class="size-10 flex items-center justify-center rounded-lg border border-[#e7ebf3] dark:border-gray-700 text-[#4e6797] hover:bg-gray-50 dark:hover:bg-gray-800 disabled:opacity-50">
                            <span class="material-symbols-outlined">chevron_left</span>
                        </button>
                        <button class="size-10 flex items-center justify-center rounded-lg bg-primary text-white font-semibold shadow-md shadow-blue-200 dark:shadow-none">1</button>
                        <button class="size-10 flex items-center justify-center rounded-lg border border-[#e7ebf3] dark:border-gray-700 text-[#4e6797] hover:bg-gray-50 dark:hover:bg-gray-800">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </button>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </main>
    <!-- Footer Simplified -->
    <footer class="bg-white dark:bg-[#1a202c] border-t border-[#e7ebf3] dark:border-[#2d3748] py-8 mt-auto">
        <div class="max-w-[1440px] mx-auto px-4 md:px-8 text-center text-[#4e6797] text-sm">
            <p>© 2023 Onlinemarket.ng. All rights reserved.</p>
        </div>
    </footer>
    <!-- Sticky Bottom Mobile Navigation -->
    <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white dark:bg-[#1a202c] border-t border-slate-200 dark:border-slate-800 px-4 py-2 z-50 flex justify-between items-center shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
        <a href="../home_page_-_onlinemarket.ng/" class="flex flex-col items-center gap-1 text-slate-500 dark:text-slate-400 hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-[24px]">home</span>
            <span class="text-[10px] font-medium">Home</span>
        </a>
        <a href="../search_results_page_-_onlinemarket.ng/" class="flex flex-col items-center gap-1 text-primary">
            <span class="material-symbols-outlined text-[24px]">search</span>
            <span class="text-[10px] font-medium">Search</span>
        </a>
        <a href="../post_new_ad_form/" class="flex flex-col items-center gap-1 text-slate-500 hover:text-primary transition-colors -mt-6">
            <div class="bg-primary text-white rounded-full p-3 shadow-lg shadow-primary/30 border-4 border-white dark:border-[#1a202c]">
                <span class="material-symbols-outlined text-[24px]">add</span>
            </div>
            <span class="text-[10px] font-medium">Sell</span>
        </a>
        <a href="<?php echo isLoggedIn() ? '../saved_ads_/_favorites_page/' : '../login_page_-_onlinemarket.ng/'; ?>" class="flex flex-col items-center gap-1 text-slate-500 dark:text-slate-400 hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-[24px]">favorite</span>
            <span class="text-[10px] font-medium">Saved</span>
        </a>
        <a href="<?php echo isLoggedIn() ? '../buyer_dashboard/' : '../login_page_-_onlinemarket.ng/'; ?>" class="flex flex-col items-center gap-1 text-slate-500 dark:text-slate-400 hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-[24px]">person</span>
            <span class="text-[10px] font-medium">Profile</span>
        </a>
    </div>
</body>

</html>