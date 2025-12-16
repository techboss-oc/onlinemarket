<?php
class Favorite
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function toggle($user_id, $ad_id)
    {
        if ($this->isSaved($user_id, $ad_id)) {
            $this->remove($user_id, $ad_id);
            return false; // Removed
        } else {
            $this->add($user_id, $ad_id);
            return true; // Added
        }
    }

    public function add($user_id, $ad_id)
    {
        $stmt = $this->db->prepare("INSERT INTO favorites (user_id, ad_id) VALUES (:uid, :aid) ON DUPLICATE KEY UPDATE created_at = CURRENT_TIMESTAMP");
        $stmt->execute([':uid' => $user_id, ':aid' => $ad_id]);
    }

    public function remove($user_id, $ad_id)
    {
        $stmt = $this->db->prepare("DELETE FROM favorites WHERE user_id = :uid AND ad_id = :aid");
        $stmt->execute([':uid' => $user_id, ':aid' => $ad_id]);
    }

    public function isSaved($user_id, $ad_id)
    {
        $stmt = $this->db->prepare("SELECT id FROM favorites WHERE user_id = :uid AND ad_id = :aid");
        $stmt->execute([':uid' => $user_id, ':aid' => $ad_id]);
        return $stmt->rowCount() > 0;
    }

    public function getUserFavorites($user_id)
    {
        $sql = "SELECT ads.*, locations.name as location_name, categories.name as category_name, 
                (SELECT image_url FROM ad_images WHERE ad_id = ads.id AND is_primary = 1 LIMIT 1) as image_url,
                favorites.created_at as saved_at
                FROM favorites 
                JOIN ads ON favorites.ad_id = ads.id
                JOIN locations ON ads.location_id = locations.id
                JOIN categories ON ads.category_id = categories.id
                WHERE favorites.user_id = :uid 
                ORDER BY favorites.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':uid' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
