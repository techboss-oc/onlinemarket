<?php
require_once '../../core/init.php';

if (!isLoggedIn()) {
    redirect('../login_page_-_onlinemarket.ng/');
}

$chatModel = new Chat();
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role']; // 'buyer' or 'seller'

// Start New Conversation logic
if (isset($_GET['seller_id']) && isset($_GET['ad_id'])) {
    $seller_id = $_GET['seller_id'];
    $ad_id = $_GET['ad_id'];

    // Prevent self-chat
    if ($seller_id != $user_id) {
        $chat_id = $chatModel->startConversation($user_id, $seller_id, $ad_id);
        redirect("index.php?chat_id=$chat_id");
    }
}

$active_chat_id = $_GET['chat_id'] ?? null;
$conversations = $chatModel->getConversations($user_id);
$messages = [];
$active_chat = null;

if ($active_chat_id) {
    $messages = $chatModel->getMessages($active_chat_id, $user_id);

    // Find active chat details
    foreach ($conversations as $conv) {
        if ($conv['id'] == $active_chat_id) {
            $active_chat = $conv;
            break;
        }
    }
} elseif (!empty($conversations)) {
    // Default to first chat if none selected
    $active_chat = $conversations[0];
    $active_chat_id = $active_chat['id'];
    $messages = $chatModel->getMessages($active_chat_id, $user_id);
}

// Handle Send Message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message']) && $active_chat_id) {
    $message = trim($_POST['message']);
    if (!empty($message)) {
        $chatModel->sendMessage($active_chat_id, $user_id, $message);
        // Redirect to avoid resubmission
        redirect("index.php?chat_id=$active_chat_id");
    }
}

