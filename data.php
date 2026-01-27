<?php
// Static data arrays for products, categories, brands and sample orders
// Keep this file simple and readable. Real apps would use a database.

$products = [
    1 => [
        'id' => 1,
        'name' => 'Gaming Console',
        'category' => 'electronics',
        'brand' => 'Sony',
        'price' => 49999,
        'originalPrice' => 59999,
        'image' => 'public/images/products/console.jpg',
        'images' => ['public/images/products/console.jpg', 'public/images/brands/sony.jpg', 'public/images/categories/electronics.jpg'],
        'rating' => 5,
        'reviews' => 156,
        'badge' => 'Best Seller',
        'description' => 'Next-gen gaming console with 4K support and exclusive games.'
    ],
    2 => [
        'id' => 2,
        'name' => 'Premium Smartwatch',
        'category' => 'electronics',
        'brand' => 'Apple',
        'price' => 12999,
        'originalPrice' => 16999,
        'image' => 'public/images/products/smartwatch.jpg',
        'images' => ['public/images/products/smartwatch.jpg', 'public/images/brands/apple.jpg', 'public/images/categories/electronics.jpg'],
        'rating' => 4.8,
        'reviews' => 234,
        'badge' => 'New',
        'description' => 'Stay connected with fitness tracking and health monitoring.'
    ],
    3 => [
        'id' => 3,
        'name' => 'Wireless Earbuds Pro',
        'category' => 'electronics',
        'brand' => 'Sony',
        'price' => 4999,
        'originalPrice' => 6999,
        'image' => 'public/images/products/earbuds.jpg',
        'images' => ['public/images/products/earbuds.jpg', 'public/images/brands/sony.jpg', 'public/images/categories/electronics.jpg'],
        'rating' => 4.9,
        'reviews' => 312,
        'badge' => 'Top Rated',
        'description' => 'Premium wireless earbuds with active noise cancellation.'
    ],
    4 => [
        'id' => 4,
        'name' => 'Sports Jacket Elite',
        'category' => 'fashion',
        'brand' => 'Nike',
        'price' => 3499,
        'originalPrice' => 5499,
        'image' => 'public/images/products/jacket.jpg',
        'images' => ['public/images/products/jacket.jpg', 'public/images/brands/nike.jpg', 'public/images/categories/fashion.jpg'],
        'rating' => 4.6,
        'reviews' => 89,
        'badge' => 'Sale',
        'description' => 'Comfortable and durable sports jacket for all seasons.'
    ],
    5 => [
        'id' => 5,
        'name' => 'Premium Phone Case',
        'category' => 'electronics',
        'brand' => 'Apple',
        'price' => 1299,
        'originalPrice' => 1999,
        'image' => 'public/images/products/phonecase.jpg',
        'images' => ['public/images/products/phonecase.jpg', 'public/images/brands/apple.jpg', 'public/images/categories/electronics.jpg'],
        'rating' => 4.4,
        'reviews' => 167,
        'badge' => '',
        'description' => 'Protect your phone with premium materials and design.'
    ],
    6 => [
        'id' => 6,
        'name' => 'Mechanical Keyboard RGB',
        'category' => 'electronics',
        'brand' => 'Samsung',
        'price' => 8999,
        'originalPrice' => 12999,
        'image' => 'public/images/products/keyboard.jpg',
        'images' => ['public/images/products/keyboard.jpg', 'public/images/brands/samsung.jpg', 'public/images/categories/electronics.jpg'],
        'rating' => 4.7,
        'reviews' => 198,
        'badge' => 'Featured',
        'description' => 'RGB mechanical keyboard with fast switches and customization.'
    ],
    7 => [
        'id' => 7,
        'name' => 'Precision Gaming Mouse',
        'category' => 'electronics',
        'brand' => 'Samsung',
        'price' => 2999,
        'originalPrice' => 4499,
        'image' => 'public/images/products/mouse.jpg',
        'images' => ['public/images/products/mouse.jpg', 'public/images/brands/samsung.jpg', 'public/images/categories/electronics.jpg'],
        'rating' => 4.5,
        'reviews' => 145,
        'badge' => '',
        'description' => 'High precision gaming mouse with customizable buttons.'
    ],
    8 => [
        'id' => 8,
        'name' => 'Ergonomic Laptop Stand',
        'category' => 'home',
        'brand' => 'Samsung',
        'price' => 2499,
        'originalPrice' => 3999,
        'image' => 'public/images/products/laptop-stand.jpg',
        'images' => ['public/images/products/laptop-stand.jpg', 'public/images/brands/samsung.jpg', 'public/images/categories/home.jpg'],
        'rating' => 4.6,
        'reviews' => 122,
        'badge' => 'Bestseller',
        'description' => 'Ergonomic laptop stand for better posture and productivity.'
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

// Helper function to format prices
if (!function_exists('format_price')) {
    function format_price($amount) {
        return 'Rs. ' . number_format($amount, 0, ',', ',');
    }
}
