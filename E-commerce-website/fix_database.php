<?php
require_once __DIR__ . '/includes/db.php';
$db = getDb();

$sqls = [
    // Add missing columns to sales_orders if they don't exist
    "ALTER TABLE sales_orders ADD COLUMN IF NOT EXISTS payment_method VARCHAR(50) DEFAULT 'cod'",
    "ALTER TABLE sales_orders ADD COLUMN IF NOT EXISTS payment_status VARCHAR(50) DEFAULT 'Pending'",
    "ALTER TABLE sales_orders ADD COLUMN IF NOT EXISTS transaction_id VARCHAR(100)",
    "ALTER TABLE sales_orders ADD COLUMN IF NOT EXISTS razorpay_order_id VARCHAR(100)",
    
    // Ensure catalog_category_products exists as it's used in the admin list query
    "CREATE TABLE IF NOT EXISTS catalog_category_products (
        product_id INTEGER REFERENCES catalog_product_entity(entity_id) ON DELETE CASCADE,
        category_id INTEGER REFERENCES catalog_category_entity(entity_id) ON DELETE CASCADE,
        PRIMARY KEY (product_id, category_id)
    )",

    // Add position to catalog_category_entity if it's missing (though it seemed present)
    "ALTER TABLE catalog_category_entity ADD COLUMN IF NOT EXISTS position INTEGER DEFAULT 0",

    // Ensure unique index for CSV upsert
    "CREATE UNIQUE INDEX IF NOT EXISTS idx_catalog_product_sku ON catalog_product_entity (sku)",
    
    // Ensure all products have categories in the junction table if they don't
];

foreach ($sqls as $sql) {
    try {
        echo "Executing: $sql\n";
        $db->exec($sql);
    } catch (Exception $e) {
        echo "Error on $sql: " . $e->getMessage() . "\n";
    }
}
echo "Database fixes applied.\n";
