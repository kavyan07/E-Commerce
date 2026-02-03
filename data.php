<?php
/**
 * Data Bridge: Connection between the old static structure and the new DAO pattern.
 * This file now fetches data from the PostgreSQL database using ProductDAO.
 */
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/src/ProductDAO.php';
require_once __DIR__ . '/src/CommonDAO.php';

// Initialize DAOs
$productDAO = new ProductDAO();
$categoryDAO = new CategoryDAO();
$brandDAO = new BrandDAO();

// 1. Fetch Products from Database
$dbProducts = $productDAO->getAllProducts();
$products = [];
foreach ($dbProducts as $p) {
    // Cleanup image path (remove public/ or ../ if present)
    $imagePath = str_replace(['public/', '../public/'], '', $p['image_main']);
    if (strpos($imagePath, 'images/') !== 0) {
        $imagePath = 'images/' . $imagePath;
    }

    $products[$p['id']] = [
        'id' => $p['id'],
        'name' => $p['name'],
        'price' => $p['price'],
        'originalPrice' => $p['original_price'],
        'image' => $imagePath,
        'description' => $p['description'],
        'badge' => $p['badge'],
        'shipping_type' => $p['shipping_type'],
        'rating' => (int) ($p['rating'] ?? 0),
        'reviews' => (int) ($p['reviews_count'] ?? 0),
        'category' => $p['category_slug'] ?? 'general',
        'brand' => $p['brand_name'] ?? 'EasyCart'
    ];
}

// 2. Fetch Categories from Database
$dbCategories = $categoryDAO->getAllCategories();
$categories = [];
foreach ($dbCategories as $cat) {
    $categories[$cat['slug']] = $cat['name'];
}

// 3. Fetch Brands from Database
$dbBrands = $brandDAO->getAllBrands();
$brands = [];
foreach ($dbBrands as $b) {
    $brands[] = $b['name'];
}

// Sample Orders (Kept in session/static for now as requested for current Phase)
$orders = $_SESSION['orders'] ?? [];

// Helper function to format prices (same as before)
if (!function_exists('format_price')) {
    function format_price($amount)
    {
        return 'Rs. ' . number_format($amount, 0, ',', ',');
    }
}
