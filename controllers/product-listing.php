<?php
// Product Listing Controller
$page_title = 'EasyCart - Products';
$page_css = 'product-listing.css';

// Simple server-side filters (optional)
$q = isset($_GET['q']) ? strtolower(trim($_GET['q'])) : '';
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';

$productList = $products; // from data.php (loaded in index.php)

// Filter by query and category
if ($q !== '') {
    $productList = array_filter($productList, function ($p) use ($q) {
        return strpos(strtolower($p['name']), $q) !== false || strpos(strtolower($p['description']), $q) !== false;
    });
}
if ($categoryFilter !== '') {
    $productList = array_filter($productList, function ($p) use ($categoryFilter) {
        return $p['category'] === $categoryFilter;
    });
}

// Sorting
if ($sort === 'price-low') {
    usort($productList, function ($a, $b) {
        return $a['price'] - $b['price'];
    });
} elseif ($sort === 'price-high') {
    usort($productList, function ($a, $b) {
        return $b['price'] - $a['price'];
    });
} elseif ($sort === 'newest') {
    usort($productList, function ($a, $b) {
        return $b['id'] - $a['id'];
    });
}

loadView('product-listing', [
    'page_title' => $page_title,
    'page_css' => $page_css,
    'productList' => $productList,
    'categories' => $categories,
    'categoryFilter' => $categoryFilter,
    'q' => $q,
    'sort' => $sort
]);
