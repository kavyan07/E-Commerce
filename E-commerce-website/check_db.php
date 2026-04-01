<?php
require 'includes/db.php';
$db = getDb();
$stmt = $db->query("SELECT entity_id, name, image_main FROM catalog_product_entity");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "<table>";
foreach ($products as $p) {
    echo "<tr><td>{$p['entity_id']}</td><td>{$p['name']}</td><td>{$p['image_main']}</td></tr>";
}
echo "</table>";
