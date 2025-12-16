<?php
require_once '../core/init.php';

if (!isLoggedIn() || $_SESSION['role'] !== 'admin') {
    redirect('../login_page_-_onlinemarket.ng/');
}

$adModel = new Ad();
$currentUser = getCurrentUser();

// Handle Status Updates
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action']; // 'approve' or 'reject'
    $id = $_GET['id'];

    if ($action === 'approve') {
        $adModel->updateStatus($id, 'active');
    } elseif ($action === 'reject') {
        $adModel->updateStatus($id, 'rejected');
    }
    redirect('index.php');
}

$ads = $adModel->getAllAdmin();

// Calculate Stats
$total_ads = count($ads);
$active_ads = 0;
$pending_ads = 0;
$rejected_ads = 0;

foreach ($ads as $ad) {
    if ($ad['status'] === 'active') $active_ads++;
    elseif ($ad['status'] === 'pending') $pending_ads++; // Assuming pending status exists or mapped
    elseif ($ad['status'] === 'rejected') $rejected_ads++;
}
?>
<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Ads Management - Onlinemarket.ng Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&amp;display=swap" rel="stylesheet" />
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
                        "primary-light": "#eef4ff",
                        "background-light": "#f6f8fb",
                        "background-white": "#ffffff",
                        "text-dark": "#0e121b",
                        "text-secondary": "#64748b",
                        "success": "#07883b",
                        "warning": "#f59e0b",
                        "danger": "#e73908",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.375rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
</head>

