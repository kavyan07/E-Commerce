<?php

class Controller_Admin_Product extends Controller_Abstract
{
    public function execute()
    {
        $action = $this->getRequest('action');

        switch ($action) {
            case 'export':
                return $this->exportAction();
            case 'import':
                return $this->importAction();
            default:
                return $this->indexAction();
        }
    }

    protected function indexAction()
    {
        loadView('admin/product/import_export');
    }

    protected function exportAction()
    {
        $db = Core_Connection::getInstance();
        $stmt = $db->query("SELECT p.*, b.name as brand_name FROM catalog_product_entity p LEFT JOIN brands b ON p.brand_id = b.id");
        $products = $stmt->fetchAll();

        $filename = "products_" . date('Ymd_His') . ".csv";

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // CSV Header
        fputcsv($output, ['sku', 'name', 'description', 'price', 'original_price', 'brand', 'image_main', 'badge', 'shipping_type']);

        foreach ($products as $product) {
            fputcsv($output, [
                $product['sku'],
                $product['name'],
                $product['description'],
                $product['price'],
                $product['original_price'],
                $product['brand_name'],
                $product['image_main'],
                $product['badge'],
                $product['shipping_type']
            ]);
        }
        fclose($output);
        exit;
    }

    protected function importAction()
    {
        if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == 0) {
            $file = $_FILES['csv_file']['tmp_name'];
            $handle = fopen($file, "r");

            // Skip header
            $header = fgetcsv($handle);

            $success = 0;
            $failed = 0;
            $duplicates = 0;
            $errors = [];

            $db = Core_Connection::getInstance();

            while (($data = fgetcsv($handle)) !== FALSE) {
                if (count($data) < 2)
                    continue; // Basic validation

                $productData = [
                    'sku' => $data[0],
                    'name' => $data[1],
                    'description' => $data[2],
                    'price' => $data[3],
                    'original_price' => $data[4],
                    'brand_name' => $data[5],
                    'image_main' => $data[6],
                    'badge' => $data[7],
                    'shipping_type' => $data[8]
                ];

                // Basic validation
                if (empty($productData['sku']) || empty($productData['name']) || empty($productData['price'])) {
                    $failed++;
                    $errors[] = "Missing required fields for SKU: " . ($productData['sku'] ?: 'Unknown');
                    continue;
                }

                try {
                    // Handle Brand
                    $brandId = null;
                    if (!empty($productData['brand_name'])) {
                        $stmt = $db->prepare("SELECT id FROM brands WHERE name = :name");
                        $stmt->execute(['name' => $productData['brand_name']]);
                        $result = $stmt->fetch();

                        if ($result) {
                            $brandId = $result['id'];
                        } else {
                            // Create brand if not exists
                            $stmt = $db->prepare("INSERT INTO brands (name) VALUES (:name)");
                            $stmt->execute(['name' => $productData['brand_name']]);
                            $brandId = $db->lastInsertId();
                        }
                    }

                    // Check if SKU exists
                    $stmt = $db->prepare("SELECT entity_id FROM catalog_product_entity WHERE sku = :sku");
                    $stmt->execute(['sku' => $productData['sku']]);
                    $check = $stmt->fetch();

                    $saveData = [
                        'sku' => $productData['sku'],
                        'name' => $productData['name'],
                        'description' => $productData['description'],
                        'price' => $productData['price'],
                        'original_price' => !empty($productData['original_price']) ? $productData['original_price'] : null,
                        'brand_id' => $brandId,
                        'image_main' => $productData['image_main'],
                        'badge' => $productData['badge'],
                        'shipping_type' => !empty($productData['shipping_type']) ? $productData['shipping_type'] : 1
                    ];

                    $product = new Model_Product();
                    if ($check) {
                        // Update if Duplicate
                        $duplicates++;
                        $product->load($check['entity_id']);

                        $updateData = $saveData;
                        unset($updateData['sku']); // Don't update SKU

                        $product->addData($updateData);
                        $product->save();
                    } else {
                        $product->setData($saveData);
                        $product->save();
                    }
                    $success++;
                } catch (Exception $e) {
                    $failed++;
                    $errors[] = "Error processing SKU {$productData['sku']}: " . $e->getMessage();
                }
            }
            fclose($handle);

            $_SESSION['import_summary'] = [
                'success' => $success,
                'failed' => $failed,
                'duplicates' => $duplicates,
                'errors' => $errors
            ];
        } else {
            $_SESSION['import_error'] = "Please upload a valid CSV file.";
        }

        $this->redirect('admin/products');
    }
}
