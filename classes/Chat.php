<?php
class Chat
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getConversations($user_id)
    {
        // Get unique conversations where user is buyer or seller
        $sql = "SELECT chats.*, 
                buyer.username as buyer_name, buyer.profile_image as buyer_image,
                seller.username as seller_name, seller.profile_image as seller_image,
                ads.title as ad_title,
                (SELECT message FROM messages WHERE chat_id = chats.id ORDER BY created_at DESC LIMIT 1) as last_message,
                (SELECT created_at FROM messages WHERE chat_id = chats.id ORDER BY created_at DESC LIMIT 1) as last_message_time,
                (SELECT COUNT(*) FROM messages WHERE chat_id = chats.id AND is_read = 0 AND sender_id != :uid) as unread_count
                FROM chats
                JOIN users as buyer ON chats.buyer_id = buyer.id
                JOIN users as seller ON chats.seller_id = seller.id
                LEFT JOIN ads ON chats.ad_id = ads.id
                WHERE chats.buyer_id = :uid OR chats.seller_id = :uid
                ORDER BY last_message_time DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':uid' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMessages($chat_id, $user_id)
    {
        // Verify participant
        $stmt = $this->db->prepare("SELECT * FROM chats WHERE id = :id AND (buyer_id = :uid OR seller_id = :uid)");
        $stmt->execute([':id' => $chat_id, ':uid' => $user_id]);
        if (!$stmt->fetch()) {
            return [];
        }

        $stmt = $this->db->prepare("SELECT * FROM messages WHERE chat_id = :id ORDER BY created_at ASC");
        $stmt->execute([':id' => $chat_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function sendMessage($chat_id, $sender_id, $message)
    {
        $stmt = $this->db->prepare("INSERT INTO messages (chat_id, sender_id, message) VALUES (:cid, :sid, :msg)");
        $stmt->execute([':cid' => $chat_id, ':sid' => $sender_id, ':msg' => $message]);
        return $this->db->lastInsertId();
    }

    public function startConversation($buyer_id, $seller_id, $ad_id)
    {
        // Check if exists
        $stmt = $this->db->prepare("SELECT id FROM chats WHERE buyer_id = :bid AND seller_id = :sid AND ad_id = :aid");
        $stmt->execute([':bid' => $buyer_id, ':sid' => $seller_id, ':aid' => $ad_id]);
        $chat = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($chat) {
            return $chat['id'];
        }

        $stmt = $this->db->prepare("INSERT INTO chats (buyer_id, seller_id, ad_id) VALUES (:bid, :sid, :aid)");
        $stmt->execute([':bid' => $buyer_id, ':sid' => $seller_id, ':aid' => $ad_id]);
        return $this->db->lastInsertId();
    }
}
