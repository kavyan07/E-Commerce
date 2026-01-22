<?php
// Static data arrays for products, categories, brands and sample orders
// Keep this file simple and readable. Real apps would use a database.

$products = [
    1 => [
        'id' => 1,
        'name' => 'Premium Sneakers',
        'category' => 'fashion',
        'brand' => 'Nike',
        'price' => 2499,
        'originalPrice' => 3999,
        'image' => 'public/images/products/sneakers.jpg',
        'rating' => 5,
        'reviews' => 128,
        'badge' => 'Best Seller',
        'description' => 'Experience ultimate comfort with our Premium Sneakers.'
    ],
    2 => [
        'id' => 2,
        'name' => 'Smart Watch',
        'category' => 'electronics',
        'brand' => 'Apple',
        'price' => 4999,
        'originalPrice' => 7499,
        'image' => 'public/images/products/smartwatch.jpg',
        'rating' => 4,
        'reviews' => 89,
        'badge' => 'New',
        'description' => 'Stay connected with our feature-packed Smart Watch.'
    ],
    3 => [
        'id' => 3,
        'name' => 'Wireless Earbuds',
        'category' => 'electronics',
        'brand' => 'Sony',
        'price' => 1999,
        'originalPrice' => 2999,
        'image' => 'public/images/products/earbuds.jpg',
        'rating' => 5,
        'reviews' => 256,
        'badge' => '',
        'description' => 'Immersive audio with active noise cancellation.'
    ],
    4 => [
        'id' => 4,
        'name' => 'Winter Jacket',
        'category' => 'fashion',
        'brand' => 'North',
        'price' => 3299,
        'originalPrice' => 5499,
        'image' => 'public/images/products/jacket.jpg',
        'rating' => 4,
        'reviews' => 74,
        'badge' => 'Sale',
        'description' => 'Stay warm in style with our premium Winter Jacket.'
    ],
    5 => [
        'id' => 5,
        'name' => 'Premium Phone Case',
        'category' => 'accessories',
        'brand' => 'Generic',
        'price' => 499,
        'originalPrice' => 999,
        'image' => 'public/images/products/phonecase.jpg',
        'rating' => 4,
        'reviews' => 42,
        'badge' => '',
        'description' => 'Protect your phone with our durable Premium Phone Case.'
    ],
];

$categories = [
    'fashion' => 'Fashion',
    'electronics' => 'Electronics',
    'accessories' => 'Accessories',
    'gaming' => 'Gaming',
    'home' => 'Home & Kitchen'
];

$brands = ['Nike', 'Apple', 'Samsung', 'Sony', 'North'];

$orders = [
    [
        'order_id' => '#ORD-001234',
        'date' => 'Jan 15, 2026',
        'items' => 3,
        'total' => 10549,
        'status' => 'Delivered'
    ],
    [
        'order_id' => '#ORD-001235',
        'date' => 'Jan 12, 2026',
        'items' => 2,
        'total' => 5799,
        'status' => 'Delivered'
    ],
    [
        'order_id' => '#ORD-001236',
        'date' => 'Jan 10, 2026',
        'items' => 1,
        'total' => 2499,
        'status' => 'Shipped'
    ],
];

// Helper: format currency
function format_price($v) {
    return 'Rs. ' . number_format((int)$v);
}
