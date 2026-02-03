<?php
// Cart Controller
$action = $_POST['action'] ?? $_GET['action'] ?? '';
$product_id = (int) ($_POST['product_id'] ?? $_GET['id'] ?? 0);
$quantity = (int) ($_POST['quantity'] ?? 1);

// Handle non-AJAX actions (legacy support)
if ($action === 'add' && $product_id > 0) {
    if (!isset($_SESSION['cart']))
        $_SESSION['cart'] = [];
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $p = $products[$product_id];
        $_SESSION['cart'][$product_id] = [
            'product_id' => $product_id,
            'name' => $p['name'],
            'price' => $p['price'],
            'image' => $p['image'],
            'quantity' => $quantity
        ];
    }
    header("Location: cart");
    exit;
}

if ($action === 'remove' && $product_id > 0) {
    unset($_SESSION['cart'][$product_id]);
    header("Location: cart");
    exit;
}

$page_title = 'EasyCart - Shopping Cart';
$page_css = 'cart.css';

loadView('cart', [
    'page_title' => $page_title,
    'page_css' => $page_css,
    'cart' => $_SESSION['cart'] ?? []
]);
