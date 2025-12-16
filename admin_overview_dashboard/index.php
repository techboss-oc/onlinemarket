<?php
require_once '../core/init.php';

if (!isLoggedIn() || $_SESSION['role'] !== 'admin') {
    redirect('../login_page_-_onlinemarket.ng/');
}

$db = Database::getInstance()->getConnection();

// Stats
$stats = [];
$stats['users'] = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
$stats['active_ads'] = $db->query("SELECT COUNT(*) FROM ads WHERE status = 'active'")->fetchColumn();
$stats['total_ads'] = $db->query("SELECT COUNT(*) FROM ads")->fetchColumn();
// Mock revenue for now as we don't have real payments
$stats['revenue'] = '₦ 0.00';

// Recent Listings
$sql = "SELECT ads.*, users.username as seller_name, categories.name as category_name,
        (SELECT image_url FROM ad_images WHERE ad_id = ads.id AND is_primary = 1 LIMIT 1) as image_url
        FROM ads 
        JOIN users ON ads.user_id = users.id 
        JOIN categories ON ads.category_id = categories.id
        ORDER BY created_at DESC LIMIT 10";
$listings = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

$user = getCurrentUser();
?>
<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Onlinemarket.ng - Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet" />
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
                        "surface-light": "#ffffff",
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

<body class="bg-background-light dark:bg-background-dark font-display text-gray-900 antialiased overflow-hidden">
    <div class="flex h-screen w-full">
        <!-- Side Navigation -->
        <aside class="hidden md:flex w-64 flex-col border-r border-gray-200 bg-surface-light dark:bg-[#1a202c] dark:border-gray-800 transition-all duration-300">
            <!-- Logo -->
            <div class="flex items-center gap-3 px-6 py-6 border-b border-gray-100 dark:border-gray-800">
                <div class="flex items-center justify-center size-10 rounded-xl bg-primary/10 text-primary">
                    <span class="material-symbols-outlined text-3xl">storefront</span>
                </div>
                <div class="flex flex-col">
                    <h1 class="text-base font-bold leading-none text-gray-900 dark:text-white">Onlinemarket.ng</h1>
                    <p class="text-xs font-medium text-gray-500 mt-1">Admin Panel</p>
                </div>
            </div>
            <!-- Navigation Links -->
            <nav class="flex-1 flex flex-col gap-2 p-4 overflow-y-auto">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-2">Main Menu</p>
                <a class="flex items-center gap-3 px-4 py-3 rounded-lg bg-primary text-white shadow-md shadow-primary/30 transition-all" href="index.php">
                    <span class="material-symbols-outlined text-[20px]">dashboard</span>
                    <span class="text-sm font-medium">Overview</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-all dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white" href="../user_management/index.php">
                    <span class="material-symbols-outlined text-[20px]">group</span>
                    <span class="text-sm font-medium">Users</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-all dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white" href="../ads_management/index.php">
                    <span class="material-symbols-outlined text-[20px]">list_alt</span>
                    <span class="text-sm font-medium">Listings</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-all dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white" href="../promotions_management/index.php">
                    <span class="material-symbols-outlined text-[20px]">price_change</span>
                    <span class="text-sm font-medium">Promotions & Pricing</span>
                </a>
                <div class="mt-auto pt-4 border-t border-gray-100 dark:border-gray-800">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">System</p>
                    <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-all dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white" href="#">
                        <span class="material-symbols-outlined text-[20px]">settings</span>
                        <span class="text-sm font-medium">Settings</span>
                    </a>
                    <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-red-50 hover:text-red-600 transition-all dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white" href="../core/logout.php">
                        <span class="material-symbols-outlined text-[20px]">logout</span>
                        <span class="text-sm font-medium">Logout</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Mobile Sidebar -->
        <aside id="mobile-sidebar" class="fixed inset-y-0 left-0 w-64 bg-white dark:bg-[#1a202c] z-40 transform -translate-x-full transition-transform duration-300 md:hidden flex flex-col shadow-2xl">
            <div class="h-16 flex items-center justify-between px-6 border-b border-slate-100 dark:border-slate-800">
                <span class="text-lg font-bold text-slate-800 dark:text-white">Menu</span>
                <button id="close-mobile-menu" class="text-slate-500 hover:text-red-500">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <!-- Navigation (Cloned) -->
            <nav class="flex-1 flex flex-col gap-2 p-4 overflow-y-auto">
                <a class="flex items-center gap-3 px-4 py-3 rounded-lg bg-primary text-white shadow-md shadow-primary/30 transition-all" href="index.php">
                    <span class="material-symbols-outlined text-[20px]">dashboard</span>
                    <span class="text-sm font-medium">Overview</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800" href="../user_management/index.php">
                    <span class="material-symbols-outlined text-[20px]">group</span>
                    <span class="text-sm font-medium">Users</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800" href="../ads_management/index.php">
                    <span class="material-symbols-outlined text-[20px]">list_alt</span>
                    <span class="text-sm font-medium">Listings</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800" href="../promotions_management/index.php">
                    <span class="material-symbols-outlined text-[20px]">price_change</span>
                    <span class="text-sm font-medium">Promotions & Pricing</span>
                </a>
                <div class="border-t border-slate-100 dark:border-slate-800 my-2"></div>
                <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50" href="../core/logout.php">
                    <span class="material-symbols-outlined text-[20px]">logout</span>
                    <span class="text-sm font-medium">Logout</span>
                </a>
            </nav>
        </aside>

        <!-- Overlay -->
        <div id="mobile-menu-overlay" class="fixed inset-0 bg-black/50 z-30 hidden transition-opacity opacity-0"></div>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col h-full overflow-hidden relative">
            <!-- Top Header -->
            <header class="h-16 flex items-center justify-between px-8 bg-surface-light border-b border-gray-200 dark:bg-[#1a202c] dark:border-gray-800 shrink-0 z-20">
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="md:hidden mr-4 text-slate-500 hover:text-primary">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <!-- Search -->
                <div class="flex items-center flex-1 max-w-lg">
                    <div class="relative w-full">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <span class="material-symbols-outlined">search</span>
                        </span>
                        <input class="w-full py-2 pl-10 pr-4 text-sm text-gray-700 bg-gray-50 border-none rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:bg-white transition-all dark:bg-gray-800 dark:text-gray-200 dark:focus:bg-gray-700" placeholder="Search users, listings..." type="text" />
                    </div>
                </div>
                <!-- Right Actions -->
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-3 cursor-pointer">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-bold text-gray-900 dark:text-white"><?php echo sanitize($user['username']); ?></p>
                            <p class="text-xs text-gray-500">Super Admin</p>
                        </div>
                        <div class="size-10 rounded-full bg-cover bg-center border-2 border-gray-100 dark:border-gray-700 flex items-center justify-center bg-gray-200 text-gray-500 font-bold">
                            <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Scrollable Dashboard Content -->
            <div class="flex-1 overflow-y-auto bg-background-light dark:bg-background-dark p-8">
                <div class="max-w-[1400px] mx-auto flex flex-col gap-8">
                    <!-- Page Title -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Dashboard Overview</h2>
                            <p class="text-gray-500 mt-1">Here's what's happening on your platform today.</p>
                        </div>
                    </div>
                    <!-- KPI Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                        <!-- Card 1 -->
                        <div class="bg-surface-light dark:bg-[#1a202c] p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col gap-4 group hover:border-primary/50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="p-2 bg-blue-50 text-blue-600 rounded-lg dark:bg-blue-900/20 dark:text-blue-400">
                                    <span class="material-symbols-outlined">group</span>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Users</p>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?php echo $stats['users']; ?></h3>
                            </div>
                        </div>
                        <!-- Card 2 -->
                        <div class="bg-surface-light dark:bg-[#1a202c] p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col gap-4 group hover:border-primary/50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="p-2 bg-purple-50 text-purple-600 rounded-lg dark:bg-purple-900/20 dark:text-purple-400">
                                    <span class="material-symbols-outlined">campaign</span>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Ads</p>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?php echo $stats['active_ads']; ?></h3>
                            </div>
                        </div>
                        <!-- Card 3 -->
                        <div class="bg-surface-light dark:bg-[#1a202c] p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col gap-4 group hover:border-primary/50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="p-2 bg-orange-50 text-orange-600 rounded-lg dark:bg-orange-900/20 dark:text-orange-400">
                                    <span class="material-symbols-outlined">post_add</span>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Ads</p>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?php echo $stats['total_ads']; ?></h3>
                            </div>
                        </div>
                        <!-- Card 4 -->
                        <div class="bg-surface-light dark:bg-[#1a202c] p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col gap-4 group hover:border-primary/50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg dark:bg-emerald-900/20 dark:text-emerald-400">
                                    <span class="material-symbols-outlined">payments</span>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Revenue</p>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?php echo $stats['revenue']; ?></h3>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Listings Table -->
                    <div class="bg-surface-light dark:bg-[#1a202c] rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Recent Listings</h3>
                            <button class="text-sm font-medium text-primary hover:text-primary/80">View All</button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="text-xs text-gray-500 uppercase border-b border-gray-100 dark:border-gray-700">
                                        <th class="px-6 py-4 font-semibold">Item Details</th>
                                        <th class="px-6 py-4 font-semibold">Category</th>
                                        <th class="px-6 py-4 font-semibold">Price</th>
                                        <th class="px-6 py-4 font-semibold">Status</th>
                                        <th class="px-6 py-4 font-semibold">Date</th>
                                        <th class="px-6 py-4 font-semibold text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    <?php foreach ($listings as $listing): ?>
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="size-10 rounded-lg bg-cover bg-center bg-gray-200" style="background-image: url('<?php echo $listing['image_url'] ?? 'https://via.placeholder.com/100'; ?>');"></div>
                                                    <div>
                                                        <p class="text-sm font-semibold text-gray-900 dark:text-white"><?php echo sanitize($listing['title']); ?></p>
                                                        <p class="text-xs text-gray-500">by <?php echo sanitize($listing['seller_name']); ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                                    <?php echo sanitize($listing['category_name']); ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white font-medium">₦ <?php echo number_format($listing['price']); ?></td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium 
<?php echo $listing['status'] == 'active' ? 'bg-green-100 text-green-800' : ($listing['status'] == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'); ?>">
                                                    <span class="size-1.5 rounded-full <?php echo $listing['status'] == 'active' ? 'bg-green-500' : ($listing['status'] == 'pending' ? 'bg-yellow-500' : 'bg-red-500'); ?>"></span>
                                                    <?php echo ucfirst($listing['status']); ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500"><?php echo date('M d, Y', strtotime($listing['created_at'])); ?></td>
                                            <td class="px-6 py-4 text-right">
                                                <button class="text-gray-400 hover:text-primary transition-colors">
                                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
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