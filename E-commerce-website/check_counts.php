<?php
require 'includes/db.php';
$db = getDb();
echo "Orders: " . $db->query("SELECT COUNT(*) FROM sales_orders")->fetchColumn() . "\n";
echo "Users: " . $db->query("SELECT COUNT(*) FROM users")->fetchColumn() . "\n";
echo "Categories: " . $db->query("SELECT COUNT(*) FROM catalog_category_entity")->fetchColumn() . "\n";
echo "Products: " . $db->query("SELECT COUNT(*) FROM catalog_product_entity")->fetchColumn() . "\n";
