<?php

/**
 * Admin Product Form Controller - Add/Edit Product
 */
class Controller_Admin_Product_Form extends Controller_Admin_Abstract
{
    public function execute()
    {
        $db = getDb();

        // Load brands for dropdown
        $brands = $db->query("SELECT id, name FROM brands ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
        $categories = $db->query("SELECT entity_id, name FROM catalog_category_entity ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

        $productId = isset($_GET['id']) ? (int) $_GET['id'] : null;
        $product = [];
        $isEdit = false;

        if ($productId) {
            $stmt = $db->prepare("SELECT * FROM catalog_product_entity WHERE entity_id = ?");
            $stmt->execute([$productId]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($product) {
                $isEdit = true;
                // Get category
                $catStmt = $db->prepare("SELECT category_id FROM catalog_category_products WHERE product_id = ? LIMIT 1");
                $catStmt->execute([$productId]);
                $catRow = $catStmt->fetch(PDO::FETCH_ASSOC);
                $product['category_id'] = $catRow['category_id'] ?? '';
            }
        }

        $view = new View_Default();
        $view->setTemplate('admin/product/form');
        $view->page_title = ($isEdit ? 'Edit' : 'Add') . ' Product - Admin Panel';
        $view->product = $product;
        $view->isEdit = $isEdit;
        $view->brands = $brands;
        $view->categories = $categories;

        echo $view->render();
    }
}
