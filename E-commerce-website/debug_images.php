<?php
require 'includes/db.php';
$db = getDb();
$stmt = $db->query("SELECT entity_id, name, image_main FROM catalog_product_entity ORDER BY entity_id LIMIT 10");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "<h2>Raw DB image_main values:</h2><pre>";
foreach ($rows as $r) {
    echo "ID={$r['entity_id']} | name={$r['name']}\n";
    echo "  image_main = [" . $r['image_main'] . "]\n\n";
}
echo "</pre>";
echo "<hr><h2>After data.php processing:</h2>";
require 'data.php';
echo "<pre>";
foreach ($products as $p) {
    echo "Name: {$p['name']}\n";
    echo "  image = [{$p['image']}]\n\n";
}
echo "</pre>";
