<?php
require_once __DIR__ . '/includes/db.php';
$db = getDb();
header('Content-Type: text/plain');

echo "--- Database Connection Info ---\n";
echo "DB Host: " . (getenv('DB_HOST') ?: '127.0.0.1') . "\n";
echo "DB Name: " . (getenv('DB_NAME') ?: 'ecommerce_db2') . "\n\n";

try {
    $tables = ['catalog_category_entity', 'catalog_product_entity', 'users', 'sales_orders'];
    echo "--- Table Counts ---\n";
    foreach ($tables as $t) {
        $c = $db->query("SELECT COUNT(*) FROM $t")->fetchColumn();
        echo "Table '$t' has $c rows.\n";
    }
    
    echo "\n--- Recent Orders ---\n";
    $orders = $db->query("SELECT entity_id, order_number, final_amount, created_at FROM sales_orders LIMIT 5")->fetchAll();
    if (empty($orders)) {
        echo "No orders found in table 'sales_orders'.\n";
    } else {
        foreach ($orders as $o) {
            echo "ID: {$o['entity_id']} | #{$o['order_number']} | ₹{$o['final_amount']} | Date: {$o['created_at']}\n";
        }
    }
    
    echo "\n--- Catalog Categories ---\n";
    $cats = $db->query("SELECT entity_id, name FROM catalog_category_entity")->fetchAll();
    if (empty($cats)) {
        echo "No categories found.\n";
    } else {
        foreach ($cats as $c) {
            echo "ID: {$c['entity_id']} | Name: {$c['name']}\n";
        }
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
