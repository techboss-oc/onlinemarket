<?php
require_once '../core/init.php';

if (!isLoggedIn() || $_SESSION['role'] !== 'seller') {
    redirect('../login_page_-_onlinemarket.ng/');
}

$adModel = new Ad();
$user_id = $_SESSION['user_id'];

// Handle Delete
if (isset($_GET['delete'])) {
    $ad_id = $_GET['delete'];
    if ($adModel->delete($ad_id, $user_id)) {
        // Success
        header("Location: index.php");
        exit();
    }
}

$my_ads = $adModel->getByUserId($user_id);
?>
<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>My Ads - Onlinemarket.ng</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#195de6",
                        "primary-light": "#eef4ff",
                        "primary-hover": "#144bc4",
                        "background-light": "#f8f9fc",
                        "background-dark": "#111621",
                        "surface-white": "#ffffff",
                        "text-main": "#0e121b",
                        "text-secondary": "#4e6797",
                        "border-color": "#e7ebf3",
                        "success": "#10b981",
                        "warning": "#f59e0b",
                        "danger": "#ef4444",
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
                        "soft": "0 4px 20px rgba(25, 93, 230, 0.08)",
                        "card": "0 2px 8px rgba(0, 0, 0, 0.04)",
                    }
                },
            },
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24
        }

        .material-symbols-outlined.filled {
            font-variation-settings: 'FILL' 1;
        }

        /* Custom scrollbar for webkit */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>

