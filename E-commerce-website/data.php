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
    $imagePath = $p['image_main'];
    if (strpos($imagePath, 'http') === 0) {
        // Absolute URL, do nothing
    } else {
        // Strip leading media/ or /media/ if present
        $imagePath = preg_replace('/^\/?media\//', '', $imagePath);
        
        if (strpos($imagePath, 'public/') === 0) {
            // Already starts with public/, leave as is
        } elseif (strpos($imagePath, 'images/') === 0) {
            $imagePath = 'public/' . $imagePath;
        } else {
            $imagePath = 'public/images/' . $imagePath;
        }
    }
    // Standardize to public/images/...
    $imagePath = str_replace('../public/', 'public/', $imagePath);

    // Generate multiple image paths for product gallery
    $imageBaseName = pathinfo($imagePath, PATHINFO_FILENAME);
    $imageExt = pathinfo($imagePath, PATHINFO_EXTENSION);
    $imageDir = dirname($imagePath);

    // Check for additional product images (e.g., keyboard_2.jpg, keyboard_3.jpg)
    $productImages = [$imagePath];
    for ($i = 2; $i <= 3; $i++) {
        $altImage = $imageDir . '/' . $imageBaseName . '_' . $i . '.' . $imageExt;
        // Always add to create gallery effect (will use main image as fallback if alt doesn't exist)
        $productImages[] = file_exists(__DIR__ . '/' . $altImage) ? $altImage : $imagePath;
    }

    $products[$p['id']] = [
        'id' => $p['id'],
        'name' => $p['name'],
        'price' => $p['price'],
        'originalPrice' => $p['original_price'],
        'image' => $imagePath,
        'images' => $productImages,
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
        return '₹' . number_format($amount, 2, '.', ',');
    }
}

/**
 * Proper Shipping Cost Calculation (Old Logic)
 */
function calculate_shipping_cost($method, $subtotal)
{
    $base = max(0, $subtotal);
    switch ($method) {
        case 'standard':
            return 350;
        case 'express':
            $percent = (int) round($base * 0.10);
            return (int) min(700, $percent > 0 ? $percent : 700);
        case 'white_glove':
            $percent = (int) round($base * 0.05);
            return (int) min(1600, $percent > 0 ? $percent : 1600);
        case 'freight':
            $percent = (int) round($base * 0.03);
            return (int) max(2500, $percent);
        default:
            return 350;
    }
}

/**
 * Tax Calculation (Standard 18% GST)
 */
function calculate_tax($amount)
{
    return (int) round($amount * 0.18);
}
