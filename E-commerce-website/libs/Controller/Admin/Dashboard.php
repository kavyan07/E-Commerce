<?php
// Admin Dashboard Controller

class Controller_Admin_Dashboard extends Controller_Admin_Abstract
{
    public function execute()
    {
        require_once ROOT_PATH . '/includes/db.php';
        $db = getDb();

        // 1. Total Orders
        $stmt = $db->query("SELECT COUNT(*) FROM sales_orders");
        $totalOrders = (int) $stmt->fetchColumn();

        // 2. Total Revenue (Assuming 'final_amount' is stored)
        $stmt = $db->query("SELECT SUM(final_amount) FROM sales_orders");
        $totalRevenue = (float) $stmt->fetchColumn();
        if (!$totalRevenue)
            $totalRevenue = 0; // Fallback

        // 3. Total Products
        $stmt = $db->query("SELECT COUNT(*) FROM catalog_product_entity");
        $totalProducts = (int) $stmt->fetchColumn();

        // 4. Total Users
        $stmt = $db->query("SELECT COUNT(*) FROM users");
        $totalUsers = (int) $stmt->fetchColumn();

        // 5. Recent Orders
        $stmt = $db->query("SELECT * FROM sales_orders ORDER BY created_at DESC LIMIT 5");
        $recentOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $view = new View_Default();
        $view->setTemplate('admin/dashboard');
        $view->page_title = 'Admin Dashboard';
        $view->stats = [
            'total_orders'   => $totalOrders,
            'total_revenue'  => $totalRevenue,
            'total_products' => $totalProducts,
            'total_users'    => $totalUsers,
            'recent_orders'  => $recentOrders
        ];

        echo $view->render();
    }
}
