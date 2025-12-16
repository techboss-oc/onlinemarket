<?php
require_once '../core/init.php';

if (!isLoggedIn() || $_SESSION['role'] !== 'seller') {
    redirect('../login_page_-_onlinemarket.ng/');
}

$user = getCurrentUser();
?>
<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Wallet &amp; Payments - Seller Dashboard</title>
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
                        "primary-dark": "#1041a8",
                        "background-light": "#f6f6f8",
                        "background-dark": "#111621",
                        "card-light": "#ffffff",
                        "card-dark": "#1e2433",
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
                        "glass": "0 8px 32px 0 rgba(31, 38, 135, 0.07)",
                    }
                },
            },
        }
    </script>
    <style>
        /* Custom scrollbar for webkit */
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

        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .dark .glass-panel {
            background: rgba(30, 36, 51, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-white antialiased overflow-hidden">
    <div class="flex h-screen w-full">
        <!-- Sidebar -->
        <aside class="hidden lg:flex flex-col w-64 h-full bg-white dark:bg-card-dark border-r border-slate-200 dark:border-slate-800 shrink-0 z-20 transition-colors duration-300">
            <div class="p-6 pb-2">
                <div class="flex items-center gap-3">
                    <div class="relative flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-primary to-blue-400 text-white shadow-lg shadow-primary/30">
                        <span class="material-symbols-outlined text-2xl">shopping_bag</span>
                    </div>
                    <div>
                        <h1 class="text-slate-900 dark:text-white text-lg font-bold leading-none tracking-tight">Onlinemarket</h1>
                        <p class="text-slate-500 dark:text-slate-400 text-xs font-medium mt-1">Seller Dashboard</p>
                    </div>
                </div>
            </div>
            <div class="flex flex-col flex-1 px-4 py-6 gap-2 overflow-y-auto">
                <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all group" href="../seller_dashboard_home/">
                    <span class="material-symbols-outlined text-2xl group-hover:text-primary transition-colors">dashboard</span>
                    <span class="font-medium">Dashboard</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all group" href="../my_ads_page/">
                    <span class="material-symbols-outlined text-2xl group-hover:text-primary transition-colors">storefront</span>
                    <span class="font-medium">My Ads</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all group" href="../seller_chat_/_messaging/">
                    <span class="material-symbols-outlined text-2xl group-hover:text-primary transition-colors">chat_bubble</span>
                    <span class="font-medium">Messages</span>
                    <span class="ml-auto bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">0</span>
                </a>
                <!-- Active State -->
                <a class="relative flex items-center gap-3 px-4 py-3 rounded-xl bg-primary/10 text-primary dark:text-blue-400 font-semibold shadow-sm overflow-hidden" href="#">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary rounded-r-full"></div>
                    <span class="material-symbols-outlined text-2xl fill-current">account_balance_wallet</span>
                    <span>Wallet &amp; Payments</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all group" href="#">
                    <span class="material-symbols-outlined text-2xl group-hover:text-primary transition-colors">analytics</span>
                    <span class="font-medium">Performance</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all group" href="#">
                    <span class="material-symbols-outlined text-2xl group-hover:text-primary transition-colors">settings</span>
                    <span class="font-medium">Settings</span>
                </a>
            </div>
            <div class="p-4 mt-auto">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700">
                    <div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-700 bg-cover bg-center" style='background-image: url("<?php echo $user['profile_image'] ?? 'https://via.placeholder.com/150'; ?>")'></div>
                    <div class="flex flex-col overflow-hidden">
                        <p class="text-sm font-bold text-slate-900 dark:text-white truncate"><?php echo sanitize($user['username']); ?></p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate">Premium Seller</p>
                    </div>
                </div>
                <a href="../post_new_ad_form/" class="mt-4 w-full flex items-center justify-center gap-2 bg-primary hover:bg-primary-dark text-white h-11 rounded-xl font-bold text-sm shadow-lg shadow-primary/20 transition-all transform active:scale-95">
                    <span class="material-symbols-outlined text-xl">add_circle</span>
                    Post Ad
                </a>
            </div>
        </aside>
        <!-- Main Content -->
        <main class="flex-1 flex flex-col h-full overflow-hidden relative">
            <!-- Top Navigation / Header -->
            <header class="h-16 flex items-center justify-between px-8 py-4 shrink-0 bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-md z-10 sticky top-0">
                <div class="flex items-center gap-4 lg:hidden">
                    <button class="text-slate-500 hover:text-primary">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                    <span class="font-bold text-lg text-slate-900 dark:text-white">Wallet</span>
                </div>
                <div class="hidden lg:flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
                    <span>Dashboard</span>
                    <span class="material-symbols-outlined text-xs">chevron_right</span>
                    <span class="font-semibold text-slate-900 dark:text-white">Wallet &amp; Payments</span>
                </div>
                <div class="flex items-center gap-4">
                    <button class="relative p-2 text-slate-500 hover:text-primary transition-colors">
                        <span class="material-symbols-outlined">notifications</span>
                        <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border border-white dark:border-background-dark"></span>
                    </button>
                </div>
            </header>
            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto p-4 md:p-8 pt-2 scroll-smooth">
                <div class="max-w-6xl mx-auto flex flex-col gap-8">
                    <!-- Page Heading -->
                    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                        <div>
                            <h2 class="text-3xl font-black tracking-tight text-slate-900 dark:text-white">Wallet &amp; Payments</h2>
                            <p class="text-slate-500 dark:text-slate-400 mt-1">Manage your earnings, deposits, and promotional spending.</p>
                        </div>
                        <div class="flex items-center gap-2 bg-white dark:bg-card-dark rounded-lg p-1 shadow-sm border border-slate-100 dark:border-slate-700">
                            <button class="px-4 py-1.5 text-xs font-semibold rounded bg-slate-100 dark:bg-slate-700 text-slate-900 dark:text-white">This Month</button>
                            <button class="px-4 py-1.5 text-xs font-semibold rounded text-slate-500 hover:text-primary hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">Last Month</button>
                        </div>
                    </div>
                    <!-- Hero Section: Balance Cards -->
                    <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Main Balance Card -->
                        <div class="md:col-span-2 relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#195de6] to-[#4383f1] text-white shadow-lg shadow-blue-500/20 p-8 flex flex-col justify-between min-h-[220px]">
                            <!-- Abstract decoration -->
                            <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/4"></div>
                            <div class="absolute bottom-0 left-0 w-48 h-48 bg-black opacity-10 rounded-full blur-2xl translate-y-1/3 -translate-x-1/4"></div>
                            <div class="relative z-10 flex justify-between items-start">
                                <div>
                                    <p class="text-blue-100 font-medium text-sm flex items-center gap-2">
                                        <span class="material-symbols-outlined text-lg">account_balance_wallet</span>
                                        Available Balance
                                    </p>
                                    <h3 class="text-4xl md:text-5xl font-bold mt-2 tracking-tight">₦ 0.00</h3>
                                    <div class="flex items-center gap-2 mt-2 text-blue-100 text-sm bg-white/10 w-fit px-2 py-1 rounded-lg backdrop-blur-sm">
                                        <span class="material-symbols-outlined text-base">trending_up</span>
                                        <span>+0% vs last month</span>
                                    </div>
                                </div>
                            </div>
                            <div class="relative z-10 flex flex-wrap gap-3 mt-8">
                                <button class="flex items-center justify-center gap-2 px-6 py-3 bg-white text-primary font-bold rounded-xl shadow-lg hover:shadow-xl hover:bg-slate-50 transition-all transform active:scale-95 text-sm">
                                    <span class="material-symbols-outlined text-xl">add</span>
                                    Fund Wallet
                                </button>
                                <button class="flex items-center justify-center gap-2 px-6 py-3 bg-blue-700/40 hover:bg-blue-700/60 text-white border border-white/20 font-bold rounded-xl backdrop-blur-md transition-all text-sm">
                                    <span class="material-symbols-outlined text-xl">payments</span>
                                    Withdraw Funds
                                </button>
                            </div>
                        </div>
                        <!-- Secondary Stats Card -->
                        <div class="flex flex-col gap-4">
                            <div class="flex-1 rounded-2xl bg-white dark:bg-card-dark border border-slate-100 dark:border-slate-800 p-6 shadow-soft flex flex-col justify-center">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Pending Clearance</p>
                                    <span class="material-symbols-outlined text-orange-400">schedule</span>
                                </div>
                                <p class="text-2xl font-bold text-slate-900 dark:text-white">₦ 0.00</p>
                                <p class="text-xs text-slate-400 mt-1">Available in ~2 days</p>
                            </div>
                            <div class="flex-1 rounded-2xl bg-white dark:bg-card-dark border border-slate-100 dark:border-slate-800 p-6 shadow-soft flex flex-col justify-center">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Monthly Ad Spend</p>
                                    <span class="material-symbols-outlined text-purple-400">rocket_launch</span>
                                </div>
                                <p class="text-2xl font-bold text-slate-900 dark:text-white">₦ 0.00</p>
                                <div class="w-full bg-slate-100 dark:bg-slate-700 h-1.5 rounded-full mt-3 overflow-hidden">
                                    <div class="bg-purple-500 h-full rounded-full w-0"></div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- Main Content Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-8">
                        <!-- Left Column: Transactions -->
                        <div class="lg:col-span-2 flex flex-col gap-6">
                            <!-- Filter Tabs -->
                            <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-hide">
                                <button class="px-5 py-2 rounded-full bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-sm font-bold shadow-md whitespace-nowrap">
                                    All Transactions
                                </button>
                                <button class="px-5 py-2 rounded-full bg-white dark:bg-card-dark text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-800 whitespace-nowrap transition-colors">
                                    Deposits
                                </button>
                                <button class="px-5 py-2 rounded-full bg-white dark:bg-card-dark text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-800 whitespace-nowrap transition-colors">
                                    Expenses
                                </button>
                                <button class="px-5 py-2 rounded-full bg-white dark:bg-card-dark text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-800 whitespace-nowrap transition-colors">
                                    Withdrawals
                                </button>
                            </div>
                            <!-- Transactions Table Card -->
                            <div class="bg-white dark:bg-card-dark rounded-2xl shadow-soft border border-slate-100 dark:border-slate-800 overflow-hidden">
                                <div class="flex items-center justify-between p-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">Transaction History</h3>
                                </div>
                                <div class="p-8 text-center text-gray-500">
                                    No transactions yet.
                                </div>
                            </div>
                        </div>
                        <!-- Right Column: Tools -->
                        <div class="flex flex-col gap-6">
                            <!-- Payment Methods Widget -->
                            <div class="bg-white dark:bg-card-dark rounded-2xl shadow-soft border border-slate-100 dark:border-slate-800 p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">Payment Methods</h3>
                                    <button class="text-primary text-sm font-bold hover:underline">Add New</button>
                                </div>
                                <div class="flex flex-col gap-3">
                                    <div class="p-4 text-center text-sm text-gray-500 bg-gray-50 rounded-xl">
                                        No payment methods added.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
        <a href="../wallet_&_payments/index.php" class="flex flex-col items-center gap-1 text-primary">
            <span class="material-symbols-outlined text-[24px]">account_balance_wallet</span>
            <span class="text-[10px] font-medium">Wallet</span>
        </a>
    </div>
</body>

</html>