<?php

/**
 * Admin Product Save Controller - Handles create & update
 */
class Controller_Admin_Product_Save extends Controller_Admin_Abstract
{
    public function execute()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/E-commerce-website/admin/products');
        }

        $db = getDb();

        $productId  = !empty($_POST['product_id']) ? (int) $_POST['product_id'] : null;
        $name        = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $sku         = trim($_POST['sku'] ?? '');
        $price       = isset($_POST['price']) ? (float) $_POST['price'] : 0;
        $origPrice   = !empty($_POST['original_price']) ? (float) $_POST['original_price'] : null;
        $brandId     = !empty($_POST['brand_id']) ? (int) $_POST['brand_id'] : null;
        $badge       = trim($_POST['badge'] ?? '');
        $shippingType = (int) ($_POST['shipping_type'] ?? 2);
        $stockQty    = (int) ($_POST['stock_qty'] ?? 100);
        $isActive    = isset($_POST['is_active']) ? true : false;
        $isFeatured  = isset($_POST['is_featured']) ? true : false;
        $imagePath   = trim($_POST['image_main'] ?? '');
        $categoryId  = !empty($_POST['category_id']) ? (int) $_POST['category_id'] : null;

        // Handle image upload
        if (!empty($_FILES['product_image']['tmp_name'])) {
            $uploadDir = ROOT_PATH . '/public/images/products/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0775, true);
            }
            $ext = strtolower(pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','webp','gif'];
            if (in_array($ext, $allowed)) {
                $filename = 'product_' . time() . '_' . rand(100,999) . '.' . $ext;
                if (move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadDir . $filename)) {
                    $imagePath = 'public/images/products/' . $filename;
                }
            }
        }

        if (empty($name) || $price <= 0) {
            $_SESSION['flash_message'] = ['text' => 'Product name and price are required.', 'type' => 'error'];
            $redirect = $productId ? '/E-commerce-website/admin/product/edit?id=' . $productId : '/E-commerce-website/admin/product/add';
            $this->redirect($redirect);
        }

        try {
            if ($productId) {
                // UPDATE
                $stmt = $db->prepare("
                    UPDATE catalog_product_entity SET
                        name = :name, description = :description,
                        price = :price, original_price = :original_price,
                        brand_id = :brand_id, image_main = :image_main,
                        badge = :badge, shipping_type = :shipping_type,
                        stock_qty = :stock_qty, is_active = :is_active, is_featured = :is_featured
                    WHERE entity_id = :id
                ");
                $stmt->execute([
                    'name' => $name, 'description' => $description,
                    'price' => $price, 'original_price' => $origPrice,
                    'brand_id' => $brandId, 'image_main' => $imagePath,
                    'badge' => $badge, 'shipping_type' => $shippingType,
                    'stock_qty' => $stockQty, 'is_active' => (int) $isActive,
                    'is_featured' => (int) $isFeatured, 'id' => $productId
                ]);

                // Update SKU if provided (only if not duplicate)
                if ($sku) {
                    try {
                        $db->prepare("UPDATE catalog_product_entity SET sku = ? WHERE entity_id = ?")->execute([$sku, $productId]);
                    } catch (\Exception $e) {} // ignore duplicate sku
                }

                // Update Category
                if ($categoryId) {
                    $db->prepare("DELETE FROM catalog_category_products WHERE product_id = ?")->execute([$productId]);
                    $db->prepare("INSERT INTO catalog_category_products (category_id, product_id) VALUES (?,?)")->execute([$categoryId, $productId]);
                }

                $_SESSION['flash_message'] = ['text' => '✅ Product updated successfully!', 'type' => 'success'];
            } else {
                // CREATE
                $urlKey = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
                $stmt = $db->prepare("
                    INSERT INTO catalog_product_entity (name, description, sku, price, original_price, brand_id, image_main, badge, shipping_type, stock_qty, is_active, is_featured, url_key)
                    VALUES (:name, :description, :sku, :price, :original_price, :brand_id, :image_main, :badge, :shipping_type, :stock_qty, :is_active, :is_featured, :url_key)
                ");
                $stmt->execute([
                    'name' => $name, 'description' => $description,
                    'sku' => $sku ?: null, 'price' => $price,
                    'original_price' => $origPrice, 'brand_id' => $brandId,
                    'image_main' => $imagePath, 'badge' => $badge,
                    'shipping_type' => $shippingType, 'stock_qty' => $stockQty,
                    'is_active' => (int) $isActive, 'is_featured' => (int) $isFeatured,
                    'url_key' => $urlKey
                ]);
                $newId = $db->lastInsertId('catalog_product_entity_entity_id_seq');

                // Assign category
                if ($categoryId && $newId) {
                    $db->prepare("INSERT INTO catalog_category_products (category_id, product_id) VALUES (?,?)")->execute([$categoryId, $newId]);
                }

                $_SESSION['flash_message'] = ['text' => '✅ Product created successfully!', 'type' => 'success'];
            }
        } catch (\Exception $e) {
            $_SESSION['flash_message'] = ['text' => 'Error: ' . $e->getMessage(), 'type' => 'error'];
        }

        $this->redirect('/E-commerce-website/admin/products');
    }
}
