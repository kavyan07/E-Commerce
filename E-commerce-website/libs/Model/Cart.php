<?php

class Model_Cart extends Model_Abstract
{
    public function __construct($data = [])
    {
        $this->_resource = new Model_Cart_Resource();
        parent::__construct($data);
    }

    /**
     * Get or create cart for current session/user
     */
    public function getActiveCart($sessionId, $userId = null, $guestId = null)
    {
        $db = Core_Connection::getInstance();
        $sql = "SELECT * FROM sales_cart WHERE is_active = TRUE AND (session_id = :sid";
        $params = [':sid' => $sessionId];

        if ($userId) {
            $sql .= " OR user_id = :uid";
            $params[':uid'] = $userId;
        } elseif ($guestId) {
            $sql .= " OR guest_id = :gid";
            $params[':gid'] = $guestId;
        }
        $sql .= ") LIMIT 1";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetch();

        if ($data) {
            $this->setData($data);
        } else {
            // Create new cart
            $this->setData([
                'session_id' => $sessionId,
                'user_id' => $userId,
                'guest_id' => $guestId,
                'is_active' => true
            ])->save();
        }
        return $this;
    }
}
