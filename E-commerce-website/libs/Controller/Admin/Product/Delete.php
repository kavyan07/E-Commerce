<?php

/**
 * Admin Product Delete Controller
 */
class Controller_Admin_Product_Delete extends Controller_Admin_Abstract
{
    public function execute()
    {
        $productId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if (!$productId) {
            $_SESSION['flash_message'] = ['text' => 'Invalid product.', 'type' => 'error'];
            $this->redirect('/E-commerce-website/admin/products');
        }

        $db = getDb();

        try {
            // Delete product (cascades to categories, attributes)
            $stmt = $db->prepare("DELETE FROM catalog_product_entity WHERE entity_id = ?");
            $stmt->execute([$productId]);
            $_SESSION['flash_message'] = ['text' => '🗑️ Product deleted successfully.', 'type' => 'success'];
        } catch (\Exception $e) {
            $_SESSION['flash_message'] = ['text' => 'Error deleting product: ' . $e->getMessage(), 'type' => 'error'];
        }

        $this->redirect('/E-commerce-website/admin/products');
    }
}
