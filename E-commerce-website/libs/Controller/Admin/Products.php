<?php

/**
 * Admin Products Controller - Full CRUD
 * Routes: admin/products, admin/product/add, admin/product/edit, admin/product/save, admin/product/delete
 */
class Controller_Admin_Products extends Controller_Admin_Abstract
{
    public function execute()
    {
        $db = getDb();

        // Fetch all products with brand name and a category name
        // Fetch all products simply first to ensure they show up
        $stmt = $db->query("SELECT * FROM catalog_product_entity ORDER BY entity_id DESC");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Try to add category name manually to avoid complex join issues if any
        foreach ($products as &$p) {
            // Clean image path
            if (strpos($p['image_main'], 'http') === 0) {
                // Absolute URL, keep as is
            } else {
                // Strip media/ if present and add public/images/
                $img = preg_replace('/^\/?media\//', '', $p['image_main']);
                $p['image_main'] = 'public/images/' . $img;
            }

            $catStmt = $db->prepare("SELECT c.name FROM catalog_category_entity c 
                                     JOIN catalog_category_products cp ON c.entity_id = cp.category_id 
                                     WHERE cp.product_id = ? LIMIT 1");
            $catStmt->execute([$p['entity_id']]);
            $p['category_name'] = $catStmt->fetchColumn() ?: 'Uncategorized';
        }

        // Fetch categories for the filter dropdown
        $catStmt = $db->query("SELECT entity_id, name FROM catalog_category_entity ORDER BY name");
        $categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

        $view = new View_Default();
        $view->setTemplate('admin/product/list');
        $view->page_title = 'Products - Admin Panel';
        $view->products = $products;
        $view->categories = $categories;

        echo $view->render();
    }
}
