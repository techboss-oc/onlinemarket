<?php
require_once 'config/db.php';

echo "Seeding Ads...\n";

try {
    $db = Database::getInstance()->getConnection();

    // Get User ID (Seller)
    $stmt = $db->prepare("SELECT id FROM users WHERE role = 'seller' LIMIT 1");
    $stmt->execute();
    $seller = $stmt->fetch(PDO::FETCH_ASSOC);
    $seller_id = $seller['id'];

    // Helper to get ID
    function getID($db, $table, $col, $val)
    {
        $stmt = $db->prepare("SELECT id FROM $table WHERE $col = :val");
        $stmt->execute([':val' => $val]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['id'] : 1; // Default to 1
    }

    $ads = [
        [
            'title' => '2021 Toyota Camry LE - Foreign Used',
            'price' => 8500000,
            'category' => 'vehicles',
            'location' => 'lagos',
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuB19xjG8mI-AG6-_nS5tlePOTmQtdMVnHmPBoTw7zQbNSymyFYJC3KtgsUbCcmTuAmooQLqCcE-G2jTjDfuxfAXErwtPttZW02ACbgVabyXBETFn_P9sRGqKL9S8vnNrno-rN8GhdoXV8IRO6jmKe9sKQhGbwOoysrPGRlV55s5_dNDj8NKSd_y0ZO4L1yF3jGiNi2scCzFWjamqPnWA05hi0wnFt1BRZ72OrKhKflS16rDXh90aCRUchHdfAIUrnieaLkOThZ-gkU',
            'desc' => 'Clean foreign used Toyota Camry 2021. No faults.'
        ],
        [
            'title' => '3 Bedroom Apartment with Ocean View',
            'price' => 45000000,
            'category' => 'real-estate',
            'location' => 'lagos',
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDolwDAEiDLTle97TsBH1LJbCzO8hYdFdMXv3HRG9JBhpBNBsrQAoyiYNVQs4X0MkQD9f7E2kwuyneb1La9sz-EJJlhsoULwqXnPMIyVNlYDy5vs-PMZTgkAs8SthzgfRRWmb5QMG_pXVCEa7TdDzNpuq5OaKez5xKt4-0CDsCdktI0l6uJGexAkB2seaDc-TNFlxkeDs31j8EZCTX60sTJGJF-LdKyhCzIJ3VFSMmHlOhCz2zPRWYVmrNkpuFpQq1-ao4zUTn8pW4',
            'desc' => 'Luxury apartment in Lekki Phase 1.'
        ],
        [
            'title' => 'iPhone 13 Pro Max 256GB - Clean UK Used',
            'price' => 650000,
            'category' => 'mobile-phones',
            'location' => 'lagos',
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBvR1LZEKNPzxtM124YLhqbb7KzWa6Q90afVBYqwIfj9N6gebpZV4FEoLSGPHwQ5se-TtZV9yHjELnT8LzWryT0tqk54TSLfu35azSnlv98m4moo43DrUfPorMEGhA6WFttk8R_K2FKRdiJpNu1lwKd1iDalwWCKlYa5gWWKYpOxSvSa6B6Xsr8jv-rmJey6h6mHwD-WZKcPuo4EJf9Su6FdxFVaNRxlWssuwh75MtS0bOEx4WS42ogbTE6TwCTyKQDZ9PG4M0c_OQ',
            'desc' => 'Battery health 90%. No scratches.'
        ],
        [
            'title' => 'Ergonomic Office Chair + Desk Combo',
            'price' => 85000,
            'category' => 'furniture',
            'location' => 'lagos',
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDrK0guFvP3McHwmCM95cRfA4LDdKQQwqbwCI1cRwMgkoEKCVFbU9AT3PMNV25zx7DPctANl2P3sbfTcb8QL-AXQeMq5w2q9HExocYcyfGITYRyOylZmNt_X-_AU3vlJsy_JxE1d81uSLrOg3lQ6bmODlIBDh1VBvYcBTMExxVPcajZP4LI2bzkXQFuJQjh1lZW1J9bLwQjVJKc0a29yoePTKnSH3xFtozhHfsDN2p6y3hnKxENcMNLD7l4w-S7wCgQTiX5aZ237DQ',
            'desc' => 'Comfortable chair and sturdy desk.'
        ],
        [
            'title' => 'MacBook Pro M1 2020 - 512GB SSD',
            'price' => 750000,
            'category' => 'electronics',
            'location' => 'lagos',
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAGdzQaCQviATi_DDvkjBWA4rkZ2ivvOIj7QaWTSOkqkfiv1254BQ2rxDyCxxZBBfNoHIhMIdJpGQbpqCE7mq4yrnVPUZYwxVRyLHxa3p7G0UnE4TE3qzYY6_-A2VoF5VRpgeJpghV0nT6ttwFQALzmblZNlwnh-pZ80m7T8dLgs4QhCRnwxSyYMEsIf4UYqnqdK9Rh4_ebdTA02GcqqDAOB0Hw4ERxi8sHVF7oKO5-sDGPd9GuGyuB3XDtwfeXZYC58JwnBfxkmpA',
            'desc' => 'Powerful laptop for creatives.'
        ],
        [
            'title' => 'Nike Air Force 1 - White - Size 42',
            'price' => 25000,
            'category' => 'fashion',
            'location' => 'lagos',
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAK10BMGVZYfxO55RSiZD_e4yY3w5upCWn1TFDFHhxJNzhk7PaeuxhX5EXJpyEN64iqoPYgm3wHjer47beKlJb-il_UuANcAoKFirwPwEk2wS14gwyMUE_FVdgIeYIL8o3qScBpMyj2bdxwcVsrV9ayzIetWdEBsbQN4O7LbZJDH5dts_UOAwfimbVuww_t0EboLpxSM7S87l7dNeVH1TxcWziQdHTkVcffktpq5Cq_ELMtK0fGlNUhRz9FWjBh78DfJnE-V_1u5gI',
            'desc' => 'Brand new in box.'
        ],
        [
            'title' => 'Lhasa Apso Puppies (Pure Breed)',
            'price' => 80000,
            'category' => 'services', // Or pets if exists, map to services for now
            'location' => 'abuja',
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAhSvyhFF_aLe5xYXpwLlJ7FFyXtLvk8mk5bhbpPzxP9tlm1yYnxxxwl7-IxkAkSoOdLG7qDc90IPCct57JhBCeeiJ0GaD03jdLwwcqiKSBN1L9u_OIZOl3kYbgF_8aWzOVo2pNcxM3Z11GQ7PcSJef-a7mHiNlPMqDxIkBIdm8qCndtKmDe-IcVISRWY6YWGHAlYrJo_L9O_h95Xn957FPXQcVN-DyMm8wG56Lq-PLPUj0udB1erop3Ebt-4lczRDrtfFMP8lM6QU',
            'desc' => 'Vaccinated and healthy.'
        ],
        [
            'title' => 'Firman Generator 3.5KVA Key Start',
            'price' => 180000,
            'category' => 'electronics',
            'location' => 'lagos',
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBux7ysIqKWSCRcbixZfbPrpBbxM_yxDHgbTZLQN2nHBaXMP7-9PhD9VnbNsejItuTZMfWRiAJu-dyMzyYlNHXTiQF3O9kRW52kvmCBMGSRc5z8TRfWCtXc1FgpemibU9kiFyP8OBX7oLMq85X3LQGtrYxSmskqEOffPud_AzRp448JeGsXat1GYD_GQKGJewa4ejERdKT4b5e76jhTwvCYl6yy7mkXeZgjoFnpMdOmvWdFv1UehEnV7YNQtcc4I8psULjfKM8DEH8',
            'desc' => 'Reliable power source.'
        ],
        [
            'title' => 'Sales Representative Needed Urgently',
            'price' => 0,
            'category' => 'jobs',
            'location' => 'lagos',
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBkz0XK4BBk--rZUSH6Yb4-lZjTGWUHswmDcfVbufnirWEQ7p44iu9qRlMBzPZ6UGlZxpH-oEoqNegtmqcmTnLzGLfcYIQ2JrFPbb1boZnJ-ZE4DxYuI70YHUM5UO0vMU613mugPsBxhTscnt67atZi6fb5SIGUH5byt2OtFvRjmRozCziU2kMIL_pxlT5qJXooutcAdvG1pYqZZAM2oc-wQFLENAqfeGTlCUg1vsuFZzvxFUvDAn8znQR8tcN9z5L4SircEtpi5oI',
            'desc' => 'Apply now.'
        ]
    ];

    foreach ($ads as $ad) {
        $cat_id = getID($db, 'categories', 'slug', $ad['category']);
        $loc_id = getID($db, 'locations', 'slug', $ad['location']);

        // Insert Ad
        $stmt = $db->prepare("INSERT INTO ads (user_id, category_id, location_id, title, description, price, status, views_count) VALUES (:uid, :cid, :lid, :title, :desc, :price, 'active', :views)");
        $stmt->execute([
            ':uid' => $seller_id,
            ':cid' => $cat_id,
            ':lid' => $loc_id,
            ':title' => $ad['title'],
            ':desc' => $ad['desc'],
            ':price' => $ad['price'],
            ':views' => rand(10, 500)
        ]);
        $ad_id = $db->lastInsertId();

        // Insert Image
        $stmt = $db->prepare("INSERT INTO ad_images (ad_id, image_url, is_primary) VALUES (:aid, :url, 1)");
        $stmt->execute([
            ':aid' => $ad_id,
            ':url' => $ad['image']
        ]);
    }

    echo "Ads seeded successfully.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
