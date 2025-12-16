<?php
require_once '../core/init.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password.";
    } else {
        $user = new User();
        if ($user->login($email, $password)) {
            // Redirect based on role
            if ($_SESSION['role'] === 'seller') {
                redirect('../seller_dashboard_home/');
            } elseif ($_SESSION['role'] === 'admin') {
                redirect('../admin_overview_dashboard/');
            } else {
                redirect('../home_page_-_onlinemarket.ng/');
            }
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>
<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Login - Onlinemarket.ng</title>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
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
                        "primary-hover": "#154dbf",
                        "background-light": "#f6f6f8",
                        "background-dark": "#111621",
                        "surface-light": "#ffffff",
                        "surface-dark": "#1a202c",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"],
                        "sans": ["Inter", "sans-serif"],
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "2xl": "1rem",
                        "full": "9999px"
                    },
                    boxShadow: {
                        'soft': '0 4px 20px rgba(25, 93, 230, 0.08)',
                        'glass': '0 8px 32px 0 rgba(31, 38, 135, 0.07)',
                    }
                },
            },
        }
    </script>
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-[#0e121b] dark:text-white min-h-screen flex flex-col antialiased">
    <!-- Navbar -->
    <header class="sticky top-0 z-50 w-full backdrop-blur-md bg-white/70 dark:bg-[#111621]/80 border-b border-[#e7ebf3] dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="../home_page_-_onlinemarket.ng/" class="flex-shrink-0 flex items-center gap-3 cursor-pointer">
                    <div class="size-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[24px]">shopping_bag</span>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-[#0e121b] dark:text-white">Onlinemarket.ng</span>
                </a>
                <!-- Right Side Actions -->
                <div class="flex items-center gap-4">
                    <span class="hidden sm:inline-block text-sm font-medium text-gray-500 dark:text-gray-400">Don't have an account?</span>
                    <a href="../register_page_-_onlinemarket.ng/" class="flex items-center justify-center rounded-lg h-9 px-4 bg-primary text-white text-sm font-semibold hover:bg-primary-hover transition-colors shadow-sm">
                        Register
                    </a>
                </div>
            </div>
        </div>
    </header>
    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center p-4 sm:p-6 lg:p-8 relative overflow-hidden">
        <!-- Decorative Background Elements -->
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-primary/5 blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-purple-500/5 blur-[120px] pointer-events-none"></div>
        <!-- Login Card Container -->
        <div class="w-full max-w-5xl bg-surface-light dark:bg-surface-dark rounded-2xl shadow-glass overflow-hidden flex flex-col lg:flex-row border border-white/50 dark:border-gray-700/50 relative z-10 min-h-[600px]">
            <!-- Left Side: Form -->
            <div class="w-full lg:w-1/2 p-8 sm:p-12 lg:p-16 flex flex-col justify-center">
                <!-- Header -->
                <div class="mb-10">
                    <h1 class="text-3xl sm:text-4xl font-black text-[#0e121b] dark:text-white tracking-tight mb-3">Welcome Back</h1>
                    <p class="text-gray-500 dark:text-gray-400 text-base leading-relaxed">
                        Log in to access your marketplace dashboard, manage ads, and chat with buyers.
                    </p>
                </div>

                <!-- Error Alert -->
                <?php if ($error): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline"><?php echo $error; ?></span>
                    </div>
                <?php endif; ?>

                <!-- Form -->
                <form class="space-y-6" method="POST" action="">
                    <!-- Email Input -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-[#0e121b] dark:text-white" for="email">
                            Email
                        </label>
                        <div class="relative group">
                            <input name="email" class="w-full h-12 px-4 pl-11 rounded-xl bg-gray-50 dark:bg-[#0e121b] border border-gray-200 dark:border-gray-700 text-[#0e121b] dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200 text-base" id="email" placeholder="Enter your email" type="email" required value="<?php echo isset($_POST['email']) ? sanitize($_POST['email']) : ''; ?>" />
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-primary transition-colors text-[20px]">
                                mail
                            </span>
                        </div>
                    </div>
                    <!-- Password Input -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="block text-sm font-semibold text-[#0e121b] dark:text-white" for="password">
                                Password
                            </label>
                            <a class="text-sm font-medium text-primary hover:text-primary-hover underline decoration-transparent hover:decoration-primary transition-all" href="#">
                                Forgot password?
                            </a>
                        </div>
                        <div class="relative group">
                            <input name="password" class="w-full h-12 px-4 pl-11 pr-11 rounded-xl bg-gray-50 dark:bg-[#0e121b] border border-gray-200 dark:border-gray-700 text-[#0e121b] dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200 text-base" id="password" placeholder="Enter your password" type="password" required />
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-primary transition-colors text-[20px]">
                                lock
                            </span>
                            <button class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 cursor-pointer" type="button" onclick="const p=document.getElementById('password'); p.type = p.type === 'password' ? 'text' : 'password';">
                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                            </button>
                        </div>
                    </div>
                    <!-- Submit Button -->
                    <button type="submit" class="w-full h-12 bg-primary hover:bg-primary-hover text-white font-bold rounded-xl shadow-lg shadow-primary/30 active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-2">
                        <span>Log In</span>
                        <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                    </button>
                </form>
                <!-- Divider -->
                <div class="relative my-8">
                    <div aria-hidden="true" class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200 dark:border-gray-700"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="bg-surface-light dark:bg-surface-dark px-3 text-sm text-gray-500">Or continue with</span>
                    </div>
                </div>
                <!-- Social Login -->
                <div class="grid grid-cols-2 gap-4">
                    <button class="flex items-center justify-center gap-3 h-12 px-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors duration-200 group">
                        <svg class="w-5 h-5" fill="none" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M23.766 12.2764C23.766 11.4607 23.6999 10.6406 23.5588 9.83807H12.24V14.4591H18.7217C18.4528 15.9494 17.5885 17.2678 16.323 18.1056V21.1039H20.19C22.4608 19.0139 23.766 15.9274 23.766 12.2764Z" fill="#4285F4"></path>
                            <path d="M12.2401 24.0008C15.4766 24.0008 18.2059 22.9382 20.1945 21.1039L16.3275 18.1055C15.2517 18.8375 13.8627 19.252 12.2445 19.252C9.11388 19.252 6.45946 17.1399 5.50705 14.3003H1.5166V17.3912C3.55371 21.4434 7.7029 24.0008 12.2401 24.0008Z" fill="#34A853"></path>
                            <path d="M5.50253 14.3003C5.00236 12.8199 5.00236 11.1799 5.50253 9.69951V6.60861H1.51649C-0.18551 10.0056 -0.18551 13.9945 1.51649 17.3915L5.50253 14.3003Z" fill="#FBBC05"></path>
                            <path d="M12.2401 4.74966C13.9509 4.7232 15.6044 5.36697 16.8434 6.54867L20.2695 3.12262C18.1001 1.0855 15.2208 -0.034466 12.2401 0.000808666C7.7029 0.000808666 3.55371 2.55822 1.5166 6.60861L5.50264 9.69951C6.45064 6.85993 9.10947 4.74966 12.2401 4.74966Z" fill="#EA4335"></path>
                        </svg>
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Google</span>
                    </button>
                    <button class="flex items-center justify-center gap-3 h-12 px-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors duration-200 group">
                        <svg class="w-5 h-5" fill="currentColor" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M14 13.5H16.5L17.5 9.5H14V7.5C14 6.47 14 5.5 16 5.5H17.5V2.14C17.174 2.097 15.943 2 14.643 2C11.928 2 10 3.657 10 6.7V9.5H7V13.5H10V22H14V13.5Z" fill="#1877F2"></path>
                        </svg>
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Facebook</span>
                    </button>
                </div>
                <!-- Mobile only Footer Link -->
                <div class="mt-8 text-center sm:hidden">
                    <p class="text-sm text-gray-500">
                        Don't have an account?
                        <a class="font-bold text-primary" href="../register_page_-_onlinemarket.ng/">Register</a>
                    </p>
                </div>
            </div>
            <!-- Right Side: Image/Promo -->
            <div class="hidden lg:block w-1/2 relative bg-gray-100 dark:bg-gray-800">
                <img alt="Abstract professional gradient with subtle business context" class="absolute inset-0 w-full h-full object-cover" data-alt="Abstract professional gradient with subtle business context" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAbQM-ufIMeoPO_ZapIFs2pDrP6qxIhr0NCkMlVrho4KhaiISse0blMXePU4U2oNhXuaUnZVN9_JDxzFGXkAkwzfb15qMhlA15_oWLSoBAbqlPD4tFvc2xwx53i1ZKFvAYy07n5wYThFD8xmWuIdX8Jvf2yxiLfQxmUSLCyNwr_CeXCOaBbzkU9ePJwZaf6MTiM7S4xjeGZIfz4dhOvktlAEPiyAWEMkx-Ah16OL00K8AH_g7pT5jzcfWagjdW-e2pHSSTPmu5YoSQ" />
                <div class="absolute inset-0 bg-gradient-to-t from-primary/90 to-primary/40 mix-blend-multiply"></div>
                <!-- Content Overlay -->
                <div class="absolute bottom-0 left-0 w-full p-12 text-white">
                    <div class="mb-6">
                        <div class="inline-flex items-center justify-center p-3 bg-white/20 backdrop-blur-md rounded-xl mb-6">
                            <span class="material-symbols-outlined text-3xl">storefront</span>
                        </div>
                        <h2 class="text-4xl font-bold mb-4 leading-tight">Start Buying &amp; Selling<br />With Confidence</h2>
                        <p class="text-white/90 text-lg leading-relaxed max-w-md">
                            Join over 2 million users on Nigeria's fastest growing online marketplace. Safe, fast, and easy.
                        </p>
                    </div>
                    <!-- Feature Tags -->
                    <div class="flex flex-wrap gap-3">
                        <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium border border-white/10">Verified Sellers</span>
                        <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium border border-white/10">Instant Chat</span>
                        <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium border border-white/10">Secure</span>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- Simple Footer -->
    <footer class="py-6 text-center text-sm text-gray-400 dark:text-gray-500">
        <div class="flex justify-center gap-6 mb-2">
            <a class="hover:text-primary transition-colors" href="#">Privacy Policy</a>
            <a class="hover:text-primary transition-colors" href="#">Terms of Service</a>
            <a class="hover:text-primary transition-colors" href="#">Help Center</a>
        </div>
        <p>© 2023 Onlinemarket.ng. All rights reserved.</p>
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
        <a href="../login_page_-_onlinemarket.ng/" class="flex flex-col items-center gap-1 text-primary">
            <span class="material-symbols-outlined text-[24px]">login</span>
            <span class="text-[10px] font-medium">Login</span>
        </a>
        <a href="../register_page_-_onlinemarket.ng/" class="flex flex-col items-center gap-1 text-slate-500 dark:text-slate-400 hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-[24px]">person_add</span>
            <span class="text-[10px] font-medium">Register</span>
        </a>
    </div>
</body>

</html>