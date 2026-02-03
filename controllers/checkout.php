<?php
// Checkout Controller
require_once ROOT_PATH . '/src/OrderDAO.php';

// Get cart data
$cartItems = $_SESSION['cart'] ?? [];

// Redirect if cart is empty
if (empty($cartItems)) {
    header('Location: cart');
    exit;
}

// Subtotal calculation and rule logic
$subtotal = 0;
$hasFreight = false;
$expressTotal = 0;
foreach ($cartItems as $item) {
    $p = $products[$item['product_id']] ?? null;
    if ($p) {
        $subtotal += ($p['price'] * $item['quantity']);
        if ($p['shipping_type'] == 1)
            $hasFreight = true;
        else
            $expressTotal += ($p['price'] * $item['quantity']);
    }
}

// Shipping rules
function calculate_shipping_cost(string $method, float $subtotal): int
{
    return 0; // Keeping it simple/free as per original file which set shipping to 0
}

$disabledMethods = ($hasFreight || $expressTotal > 300) ? ['standard', 'express'] : ['white_glove', 'freight'];
$selectedMethod = $_SESSION['shipping_method'] ?? (in_array('standard', $disabledMethods) ? 'freight' : 'standard');

// Coupon logic
$validCoupons = ['SAVE5' => 5, 'SAVE10' => 10, 'SAVE15' => 15];
$couponCode = $_SESSION['coupon']['code'] ?? '';
$couponPercent = $_SESSION['coupon']['percent'] ?? 0;
$couponDiscount = 0;
if ($couponCode && isset($validCoupons[$couponCode])) {
    $couponPercent = $validCoupons[$couponCode];
    $couponDiscount = (int) round($subtotal * ($couponPercent / 100));
}

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['apply_coupon'])) {
        $appliedCode = strtoupper(trim((string) $_POST['coupon']));
        if (isset($validCoupons[$appliedCode])) {
            $_SESSION['coupon'] = ['code' => $appliedCode, 'percent' => $validCoupons[$appliedCode]];
            $_SESSION['flash_message'] = ['text' => "Coupon applied!", 'type' => 'success'];
        } else {
            unset($_SESSION['coupon']);
            $_SESSION['flash_message'] = ['text' => "Invalid coupon", 'type' => 'error'];
        }
        header('Location: checkout');
        exit;
    } else {
        // Place order
        $orderDAO = new OrderDAO();
        $orderData = [
            'user_id' => $_SESSION['user_id'] ?? null,
            'cart_id' => $_SESSION['cart_id'] ?? null,
            'order_number' => 'ORD-' . strtoupper(substr(uniqid('', true), -6)),
            'subtotal' => (int) round($subtotal),
            'shipping_type' => $_POST['shipping'] ?? 'standard',
            'shipping_cost' => 0,
            'tax' => 0,
            'final_amount' => (int) round($subtotal - $couponDiscount),
            'shipping_name' => ($_POST['firstName'] ?? '') . ' ' . ($_POST['lastName'] ?? ''),
            'shipping_email' => $_POST['email'] ?? '',
            'shipping_phone' => $_POST['phone'] ?? '',
            'shipping_address' => ($_POST['street'] ?? '') . ', ' . ($_POST['city'] ?? '') . ', ' . ($_POST['state'] ?? '') . ', ' . ($_POST['zip'] ?? '') . ', ' . ($_POST['country'] ?? ''),
            'items' => array_values($cartItems)
        ];

        try {
            if ($orderDAO->createOrder($orderData)) {
                unset($_SESSION['cart'], $_SESSION['cart_id'], $_SESSION['coupon']);
                $_SESSION['flash_message'] = ['text' => 'Order placed successfully!', 'type' => 'success'];
                header('Location: my-orders');
                exit;
            }
        } catch (Exception $e) {
            $_SESSION['flash_message'] = ['text' => 'Error: ' . $e->getMessage(), 'type' => 'error'];
        }
    }
}

$page_title = 'Checkout - EasyCart';
$page_css = 'checkout.css';

loadView('checkout', [
    'page_title' => $page_title,
    'page_css' => $page_css,
    'cartItems' => $cartItems,
    'subtotal' => $subtotal,
    'couponDiscount' => $couponDiscount,
    'couponCode' => $couponCode,
    'couponPercent' => $couponPercent,
    'disabledMethods' => $disabledMethods,
    'selectedMethod' => $selectedMethod,
    'total' => (int) round($subtotal - $couponDiscount)
]);
