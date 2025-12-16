<?php
require_once '../core/init.php';

$adModel = new Ad();
$ad_id = $_GET['id'] ?? 0;
$ad = $adModel->getById($ad_id);

if (!$ad) {
    redirect('../home_page_-_onlinemarket.ng/');
}

// Increment view count
$adModel->incrementViews($ad_id);

$similar_ads = $adModel->getSimilar($ad['category_id'], $ad['id']);
?>
<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title><?php echo sanitize($ad['title']); ?> - Onlinemarket.ng</title>
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- Tailwind Configuration -->
    <script>
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

<body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-white antialiased transition-colors duration-200">
    <!-- Top Navigation -->
    <header class="sticky top-0 z-50 w-full bg-white/90 dark:bg-[#111621]/90 backdrop-blur-md border-b border-gray-200 dark:border-gray-800">
        <div class="px-4 md:px-10 py-3 flex items-center justify-between max-w-[1400px] mx-auto">
            <div class="flex items-center gap-8">
                <a href="../home_page_-_onlinemarket.ng/" class="flex items-center gap-2 text-slate-900 dark:text-white group cursor-pointer">
                    <span class="material-symbols-outlined text-primary text-3xl group-hover:rotate-12 transition-transform">shopping_bag</span>
                    <h2 class="text-xl font-bold tracking-tight">Onlinemarket.ng</h2>
                </a>
                <!-- Search Bar -->
                <div class="hidden md:flex items-center w-full max-w-md h-10 rounded-lg bg-gray-100 dark:bg-gray-800 border-none overflow-hidden focus-within:ring-2 focus-within:ring-primary/20 transition-all">
                    <form action="../search_results_page_-_onlinemarket.ng/" class="w-full flex h-full">
                        <div class="px-3 text-slate-400 flex items-center">
                            <span class="material-symbols-outlined text-[20px]">search</span>
                        </div>
                        <input name="q" class="w-full bg-transparent border-none outline-none text-sm placeholder-slate-400 text-slate-900 dark:text-white focus:ring-0" placeholder="I am looking for..." type="text" />
                    </form>
                </div>
            </div>
            <div class="flex items-center gap-6">
                <nav class="hidden lg:flex gap-6">
                    <a class="text-sm font-medium hover:text-primary transition-colors" href="../home_page_-_onlinemarket.ng/">Home</a>
                    <a class="text-sm font-medium hover:text-primary transition-colors" href="../category_listing_page_-_onlinemarket.ng/">Categories</a>
                </nav>
                <div class="flex items-center gap-4">
                    <a href="../post_new_ad_form/" class="bg-primary hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-bold transition-colors shadow-sm shadow-blue-500/30">Post Ad</a>
                </div>
            </div>
        </div>
    </header>
    <main class="max-w-7xl mx-auto px-4 md:px-8 py-6">
        <!-- Breadcrumbs & Actions Row -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <!-- Breadcrumbs -->
            <nav class="flex flex-wrap items-center text-sm text-slate-500 dark:text-slate-400">
                <a class="hover:text-primary transition-colors" href="../home_page_-_onlinemarket.ng/">Home</a>
                <span class="mx-2 material-symbols-outlined text-[16px]">chevron_right</span>
                <a class="hover:text-primary transition-colors" href="../category_listing_page_-_onlinemarket.ng/"><?php echo sanitize($ad['category_name']); ?></a>
                <span class="mx-2 material-symbols-outlined text-[16px]">chevron_right</span>
                <span class="text-slate-900 dark:text-white font-medium"><?php echo sanitize($ad['title']); ?></span>
            </nav>
            <!-- Actions (Share/Like) -->
            <div class="flex gap-3">
                <button class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-all text-sm font-medium text-slate-700 dark:text-slate-200 shadow-sm">
                    <span class="material-symbols-outlined text-[20px]">share</span> Share
                </button>
                <button class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-all text-sm font-medium text-slate-700 dark:text-slate-200 shadow-sm group">
                    <span class="material-symbols-outlined text-[20px] group-hover:text-red-500 transition-colors">favorite</span> Save
                </button>
            </div>
        </div>
        <!-- Main Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Column: Media & Details (8 cols) -->
            <div class="lg:col-span-8 flex flex-col gap-6">
                <!-- Image Gallery -->
                <div class="bg-white dark:bg-gray-800 rounded-xl p-3 shadow-sm border border-gray-100 dark:border-gray-700">
                    <!-- Main Image Frame -->
                    <?php
                    $main_image = !empty($ad['images']) ? $ad['images'][0] : 'https://via.placeholder.com/800x600?text=No+Image';
                    ?>
                    <div class="aspect-video w-full bg-gray-100 dark:bg-gray-900 rounded-lg overflow-hidden relative group mb-3">
                        <div class="absolute inset-0 bg-center bg-contain bg-no-repeat transition-transform duration-700 hover:scale-105 cursor-zoom-in" style="background-image: url('<?php echo $main_image; ?>');"></div>
                    </div>
                    <!-- Thumbnails Row -->
                    <?php if (!empty($ad['images']) && count($ad['images']) > 1): ?>
                        <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-hide snap-x">
                            <?php foreach ($ad['images'] as $index => $img): ?>
                                <div class="snap-start w-24 h-24 flex-shrink-0 rounded-lg border-2 <?php echo $index === 0 ? 'border-primary' : 'border-transparent hover:border-gray-300'; ?> cursor-pointer bg-cover bg-center transition-all" style="background-image: url('<?php echo $img; ?>');"></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- Product Info & Specs -->
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 md:p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="mb-6">
                        <div class="flex flex-wrap items-center gap-y-2 gap-x-3 text-sm text-slate-500 mb-3">
                            <span class="bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 px-2.5 py-0.5 rounded text-xs font-bold uppercase tracking-wider border border-green-200 dark:border-green-800"><?php echo ucfirst($ad['condition_state'] ?? 'Used'); ?></span>
                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                            <span>Posted <?php echo time_elapsed_string($ad['created_at']); ?></span>
                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                            <span class="flex items-center gap-1 text-slate-600 dark:text-slate-400"><span class="material-symbols-outlined text-[16px]">location_on</span> <?php echo ucfirst($ad['location_name']); ?></span>
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight text-slate-900 dark:text-white mb-3 leading-tight"><?php echo sanitize($ad['title']); ?></h1>
                        <div class="flex items-center gap-4 text-sm text-slate-500">
                            <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[18px]">visibility</span> <?php echo $ad['views_count']; ?> views</span>
                            <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[18px]">bookmark</span> 0 saves</span>
                        </div>
                    </div>
                    <div class="h-px bg-gray-100 dark:bg-gray-700 my-8"></div>
                    <!-- Specs Grid -->
                    <h3 class="text-lg font-bold mb-5 text-slate-900 dark:text-white">Specifications</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-y-6 gap-x-4 mb-10">
                        <div class="flex flex-col gap-1">
                            <p class="text-xs text-slate-500 uppercase tracking-wide font-semibold">Condition</p>
                            <p class="font-medium text-slate-900 dark:text-slate-200"><?php echo ucfirst($ad['condition_state'] ?? 'Used'); ?></p>
                        </div>
                        <?php if (!empty($ad['brand'])): ?>
                            <div class="flex flex-col gap-1">
                                <p class="text-xs text-slate-500 uppercase tracking-wide font-semibold">Brand</p>
                                <p class="font-medium text-slate-900 dark:text-slate-200"><?php echo sanitize($ad['brand']); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <!-- Description -->
                    <h3 class="text-lg font-bold mb-3 text-slate-900 dark:text-white">Description</h3>
                    <div class="prose prose-slate dark:prose-invert max-w-none text-slate-600 dark:text-slate-300 leading-relaxed whitespace-pre-line">
                        <?php echo sanitize($ad['description']); ?>
                    </div>
                </div>
            </div>
            <!-- Right Column: Sidebar (4 cols) -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Price Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                    <p class="text-sm text-slate-500 font-medium mb-1">Asking Price</p>
                    <h2 class="text-4xl font-black text-primary tracking-tight">₦ <?php echo number_format($ad['price']); ?></h2>
                    <p class="text-xs text-green-600 font-semibold bg-green-50 dark:bg-green-900/20 inline-block px-2 py-1 rounded mt-3">Negotiable</p>
                </div>
                <!-- Seller Card (Sticky Container) -->
                <div class="sticky top-24 space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden relative isolate">
                        <!-- Subtle Glass effect decoration -->
                        <div class="absolute -top-12 -right-12 w-40 h-40 bg-blue-50 dark:bg-blue-900/20 rounded-full blur-3xl -z-10"></div>
                        <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-primary to-blue-300"></div>
                        <div class="flex items-center gap-4 mb-6">
                            <div class="relative">
                                <div class="w-16 h-16 rounded-full bg-gray-200 bg-cover bg-center border-2 border-white dark:border-gray-700 shadow-md flex items-center justify-center font-bold text-xl text-gray-500">
                                    <?php echo strtoupper(substr($ad['seller_name'], 0, 1)); ?>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-1">
                                    <?php echo sanitize($ad['seller_name']); ?>
                                    <span class="material-symbols-outlined text-blue-500 text-[18px] filled" title="Verified Seller">verified</span>
                                </h3>
                                <p class="text-xs text-slate-500 font-medium uppercase tracking-wide mb-1">Joined <?php echo date('M Y', strtotime($ad['seller_joined'])); ?></p>
                            </div>
                        </div>
                        <div class="flex flex-col gap-3">
                            <button class="w-full bg-primary hover:bg-blue-600 active:scale-[0.98] text-white font-bold py-3.5 px-4 rounded-lg shadow-lg shadow-blue-500/30 transition-all flex items-center justify-center gap-2 group">
                                <span class="material-symbols-outlined group-hover:animate-bounce">call</span>
                                <?php echo sanitize($ad['seller_phone'] ?? 'Contact Seller'); ?>
                            </button>
                            <a href="../buyer_chat_/_messaging_page/?seller_id=<?php echo $ad['user_id']; ?>&ad_id=<?php echo $ad['id']; ?>" class="w-full bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 active:scale-[0.98] text-slate-900 dark:text-white font-bold py-3 px-4 rounded-lg transition-all flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-green-600">chat</span>
                                Start Chat
                            </a>
                        </div>
                    </div>
                    <!-- Safety Tips -->
                    <div class="bg-orange-50 dark:bg-orange-900/10 border border-orange-100 dark:border-orange-800/30 rounded-xl p-5 relative overflow-hidden">
                        <h4 class="font-bold text-orange-800 dark:text-orange-200 flex items-center gap-2 mb-3 relative z-10">
                            <span class="material-symbols-outlined filled">security</span> Safety Tips
                        </h4>
                        <ul class="text-sm text-slate-700 dark:text-slate-300 space-y-2 list-disc pl-4 relative z-10">
                            <li>Don't pay in advance, including for delivery.</li>
                            <li>Meet at a safe, public place.</li>
                            <li>Inspect the item before paying.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Similar Ads Section -->
        <div class="mt-20">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Similar Ads</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($similar_ads as $item): ?>
                    <!-- Ad Card -->
                    <div class="group bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                        <a href="?id=<?php echo $item['id']; ?>" class="block aspect-[4/3] bg-gray-200 bg-cover bg-center group-hover:scale-105 transition-transform duration-500 relative" style="background-image: url('<?php echo $item['image_url']; ?>');">
                        </a>
                        <div class="p-4">
                            <a href="?id=<?php echo $item['id']; ?>">
                                <h3 class="font-bold text-slate-900 dark:text-white truncate text-lg hover:text-primary transition-colors"><?php echo sanitize($item['title']); ?></h3>
                            </a>
                            <p class="text-primary font-bold mt-1 text-lg">₦ <?php echo number_format($item['price']); ?></p>
                            <div class="flex justify-between items-center mt-3 text-xs text-slate-400">
                                <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">location_on</span> <?php echo ucfirst($item['location_name']); ?></span>
                                <span><?php echo time_elapsed_string($item['created_at']); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
    <!-- Footer -->
    <footer class="mt-20 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 py-12">
        <div class="max-w-7xl mx-auto px-4 md:px-8 text-center text-slate-500 text-sm">
            <p>© 2023 Onlinemarket.ng. All rights reserved.</p>
        </div>
    </footer>
    <!-- Sticky Bottom Mobile Navigation -->
    <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white dark:bg-[#1a202c] border-t border-slate-200 dark:border-slate-800 px-4 py-2 z-50 flex justify-between items-center shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
        <a href="../home_page_-_onlinemarket.ng/" class="flex flex-col items-center gap-1 text-slate-500 dark:text-slate-400 hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-[24px]">home</span>
            <span class="text-[10px] font-medium">Home</span>
        </a>
        <a href="../search_results_page_-_onlinemarket.ng/" class="flex flex-col items-center gap-1 text-slate-500 dark:text-slate-400 hover:text-primary transition-colors">
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