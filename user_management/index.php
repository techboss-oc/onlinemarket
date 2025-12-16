<?php
require_once '../core/init.php';

if (!isLoggedIn() || $_SESSION['role'] !== 'admin') {
    redirect('../login_page_-_onlinemarket.ng/');
}

$userModel = new User();
$currentUser = getCurrentUser();

// Handle Actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];

    if ($action === 'verify') {
        $userModel->toggleVerification($id, 1);
    } elseif ($action === 'unverify') {
        $userModel->toggleVerification($id, 0);
    } elseif ($action === 'make_admin') {
        $userModel->updateRole($id, 'admin');
    } elseif ($action === 'make_seller') {
        $userModel->updateRole($id, 'seller');
    } elseif ($action === 'make_buyer') {
        $userModel->updateRole($id, 'buyer');
    } elseif ($action === 'delete') {
        $userModel->deleteUser($id);
    }
    redirect('index.php');
}

$users = $userModel->getAll();

?>
<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>User Management - Onlinemarket.ng Admin</title>
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
    <aside class="w-64 bg-surface border-r border-slate-200 flex-shrink-0 flex flex-col h-full z-20">
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
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-primary/10 text-primary transition-colors" href="#">
                <span class="material-symbols-outlined text-[22px] fill-1">group</span>
                <span class="text-sm font-semibold">User Management</span>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors group" href="../ads_management/index.php">
                <span class="material-symbols-outlined text-[22px] group-hover:text-primary transition-colors">campaign</span>
                <span class="text-sm font-medium">Ads &amp; Listings</span>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors group" href="../promotions_management/index.php">
                <span class="material-symbols-outlined text-[22px] group-hover:text-primary transition-colors">price_change</span>
                <span class="text-sm font-medium">Promotions & Pricing</span>
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
    <aside id="mobile-sidebar" class="fixed inset-y-0 left-0 w-64 bg-surface z-40 transform -translate-x-full transition-transform duration-300 md:hidden flex flex-col shadow-2xl">
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
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-primary/10 text-primary transition-colors" href="#">
                <span class="material-symbols-outlined text-[22px] fill-1">group</span>
                <span class="text-sm font-semibold">User Management</span>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors group" href="../ads_management/index.php">
                <span class="material-symbols-outlined text-[22px] group-hover:text-primary transition-colors">campaign</span>
                <span class="text-sm font-medium">Ads &amp; Listings</span>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors group" href="../promotions_management/index.php">
                <span class="material-symbols-outlined text-[22px] group-hover:text-primary transition-colors">price_change</span>
                <span class="text-sm font-medium">Promotions & Pricing</span>
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
                <button id="mobile-menu-btn" class="md:hidden p-2 text-slate-500 hover:text-primary">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <!-- Breadcrumbs -->
                <nav class="flex items-center text-sm text-slate-500">
                    <a class="hover:text-primary transition-colors" href="../admin_overview_dashboard/">Dashboard</a>
                    <span class="mx-2 text-slate-300">/</span>
                    <span class="text-slate-900 font-medium">User Management</span>
                </nav>
            </div>
        </header>
        <!-- Scrollable Content -->
        <main class="flex-1 overflow-y-auto p-6 md:p-8 scroll-smooth">
            <div class="max-w-7xl mx-auto flex flex-col gap-8">
                <!-- Title & Actions -->
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Users</h2>
                        <p class="text-slate-500 mt-1">Manage buyers, sellers, and platform administrators.</p>
                    </div>
                </div>

                <!-- Main Data Card -->
                <div class="bg-surface rounded-xl border border-slate-200 shadow-soft flex flex-col">
                    <!-- Data Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                                    <th class="px-6 py-4">User</th>
                                    <th class="px-6 py-4">Role</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4">Joined Date</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                <?php foreach ($users as $u): ?>
                                    <tr class="group hover:bg-slate-50 transition-colors cursor-pointer">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="size-10 rounded-full bg-cover bg-center border border-slate-200 bg-gray-200 text-gray-500 flex items-center justify-center font-bold" style='background-image: url("<?php echo $u['profile_image'] ?? ''; ?>");'>
                                                    <?php if (empty($u['profile_image'])) echo strtoupper(substr($u['username'], 0, 1)); ?>
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-slate-900"><?php echo sanitize($u['username']); ?></p>
                                                    <p class="text-slate-500 text-xs"><?php echo sanitize($u['email']); ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium 
    <?php echo $u['role'] == 'seller' ? 'bg-purple-50 text-purple-700 border border-purple-100' : ($u['role'] == 'admin' ? 'bg-red-50 text-red-700 border border-red-100' : 'bg-blue-50 text-blue-700 border border-blue-100'); ?>">
                                                <span class="size-1.5 rounded-full 
        <?php echo $u['role'] == 'seller' ? 'bg-purple-500' : ($u['role'] == 'admin' ? 'bg-red-500' : 'bg-blue-500'); ?>">
                                                </span>
                                                <?php echo ucfirst($u['role']); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php if ($u['is_verified']): ?>
                                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                    <span class="material-symbols-outlined text-[14px]">verified</span> Verified
                                                </div>
                                            <?php else: ?>
                                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-100">
                                                    <span class="material-symbols-outlined text-[14px]">hourglass_empty</span> Pending
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 text-slate-600">
                                            <?php echo date('M d, Y', strtotime($u['created_at'])); ?>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2 group-hover:opacity-100 transition-opacity">
                                                <div class="relative group/actions">
                                                    <button class="p-1.5 text-slate-400 hover:text-primary hover:bg-primary/10 rounded transition-colors">
                                                        <span class="material-symbols-outlined text-[20px]">more_vert</span>
                                                    </button>
                                                    <!-- Dropdown -->
                                                    <div class="absolute right-0 top-full mt-1 w-48 bg-white border border-slate-100 rounded-lg shadow-lg opacity-0 invisible group-hover/actions:opacity-100 group-hover/actions:visible transition-all z-10">
                                                        <div class="py-1">
                                                            <?php if (!$u['is_verified']): ?>
                                                                <a href="?action=verify&id=<?php echo $u['id']; ?>" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-primary">Verify User</a>
                                                            <?php else: ?>
                                                                <a href="?action=unverify&id=<?php echo $u['id']; ?>" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-amber-600">Unverify User</a>
                                                            <?php endif; ?>

                                                            <div class="border-t border-slate-100 my-1"></div>

                                                            <?php if ($u['role'] !== 'admin'): ?>
                                                                <a href="?action=make_admin&id=<?php echo $u['id']; ?>" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-primary">Make Admin</a>
                                                            <?php endif; ?>
                                                            <?php if ($u['role'] !== 'seller'): ?>
                                                                <a href="?action=make_seller&id=<?php echo $u['id']; ?>" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-primary">Make Seller</a>
                                                            <?php endif; ?>
                                                            <?php if ($u['role'] !== 'buyer'): ?>
                                                                <a href="?action=make_buyer&id=<?php echo $u['id']; ?>" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-primary">Make Buyer</a>
                                                            <?php endif; ?>

                                                            <div class="border-t border-slate-100 my-1"></div>
                                                            <a href="?action=delete&id=<?php echo $u['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?')" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">Delete User</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
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