<body class="bg-background-light text-text-main h-screen overflow-hidden flex flex-col">
    <!-- Top Navigation (Mobile/Tablet usually, but kept for desktop structure or future use) -->
    <!-- In this layout, we are focusing on a sidebar layout as per request context -->
    <div class="flex h-full w-full overflow-hidden">
        <!-- Sidebar Navigation -->
        <aside class="w-64 bg-surface-white border-r border-border-color flex-col hidden lg:flex h-full shrink-0 z-20">
            <div class="p-6 flex items-center gap-2 border-b border-border-color/50">
                <a href="../home_page_-_onlinemarket.ng/" class="flex items-center gap-2">
                    <div class="size-8 bg-primary rounded-lg flex items-center justify-center text-white">
                        <span class="material-symbols-outlined">shopping_bag</span>
                    </div>
                    <h1 class="text-xl font-bold tracking-tight text-primary">Onlinemarket<span class="text-text-main">.ng</span></h1>
                </a>
            </div>
            <div class="flex-1 overflow-y-auto py-6 px-4 flex flex-col gap-6">
                <!-- User Profile Snippet -->
                <div class="flex items-center gap-3 px-2 mb-2">
                    <div class="bg-center bg-no-repeat bg-cover rounded-full size-12 shadow-sm border-2 border-white ring-1 ring-border-color flex items-center justify-center bg-gray-200 text-gray-500 font-bold text-lg">
                        <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                    </div>
                    <div class="flex flex-col overflow-hidden">
                        <h2 class="text-text-main text-sm font-semibold truncate"><?php echo sanitize($_SESSION['username']); ?></h2>
                        <p class="text-text-secondary text-xs font-medium flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px] text-primary filled">verified</span> Verified Seller
                        </p>
                    </div>
                </div>
                <!-- Navigation Links -->
                <nav class="flex flex-col gap-1">
                    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-text-secondary hover:bg-primary-light hover:text-primary transition-colors group" href="../seller_dashboard_home/">
                        <span class="material-symbols-outlined text-[20px] group-hover:text-primary transition-colors">dashboard</span>
                        <span class="text-sm font-medium">Dashboard</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-primary/10 text-primary transition-colors" href="#">
                        <span class="material-symbols-outlined text-[20px] filled">list_alt</span>
                        <span class="text-sm font-bold">My Ads</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-text-secondary hover:bg-primary-light hover:text-primary transition-colors group" href="../seller_chat_/_messaging/">
                        <span class="material-symbols-outlined text-[20px] group-hover:text-primary transition-colors">chat_bubble</span>
                        <span class="text-sm font-medium">Messages</span>
                        <span class="ml-auto bg-primary text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">0</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-text-secondary hover:bg-primary-light hover:text-primary transition-colors group" href="../wallet_&_payments/">
                        <span class="material-symbols-outlined text-[20px] group-hover:text-primary transition-colors">account_balance_wallet</span>
                        <span class="text-sm font-medium">Wallet</span>
                    </a>
                </nav>
            </div>
            <div class="p-4 border-t border-border-color">
                <a href="../post_new_ad_form/" class="flex w-full items-center justify-center gap-2 rounded-lg h-10 bg-primary text-white text-sm font-bold shadow-soft hover:bg-primary-hover transition-all transform active:scale-95">
                    <span class="material-symbols-outlined text-[20px]">add</span>
                    <span>Post New Ad</span>
                </a>
            </div>
        </aside>
        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col h-full overflow-hidden bg-background-light relative">
            <!-- Sticky Header Backdrop (Glassmorphism) -->
            <div class="absolute top-0 left-0 w-full h-24 bg-background-light/80 backdrop-blur-md z-10 pointer-events-none border-b border-transparent"></div>
            <div class="flex-1 overflow-y-auto z-0 pt-8 pb-10 px-6 md:px-10 lg:px-12 scroll-smooth">
                <div class="max-w-6xl mx-auto space-y-8 relative">
                    <!-- Page Heading -->
                    <div class="flex flex-wrap items-end justify-between gap-4 relative z-20">
                        <div class="flex flex-col gap-1">
                            <h1 class="text-text-main text-3xl font-black tracking-tight">My Advertisements</h1>
                            <p class="text-text-secondary text-base">Manage your active, pending, and closed listings effectively.</p>
                        </div>
                        <div class="flex gap-3">
                            <a href="../post_new_ad_form/" class="flex md:hidden h-10 items-center justify-center gap-2 rounded-lg bg-primary text-white px-4 text-sm font-bold shadow-soft">
                                <span class="material-symbols-outlined text-[20px]">add</span>
                                <span>Post Ad</span>
                            </a>
                        </div>
                    </div>

                    <!-- Tabs & Content -->
                    <div class="flex flex-col gap-6 relative z-20">
                        <!-- Custom Tabs -->
                        <div class="border-b border-border-color">
                            <div class="flex gap-8">
                                <button class="relative pb-4 text-sm font-bold text-primary border-b-[3px] border-primary transition-all">
                                    All Ads <span class="ml-1 bg-primary/10 text-primary px-2 py-0.5 rounded-full text-xs"><?php echo count($my_ads); ?></span>
                                </button>
                            </div>
                        </div>
                        <!-- Ad List Grid -->
                        <div class="grid grid-cols-1 gap-4">
                            <?php if (empty($my_ads)): ?>
                                <div class="text-center py-10">
                                    <p class="text-text-secondary">You haven't posted any ads yet.</p>
                                    <a href="../post_new_ad_form/" class="text-primary font-bold hover:underline">Post your first ad</a>
                                </div>
                            <?php else: ?>
                                <?php foreach ($my_ads as $ad): ?>
                                    <!-- Card -->
                                    <div class="bg-surface-white rounded-xl p-4 shadow-card hover:shadow-soft border border-transparent hover:border-primary/20 transition-all duration-300 group flex flex-col sm:flex-row gap-4">
                                        <!-- Image -->
                                        <div class="relative w-full sm:w-48 h-48 sm:h-32 shrink-0 rounded-lg overflow-hidden bg-gray-100">
                                            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-110" style='background-image: url("<?php echo $ad['image_url'] ?? 'https://via.placeholder.com/300'; ?>");'></div>
                                            <?php if ($ad['is_featured']): ?>
                                                <div class="absolute top-2 left-2 bg-yellow-400 text-yellow-900 text-[10px] font-bold px-2 py-1 rounded shadow-sm flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-[12px] filled">bolt</span> BOOSTED
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <!-- Content -->
                                        <div class="flex-1 flex flex-col justify-between">
                                            <div>
                                                <div class="flex justify-between items-start">
                                                    <div>
                                                        <h3 class="text-lg font-bold text-text-main group-hover:text-primary transition-colors"><?php echo sanitize($ad['title']); ?></h3>
                                                        <p class="text-text-secondary text-sm mt-1"><?php echo sanitize($ad['category_name']); ?></p>
                                                    </div>
                                                    <div class="flex flex-col items-end">
                                                        <span class="text-xl font-black text-text-main">₦<?php echo number_format($ad['price']); ?></span>
                                                        <span class="text-xs text-text-secondary">Posted <?php echo time_elapsed_string($ad['created_at']); ?></span>
                                                    </div>
                                                </div>
                                                <!-- Stats -->
                                                <div class="flex items-center gap-6 mt-4">
                                                    <div class="flex items-center gap-1.5 text-text-secondary" title="Views">
                                                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                                                        <span class="text-sm font-medium"><?php echo $ad['views_count']; ?></span>
                                                    </div>
                                                    <div class="ml-auto inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-success/10 text-success text-xs font-bold border border-success/20">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-success"></span> <?php echo ucfirst($ad['status']); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Actions -->
                                        <div class="flex sm:flex-col justify-between sm:justify-center gap-2 sm:border-l border-border-color sm:pl-4 min-w-[120px]">
                                            <div class="flex gap-2">
                                                <a href="?delete=<?php echo $ad['id']; ?>" onclick="return confirm('Are you sure you want to delete this ad?');" class="flex-1 flex items-center justify-center gap-1 bg-background-light hover:bg-red-50 text-text-main hover:text-danger border border-transparent hover:border-red-100 text-xs font-bold py-2 px-3 rounded-lg transition-all" title="Delete">
                                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- Pagination -->
                    <div class="flex items-center justify-between border-t border-border-color pt-6">
                        <p class="text-sm text-text-secondary">Showing <span class="font-bold text-text-main"><?php echo count($my_ads); ?></span> results</p>
                    </div>
                </div>
                <!-- Bottom spacing -->
                <div class="h-10"></div>
            </div>
    </div>
    </main>
    </div>
    <!-- Sticky Bottom Mobile Navigation -->
    <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white dark:bg-[#1a202c] border-t border-slate-200 dark:border-slate-800 px-4 py-2 z-50 flex justify-between items-center shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
        <a href="../seller_dashboard_home/index.php" class="flex flex-col items-center gap-1 text-slate-500 dark:text-slate-400 hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-[24px]">dashboard</span>
            <span class="text-[10px] font-medium">Home</span>
        </a>
        <a href="../my_ads_page/index.php" class="flex flex-col items-center gap-1 text-primary">
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
</body>

</html>