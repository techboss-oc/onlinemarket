<?php
require_once '../../core/init.php';

if (!isLoggedIn()) {
    redirect('../login_page_-_onlinemarket.ng/');
}

$favModel = new Favorite();
$user_id = $_SESSION['user_id'];

// Handle Remove Action
if (isset($_GET['remove_id'])) {
    $favModel->remove($user_id, $_GET['remove_id']);
    header("Location: index.php");
    exit();
}

// Handle Clear All Action
if (isset($_GET['action']) && $_GET['action'] === 'clear_all') {
    // Implement clear all logic if added to model, or loop
    // For now, simpler to leave or implement later
}

$saved_ads = $favModel->getUserFavorites($user_id);
?>
<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Saved Ads - Onlinemarket.ng</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
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

<body class="bg-background-light dark:bg-background-dark font-display text-[#0e121b] antialiased min-h-screen flex flex-col">
    <!-- Top Navigation -->
    <header class="sticky top-0 z-50 w-full border-b border-[#e7ebf3] bg-white/90 backdrop-blur-md dark:bg-[#111621]/90 dark:border-gray-800">
        <div class="px-4 md:px-10 py-3 flex items-center justify-between gap-4">
            <!-- Logo Area -->
            <div class="flex items-center gap-8">
                <a class="flex items-center gap-2 text-[#0e121b] dark:text-white" href="../home_page_-_onlinemarket.ng/">
                    <div class="size-8 text-primary flex items-center justify-center bg-primary/10 rounded-lg">
                        <span class="material-symbols-outlined">shopping_bag</span>
                    </div>
                    <h2 class="text-xl font-bold leading-tight tracking-tight hidden sm:block">Onlinemarket.ng</h2>
                </a>
            </div>
            <!-- Nav Actions -->
            <div class="flex items-center justify-end gap-6 flex-1">
                <nav class="hidden md:flex items-center gap-6">
                    <a class="text-[#0e121b] dark:text-gray-300 text-sm font-medium hover:text-primary transition-colors" href="../home_page_-_onlinemarket.ng/">Home</a>
                    <a class="text-[#0e121b] dark:text-gray-300 text-sm font-medium hover:text-primary transition-colors" href="../buyer_dashboard/">Dashboard</a>
                </nav>
                <div class="flex items-center gap-3">
                    <a href="../post_new_ad_form/" class="hidden sm:flex h-10 px-4 items-center justify-center rounded-lg bg-primary text-white text-sm font-bold shadow-md shadow-primary/20 hover:bg-blue-700 transition-colors">
                        Post Ad
                    </a>
                    <div class="bg-center bg-no-repeat bg-cover rounded-full size-10 border-2 border-white shadow-sm flex items-center justify-center bg-gray-200 text-gray-500 font-bold">
                        <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Main Content -->
    <main class="flex-1 flex flex-col items-center w-full px-4 sm:px-6 lg:px-8 py-8">
        <div class="w-full max-w-7xl flex flex-col gap-6">
            <!-- Page Header & Title -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 pb-4 border-b border-gray-200">
                <div class="flex flex-col gap-1">
                    <h1 class="text-[#0e121b] dark:text-white text-3xl md:text-4xl font-black tracking-tight">My Saved Ads</h1>
                    <p class="text-[#4e6797] text-base font-normal"><?php echo count($saved_ads); ?> items saved</p>
                </div>
            </div>

            <!-- Ad Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

                <?php if (empty($saved_ads)): ?>
                    <div class="col-span-full py-20 text-center bg-white rounded-xl">
                        <span class="material-symbols-outlined text-6xl text-gray-300 mb-4">favorite_border</span>
                        <h3 class="text-xl font-bold text-gray-700">No saved ads yet</h3>
                        <p class="text-gray-500 mt-2 mb-6">Start browsing to find items you love!</p>
                        <a href="../home_page_-_onlinemarket.ng/" class="bg-primary text-white px-6 py-2.5 rounded-lg font-bold hover:bg-blue-700 transition-colors">Browse Ads</a>
                    </div>
                <?php else: ?>
                    <?php foreach ($saved_ads as $item): ?>
                        <!-- Card -->
                        <article class="group flex flex-col bg-white dark:bg-[#1a202c] rounded-xl shadow-sm hover:shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:-translate-y-1 transition-all duration-300 border border-gray-100 overflow-hidden relative">
                            <div class="relative aspect-[4/3] bg-gray-100 overflow-hidden">
                                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="<?php echo $item['image_url']; ?>" />
                                <a href="?remove_id=<?php echo $item['id']; ?>" class="absolute top-3 right-3 p-2 bg-white/90 backdrop-blur-sm rounded-full text-red-500 hover:scale-110 transition-transform shadow-sm z-10" title="Remove from favorites">
                                    <span class="material-symbols-outlined fill-current text-[20px]" style="font-variation-settings: 'FILL' 1;">favorite</span>
                                </a>
                            </div>
                            <div class="p-4 flex flex-col gap-2 flex-1">
                                <div class="flex justify-between items-start">
                                    <h3 class="text-lg font-bold text-primary">₦ <?php echo number_format($item['price']); ?></h3>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-green-600 bg-green-50 px-2 py-1 rounded"><?php echo ucfirst($item['condition_state'] ?? 'Used'); ?></span>
                                </div>
                                <a href="../single_ad_view_page_-_onlinemarket.ng/?id=<?php echo $item['id']; ?>">
                                    <h4 class="text-[#0e121b] font-medium text-sm line-clamp-2 leading-snug group-hover:text-primary transition-colors"><?php echo sanitize($item['title']); ?></h4>
                                </a>
                                <div class="flex items-center text-gray-400 text-xs gap-1 mt-1">
                                    <span class="material-symbols-outlined text-[14px]">location_on</span>
                                    <span><?php echo ucfirst($item['location_name']); ?></span>
                                    <span class="mx-1">•</span>
                                    <span>Saved <?php echo time_elapsed_string($item['saved_at']); ?></span>
                                </div>
                            </div>
                            <div class="px-4 pb-4 mt-auto flex items-center justify-between">
                                <div class="flex gap-2 w-full">
                                    <a href="../single_ad_view_page_-_onlinemarket.ng/?id=<?php echo $item['id']; ?>" class="flex-1 flex items-center justify-center rounded-lg bg-blue-50 text-primary hover:bg-primary hover:text-white transition-colors py-2 text-sm font-bold">
                                        View Ad
                                    </a>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </div>
    </main>
    <!-- Simple Footer -->
    <footer class="w-full border-t border-[#e7ebf3] bg-white py-8 px-4">
        <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-4 md:flex-row">
            <p class="text-sm text-[#4e6797]">© 2024 Onlinemarket.ng. All rights reserved.</p>
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
        <a href="../saved_ads_/_favorites_page/" class="flex flex-col items-center gap-1 text-primary">
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