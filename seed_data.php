<?php
require_once 'core/init.php';

$db = Database::getInstance()->getConnection();

echo "Seeding Database...\n";

// 1. Create Users
$users = [
    [
        'username' => 'JohnDoe_Seller',
        'email' => 'seller@test.com',
        'password' => 'password123',
        'role' => 'seller',
        'phone' => '08012345678',
        'location' => 'Lagos',
        'is_verified' => 1
    ],
    [
        'username' => 'JaneSmith_Buyer',
        'email' => 'buyer@test.com',
        'password' => 'password123',
        'role' => 'buyer',
        'phone' => '08098765432',
        'location' => 'Abuja',
        'is_verified' => 1
    ],
    [
        'username' => 'AdminUser',
        'email' => 'admin@test.com',
        'password' => 'admin123',
        'role' => 'admin',
        'phone' => '08000000000',
        'location' => 'Lagos',
        'is_verified' => 1
    ],
    [
        'username' => 'Mike_Gadgets',
        'email' => 'mike@test.com',
        'password' => 'password123',
        'role' => 'seller',
        'phone' => '08123456789',
        'location' => 'Port Harcourt',
        'is_verified' => 0
    ]
];

$user_ids = [];

foreach ($users as $u) {
    // Check if exists
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$u['email']]);
    if ($stmt->rowCount() == 0) {
        $hash = password_hash($u['password'], PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO users (username, email, password_hash, role, phone, location, is_verified, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$u['username'], $u['email'], $hash, $u['role'], $u['phone'], $u['location'], $u['is_verified']]);
        $user_ids[$u['role']][] = $db->lastInsertId();
        echo "Created user: {$u['username']}\n";
    } else {
        $id = $stmt->fetchColumn();
        $user_ids[$u['role']][] = $id;
        echo "User exists: {$u['username']}\n";
    }
}

// 2. Ensure Categories Exist (Basic check)
$cats = [
    ['Vehicles', 'directions_car'],
    ['Property', 'house'],
    ['Mobile Phones', 'smartphone'],
    ['Electronics', 'tv'],
    ['Furniture', 'chair'],
    ['Fashion', 'checkroom'],
];

$cat_ids = [];
foreach ($cats as $c) {
    $slug = strtolower(str_replace(' ', '-', $c[0]));
    $stmt = $db->prepare("SELECT id FROM categories WHERE slug = ?");
    $stmt->execute([$slug]);
    if ($stmt->rowCount() == 0) {
        $stmt = $db->prepare("INSERT INTO categories (name, slug, icon) VALUES (?, ?, ?)");
        $stmt->execute([$c[0], $slug, $c[1]]);
        $cat_ids[$slug] = $db->lastInsertId();
    } else {
        $cat_ids[$slug] = $stmt->fetchColumn();
    }
}

