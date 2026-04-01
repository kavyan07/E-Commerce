<?php
require_once 'includes/db.php';
$db = getDb();
$tables = ['catalog_product_entity', 'catalog_category_entity', 'catalog_category_products'];
foreach ($tables as $table) {
    echo "--- Columns in $table ---\n";
    $stmt = $db->prepare("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = ?");
    $stmt->execute([$table]);
    $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$cols) {
        echo "Table does not exist!\n";
    }
    foreach ($cols as $col) {
        echo "{$col['column_name']} ({$col['data_type']})\n";
    }
    echo "\n";
}
