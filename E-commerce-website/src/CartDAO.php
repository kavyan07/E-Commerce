<?php
/**
 * Cart Data Access Object (Advanced Schema)
 * Handles Guest and User persistent carts in PostgreSQL
 */
require_once __DIR__ . '/../includes/db.php';

class CartDAO
{
    private $db;

    public function __construct()
    {
        $this->db = getDb();
    }

    /**
     * Get or create a cart for the current session/user
     */
    public function getOrCreateCart($sessionId, $userId = null, $guestId = null)
    {
        // If userId is provided, verify it actually exists in the current database
        if ($userId) {
            $checkUser = $this->db->prepare("SELECT id FROM users WHERE id = ?");
            $checkUser->execute([(int) $userId]);
            if (!$checkUser->fetch()) {
                $userId = null; // User doesn't exist in this database (e.g., after a DB rebuild)
                if (isset($_SESSION['user_id']))
                    unset($_SESSION['user_id']);
                if (isset($_SESSION['user']))
                    unset($_SESSION['user']);
            }
        }

        // Check if active cart exists for this session or user
        if ($userId) {
            $stmt = $this->db->prepare("SELECT cart_id FROM sales_cart WHERE user_id = ? AND is_active = TRUE ORDER BY created_at DESC LIMIT 1");
            $stmt->execute([(int) $userId]);
        } else {
            $stmt = $this->db->prepare("SELECT cart_id FROM sales_cart WHERE session_id = ? AND is_active = TRUE ORDER BY created_at DESC LIMIT 1");
            $stmt->execute([$sessionId]);
        }

        $cartRecord = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cartRecord && $cartRecord['cart_id'] > 0) {
            return (int) $cartRecord['cart_id'];
        }

        // Create new cart
        $sql = "INSERT INTO sales_cart (session_id, user_id, guest_id) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$sessionId, $userId, $guestId]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Add item to cart
     */
    public function addItem($cartId, $productId, $qty, $price)
    {
        // Check if item already in cart
        $stmt = $this->db->prepare("SELECT entity_id, quantity FROM sale_cart_product WHERE cart_id = ? AND product_id = ?");
        $stmt->execute([(int) $cartId, (int) $productId]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            $newQty = (int) $existing['quantity'] + (int) $qty;
            $stmt = $this->db->prepare("UPDATE sale_cart_product SET quantity = ? WHERE entity_id = ?");
            return $stmt->execute([$newQty, (int) $existing['entity_id']]);
        } else {
            $stmt = $this->db->prepare("INSERT INTO sale_cart_product (cart_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            return $stmt->execute([(int) $cartId, (int) $productId, (int) $qty, $price]);
        }
    }

    /**
     * Update item quantity
     */
    public function updateItem($cartId, $productId, $qty)
    {
        $stmt = $this->db->prepare("UPDATE sale_cart_product SET quantity = ? WHERE cart_id = ? AND product_id = ?");
        return $stmt->execute([(int) $qty, (int) $cartId, (int) $productId]);
    }

    /**
     * Remove item from cart
     */
    public function removeItem($cartId, $productId)
    {
        $stmt = $this->db->prepare("DELETE FROM sale_cart_product WHERE cart_id = ? AND product_id = ?");
        return $stmt->execute([(int) $cartId, (int) $productId]);
    }

    /**
     * Get all items in cart
     */
    public function getItems($cartId)
    {
        if (empty($cartId))
            return [];
        $sql = "SELECT cp.*, p.name, p.image_main as image 
                FROM sale_cart_product cp
                JOIN catalog_product_entity p ON cp.product_id = p.entity_id
                WHERE cp.cart_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([(int) $cartId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