// 3. Create Real Ads
$seller_id = $user_ids['seller'][0] ?? 1; // Default to first seller
$ads = [
    [
        'title' => 'Toyota Camry 2020 - Clean Title',
        'description' => 'Foreign used Toyota Camry 2020. Very clean, accident free, low mileage. AC chilling, engine silent. Buy and drive.',
        'price' => 8500000,
        'category_id' => $cat_ids['vehicles'],
        'location_name' => 'Ikeja, Lagos',
        'condition_state' => 'Foreign Used',
        'image' => 'https://images.unsplash.com/photo-1621007947382-bb3c3968e3bb?auto=format&fit=crop&w=800&q=80'
    ],
    [
        'title' => 'iPhone 13 Pro Max - 256GB',
        'description' => 'UK used iPhone 13 Pro Max. 256GB storage, Sierra Blue. Battery health 95%. Comes with charger.',
        'price' => 650000,
        'category_id' => $cat_ids['mobile-phones'],
        'location_name' => 'Lekki, Lagos',
        'condition_state' => 'Used',
        'image' => 'https://images.unsplash.com/photo-1632661674596-df8be070a5c5?auto=format&fit=crop&w=800&q=80'
    ],
    [
        'title' => '3 Bedroom Apartment for Rent',
        'description' => 'Spacious 3 bedroom flat in a secured estate. All rooms ensuite, pop ceiling, running water, prepaid meter.',
        'price' => 1500000,
        'category_id' => $cat_ids['property'],
        'location_name' => 'Maitama, Abuja',
        'condition_state' => 'New',
        'image' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=800&q=80'
    ],
    [
        'title' => 'MacBook Pro M1 2020',
        'description' => 'MacBook Pro M1 chip, 8GB RAM, 256GB SSD. Space Grey. Slightly used for 3 months. Box available.',
        'price' => 750000,
        'category_id' => $cat_ids['electronics'],
        'location_name' => 'Yaba, Lagos',
        'condition_state' => 'Used',
        'image' => 'https://images.unsplash.com/photo-1611186871348-b1ce696e52c9?auto=format&fit=crop&w=800&q=80'
    ],
    [
        'title' => 'L-Shaped Sofa Set',
        'description' => 'Modern grey fabric L-shaped sofa. Very comfortable, high density foam. Perfect for living room.',
        'price' => 350000,
        'category_id' => $cat_ids['furniture'],
        'location_name' => 'Surulere, Lagos',
        'condition_state' => 'New',
        'image' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=800&q=80'
    ],
    [
        'title' => 'Nike Air Jordan 1 High',
        'description' => 'Original Nike Air Jordan 1 High. Size 43. Brand new in box.',
        'price' => 45000,
        'category_id' => $cat_ids['fashion'],
        'location_name' => 'Garki, Abuja',
        'condition_state' => 'New',
        'image' => 'https://images.unsplash.com/photo-1552346154-21d32810aba3?auto=format&fit=crop&w=800&q=80'
    ]
];

foreach ($ads as $ad) {
    // Insert Ad
    $stmt = $db->prepare("INSERT INTO ads (user_id, category_id, title, description, price, location_name, condition_state, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, 'active', NOW())");
    $stmt->execute([$seller_id, $ad['category_id'], $ad['title'], $ad['description'], $ad['price'], $ad['location_name'], $ad['condition_state']]);
    $ad_id = $db->lastInsertId();
    
    // Insert Image
    $stmt = $db->prepare("INSERT INTO ad_images (ad_id, image_url, is_primary) VALUES (?, ?, 1)");
    $stmt->execute([$ad_id, $ad['image']]);
    
    echo "Created Ad: {$ad['title']}\n";
}

// 4. Create Messages
$buyer_id = $user_ids['buyer'][0] ?? 2;
$ad_id = 1; // Assuming first ad is Toyota Camry

// Check if conversation exists
$stmt = $db->prepare("SELECT id FROM conversations WHERE buyer_id = ? AND seller_id = ? AND ad_id = ?");
$stmt->execute([$buyer_id, $seller_id, $ad_id]);

if ($stmt->rowCount() == 0) {
    // Create Conversation
    $stmt = $db->prepare("INSERT INTO conversations (buyer_id, seller_id, ad_id, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
    $stmt->execute([$buyer_id, $seller_id, $ad_id]);
    $chat_id = $db->lastInsertId();
    
    // Add Messages
    $messages = [
        ['sender' => $buyer_id, 'msg' => 'Good afternoon, is this car still available?'],
        ['sender' => $seller_id, 'msg' => 'Yes it is available. You can come for inspection at Ikeja.'],
        ['sender' => $buyer_id, 'msg' => 'Okay, what is the last price?'],
        ['sender' => $seller_id, 'msg' => 'The price is slightly negotiable. 8.3m last.'],
        ['sender' => $buyer_id, 'msg' => 'Alright, I will come tomorrow morning.']
    ];
    
    foreach ($messages as $m) {
        $stmt = $db->prepare("INSERT INTO messages (conversation_id, sender_id, message, is_read, created_at) VALUES (?, ?, ?, 0, NOW())");
        $stmt->execute([$chat_id, $m['sender'], $m['msg']]);
    }
    
    // Update last message in conversation
    $last_msg = end($messages)['msg'];
    $stmt = $db->prepare("UPDATE conversations SET last_message = ?, updated_at = NOW() WHERE id = ?");
    $stmt->execute([$last_msg, $chat_id]);
    
    echo "Created test conversation for Toyota Camry.\n";
}

echo "Done!\n";
