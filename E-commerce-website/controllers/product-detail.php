<?php
// Product Detail Controller
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$product = $products[$id] ?? null;

if (!$product) {
    header("Location: product-listing");
    exit;
}

$page_title = 'EasyCart - ' . $product['name'];
$page_css = 'product-detail.css';

loadView('product-detail', [
    'page_title' => $page_title,
    'page_css' => $page_css,
    'product' => $product,
    'products' => $products
]);
