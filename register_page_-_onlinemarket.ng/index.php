<?php
require_once '../core/init.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'] ?? 'buyer';

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        $user = new User();
        // Basic check for existing user is done in register method, but phone is separate.
        // I should probably update register method to handle phone, or update it here.
        // For now, I'll stick to the User class register method which takes (username, email, pass, role).
        // I need to update User class to handle phone number update after registration or modify the register method.

        // Let's modify the register call to include phone if I update the class, 
        // OR just register then update the phone.

        $result = $user->register($username, $email, $password, $role);

        if ($result === true) {
            // Update phone number manually for now since register doesn't take it
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("UPDATE users SET phone = :phone WHERE email = :email");
            $stmt->execute([':phone' => $phone, ':email' => $email]);

            $success = "Registration successful! You can now login.";
            // Optional: Auto login
            // redirect('../login_page_-_onlinemarket.ng/');
        } else {
            $error = $result;
        }
    }
}
?>
<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Register - Onlinemarket.ng</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&amp;family=Noto+Sans:wght@400;500;600;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#195de6",
                        "primary-hover": "#154bb8",
                        "background-light": "#f6f6f8",
                        "background-dark": "#111621",
                        "surface-light": "#ffffff",
                        "surface-dark": "#1a202c",
                        "text-main": "#0e121b",
                        "text-secondary": "#4e6797",
                        "border-light": "#e7ebf3",
                        "border-dark": "#2d3748",
                    },
                    fontFamily: {
                        "display": ["Inter", "Noto Sans", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.5rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "2xl": "1rem",
                        "full": "9999px"
                    },
                    boxShadow: {
                        'soft': '0 4px 20px -2px rgba(0, 0, 0, 0.05)',
                        'glow': '0 0 15px rgba(25, 93, 230, 0.15)',
                    }
                },
            },
        }
    </script>
    <style>
        /* Custom scrollbar for webkit */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 20px;
        }

        /* Glassmorphism utility */
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .dark .glass {
            background: rgba(17, 22, 33, 0.7);
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark min-h-screen flex flex-col font-display antialiased text-text-main dark:text-white transition-colors duration-300">
    <!-- Top Navigation -->
    <header class="sticky top-0 z-50 w-full glass border-b border-border-light dark:border-border-dark">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="../home_page_-_onlinemarket.ng/" class="flex items-center gap-2 cursor-pointer">
                    <div class="size-8 text-primary flex items-center justify-center rounded-lg bg-primary/10">
                        <span class="material-symbols-outlined text-2xl">storefront</span>
                    </div>
                    <h2 class="text-text-main dark:text-white text-xl font-bold tracking-tight">Onlinemarket.ng</h2>
                </a>
                <!-- Right Actions -->
                <div class="hidden sm:flex items-center gap-3">
                    <button class="flex items-center justify-center rounded-lg h-9 px-4 text-text-secondary hover:text-primary transition-colors text-sm font-semibold">
                        Help
                    </button>
                    <a href="../login_page_-_onlinemarket.ng/" class="flex items-center justify-center rounded-lg h-9 px-5 bg-primary/10 hover:bg-primary/20 text-primary text-sm font-bold transition-colors">
                        Log In
                    </a>
                </div>
                <!-- Mobile Menu Button -->
                <button class="sm:hidden p-2 text-text-secondary hover:text-primary">
                    <span class="material-symbols-outlined">menu</span>
                </button>
            </div>
        </div>
    </header>
    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center p-4 sm:p-6 lg:p-10 relative overflow-hidden">
        <!-- Background Decor -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
            <div class="absolute -top-[20%] -right-[10%] w-[600px] h-[600px] rounded-full bg-primary/5 blur-[100px]"></div>
            <div class="absolute top-[40%] -left-[10%] w-[500px] h-[500px] rounded-full bg-blue-400/5 blur-[80px]"></div>
        </div>
        <div class="flex w-full max-w-[1000px] bg-surface-light dark:bg-surface-dark rounded-2xl shadow-soft overflow-hidden border border-border-light dark:border-border-dark min-h-[600px]">
            <!-- Left Side: Form -->
            <div class="flex-1 flex flex-col justify-center p-8 sm:p-12 w-full max-w-lg mx-auto lg:max-w-none">
                <div class="mb-8 text-center sm:text-left">
                    <h1 class="text-3xl font-extrabold text-text-main dark:text-white tracking-tight mb-2">Create an account</h1>
                    <p class="text-text-secondary dark:text-slate-400">Join the largest marketplace in your neighborhood.</p>
                </div>

                <?php if ($error): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline"><?php echo $error; ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline"><?php echo $success; ?> <a href="../login_page_-_onlinemarket.ng/" class="font-bold underline">Login now</a></span>
                    </div>
                <?php endif; ?>

                <form class="space-y-5" method="POST" action="">
                    <!-- Name Input -->
                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-text-main dark:text-gray-200" for="fullname">Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-text-secondary">
                                <span class="material-symbols-outlined text-[20px]">person</span>
                            </div>
                            <input name="username" class="block w-full pl-10 pr-3 py-3 border border-border-light dark:border-border-dark rounded-lg bg-background-light/50 dark:bg-background-dark/50 text-text-main dark:text-white placeholder-text-secondary focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all sm:text-sm" id="fullname" placeholder="e.g. John Doe" type="text" required value="<?php echo isset($_POST['username']) ? sanitize($_POST['username']) : ''; ?>" />
                        </div>
                    </div>
                    <!-- Email Input -->
                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-text-main dark:text-gray-200" for="email">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-text-secondary">
                                <span class="material-symbols-outlined text-[20px]">mail</span>
                            </div>
                            <input name="email" class="block w-full pl-10 pr-3 py-3 border border-border-light dark:border-border-dark rounded-lg bg-background-light/50 dark:bg-background-dark/50 text-text-main dark:text-white placeholder-text-secondary focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all sm:text-sm" id="email" placeholder="name@example.com" type="email" required value="<?php echo isset($_POST['email']) ? sanitize($_POST['email']) : ''; ?>" />
                        </div>
                    </div>
                    <!-- Phone Input -->
                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-text-main dark:text-gray-200" for="phone">Phone Number</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-text-secondary">
                                <span class="material-symbols-outlined text-[20px]">call</span>
                            </div>
                            <input name="phone" class="block w-full pl-10 pr-3 py-3 border border-border-light dark:border-border-dark rounded-lg bg-background-light/50 dark:bg-background-dark/50 text-text-main dark:text-white placeholder-text-secondary focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all sm:text-sm" id="phone" placeholder="080 1234 5678" type="tel" required value="<?php echo isset($_POST['phone']) ? sanitize($_POST['phone']) : ''; ?>" />
                        </div>
                    </div>

                    <!-- Role Selection -->
                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-text-main dark:text-gray-200">I want to</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="flex items-center p-3 border border-border-light dark:border-border-dark rounded-lg cursor-pointer bg-background-light/50 dark:bg-background-dark/50 hover:bg-primary/5 transition-colors">
                                <input type="radio" name="role" value="buyer" class="h-4 w-4 text-primary focus:ring-primary border-gray-300" <?php echo (!isset($_POST['role']) || $_POST['role'] == 'buyer') ? 'checked' : ''; ?>>
                                <span class="ml-2 text-sm font-medium text-text-main dark:text-white">Buy Items</span>
                            </label>
                            <label class="flex items-center p-3 border border-border-light dark:border-border-dark rounded-lg cursor-pointer bg-background-light/50 dark:bg-background-dark/50 hover:bg-primary/5 transition-colors">
                                <input type="radio" name="role" value="seller" class="h-4 w-4 text-primary focus:ring-primary border-gray-300" <?php echo (isset($_POST['role']) && $_POST['role'] == 'seller') ? 'checked' : ''; ?>>
                                <span class="ml-2 text-sm font-medium text-text-main dark:text-white">Sell Items</span>
                            </label>
                        </div>
                    </div>

                    <!-- Password Group -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-sm font-medium text-text-main dark:text-gray-200" for="password">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-text-secondary">
                                    <span class="material-symbols-outlined text-[20px]">lock</span>
                                </div>
                                <input name="password" class="block w-full pl-10 pr-10 py-3 border border-border-light dark:border-border-dark rounded-lg bg-background-light/50 dark:bg-background-dark/50 text-text-main dark:text-white placeholder-text-secondary focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all sm:text-sm" id="password" placeholder="Min. 6 chars" type="password" required />
                                <button class="absolute inset-y-0 right-0 pr-3 flex items-center text-text-secondary hover:text-text-main cursor-pointer" type="button" onclick="const p=document.getElementById('password'); p.type = p.type === 'password' ? 'text' : 'password';">
                                    <span class="material-symbols-outlined text-[20px]">visibility_off</span>
                                </button>
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-sm font-medium text-text-main dark:text-gray-200" for="confirm_password">Confirm Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-text-secondary">
                                    <span class="material-symbols-outlined text-[20px]">lock_reset</span>
                                </div>
                                <input name="confirm_password" class="block w-full pl-10 pr-3 py-3 border border-border-light dark:border-border-dark rounded-lg bg-background-light/50 dark:bg-background-dark/50 text-text-main dark:text-white placeholder-text-secondary focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all sm:text-sm" id="confirm_password" placeholder="Retype password" type="password" required />
                            </div>
                        </div>
                    </div>
                    <!-- Checkbox -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary dark:bg-gray-700 dark:border-gray-600" id="terms" name="terms" type="checkbox" required />
                        </div>
                        <div class="ml-3 text-sm">
                            <label class="font-medium text-text-secondary" for="terms">I agree to the <a class="text-primary hover:underline" href="#">Terms of Service</a> and <a class="text-primary hover:underline" href="#">Privacy Policy</a>.</label>
                        </div>
                    </div>
                    <!-- Submit Button -->
                    <button class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-primary hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors shadow-glow mt-2" type="submit">
                        Create Account
                    </button>
                </form>
                <div class="mt-8">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-border-light dark:border-border-dark"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-surface-light dark:bg-surface-dark text-text-secondary">Or continue with</span>
                        </div>
                    </div>
                    <div class="mt-6 grid grid-cols-2 gap-3">
                        <a class="w-full inline-flex justify-center py-2.5 px-4 border border-border-light dark:border-border-dark rounded-lg shadow-sm bg-white dark:bg-gray-800 text-sm font-medium text-text-main dark:text-white hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" href="#">
                            <img alt="Google Logo" class="h-5 w-5 mr-2" data-alt="Google logo icon" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBpt33NVmnuJjQUQRyAQ829uYZXPI97DAKzRBBrej6vagnKhN3QP9xpsR2sub2mrl0EFGBJ8acVD6uAeG2cFuUGz3dbYkHN7e4LMdTrREYBLpODdY3Hr3hwEbrPazY9ikoy2HayPNRnxDhPZoIdl5sgq7MNZzPkvse9ATZQJvemLU1oGMUDvufiuW_TBhhFx_bOLuHhaC6cbj0ANk-q4Gvg00WyBXFBJcAMbOLMKzDn8QCtvUGiq7HJyi9mERfG2HLFIj9IIcfoG6c" />
                            <span>Google</span>
                        </a>
                        <a class="w-full inline-flex justify-center py-2.5 px-4 border border-border-light dark:border-border-dark rounded-lg shadow-sm bg-white dark:bg-gray-800 text-sm font-medium text-text-main dark:text-white hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" href="#">
                            <img alt="Facebook Logo" class="h-5 w-5 mr-2" data-alt="Facebook logo icon" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDwi6XOsVa9vW_Ynx-H3s7RewqfMgFC2Ld6gkzu-RI4NBdfP_an2V3wCS8cwkzaE_2aoVgd3hi2i9bmauWYk0jGH_gkgfZ5MUewv7iJgAiCPUPYBOmJ83k9JhcTbMYOR9qRGQbpnWF-pF3WkMN1U0Tagy0PVAHGXVSJnTDqy3T5QDYKvvLw-UKsvh4q37uXXdh6GZ1mzWF-z7OAnzRnbTP7aQ8KII5r1nuGk22fGvx2MEny5ihOq-6iytZeZtP7yeKPdF7jNC3Gcy4" />
                            <span>Facebook</span>
                        </a>
                    </div>
                </div>
                <p class="mt-8 text-center text-sm text-text-secondary">
                    Already have an account?
                    <a class="font-semibold text-primary hover:text-primary-hover" href="../login_page_-_onlinemarket.ng/">Log in</a>
                </p>
            </div>
            <!-- Right Side: Marketing/Image -->
            <div class="hidden lg:flex lg:flex-1 relative bg-background-light dark:bg-background-dark">
                <img alt="Happy shopper holding bags" class="absolute inset-0 w-full h-full object-cover" data-alt="Abstract happy person shopping online with soft lighting" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDhPjT2vu28IkaUyhEl6uzUIxUozMYLs5zKQKlzLxEVCm_Cv1JSAtJLrOmMthtdmAT19agrvbJEdClEtlfoqstzqyZ6E6pnbps-q4p708W3N4klK1AfdOlb_DLfZ28dBiED3CL_333wNJlThYGJMqrnudMWgAADoy1jwaSsoeLK26xq4l3p3lVFfqKxFZhthLYh1Towiycnc0epDTCtILoBG7XhVQV6YKjH1rxXemzf6SbhCjq1BAQWzyMr_qa7kxYcF7nTLLzFZlY" />
                <div class="absolute inset-0 bg-gradient-to-t from-primary/90 to-primary/40 mix-blend-multiply"></div>
                <div class="absolute bottom-0 left-0 right-0 p-12 text-white">
                    <div class="mb-6">
                        <span class="inline-flex items-center justify-center p-3 bg-white/20 backdrop-blur-sm rounded-xl mb-4">
                            <span class="material-symbols-outlined text-3xl">rocket_launch</span>
                        </span>
                        <h2 class="text-3xl font-bold mb-3">Sell faster, buy smarter.</h2>
                        <p class="text-white/90 text-lg leading-relaxed">Join over 2 million verified users trading securely every day. Your neighborhood marketplace is just a click away.</p>
                    </div>
                    <!-- Testimonial or Trust Badges -->
                    <div class="flex items-center gap-4 pt-6 border-t border-white/20">
                        <div class="flex -space-x-3">
                            <img alt="User avatar 1" class="w-10 h-10 rounded-full border-2 border-white" data-alt="Portrait of a smiling young woman" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBrhCMHnJcmpfhmNuPUn9z1nUSBMW7GHZbtTCV5WKmsXBjiPR-fHwRdm2UziEFNIdo4bMZsPn893P06GNDulK-rg0gi3FWvyfpEteWPMPWcennksB_sjPe-AaR8TzsBSKanOl17Vh57HtuCc9ChFieXhi7ozrBSstWsophksulQ1I1MhHTMTiKQsnMP3WYXhC_1SM0lFwmWZnXSnGhRkpKt5NQzMAPaOd1b2X1kh73MjE9ZPOPb2h_eybuxL4UUqlo7NBWeRbjK3qc" />
                            <img alt="User avatar 2" class="w-10 h-10 rounded-full border-2 border-white" data-alt="Portrait of a serious young man" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBcjjdOpstl9jx-k5Dd8YDkDGgWAjiZ4GEWG0T3t-Rv87VWwqiOJu1nqvsA4B4UI51ewbpnTrD4zwYiEylnvIw3owh7RsHrwWz2k3gPC700gHGDVGryfH7gb1j_-jxZHI5FAhpWglwcTZkkfiTz51oa2FPeSosYFvORyk_sgVs-DCTDTu0wzM5JRUoVS8YjfOWQqKO0JjyIVut10BJBAkdIjqkY4pZ1zT1k3iU1n7Ya6oS1ppnUgJjO6dg7RPu07ks-QmIua_Tk7sc" />
                            <img alt="User avatar 3" class="w-10 h-10 rounded-full border-2 border-white" data-alt="Portrait of a smiling man with glasses" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAReC9gr80IlkjFyKiJSWW7U3GKBnPDZNyE3VOxK_JYNIaDeP1vAZCbz-njvnX2ZUaGo7ReLKM3zQWoYgoLjRtpafzU8fsko2h0mTTBQxznpHrOf-guJCUZFov6igM586gpPgDiOERL-mzwzIzVTrvIkp86FOTAQagL4PcAFcZAFlkHy718oikuaE6ueiExdT3Gb9fiqTc5kkY5qoQhn8Egz_mTDjkTEz_gJabfuFMEdNWtxKGnIq1NvrfH47KM_Uri1gkbG3nX5Sg" />
                        </div>
                        <div class="text-sm font-medium">
                            <p>Trusted by locals</p>
                            <div class="flex text-yellow-400 text-xs">
                                <span class="material-symbols-outlined text-[16px] fill-current">star</span>
                                <span class="material-symbols-outlined text-[16px] fill-current">star</span>
                                <span class="material-symbols-outlined text-[16px] fill-current">star</span>
                                <span class="material-symbols-outlined text-[16px] fill-current">star</span>
                                <span class="material-symbols-outlined text-[16px] fill-current">star</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- Simple Footer -->
    <footer class="border-t border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="text-sm text-text-secondary">© 2023 Onlinemarket.ng. All rights reserved.</p>
            <div class="flex gap-6 text-sm text-text-secondary font-medium">
                <a class="hover:text-primary transition-colors" href="#">Privacy</a>
                <a class="hover:text-primary transition-colors" href="#">Terms</a>
                <a class="hover:text-primary transition-colors" href="#">Support</a>
            </div>
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
        <a href="../login_page_-_onlinemarket.ng/" class="flex flex-col items-center gap-1 text-slate-500 dark:text-slate-400 hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-[24px]">login</span>
            <span class="text-[10px] font-medium">Login</span>
        </a>
        <a href="../register_page_-_onlinemarket.ng/" class="flex flex-col items-center gap-1 text-primary">
            <span class="material-symbols-outlined text-[24px]">person_add</span>
            <span class="text-[10px] font-medium">Register</span>
        </a>
    </div>
</body>

</html>