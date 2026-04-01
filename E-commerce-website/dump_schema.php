<?php
require 'includes/db.php';
$db = getDb();
echo "Table: catalog_product_entity\n";
$stmt = $db->query("SELECT * FROM catalog_product_entity LIMIT 1");
print_r($stmt->fetch(PDO::FETCH_ASSOC));

echo "\nTable: catalog_category_entity\n";
$stmt = $db->query("SELECT * FROM catalog_category_entity LIMIT 1");
print_r($stmt->fetch(PDO::FETCH_ASSOC));
