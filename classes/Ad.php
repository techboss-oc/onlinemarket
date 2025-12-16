<?php
class Ad
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getTrending($limit = 4)
    {
        // For now, just getting random active ads or by views if I had data
        $sql = "SELECT ads.*, locations.name as location_name, categories.name as category_name, 
                (SELECT image_url FROM ad_images WHERE ad_id = ads.id AND is_primary = 1 LIMIT 1) as image_url
                FROM ads 
                JOIN locations ON ads.location_id = locations.id
                JOIN categories ON ads.category_id = categories.id
                WHERE status = 'active' 
                ORDER BY views_count DESC 
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLatest($limit = 8)
    {
        $sql = "SELECT ads.*, locations.name as location_name, categories.name as category_name, 
                (SELECT image_url FROM ad_images WHERE ad_id = ads.id AND is_primary = 1 LIMIT 1) as image_url
                FROM ads 
                JOIN locations ON ads.location_id = locations.id
                JOIN categories ON ads.category_id = categories.id
                WHERE status = 'active' 
                ORDER BY created_at DESC 
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByUserId($user_id)
    {
        $sql = "SELECT ads.*, categories.name as category_name, 
                (SELECT image_url FROM ad_images WHERE ad_id = ads.id AND is_primary = 1 LIMIT 1) as image_url
                FROM ads 
                JOIN categories ON ads.category_id = categories.id
                WHERE user_id = :uid 
                ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':uid' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStats($user_id)
    {
        $stats = [
            'total_ads' => 0,
            'total_views' => 0,
            'active_ads' => 0,
            'expired_ads' => 0
        ];

        // Total Ads & Views
        $stmt = $this->db->prepare("SELECT COUNT(*) as total, SUM(views_count) as views FROM ads WHERE user_id = :uid");
        $stmt->execute([':uid' => $user_id]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['total_ads'] = $res['total'] ?? 0;
        $stats['total_views'] = $res['views'] ?? 0;

        // Active Ads
        $stmt = $this->db->prepare("SELECT COUNT(*) as active FROM ads WHERE user_id = :uid AND status = 'active'");
        $stmt->execute([':uid' => $user_id]);
        $stats['active_ads'] = $stmt->fetch(PDO::FETCH_ASSOC)['active'];

        // Expired/Other Ads
        $stmt = $this->db->prepare("SELECT COUNT(*) as expired FROM ads WHERE user_id = :uid AND status = 'expired'");
        $stmt->execute([':uid' => $user_id]);
        $stats['expired_ads'] = $stmt->fetch(PDO::FETCH_ASSOC)['expired'];

        return $stats;
    }

    public function create($data)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO ads (user_id, category_id, location_id, title, description, price, condition_state, brand, status) VALUES (:uid, :cid, :lid, :title, :desc, :price, :cond, :brand, 'active')");
            $stmt->execute([
                ':uid' => $data['user_id'],
                ':cid' => $data['category_id'],
                ':lid' => $data['location_id'],
                ':title' => $data['title'],
                ':desc' => $data['description'],
                ':price' => $data['price'],
                ':cond' => $data['condition'],
                ':brand' => $data['brand'] ?? null
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function addImage($ad_id, $url, $is_primary = 0)
    {
        $stmt = $this->db->prepare("INSERT INTO ad_images (ad_id, image_url, is_primary) VALUES (:aid, :url, :is_primary)");
        $stmt->execute([
            ':aid' => $ad_id,
            ':url' => $url,
            ':is_primary' => $is_primary
        ]);
    }

    public function delete($id, $user_id)
    {
        $stmt = $this->db->prepare("DELETE FROM ads WHERE id = :id AND user_id = :uid");
        $stmt->execute([':id' => $id, ':uid' => $user_id]);
        return $stmt->rowCount() > 0;
    }

    public function search($query = '', $category_slug = '', $location_slug = '')
    {
        $sql = "SELECT ads.*, locations.name as location_name, categories.name as category_name, 
                (SELECT image_url FROM ad_images WHERE ad_id = ads.id AND is_primary = 1 LIMIT 1) as image_url
                FROM ads 
                JOIN locations ON ads.location_id = locations.id
                JOIN categories ON ads.category_id = categories.id
                WHERE status = 'active'";

        $params = [];

        if (!empty($query)) {
            $sql .= " AND (title LIKE :q OR description LIKE :q)";
            $params[':q'] = "%$query%";
        }

        if (!empty($category_slug)) {
            $sql .= " AND categories.slug = :cat";
            $params[':cat'] = $category_slug;
        }

        if (!empty($location_slug)) {
            $sql .= " AND locations.slug = :loc";
            $params[':loc'] = $location_slug;
        }

        $sql .= " ORDER BY created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        // Get Ad Details with User and Location/Category
        $sql = "SELECT ads.*, 
                locations.name as location_name, 
                categories.name as category_name, 
                users.username as seller_name, 
                users.phone as seller_phone, 
                users.email as seller_email,
                users.created_at as seller_joined,
                users.profile_image as seller_image
                FROM ads 
                JOIN locations ON ads.location_id = locations.id
                JOIN categories ON ads.category_id = categories.id
                JOIN users ON ads.user_id = users.id
                WHERE ads.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $ad = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($ad) {
            // Get Images
            $stmt = $this->db->prepare("SELECT image_url FROM ad_images WHERE ad_id = :id ORDER BY is_primary DESC");
            $stmt->execute([':id' => $id]);
            $ad['images'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
        }

        return $ad;
    }

    public function getSimilar($category_id, $exclude_id, $limit = 4)
    {
        $sql = "SELECT ads.*, locations.name as location_name, categories.name as category_name, 
                (SELECT image_url FROM ad_images WHERE ad_id = ads.id AND is_primary = 1 LIMIT 1) as image_url
                FROM ads 
                JOIN locations ON ads.location_id = locations.id
                JOIN categories ON ads.category_id = categories.id
                WHERE status = 'active' AND category_id = :cid AND ads.id != :eid
                ORDER BY rand() 
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':cid', $category_id, PDO::PARAM_INT);
        $stmt->bindValue(':eid', $exclude_id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function incrementViews($id)
    {
        $stmt = $this->db->prepare("UPDATE ads SET views_count = views_count + 1 WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    public function getAllAdmin()
    {
        $sql = "SELECT ads.*, users.username as seller_name, categories.name as category_name, locations.name as location_name,
                (SELECT image_url FROM ad_images WHERE ad_id = ads.id AND is_primary = 1 LIMIT 1) as image_url
                FROM ads 
                JOIN users ON ads.user_id = users.id 
                JOIN categories ON ads.category_id = categories.id
                JOIN locations ON ads.location_id = locations.id
                ORDER BY created_at DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status)
    {
        $stmt = $this->db->prepare("UPDATE ads SET status = :status WHERE id = :id");
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }
}
