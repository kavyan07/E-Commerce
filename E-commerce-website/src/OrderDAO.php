<?php
/**
 * Order Data Access Object (Advanced Schema)
 */
require_once __DIR__ . '/../includes/db.php';

class OrderDAO
{
    private $db;

    public function __construct()
    {
        $this->db = getDb();
    }

    public function createOrder($data)
    {
        try {
            $this->db->beginTransaction();

            // Insert into sales_orders
            $sql = "INSERT INTO sales_orders (
                order_number, user_id, cart_id, subtotal, tax, 
                shipping_cost, final_amount, shipping_type,
                shipping_name, shipping_email, shipping_phone, shipping_address
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $data['order_number'],
                $data['user_id'] ?? null,
                $data['cart_id'] ?? null,
                $data['subtotal'],
                $data['tax'] ?? 0,
                $data['shipping_cost'] ?? 0,
                $data['final_amount'],
                $data['shipping_type'],
                $data['shipping_name'],
                $data['shipping_email'],
                $data['shipping_phone'],
                $data['shipping_address']
            ]);

            $orderId = $this->db->lastInsertId();

            // 1. Get items from the database cart (not just session)
            $cartItemsStmt = $this->db->prepare("
                SELECT cp.product_id, p.name, cp.price, cp.quantity 
                FROM sale_cart_product cp
                JOIN catalog_product_entity p ON cp.product_id = p.entity_id
                WHERE cp.cart_id = ?
            ");
            $cartItemsStmt->execute([$data['cart_id']]);
            $dbItems = $cartItemsStmt->fetchAll(PDO::FETCH_ASSOC);

            // 2. Transfer items to sales_order_items
            $itemSql = "INSERT INTO sales_order_items (order_id, product_id, name, price, quantity, total) 
                        VALUES (?, ?, ?, ?, ?, ?)";
            $itemStmt = $this->db->prepare($itemSql);

            foreach ($dbItems as $item) {
                $itemStmt->execute([
                    $orderId,
                    $item['product_id'],
                    $item['name'],
                    $item['price'],
                    $item['quantity'],
                    $item['price'] * $item['quantity']
                ]);
            }

            // 3. Mark cart as inactive (Closed)
            if (isset($data['cart_id'])) {
                $updCart = $this->db->prepare("UPDATE sales_cart SET is_active = FALSE WHERE cart_id = ?");
                $updCart->execute([$data['cart_id']]);
            }

            $this->db->commit();
            return $orderId;
        } catch (Exception $e) {
            if ($this->db->inTransaction())
                $this->db->rollBack();
            error_log("OrderDAO Error: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get orders for a specific user
     */
    public function getOrdersByUser($userId)
    {
        $sql = "SELECT *, created_at as date FROM sales_orders WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get order items for a specific order
     */
    public function getOrderItems($orderId)
    {
        $sql = "SELECT * FROM sales_order_items WHERE order_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get summary statistics for a user dashboard
     */
    public function getDashboardStats($userId)
    {
        $sql = "SELECT COUNT(*) as total_orders, COALESCE(SUM(final_amount), 0) as total_spent 
                FROM sales_orders WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get individual orders for chart plotting (Order amount vs Date)
     */
    public function getOrderTrend($userId)
    {
        $sql = "SELECT created_at as order_timestamp, final_amount, order_number 
                FROM sales_orders 
                WHERE user_id = ? 
                ORDER BY created_at ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
