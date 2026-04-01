<?php
/**
 * Admin Category Save Controller
 */
class Controller_Admin_Category_Save extends Controller_Admin_Abstract
{
    public function execute()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/E-commerce-website/admin/categories');
        }

        $db = getDb();
        $categoryId = isset($_POST['category_id']) ? (int) $_POST['category_id'] : null;
        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $parentId = isset($_POST['parent_id']) ? (int) $_POST['parent_id'] : 0;
        $position = isset($_POST['position']) ? (int) $_POST['position'] : 0;

        if (!$name) {
            $_SESSION['flash_message'] = ['text' => 'Category name is required', 'type' => 'error'];
            $this->redirect('/E-commerce-website/admin/categories');
        }

        if (!$slug) {
            $slug = strtolower(str_replace(' ', '-', preg_replace('/[^A-Za-z0-9 -]/', '', $name)));
        }

        try {
            if ($categoryId) {
                // Update
                $sql = "UPDATE catalog_category_entity SET name = ?, slug = ?, parent_id = ?, position = ? WHERE entity_id = ?";
                $stmt = $db->prepare($sql);
                $stmt->execute([$name, $slug, $parentId, $position, $categoryId]);
                $_SESSION['flash_message'] = ['text' => '✅ Category updated successfully!', 'type' => 'success'];
            } else {
                // Insert
                $sql = "INSERT INTO catalog_category_entity (name, slug, parent_id, position) VALUES (?, ?, ?, ?)";
                $stmt = $db->prepare($sql);
                $stmt->execute([$name, $slug, $parentId, $position]);
                $_SESSION['flash_message'] = ['text' => '✅ Category added successfully!', 'type' => 'success'];
            }
        } catch (\Exception $e) {
            $_SESSION['flash_message'] = ['text' => 'Error: ' . $e->getMessage(), 'type' => 'error'];
        }

        $this->redirect('/E-commerce-website/admin/categories');
    }
}
