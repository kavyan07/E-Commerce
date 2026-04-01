<?php

/**
 * Admin Users Controller
 */
class Controller_Admin_Users extends Controller_Admin_Abstract
{
    public function execute()
    {
        $db = getDb();
        $stmt = $db->query("
            SELECT u.*, 
            (SELECT COUNT(*) FROM sales_orders WHERE user_id = u.id) as order_count,
            (SELECT COALESCE(SUM(final_amount), 0) FROM sales_orders WHERE user_id = u.id) as lifetime_value
            FROM users u
            ORDER BY u.created_at DESC");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $view = new View_Default();
        $view->setTemplate('admin/users');
        $view->page_title = 'Customers - Admin Panel';
        $view->users = $users;

        echo $view->render();
    }
}
