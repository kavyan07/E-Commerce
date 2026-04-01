<?php
require_once __DIR__ . '/../includes/db.php';
$db = getDb();
try {
    echo "Current DB: ecommerce_db2\n";
    echo "Databases found: " . implode(', ', $db->query("SELECT datname FROM pg_database WHERE datistemplate = false")->fetchAll(PDO::FETCH_COLUMN)) . "\n";
    
    echo "Schemata in current DB: " . implode(', ', $db->query("SELECT schema_name FROM information_schema.schemata")->fetchAll(PDO::FETCH_COLUMN)) . "\n";
    
    echo "Tables in public schema: " . implode(', ', $db->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'")->fetchAll(PDO::FETCH_COLUMN)) . "\n";
    
    $tables = ['catalog_category_entity', 'catalog_product_entity', 'users', 'sales_orders'];
    foreach ($tables as $t) {
        $c = $db->query("SELECT COUNT(*) FROM $t")->fetchColumn();
        echo "Count for $t: $c\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
