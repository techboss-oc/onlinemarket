<?php
require_once '../core/init.php';

if (!isLoggedIn() || $_SESSION['role'] !== 'admin') {
    redirect('../login_page_-_onlinemarket.ng/');
}

$currentUser = getCurrentUser();
$db = Database::getInstance()->getConnection();

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_pricing'])) {
        // Update or Insert Pricing
        $types = ['top_search', 'homepage_feature', 'urgent_label'];
        foreach ($types as $type) {
            $price = $_POST[$type . '_price'];
            $duration = $_POST[$type . '_duration'];

            // Check if exists
            $stmt = $db->prepare("SELECT id FROM promotion_plans WHERE type = :type");
            $stmt->execute([':type' => $type]);
            if ($stmt->rowCount() > 0) {
                $sql = "UPDATE promotion_plans SET price = :price, duration_days = :duration WHERE type = :type";
            } else {
                $sql = "INSERT INTO promotion_plans (type, price, duration_days) VALUES (:type, :price, :duration)";
            }

            $stmt = $db->prepare($sql);
            $stmt->execute([':price' => $price, ':duration' => $duration, ':type' => $type]);
        }
        $success = "Pricing updated successfully.";
    }
}

// Fetch Current Plans
$plans = [];
$stmt = $db->query("SELECT * FROM promotion_plans");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $plans[$row['type']] = $row;
}

// Defaults if empty
$defaults = [
    'top_search' => ['price' => 2000, 'duration_days' => 7],
    'homepage_feature' => ['price' => 5000, 'duration_days' => 3],
    'urgent_label' => ['price' => 1000, 'duration_days' => 5],
];

foreach ($defaults as $key => $val) {
    if (!isset($plans[$key])) {
        $plans[$key] = $val;
    }
}
?>
<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Promotions & Pricing - Onlinemarket.ng Admin</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet" />
    <!-- Icons -->
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
                        "primary-hover": "#144bc2",
                        "primary-light": "#eef4ff",
                        "background-light": "#f6f6f8",
                        "background-dark": "#111621",
                        "surface": "#ffffff",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.375rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "2xl": "1rem",
                        "full": "9999px"
                    },
                    boxShadow: {
                        'soft': '0 2px 10px rgba(0, 0, 0, 0.03)',
                        'glow': '0 0 15px rgba(25, 93, 230, 0.15)',
                    }
                },
            },
        }
    </script>
</head>

