<?php
require_once __DIR__ . '/../includes/db.php';
$db = getDb();
try {
    echo "Checking sales_orders table...\n";
    $stmt = $db->query("SELECT column_name FROM information_schema.columns WHERE table_name = 'sales_orders'");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Columns in sales_orders: " . implode(', ', $columns) . "\n\n";

    echo "Checking catalog_category_entity table...\n";
    $stmt = $db->query("SELECT column_name FROM information_schema.columns WHERE table_name = 'catalog_category_entity'");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Columns in catalog_category_entity: " . implode(', ', $columns) . "\n\n";

    echo "Counts:\n";
    $c = $db->query("SELECT COUNT(*) FROM catalog_category_entity")->fetchColumn();
    $o = $db->query("SELECT COUNT(*) FROM sales_orders")->fetchColumn();
    $p = $db->query("SELECT COUNT(*) FROM catalog_product_entity")->fetchColumn();
    $u = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    echo "Categories: $c, Orders: $o, Products: $p, Users: $u\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