// Helper to get other user info
function getOtherUser($chat, $my_id)
{
    if ($chat['buyer_id'] == $my_id) {
        return [
            'name' => $chat['seller_name'],
            'image' => $chat['seller_image'],
            'role' => 'Seller'
        ];
    } else {
        return [
            'name' => $chat['buyer_name'],
            'image' => $chat['buyer_image'],
            'role' => 'Buyer'
        ];
    }
}
?>
<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Messages - Onlinemarket.ng</title>
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
                        "2xl": "1rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <style>
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
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
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-[#0e121b] h-screen flex flex-col overflow-hidden">
    <!-- Top Navigation -->
    <header class="flex-none items-center justify-between whitespace-nowrap border-b border-solid border-b-[#e7ebf3] px-10 py-3 bg-white z-20">
        <div class="flex items-center justify-between gap-8 max-w-[1400px] mx-auto w-full">
            <div class="flex items-center gap-8">
                <a href="../home_page_-_onlinemarket.ng/" class="flex items-center gap-4 text-[#0e121b]">
                    <div class="size-8 flex items-center justify-center text-primary bg-primary/10 rounded-lg">
                        <span class="material-symbols-outlined text-[24px]">shopping_bag</span>
                    </div>
                    <h2 class="text-[#0e121b] text-xl font-bold leading-tight tracking-[-0.015em]">Onlinemarket.ng</h2>
                </a>
                <div class="hidden md:flex items-center gap-9">
                    <a class="text-[#4e6797] hover:text-primary transition-colors text-sm font-medium leading-normal" href="../home_page_-_onlinemarket.ng/">Home</a>
                    <a class="text-primary text-sm font-bold leading-normal" href="#">Messages</a>
                    <a class="text-[#4e6797] hover:text-primary transition-colors text-sm font-medium leading-normal" href="../buyer_dashboard/">Dashboard</a>
                </div>
            </div>
        </div>
    </header>
    <!-- Main Layout -->
    <div class="flex flex-1 overflow-hidden max-w-[1400px] mx-auto w-full">
        <!-- Sidebar: Conversation List -->
        <aside class="w-full md:w-[380px] flex flex-col border-r border-[#e7ebf3] bg-white h-full z-10 shadow-sm md:shadow-none <?php echo $active_chat_id ? 'hidden md:flex' : 'flex'; ?>">
            <div class="px-5 pt-6 pb-2 shrink-0">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-2xl font-bold tracking-tight text-[#0e121b]">Messages</h1>
                </div>
                <!-- Search (Visual Only) -->
                <label class="flex flex-col h-11 w-full mb-2">
                    <div class="flex w-full flex-1 items-stretch rounded-xl h-full shadow-sm ring-1 ring-black/5">
                        <div class="text-[#9ca3af] flex border-none bg-white items-center justify-center pl-3 rounded-l-xl border-r-0">
                            <span class="material-symbols-outlined text-[20px]">search</span>
                        </div>
                        <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-[#0e121b] focus:outline-0 focus:ring-0 border-none bg-white focus:border-none h-full placeholder:text-[#9ca3af] px-3 rounded-l-none border-l-0 pl-2 text-[15px] font-normal" placeholder="Search messages..." value="" />
                    </div>
                </label>
            </div>
            <!-- Conversations List -->
            <div class="flex-1 overflow-y-auto px-3 pb-4 space-y-1">
                <?php if (empty($conversations)): ?>
                    <div class="flex flex-col items-center justify-center h-64 text-center px-6">
                        <div class="size-16 rounded-full bg-slate-50 flex items-center justify-center mb-4">
                            <span class="material-symbols-outlined text-3xl text-slate-300">chat_bubble_outline</span>
                        </div>
                        <p class="text-slate-900 font-medium mb-1">No messages yet</p>
                        <p class="text-slate-500 text-sm">Conversations will appear here when you contact sellers.</p>
                        <a href="../search_results_page_-_onlinemarket.ng/" class="mt-4 text-primary text-sm font-semibold hover:underline">Browse Ads</a>
                    </div>
                <?php else: ?>
                    <?php foreach ($conversations as $conv):
                        $other = getOtherUser($conv, $user_id);
                        $isActive = $active_chat_id == $conv['id'];
                    ?>
                        <a href="?chat_id=<?php echo $conv['id']; ?>" class="group flex items-center gap-3 px-3 py-3 rounded-xl cursor-pointer transition-colors <?php echo $isActive ? 'bg-primary/5 border border-primary/10' : 'hover:bg-[#f8f9fc]'; ?>">
                            <div class="relative shrink-0">
                                <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full h-12 w-12 border border-[#e7ebf3]" style='background-image: url("<?php echo $other['image'] ?? 'https://via.placeholder.com/50'; ?>");'>
                                    <?php if (empty($other['image'])) echo '<div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-500 font-bold">' . strtoupper(substr($other['name'], 0, 1)) . '</div>'; ?>
                                </div>
                            </div>
                            <div class="flex flex-col flex-1 min-w-0">
                                <div class="flex justify-between items-baseline mb-0.5">
                                    <p class="text-[#0e121b] text-[15px] font-bold truncate"><?php echo sanitize($other['name']); ?></p>
                                    <p class="text-[#8896ab] text-xs whitespace-nowrap ml-2"><?php echo time_elapsed_string($conv['last_message_time']); ?></p>
                                </div>
                                <p class="text-[#4e6797] text-sm truncate font-medium"><?php echo sanitize($conv['last_message'] ?? 'Start chatting...'); ?></p>
                                <p class="text-[#8896ab] text-xs truncate mt-1 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">sell</span>
                                    <?php echo sanitize($conv['ad_title']); ?>
                                </p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <!-- Bottom Nav Spacer for Mobile -->
            <div class="h-16 md:hidden"></div>
        </aside>
        <!-- Main Chat Window -->
        <main class="flex-col flex-1 relative bg-[#f2f4f8] <?php echo $active_chat_id ? 'flex fixed inset-0 z-50 md:static md:z-0' : 'hidden md:flex'; ?>">
            <?php if ($active_chat):
                $active_other = getOtherUser($active_chat, $user_id);
            ?>
                <!-- Chat Header -->
                <div class="glass-panel sticky top-0 z-10 px-4 py-2 md:px-6 md:py-3 flex flex-col gap-2 md:gap-3 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <a href="index.php" class="md:hidden text-gray-500 hover:text-primary p-2 -ml-2"><span class="material-symbols-outlined">arrow_back</span></a>
                            <div class="relative">
                                <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full h-10 w-10 border border-white shadow-sm" style='background-image: url("<?php echo $active_other['image'] ?? 'https://via.placeholder.com/50'; ?>");'>
                                    <?php if (empty($active_other['image'])) echo '<div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-500 font-bold rounded-full">' . strtoupper(substr($active_other['name'], 0, 1)) . '</div>'; ?>
                                </div>
                                <div class="absolute bottom-0 right-0 size-2.5 bg-green-500 rounded-full border-2 border-white"></div>
                            </div>
                            <div>
                                <h2 class="text-[#0e121b] text-base font-bold leading-tight"><?php echo sanitize($active_other['name']); ?></h2>
                                <p class="text-green-600 text-xs font-medium flex items-center gap-1">
                                    <span class="inline-block size-1.5 rounded-full bg-green-500"></span> Online
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Context Strip: Ad Item -->
                    <div class="flex items-center justify-between bg-white/50 border border-white/60 p-2 rounded-lg">
                        <div class="flex items-center gap-3 overflow-hidden">
                            <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-md h-10 w-10 shrink-0 bg-gray-200" style='background-image: url("https://via.placeholder.com/50");'></div>
                            <div class="min-w-0">
                                <p class="text-[#0e121b] text-sm font-semibold truncate"><?php echo sanitize($active_chat['ad_title']); ?></p>
                                <p class="text-slate-500 text-xs truncate">Ref: #<?php echo $active_chat['ad_id']; ?></p>
                            </div>
                        </div>
                        <a href="../single_ad_view_page_-_onlinemarket.ng/?id=<?php echo $active_chat['ad_id']; ?>" class="px-3 py-1.5 text-xs font-semibold text-primary bg-primary/10 rounded-md hover:bg-primary hover:text-white transition-colors whitespace-nowrap">
                            View Ad
                        </a>
                    </div>
                </div>
                <!-- Messages Area -->
                <div class="flex-1 overflow-y-auto p-4 md:p-6 space-y-6 flex flex-col pb-24 md:pb-6" id="message-container">
                    <?php foreach ($messages as $msg):
                        $isMe = $msg['sender_id'] == $user_id;
                    ?>
                        <div class="flex items-end gap-3 max-w-[85%] md:max-w-[70%] <?php echo $isMe ? 'self-end flex-row-reverse' : ''; ?>">
                            <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full h-8 w-8 mb-1 shrink-0 bg-gray-200 flex items-center justify-center text-[10px] font-bold text-gray-500" style='background-image: url("<?php echo $isMe ? 'https://via.placeholder.com/50' : ($active_other['image'] ?? 'https://via.placeholder.com/50'); ?>");'>
                                <?php
                                if ($isMe) {
                                    echo '';
                                } else if (empty($active_other['image'])) {
                                    echo strtoupper(substr($active_other['name'], 0, 1));
                                }
                                ?>
                            </div>
                            <div class="flex flex-col gap-1 <?php echo $isMe ? 'items-end' : ''; ?>">
                                <div class="<?php echo $isMe ? 'bg-primary text-white rounded-br-sm' : 'bg-white text-[#0e121b] rounded-bl-sm'; ?> p-3 rounded-2xl shadow-sm text-[15px] leading-relaxed break-words">
                                    <p><?php echo sanitize($msg['message']); ?></p>
                                </div>
                                <span class="text-[#8896ab] text-[10px] ml-1"><?php echo date('h:i A', strtotime($msg['created_at'])); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- Input Area -->
                <div class="p-4 md:p-6 pt-2 sticky bottom-0 bg-[#f2f4f8] md:bg-transparent">
                    <form method="POST" action="" class="glass-panel rounded-2xl p-2 flex items-end gap-2 shadow-lg bg-white">
                        <div class="flex-1 py-3 px-2">
                            <textarea name="message" class="w-full bg-transparent border-none focus:ring-0 p-0 text-[#0e121b] placeholder:text-[#9ca3af] resize-none max-h-32 text-[15px]" placeholder="Type a message..." rows="1" required></textarea>
                        </div>
                        <button type="submit" class="bg-primary hover:bg-blue-700 text-white p-3 rounded-xl shadow-md transition-all active:scale-95 shrink-0 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[24px]">send</span>
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <div class="flex-1 flex flex-col items-center justify-center text-gray-500">
                    <span class="material-symbols-outlined text-6xl mb-4 text-gray-300">chat</span>
                    <p>Select a conversation to start messaging</p>
                </div>
            <?php endif; ?>
        </main>
    </div>
    <script>
        // Scroll to bottom of message container
        const msgContainer = document.getElementById('message-container');
        if (msgContainer) {
            msgContainer.scrollTop = msgContainer.scrollHeight;
        }
    </script>
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
        <a href="../buyer_chat_/_messaging_page/" class="flex flex-col items-center gap-1 text-primary">
            <span class="material-symbols-outlined text-[24px]">chat</span>
            <span class="text-[10px] font-medium">Chat</span>
        </a>
        <a href="../buyer_profile_&_settings/" class="flex flex-col items-center gap-1 text-slate-500 dark:text-slate-400 hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-[24px]">person</span>
            <span class="text-[10px] font-medium">Profile</span>
        </a>
    </div>
</body>

</html>