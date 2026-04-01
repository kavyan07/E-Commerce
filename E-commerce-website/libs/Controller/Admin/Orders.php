<?php

/**
 * Admin Orders Controller
 */
class Controller_Admin_Orders extends Controller_Admin_Abstract
{
    public function execute()
    {
        $db = getDb();

        $stmt = $db->query("
            SELECT o.*, u.first_name, u.last_name, u.email as user_email
            FROM sales_orders o
            LEFT JOIN users u ON o.user_id = u.id
            ORDER BY o.created_at DESC
        ");
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $view = new View_Default();
        $view->setTemplate('admin/orders');
        $view->page_title = 'Orders - Admin Panel';
        $view->orders = $orders;

        echo $view->render();
    }
}
