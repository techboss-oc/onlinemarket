<?php
require_once '../core/init.php';

if (!isLoggedIn()) {
    redirect('../login_page_-_onlinemarket.ng/');
}

$userModel = new User();
$user = getCurrentUser();
$error = '';
$success = '';

// Handle Profile Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $username = trim($_POST['username']); // Combined or single field
        $phone = trim($_POST['phone']);
        $location = trim($_POST['location']);
        $bio = trim($_POST['bio']);

        $data = [
            'username' => $username,
            'phone' => $phone,
            'location' => $location,
            'bio' => $bio
        ];

        // Handle Image Upload
        if (isset($_FILES['profile_image']) && !empty($_FILES['profile_image']['name'])) {
            $target_dir = "../uploads/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $file_name = time() . "_" . basename($_FILES["profile_image"]["name"]);
            $target_file = $target_dir . $file_name;

            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                $data['profile_image'] = 'http://market.test/uploads/' . $file_name;
            }
        }

        if ($userModel->updateProfile($user['id'], $data)) {
            $success = "Profile updated successfully.";
            // Refresh user data
            $user = getCurrentUser();
            // Update session username if changed
            $_SESSION['username'] = $user['username'];
        } else {
            $error = "Failed to update profile.";
        }
    } elseif (isset($_POST['update_password'])) {
        $current = $_POST['current_password'];
        $new = $_POST['new_password'];
        $confirm = $_POST['confirm_password'];

        if ($new !== $confirm) {
            $error = "New passwords do not match.";
        } else {
            if ($userModel->updatePassword($user['id'], $current, $new)) {
                $success = "Password updated successfully.";
            } else {
                $error = "Incorrect current password.";
            }
        }
    }
}
?>
<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Profile Settings - Onlinemarket.ng</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet" />
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
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-[#0e121b] min-h-screen flex flex-col">
    <!-- Top Navigation -->
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-[#e7ebf3] w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-8">
                    <a href="../home_page_-_onlinemarket.ng/" class="flex items-center gap-2">
                        <div class="size-8 text-primary bg-primary/10 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined">shopping_bag</span>
                        </div>
                        <h2 class="text-xl font-bold tracking-tight">Onlinemarket.ng</h2>
                    </a>
                    <nav class="hidden md:flex items-center gap-6">
                        <a class="text-sm font-medium hover:text-primary transition-colors" href="../home_page_-_onlinemarket.ng/">Home</a>
                        <a class="text-sm font-medium hover:text-primary transition-colors" href="<?php echo $_SESSION['role'] == 'seller' ? '../seller_dashboard_home/' : '../buyer_dashboard/'; ?>">Dashboard</a>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- Main Content -->
    <div class="flex-grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar -->
            <aside class="w-full lg:w-64 flex-shrink-0">
                <div class="sticky top-24 space-y-6">
                    <div class="bg-white rounded-xl p-5 shadow-sm border border-[#e7ebf3]">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="bg-center bg-no-repeat bg-cover rounded-full size-12 bg-gray-200" style='background-image: url("<?php echo $user['profile_image'] ?? 'https://via.placeholder.com/150'; ?>");'></div>
                            <div>
                                <h3 class="font-bold text-base"><?php echo sanitize($user['username']); ?></h3>
                                <p class="text-xs text-gray-500"><?php echo ucfirst($user['role']); ?></p>
                            </div>
                        </div>
                        <div class="h-px bg-[#e7ebf3] w-full mb-4"></div>
                        <div class="flex items-center gap-2 text-gray-500">
                            <span class="material-symbols-outlined text-[18px] text-primary">verified</span>
                            <span class="text-xs font-medium">Verified User</span>
                        </div>
                    </div>
                </div>
            </aside>
            <!-- Main Area -->
            <main class="flex-1 min-w-0 space-y-6">
                <div class="flex items-center justify-between">
                    <h1 class="text-3xl font-bold tracking-tight">Settings</h1>
                </div>

                <?php if ($error): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline"><?php echo $error; ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline"><?php echo $success; ?></span>
                    </div>
                <?php endif; ?>

                <!-- Profile Form -->
                <form method="POST" enctype="multipart/form-data">
                    <section class="rounded-xl overflow-hidden shadow-sm relative group bg-white border border-[#e7ebf3] mb-6">
                        <div class="h-32 bg-gradient-to-r from-blue-600 to-indigo-600 relative"></div>
                        <div class="px-6 pb-6">
                            <div class="flex flex-col sm:flex-row items-end -mt-12 gap-6">
                                <div class="relative">
                                    <div class="size-32 rounded-full border-4 border-white shadow-md bg-cover bg-center bg-gray-200" style='background-image: url("<?php echo $user['profile_image'] ?? 'https://via.placeholder.com/150'; ?>");'></div>
                                    <label class="absolute bottom-1 right-1 bg-white p-2 rounded-full shadow-md hover:text-primary transition-colors border border-[#e7ebf3] cursor-pointer">
                                        <span class="material-symbols-outlined text-[20px] block">edit</span>
                                        <input type="file" name="profile_image" class="hidden" accept="image/*">
                                    </label>
                                </div>
                                <div class="flex-1 pb-2">
                                    <h2 class="text-2xl font-bold"><?php echo sanitize($user['username']); ?></h2>
                                    <p class="text-gray-500">Update your photo and details.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="bg-white rounded-xl border border-[#e7ebf3] shadow-sm p-6 lg:p-8 mb-6">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="material-symbols-outlined text-primary text-[28px]">badge</span>
                            <h2 class="text-xl font-bold">Personal Details</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-semibold">Full Name</label>
                                <input name="username" class="block w-full rounded-lg border-gray-300 bg-gray-50 focus:border-primary focus:ring-primary/20 sm:text-sm py-3 px-4" type="text" value="<?php echo sanitize($user['username']); ?>" />
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold">Email Address</label>
                                <input class="block w-full rounded-lg border-gray-300 bg-gray-100 text-gray-500 cursor-not-allowed sm:text-sm py-3 px-4" disabled value="<?php echo sanitize($user['email']); ?>" />
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold">Phone Number</label>
                                <input name="phone" class="block w-full rounded-lg border-gray-300 bg-gray-50 focus:border-primary focus:ring-primary/20 sm:text-sm py-3 px-4" type="tel" value="<?php echo sanitize($user['phone'] ?? ''); ?>" />
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold">Location</label>
                                <input name="location" class="block w-full rounded-lg border-gray-300 bg-gray-50 focus:border-primary focus:ring-primary/20 sm:text-sm py-3 px-4" type="text" value="<?php echo sanitize($user['location'] ?? ''); ?>" />
                            </div>
                            <div class="space-y-2 md:col-span-2">
                                <label class="text-sm font-semibold">Bio</label>
                                <textarea name="bio" class="block w-full rounded-lg border-gray-300 bg-gray-50 focus:border-primary focus:ring-primary/20 sm:text-sm py-3 px-4 resize-none" rows="3"><?php echo sanitize($user['bio'] ?? ''); ?></textarea>
                            </div>
                        </div>
                        <div class="pt-4 flex justify-end">
                            <button name="update_profile" class="bg-primary hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-md transition-all" type="submit">
                                Save Changes
                            </button>
                        </div>
                    </section>
                </form>

                <!-- Password Section -->
                <form method="POST">
                    <section class="bg-white rounded-xl border border-[#e7ebf3] shadow-sm p-6 lg:p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="material-symbols-outlined text-primary text-[28px]">shield_lock</span>
                            <h2 class="text-xl font-bold">Security</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2 md:col-span-2">
                                <label class="text-sm font-semibold">Current Password</label>
                                <input name="current_password" class="block w-full md:w-1/2 rounded-lg border-gray-300 bg-gray-50 focus:border-primary focus:ring-primary/20 sm:text-sm py-3 px-4" type="password" required />
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold">New Password</label>
                                <input name="new_password" class="block w-full rounded-lg border-gray-300 bg-gray-50 focus:border-primary focus:ring-primary/20 sm:text-sm py-3 px-4" type="password" required />
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold">Confirm New Password</label>
                                <input name="confirm_password" class="block w-full rounded-lg border-gray-300 bg-gray-50 focus:border-primary focus:ring-primary/20 sm:text-sm py-3 px-4" type="password" required />
                            </div>
                        </div>
                        <div class="flex justify-end pt-4">
                            <button name="update_password" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium py-2 px-5 rounded-lg transition-colors" type="submit">
                                Update Password
                            </button>
                        </div>
                    </section>
                </form>

            </main>
        </div>
    </div>
</body>

</html>