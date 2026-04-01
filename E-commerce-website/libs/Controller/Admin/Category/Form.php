<?php
/**
 * Admin Category Form Controller
 */
class Controller_Admin_Category_Form extends Controller_Admin_Abstract
{
    public function execute()
    {
        $db = getDb();
        $categoryId = $this->getRequest('id');
        $category = [];
        $isEdit = false;

        if ($categoryId) {
            $stmt = $db->prepare("SELECT * FROM catalog_category_entity WHERE entity_id = ?");
            $stmt->execute([$categoryId]);
            $category = $stmt->fetch();
            if ($category) {
                $isEdit = true;
            }
        }

        $view = new View_Default();
        $view->setTemplate('admin/category/form');
        $view->page_title = ($isEdit ? 'Edit' : 'Add') . ' Category - Admin Panel';
        $view->category = $category;
        $view->isEdit = $isEdit;

        echo $view->render();
    }
}
