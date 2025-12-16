<?php
require_once '../core/init.php';

if (!isLoggedIn() || $_SESSION['role'] !== 'seller') {
    redirect('../login_page_-_onlinemarket.ng/');
}

$user = getCurrentUser();
$adModel = new Ad();
$stats = $adModel->getStats($user['id']);
$recent_ads = $adModel->getByUserId($user['id']); // Fetch all for now, can limit in view or SQL
?>
<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Seller Dashboard Home - Onlinemarket.ng</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
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
                        "2xl": "1rem",
                        "full": "9999px"
                    },
                    boxShadow: {
                        'soft': '0 10px 40px -10px rgba(25, 93, 230, 0.1)',
                        'glow': '0 0 20px rgba(25, 93, 230, 0.3)'
                    }
                },
            },
        }
    </script>
    <style>
        /* Custom Scrollbar for a cleaner look */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .dark .glass-card {
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-slate-800 dark:text-slate-100 font-display transition-colors duration-200">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 hidden md:flex flex-col bg-white dark:bg-[#1a202c] border-r border-slate-200 dark:border-slate-800 z-20">
            <div class="h-16 flex items-center px-6 border-b border-slate-100 dark:border-slate-800">
                <a href="../home_page_-_onlinemarket.ng/" class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center text-white font-bold text-xl">O</div>
                    <span class="text-slate-900 dark:text-white text-lg font-bold tracking-tight">Onlinemarket.ng</span>
                </a>
            </div>
            <div class="flex flex-col justify-between flex-1 overflow-y-auto py-6 px-4">
                <nav class="flex flex-col gap-2">
                    <div class="px-2 mb-2">
                        <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Seller Center</p>
                    </div>
                    <a class="flex items-center gap-3 px-4 py-3 rounded-xl bg-primary/10 text-primary font-medium transition-all group" href="index.php">
                        <span class="material-symbols-outlined text-[22px]">dashboard</span>
                        <span class="text-sm">Dashboard</span>
                    </a>
                    <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition-all font-medium" href="../my_ads_page/index.php">
                        <span class="material-symbols-outlined text-[22px]">list_alt</span>
                        <span class="text-sm">My Ads</span>
                    </a>
                    <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition-all font-medium justify-between" href="../seller_chat_/_messaging/index.php">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[22px]">mail</span>
                            <span class="text-sm">Messages</span>
                        </div>
                        <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">0</span>
                    </a>
                    <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition-all font-medium" href="../wallet_&_payments/index.php">
                        <span class="material-symbols-outlined text-[22px]">account_balance_wallet</span>
                        <span class="text-sm">Wallet</span>
                    </a>
                </nav>
                <div class="flex flex-col gap-2 mt-auto">
                    <div class="border-t border-slate-100 dark:border-slate-800 my-2"></div>
                    <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition-all font-medium" href="../buyer_profile_&_settings/index.php">
                        <span class="material-symbols-outlined text-[22px]">settings</span>
                        <span class="text-sm">Settings</span>
                    </a>
                    <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-600 dark:hover:text-red-400 transition-all font-medium" href="../core/logout.php">
                        <span class="material-symbols-outlined text-[22px]">logout</span>
                        <span class="text-sm">Log Out</span>
                    </a>
                </div>
            </div>
        </aside>
        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 h-full relative">
            <!-- Top Header -->
            <header class="h-16 flex items-center justify-between px-6 bg-white/80 dark:bg-[#1a202c]/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 sticky top-0 z-10">
                <div class="flex items-center gap-4">
                    <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                    <!-- Search -->
                    <form action="../search_results_page_-_onlinemarket.ng/index.php" method="GET" class="relative hidden sm:block w-96">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-slate-400 text-[20px]">search</span>
                        </div>
                        <input name="q" class="block w-full pl-10 pr-3 py-2 border-none rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary/50 text-sm" placeholder="Search ads, messages, or orders..." type="text" />
                    </form>
                </div>
                <div class="flex items-center gap-4">
                    <button class="relative p-2 rounded-lg text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-primary transition-colors">
                        <span class="material-symbols-outlined">notifications</span>
                        <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white dark:border-[#1a202c]"></span>
                    </button>
                    <div class="h-8 w-[1px] bg-slate-200 dark:bg-slate-700 mx-1"></div>
                    <a href="../buyer_profile_&_settings/index.php" class="flex items-center gap-3 cursor-pointer">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-semibold text-slate-900 dark:text-white leading-none"><?php echo sanitize($user['username']); ?></p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 leading-none mt-1">Verified Seller</p>
                        </div>
                        <div class="h-10 w-10 rounded-full bg-slate-200 bg-cover bg-center border-2 border-white dark:border-slate-700 shadow-sm flex items-center justify-center font-bold text-gray-500" style='background-image: url("<?php echo $user['profile_image'] ?? ''; ?>");'>
                            <?php if (empty($user['profile_image'])) echo strtoupper(substr($user['username'], 0, 1)); ?>
                        </div>
                    </a>
                </div>
            </header>

            <!-- Mobile Menu Sidebar Overlay (Hidden by default) -->
            <div id="mobile-menu-overlay" class="fixed inset-0 bg-black/50 z-30 hidden transition-opacity opacity-0"></div>

            <!-- Mobile Sidebar -->
            <aside id="mobile-sidebar" class="fixed inset-y-0 left-0 w-64 bg-white dark:bg-[#1a202c] z-40 transform -translate-x-full transition-transform duration-300 md:hidden flex flex-col">
                <div class="h-16 flex items-center justify-between px-6 border-b border-slate-100 dark:border-slate-800">
                    <span class="text-lg font-bold">Menu</span>
                    <button id="close-mobile-menu" class="text-slate-500">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <!-- Mobile Navigation Links (Clone of desktop) -->
                <nav class="flex-1 overflow-y-auto p-4 flex flex-col gap-2">
                    <a class="flex items-center gap-3 px-4 py-3 rounded-xl bg-primary/10 text-primary font-medium" href="index.php">
                        <span class="material-symbols-outlined">dashboard</span> Dashboard
                    </a>
                    <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400" href="../my_ads_page/index.php">
                        <span class="material-symbols-outlined">list_alt</span> My Ads
                    </a>
                    <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400" href="../seller_chat_/_messaging/index.php">
                        <span class="material-symbols-outlined">mail</span> Messages
                    </a>
                    <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400" href="../wallet_&_payments/index.php">
                        <span class="material-symbols-outlined">account_balance_wallet</span> Wallet
                    </a>
                    <div class="border-t my-2"></div>
                    <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400" href="../buyer_profile_&_settings/index.php">
                        <span class="material-symbols-outlined">settings</span> Settings
                    </a>
                    <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-red-600" href="../core/logout.php">
                        <span class="material-symbols-outlined">logout</span> Log Out
                    </a>
                </nav>
            </aside>

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

                mobileBtn.addEventListener('click', toggleMenu);
                closeBtn.addEventListener('click', toggleMenu);
                overlay.addEventListener('click', toggleMenu);
            </script>
            <!-- Scrollable Dashboard Content -->
            <main class="flex-1 overflow-y-auto p-6 lg:p-10 space-y-8">
                <!-- Page Heading -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">Good morning, <?php echo sanitize($user['username']); ?> 👋</h1>
                        <p class="text-slate-500 dark:text-slate-400 mt-1">Here's what's happening with your store today.</p>
                    </div>
                    <a href="../post_new_ad_form/" class="flex items-center justify-center gap-2 bg-primary hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5 active:translate-y-0 font-medium text-sm">
                        <span class="material-symbols-outlined text-[20px]">add_circle</span>
                        Post New Ad
                    </a>
                </div>
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Total Ads -->
                    <div class="glass-card bg-white dark:bg-[#1a202c] p-6 rounded-2xl shadow-soft hover:shadow-lg transition-all duration-300 group">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                                <span class="material-symbols-outlined">inventory_2</span>
                            </div>
                        </div>
                        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Total Ads</p>
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mt-1"><?php echo $stats['total_ads']; ?></h3>
                    </div>
                    <!-- Total Views -->
                    <div class="glass-card bg-white dark:bg-[#1a202c] p-6 rounded-2xl shadow-soft hover:shadow-lg transition-all duration-300 group">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                <span class="material-symbols-outlined">visibility</span>
                            </div>
                        </div>
                        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Total Views</p>
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mt-1"><?php echo $stats['total_views']; ?></h3>
                    </div>
                    <!-- Unread Messages -->
                    <div class="glass-card bg-white dark:bg-[#1a202c] p-6 rounded-2xl shadow-soft hover:shadow-lg transition-all duration-300 group">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-3 bg-rose-50 dark:bg-rose-900/20 rounded-xl text-rose-600 group-hover:bg-rose-600 group-hover:text-white transition-colors">
                                <span class="material-symbols-outlined">chat_bubble</span>
                            </div>
                        </div>
                        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Unread Messages</p>
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mt-1">0</h3>
                    </div>
                    <!-- Rating -->
                    <div class="glass-card bg-white dark:bg-[#1a202c] p-6 rounded-2xl shadow-soft hover:shadow-lg transition-all duration-300 group">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-3 bg-amber-50 dark:bg-amber-900/20 rounded-xl text-amber-500 group-hover:bg-amber-500 group-hover:text-white transition-colors">
                                <span class="material-symbols-outlined">star</span>
                            </div>
                        </div>
                        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Rating</p>
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mt-1">5.0</h3>
                    </div>
                </div>

                <!-- Recent Ads Table -->
                <div class="glass-card bg-white dark:bg-[#1a202c] rounded-2xl shadow-soft overflow-hidden">
                    <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Recent Ads</h3>
                        <a href="../my_ads_page/index.php" class="text-slate-500 hover:text-primary text-sm font-medium transition-colors">Manage All Ads</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-600 dark:text-slate-400">
                            <thead class="bg-slate-50 dark:bg-slate-800 text-xs uppercase font-semibold text-slate-500">
                                <tr>
                                    <th class="px-6 py-4">Item Details</th>
                                    <th class="px-6 py-4">Price</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4">Views</th>
                                    <th class="px-6 py-4 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                <?php if (empty($recent_ads)): ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center">No ads found. <a href="../post_new_ad_form/" class="text-primary font-bold">Post your first ad!</a></td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach (array_slice($recent_ads, 0, 5) as $ad): ?>
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-12 h-12 rounded-lg bg-slate-200 bg-cover bg-center" style="background-image: url('<?php echo $ad['image_url'] ?? 'https://via.placeholder.com/100'; ?>');"></div>
                                                    <div>
                                                        <p class="font-semibold text-slate-900 dark:text-white"><?php echo sanitize($ad['title']); ?></p>
                                                        <p class="text-xs text-slate-400"><?php echo sanitize($ad['category_name']); ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 font-medium">₦ <?php echo number_format($ad['price']); ?></td>
                                            <td class="px-6 py-4">
                                                <span class="bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-bold px-2.5 py-1 rounded-full"><?php echo ucfirst($ad['status']); ?></span>
                                            </td>
                                            <td class="px-6 py-4"><?php echo $ad['views_count']; ?></td>
                                            <td class="px-6 py-4 text-right">
                                                <button class="text-slate-400 hover:text-primary transition-colors">
                                                    <span class="material-symbols-outlined text-[20px]">more_vert</span>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
        <!-- Sticky Bottom Mobile Navigation -->
        <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white dark:bg-[#1a202c] border-t border-slate-200 dark:border-slate-800 px-4 py-2 z-50 flex justify-between items-center shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
            <a href="index.php" class="flex flex-col items-center gap-1 text-primary">
                <span class="material-symbols-outlined text-[24px]">dashboard</span>
                <span class="text-[10px] font-medium">Home</span>
            </a>
            <a href="../my_ads_page/index.php" class="flex flex-col items-center gap-1 text-slate-500 dark:text-slate-400 hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-[24px]">list_alt</span>
                <span class="text-[10px] font-medium">Ads</span>
            </a>
            <a href="../post_new_ad_form/" class="flex flex-col items-center gap-1 text-slate-500 hover:text-primary transition-colors -mt-6">
                <div class="bg-primary text-white rounded-full p-3 shadow-lg shadow-primary/30 border-4 border-white dark:border-[#1a202c]">
                    <span class="material-symbols-outlined text-[24px]">add</span>
                </div>
                <span class="text-[10px] font-medium">Sell</span>
            </a>
            <a href="../seller_chat_/_messaging/index.php" class="flex flex-col items-center gap-1 text-slate-500 dark:text-slate-400 hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-[24px]">mail</span>
                <span class="text-[10px] font-medium">Chat</span>
            </a>
            <a href="../wallet_&_payments/index.php" class="flex flex-col items-center gap-1 text-slate-500 dark:text-slate-400 hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-[24px]">account_balance_wallet</span>
                <span class="text-[10px] font-medium">Wallet</span>
            </a>
        </div>
    </div>
</body>

</html>