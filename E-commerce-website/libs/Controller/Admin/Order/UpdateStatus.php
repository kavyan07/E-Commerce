<?php

/**
 * Admin Order Status Update Controller
 */
class Controller_Admin_Order_UpdateStatus extends Controller_Admin_Abstract
{
    public function execute()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/E-commerce-website/admin/orders');
        }

        $orderId = isset($_POST['order_id']) ? (int) $_POST['order_id'] : 0;
        $status  = trim($_POST['status'] ?? 'Processing');

        if ($orderId) {
            $db = getDb();
            try {
                $stmt = $db->prepare("UPDATE sales_orders SET status = :status WHERE entity_id = :id");
                $stmt->execute(['status' => $status, 'id' => $orderId]);
                $_SESSION['flash_message'] = ['text' => "✅ Order status updated to $status", 'type' => 'success'];
            } catch (\Exception $e) {
                $_SESSION['flash_message'] = ['text' => "Error: " . $e->getMessage(), 'type' => 'error'];
            }
        }

        $this->redirect('/E-commerce-website/admin/orders');
    }
}