<body class="bg-background-light font-display text-text-dark antialiased overflow-hidden">
    <div class="flex h-screen w-full overflow-hidden">
        <!-- Sidebar -->
        <aside class="hidden lg:flex flex-col w-72 bg-white border-r border-border-light h-full flex-shrink-0 z-20">
            <div class="p-6 flex items-center gap-3 border-b border-border-light/50">
                <div class="bg-primary/10 text-primary p-2 rounded-lg">
                    <span class="material-symbols-outlined text-2xl">grid_view</span>
                </div>
                <h1 class="text-lg font-bold tracking-tight text-text-dark">Onlinemarket<span class="text-primary">.ng</span></h1>
            </div>
            <div class="flex-1 overflow-y-auto py-6 px-4 flex flex-col gap-6">
                <!-- User Profile -->
                <div class="flex items-center gap-3 px-2">
                    <div class="h-10 w-10 rounded-full bg-cover bg-center ring-2 ring-primary/20 bg-gray-200 text-gray-500 flex items-center justify-center font-bold">
                        <?php echo strtoupper(substr($currentUser['username'], 0, 1)); ?>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-text-dark"><?php echo sanitize($currentUser['username']); ?></p>
                        <p class="text-xs text-text-secondary">Super Admin</p>
                    </div>
                </div>
                <!-- Nav -->
                <nav class="flex flex-col gap-1">
                    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-text-secondary hover:bg-background-light hover:text-text-dark transition-colors" href="../admin_overview_dashboard/">
                        <span class="material-symbols-outlined text-[22px]">dashboard</span>
                        <span class="text-sm font-medium">Dashboard</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-primary/10 text-primary transition-colors" href="#">
                        <span class="material-symbols-outlined text-[22px] fill-1">list_alt</span>
                        <span class="text-sm font-medium">Ads Management</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-text-secondary hover:bg-background-light hover:text-text-dark transition-colors" href="../user_management/">
                        <span class="material-symbols-outlined text-[22px]">group</span>
                        <span class="text-sm font-medium">Users</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-text-secondary hover:bg-background-light hover:text-text-dark transition-colors" href="../promotions_management/index.php">
                        <span class="material-symbols-outlined text-[22px]">price_change</span>
                        <span class="text-sm font-medium">Promotions & Pricing</span>
                    </a>
                </nav>
            </div>
        </aside>

        <!-- Mobile Sidebar -->
        <aside id="mobile-sidebar" class="fixed inset-y-0 left-0 w-72 bg-white z-40 transform -translate-x-full transition-transform duration-300 lg:hidden flex flex-col shadow-2xl">
            <div class="h-16 flex items-center justify-between px-6 border-b border-border-light">
                <span class="text-lg font-bold text-text-dark">Menu</span>
                <button id="close-mobile-menu" class="text-text-secondary hover:text-danger">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <nav class="flex-1 overflow-y-auto p-4 flex flex-col gap-2">
                <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-text-secondary hover:bg-background-light hover:text-text-dark transition-colors" href="../admin_overview_dashboard/">
                    <span class="material-symbols-outlined text-[22px]">dashboard</span>
                    <span class="text-sm font-medium">Dashboard</span>
                </a>
                <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-primary/10 text-primary transition-colors" href="#">
                    <span class="material-symbols-outlined text-[22px] fill-1">list_alt</span>
                    <span class="text-sm font-medium">Ads Management</span>
                </a>
                <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-text-secondary hover:bg-background-light hover:text-text-dark transition-colors" href="../user_management/">
                    <span class="material-symbols-outlined text-[22px]">group</span>
                    <span class="text-sm font-medium">Users</span>
                </a>
                <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-text-secondary hover:bg-background-light hover:text-text-dark transition-colors" href="../promotions_management/index.php">
                    <span class="material-symbols-outlined text-[22px]">price_change</span>
                    <span class="text-sm font-medium">Promotions & Pricing</span>
                </a>
                <div class="border-t border-border-light my-2"></div>
                <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-danger hover:bg-danger/10 transition-colors" href="../core/logout.php">
                    <span class="material-symbols-outlined text-[22px]">logout</span>
                    <span class="text-sm font-medium">Logout</span>
                </a>
            </nav>
        </aside>

        <!-- Overlay -->
        <div id="mobile-menu-overlay" class="fixed inset-0 bg-black/50 z-30 hidden transition-opacity opacity-0"></div>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col h-full relative overflow-hidden bg-background-light">
            <!-- Header -->
            <header class="h-16 bg-white/80 backdrop-blur-md border-b border-border-light flex items-center justify-between px-6 sticky top-0 z-10">
                <div class="flex items-center gap-4">
                    <button id="mobile-menu-btn" class="lg:hidden p-2 text-text-secondary hover:text-primary">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                    <div class="hidden md:flex items-center text-sm">
                        <a class="text-text-secondary hover:text-primary transition-colors" href="../admin_overview_dashboard/">Dashboard</a>
                        <span class="material-symbols-outlined text-base text-text-secondary mx-2">chevron_right</span>
                        <span class="font-medium text-text-dark">Ads Management</span>
                    </div>
                </div>
            </header>
            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto p-6 lg:p-8 scroll-smooth">
                <div class="max-w-7xl mx-auto flex flex-col gap-8">
                    <!-- Stats -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="bg-white p-5 rounded-xl border border-border-light shadow-sm">
                            <p class="text-text-secondary text-sm font-medium">Total Ads</p>
                            <h3 class="text-3xl font-bold text-text-dark mt-1"><?php echo $total_ads; ?></h3>
                        </div>
                        <div class="bg-white p-5 rounded-xl border border-border-light shadow-sm">
                            <p class="text-text-secondary text-sm font-medium">Active Ads</p>
                            <h3 class="text-3xl font-bold text-success mt-1"><?php echo $active_ads; ?></h3>
                        </div>
                        <div class="bg-white p-5 rounded-xl border border-border-light shadow-sm">
                            <p class="text-text-secondary text-sm font-medium">Rejected Ads</p>
                            <h3 class="text-3xl font-bold text-danger mt-1"><?php echo $rejected_ads; ?></h3>
                        </div>
                    </div>
                    <!-- Table -->
                    <div class="bg-white border border-border-light rounded-xl overflow-hidden shadow-sm mb-8">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-background-light/50 border-b border-border-light text-xs uppercase text-text-secondary font-semibold tracking-wider">
                                        <th class="p-4">Ad Details</th>
                                        <th class="p-4">Category</th>
                                        <th class="p-4">Seller</th>
                                        <th class="p-4">Price</th>
                                        <th class="p-4">Status</th>
                                        <th class="p-4">Date</th>
                                        <th class="p-4 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-border-light text-sm text-text-dark">
                                    <?php foreach ($ads as $ad): ?>
                                        <tr class="hover:bg-primary-light/30 transition-colors">
                                            <td class="p-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="h-12 w-12 rounded-lg bg-gray-100 flex-shrink-0 bg-cover bg-center border border-border-light" style="background-image: url('<?php echo $ad['image_url'] ?? 'https://via.placeholder.com/100'; ?>');"></div>
                                                    <div>
                                                        <p class="font-semibold text-text-dark hover:text-primary cursor-pointer transition-colors"><?php echo sanitize($ad['title']); ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-4"><span class="px-2 py-1 rounded-md bg-gray-100 text-xs font-medium text-text-secondary"><?php echo sanitize($ad['category_name']); ?></span></td>
                                            <td class="p-4"><?php echo sanitize($ad['seller_name']); ?></td>
                                            <td class="p-4 font-medium">₦<?php echo number_format($ad['price']); ?></td>
                                            <td class="p-4">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium 
    <?php echo $ad['status'] == 'active' ? 'bg-success/10 text-success' : ($ad['status'] == 'rejected' ? 'bg-danger/10 text-danger' : 'bg-gray-100 text-gray-500'); ?>">
                                                    <?php echo ucfirst($ad['status']); ?>
                                                </span>
                                            </td>
                                            <td class="p-4 text-text-secondary"><?php echo date('M d, Y', strtotime($ad['created_at'])); ?></td>
                                            <td class="p-4 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <?php if ($ad['status'] !== 'active'): ?>
                                                        <a href="?action=approve&id=<?php echo $ad['id']; ?>" class="p-1.5 hover:bg-success/10 text-success rounded transition-colors" title="Approve">
                                                            <span class="material-symbols-outlined text-xl">check_circle</span>
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if ($ad['status'] !== 'rejected'): ?>
                                                        <a href="?action=reject&id=<?php echo $ad['id']; ?>" class="p-1.5 hover:bg-danger/10 text-danger rounded transition-colors" title="Reject">
                                                            <span class="material-symbols-outlined text-xl">cancel</span>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
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