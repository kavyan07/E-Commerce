<?php
/**
 * Admin Category Delete Controller
 */
class Controller_Admin_Category_Delete extends Controller_Admin_Abstract
{
    public function execute()
    {
        $categoryId = $this->getRequest('id');
        if (!$categoryId) {
            $this->redirect('/E-commerce-website/admin/categories');
        }

        $db = getDb();
        try {
            // First check if products are assigned
            $check = $db->prepare("SELECT COUNT(*) FROM catalog_category_products WHERE category_id = ?");
            $check->execute([$categoryId]);
            $count = $check->fetchColumn();

            if ($count > 0) {
                $_SESSION['flash_message'] = ['text' => "Cannot delete category: $count products are currently assigned to it.", 'type' => 'error'];
            } else {
                $stmt = $db->prepare("DELETE FROM catalog_category_entity WHERE entity_id = ?");
                $stmt->execute([$categoryId]);
                $_SESSION['flash_message'] = ['text' => '✅ Category deleted successfully!', 'type' => 'success'];
            }
        } catch (Exception $e) {
            $_SESSION['flash_message'] = ['text' => 'Error: ' . $e->getMessage(), 'type' => 'error'];
        }

        $this->redirect('/E-commerce-website/admin/categories');
    }
}