<body class="bg-background-light text-slate-800 font-display antialiased overflow-hidden h-screen flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-surface border-r border-slate-200 flex-shrink-0 flex flex-col h-full z-20 hidden lg:flex">
        <!-- Logo Area -->
        <div class="h-16 flex items-center gap-3 px-6 border-b border-slate-100">
            <div class="bg-primary/10 text-primary p-1.5 rounded-lg">
                <span class="material-symbols-outlined text-[24px]">shopping_bag</span>
            </div>
            <div class="flex flex-col">
                <h1 class="text-slate-900 text-base font-bold leading-none tracking-tight">Onlinemarket<span class="text-primary">.ng</span></h1>
                <p class="text-slate-400 text-xs font-medium mt-1">Admin Dashboard</p>
            </div>
        </div>
        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-6 px-3 flex flex-col gap-1">
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors group" href="../admin_overview_dashboard/">
                <span class="material-symbols-outlined text-[22px] group-hover:text-primary transition-colors">dashboard</span>
                <span class="text-sm font-medium">Dashboard</span>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors group" href="../user_management/">
                <span class="material-symbols-outlined text-[22px] group-hover:text-primary transition-colors">group</span>
                <span class="text-sm font-medium">User Management</span>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors group" href="../ads_management/index.php">
                <span class="material-symbols-outlined text-[22px] group-hover:text-primary transition-colors">campaign</span>
                <span class="text-sm font-medium">Ads &amp; Listings</span>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-primary/10 text-primary transition-colors" href="#">
                <span class="material-symbols-outlined text-[22px] fill-1">price_change</span>
                <span class="text-sm font-semibold">Promotions & Pricing</span>
            </a>
        </nav>
        <!-- User Profile (Bottom Sidebar) -->
        <div class="p-4 border-t border-slate-100">
            <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-50 cursor-pointer transition-colors">
                <div class="size-9 rounded-full bg-cover bg-center border border-slate-200 bg-gray-200 text-gray-500 flex items-center justify-center font-bold">
                    <?php echo strtoupper(substr($currentUser['username'], 0, 1)); ?>
                </div>
                <div class="flex flex-col overflow-hidden">
                    <p class="text-sm font-semibold text-slate-900 truncate"><?php echo sanitize($currentUser['username']); ?></p>
                    <p class="text-xs text-slate-500 truncate"><?php echo sanitize($currentUser['email']); ?></p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Mobile Sidebar -->
    <aside id="mobile-sidebar" class="fixed inset-y-0 left-0 w-64 bg-surface z-40 transform -translate-x-full transition-transform duration-300 lg:hidden flex flex-col shadow-2xl">
        <div class="h-16 flex items-center justify-between px-6 border-b border-slate-100">
            <span class="text-lg font-bold text-slate-800">Menu</span>
            <button id="close-mobile-menu" class="text-slate-500 hover:text-red-500">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <!-- Navigation (Cloned) -->
        <nav class="flex-1 overflow-y-auto py-6 px-3 flex flex-col gap-1">
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors group" href="../admin_overview_dashboard/">
                <span class="material-symbols-outlined text-[22px] group-hover:text-primary transition-colors">dashboard</span>
                <span class="text-sm font-medium">Dashboard</span>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors group" href="../user_management/">
                <span class="material-symbols-outlined text-[22px] group-hover:text-primary transition-colors">group</span>
                <span class="text-sm font-medium">User Management</span>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors group" href="../ads_management/index.php">
                <span class="material-symbols-outlined text-[22px] group-hover:text-primary transition-colors">campaign</span>
                <span class="text-sm font-medium">Ads &amp; Listings</span>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-primary/10 text-primary transition-colors" href="#">
                <span class="material-symbols-outlined text-[22px] fill-1">price_change</span>
                <span class="text-sm font-semibold">Promotions & Pricing</span>
            </a>
            <div class="border-t border-slate-100 my-2"></div>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-red-600 hover:bg-red-50 transition-colors group" href="../core/logout.php">
                <span class="material-symbols-outlined text-[22px]">logout</span>
                <span class="text-sm font-medium">Logout</span>
            </a>
        </nav>
    </aside>

    <!-- Overlay -->
    <div id="mobile-menu-overlay" class="fixed inset-0 bg-black/50 z-30 hidden transition-opacity opacity-0"></div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col h-full overflow-hidden relative">
        <!-- Header -->
        <header class="h-16 bg-surface/80 backdrop-blur-md border-b border-slate-200 flex items-center justify-between px-6 sticky top-0 z-10">
            <div class="flex items-center gap-4">
                <button id="mobile-menu-btn" class="lg:hidden p-2 text-slate-500 hover:text-primary">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <!-- Breadcrumbs -->
                <nav class="flex items-center text-sm text-slate-500">
                    <a class="hover:text-primary transition-colors" href="../admin_overview_dashboard/">Dashboard</a>
                    <span class="mx-2 text-slate-300">/</span>
                    <span class="text-slate-900 font-medium">Promotions</span>
                </nav>
            </div>
        </header>
        <!-- Scrollable Content -->
        <main class="flex-1 overflow-y-auto p-6 md:p-8 scroll-smooth">
            <div class="max-w-4xl mx-auto flex flex-col gap-8">
                <!-- Title & Actions -->
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Pricing & Promotion Plans</h2>
                        <p class="text-slate-500 mt-1">Configure the costs and duration for ad promotions.</p>
                    </div>
                </div>

                <?php if (isset($success)): ?>
                    <div class="bg-emerald-50 text-emerald-700 p-4 rounded-lg border border-emerald-100 flex items-center gap-2">
                        <span class="material-symbols-outlined">check_circle</span>
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>

                <!-- Form -->
                <form method="POST" class="flex flex-col gap-6">
                    <!-- Top Search Plan -->
                    <div class="bg-surface rounded-xl border border-slate-200 shadow-soft p-6">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="size-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[28px]">search</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">Top Search Result</h3>
                                <p class="text-sm text-slate-500">Ads appear at the top of search results.</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-slate-700">Price (₦)</label>
                                <input name="top_search_price" type="number" value="<?php echo $plans['top_search']['price']; ?>" class="block w-full rounded-lg border-slate-300 focus:border-primary focus:ring-primary/20 sm:text-sm" required>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-slate-700">Duration (Days)</label>
                                <input name="top_search_duration" type="number" value="<?php echo $plans['top_search']['duration_days']; ?>" class="block w-full rounded-lg border-slate-300 focus:border-primary focus:ring-primary/20 sm:text-sm" required>
                            </div>
                        </div>
                    </div>

                    <!-- Homepage Feature Plan -->
                    <div class="bg-surface rounded-xl border border-slate-200 shadow-soft p-6">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="size-12 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[28px]">star</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">Homepage Feature</h3>
                                <p class="text-sm text-slate-500">Ads appear on the main homepage slider.</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-slate-700">Price (₦)</label>
                                <input name="homepage_feature_price" type="number" value="<?php echo $plans['homepage_feature']['price']; ?>" class="block w-full rounded-lg border-slate-300 focus:border-primary focus:ring-primary/20 sm:text-sm" required>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-slate-700">Duration (Days)</label>
                                <input name="homepage_feature_duration" type="number" value="<?php echo $plans['homepage_feature']['duration_days']; ?>" class="block w-full rounded-lg border-slate-300 focus:border-primary focus:ring-primary/20 sm:text-sm" required>
                            </div>
                        </div>
                    </div>

                    <!-- Urgent Label Plan -->
                    <div class="bg-surface rounded-xl border border-slate-200 shadow-soft p-6">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="size-12 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[28px]">priority_high</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">Urgent Label</h3>
                                <p class="text-sm text-slate-500">Ads get a distinct 'Urgent' badge.</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-slate-700">Price (₦)</label>
                                <input name="urgent_label_price" type="number" value="<?php echo $plans['urgent_label']['price']; ?>" class="block w-full rounded-lg border-slate-300 focus:border-primary focus:ring-primary/20 sm:text-sm" required>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-slate-700">Duration (Days)</label>
                                <input name="urgent_label_duration" type="number" value="<?php echo $plans['urgent_label']['duration_days']; ?>" class="block w-full rounded-lg border-slate-300 focus:border-primary focus:ring-primary/20 sm:text-sm" required>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 pb-12">
                        <button type="submit" name="update_pricing" class="bg-primary hover:bg-primary-hover text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-primary/30 transition-all active:scale-95">
                            Save Changes
                        </button>
                    </div>
                </form>
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