<?php
// Controller_Admin_Product_Import.php

class Controller_Admin_Product_Import extends Controller_Admin_Abstract
{
    public function execute()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['product_csv'])) {
            $_SESSION['import_msg'] = 'No valid CSV file uploaded.';
            header('Location: /E-commerce-website/admin/dashboard');
            exit;
        }

        $file = $_FILES['product_csv']['tmp_name'];
        if (!file_exists($file)) {
            $_SESSION['import_msg'] = 'File upload failed.';
            header('Location: /E-commerce-website/admin/dashboard');
            exit;
        }

        require_once ROOT_PATH . '/includes/db.php';
        $db = getDb();

        $handle = fopen($file, 'r');
        $headers = fgetcsv($handle); // Assuming first line is header

        $imported = 0;
        $failed = 0;
        $errors = [];

        // Headers expected: SKU, Name, Price, Description, Image, Stock, CategoryID
        while (($data = fgetcsv($handle)) !== false) {
            // Skip empty rows
            if (empty(array_filter($data))) {
                continue;
            }

            if (count($data) < 7) {
                $failed++;
                $errors[] = "Row has insufficient columns: " . implode(',', $data);
                continue;
            }

            // Map columns
            $sku = trim($data[0]);
            $name = trim($data[1]);
            $price = (float) $data[2];
            $desc = trim($data[3]);
            $img = trim($data[4]);
            // Strip leading media/ if present
            $img = preg_replace('/^\/?media\//', '', $img);
            $stock = (int) $data[5];
            $catId = !empty($data[6]) ? (int) $data[6] : null;

            // Generate URL key from product name
            $urlKey = strtolower(str_replace(' ', '-', preg_replace('/[^A-Za-z0-9 -]/', '', $name)));
            $urlKey = $urlKey ?: $sku; // Fallback to SKU if name is empty

            try {
                // Upsert logic (insert or update on duplicate SKU)
                $sql = "INSERT INTO catalog_product_entity 
                        (sku, name, price, description, image_main, stock_qty, url_key, is_active, is_featured) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, TRUE, FALSE) 
                        ON CONFLICT (sku) 
                        DO UPDATE SET 
                        name = EXCLUDED.name, 
                        price = EXCLUDED.price, 
                        description = EXCLUDED.description, 
                        image_main = EXCLUDED.image_main, 
                        stock_qty = EXCLUDED.stock_qty,
                        url_key = EXCLUDED.url_key
                        RETURNING entity_id";

                $stmt = $db->prepare($sql);
                $stmt->execute([$sku, $name, $price, $desc, $img, $stock, $urlKey]);
                $entityId = $stmt->fetchColumn();

                if ($entityId) {
                    // Handle category mapping
                    if ($catId) {
                        $catSql = "INSERT INTO catalog_category_products (category_id, product_id) VALUES (?, ?)
                                   ON CONFLICT DO NOTHING";
                        $db->prepare($catSql)->execute([$catId, $entityId]);
                    }
                    $imported++;
                } else {
                    $failed++;
                    $errors[] = "Failed to import SKU: $sku";
                }
            } catch (Exception $e) {
                $failed++;
                $errors[] = "Error importing SKU $sku: " . $e->getMessage();
            }
        }
        fclose($handle);

        // Set detailed summary for the UI
        $_SESSION['import_summary'] = [
            'success' => $imported,
            'duplicates' => 0, // In this simple script we don't track separately
            'failed' => $failed,
            'errors' => array_slice($errors, 0, 5) // Show top 5 errors
        ];

        header('Location: /E-commerce-website/admin/products');
        exit;
    }
}
