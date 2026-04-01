<?php
// Controller_Admin_Product_Export.php

class Controller_Admin_Product_Export extends Controller_Admin_Abstract
{
    public function execute()
    {
        require_once ROOT_PATH . '/includes/db.php';
        $db = getDb();

        $sql = "SELECT sku, name, price, description, image_main, stock, category_id 
                FROM catalog_product_entity";
        $stmt = $db->query($sql);

        // Output headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="products_export_' . date('Y-m-d_H-i-s') . '.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $out = fopen('php://output', 'w');

        // Write CSV Header
        fputcsv($out, ['SKU', 'Name', 'Price', 'Description', 'Image Path', 'Stock', 'Category ID']);

        // Fetch and Write Rows
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            fputcsv($out, $row);
        }

        fclose($out);
        exit;
    }
}
