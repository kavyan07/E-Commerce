<?php

/**
 * Admin Categories Controller
 */
class Controller_Admin_Categories extends Controller_Admin_Abstract
{
    public function execute()
    {
        $db = getDb();
        $stmt = $db->query("
            SELECT c.*, (SELECT COUNT(*) FROM catalog_category_products WHERE category_id = c.entity_id) as product_count
            FROM catalog_category_entity c
            ORDER BY c.name ASC");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $view = new View_Default();
        $view->setTemplate('admin/categories');
        $view->page_title = 'Categories - Admin Panel';
        $view->categories = $categories;

        echo $view->render();
    }
}
