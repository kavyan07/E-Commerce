<?php
// Checkout Controller
if (!isset($_SESSION['user_id'])) {
    $_SESSION['flash_message'] = ['text' => 'Please login to proceed to checkout.', 'type' => 'info'];
    header('Location: login');
    exit;
}
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

// Shipping costs configuration (Using global helper)
$shippingCosts = [
    'standard' => calculate_shipping_cost('standard', $subtotal),
    'express' => calculate_shipping_cost('express', $subtotal),
    'white_glove' => calculate_shipping_cost('white_glove', $subtotal),
    'freight' => calculate_shipping_cost('freight', $subtotal)
];

// Shipping restrictions
$disabledMethods = ($hasFreight || $expressTotal > 300) ? ['standard', 'express'] : ['white_glove', 'freight'];

$selectedMethod = $_POST['shipping'] ?? $_SESSION['shipping_method'] ?? (in_array('standard', $disabledMethods) ? 'freight' : 'standard');
$_SESSION['shipping_method'] = $selectedMethod;

// Calculate current shipping cost
$currentShippingCost = $shippingCosts[$selectedMethod] ?? 350;

// Coupon logic
$validCoupons = ['SAVE5' => 5, 'SAVE10' => 10, 'SAVE15' => 15];
$couponCode = $_SESSION['coupon']['code'] ?? '';
$couponPercent = $_SESSION['coupon']['percent'] ?? 0;
$couponDiscount = 0;
if ($couponCode && isset($validCoupons[$couponCode])) {
    $couponPercent = $validCoupons[$couponCode];
    $couponDiscount = (int) round($subtotal * ($couponPercent / 100));
}

// Tax calculation
$tax = calculate_tax($subtotal - $couponDiscount);
$finalTotal = ($subtotal - $couponDiscount) + $currentShippingCost + $tax;

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
    } elseif (isset($_POST['place_order'])) {
        // Place order
        $orderDAO = new OrderDAO();
        $orderData = [
            'user_id' => $_SESSION['user_id'] ?? null,
            'cart_id' => $_SESSION['cart_id'] ?? null,
            'order_number' => 'ORD-' . strtoupper(substr(uniqid('', true), -6)),
            'subtotal' => (int) round($subtotal),
            'shipping_type' => $selectedMethod,
            'shipping_cost' => $currentShippingCost,
            'tax' => $tax,
            'final_amount' => $finalTotal,
            'shipping_name' => ($_POST['firstName'] ?? '') . ' ' . ($_POST['lastName'] ?? ''),
            'shipping_email' => $_POST['email'] ?? '',
            'shipping_phone' => $_POST['phone'] ?? '',
            'shipping_address' => ($_POST['street'] ?? '') . ', ' . ($_POST['city'] ?? '') . ', ' . ($_POST['state'] ?? '') . ', ' . ($_POST['zip'] ?? '') . ', ' . ($_POST['country'] ?? ''),
            'items' => array_values($cartItems),
            'payment_method' => $_POST['payment'] ?? 'COD',
            'payment_status' => (isset($_POST['payment']) && in_array($_POST['payment'], ['razorpay', 'card', 'upi'])) ? 'Paid' : 'Pending',
            'transaction_id' => $_POST['razorpay_payment_id'] ?? null,
            'razorpay_order_id' => $_POST['razorpay_order_id'] ?? null
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
    'tax' => $tax,
    'shippingCost' => $currentShippingCost,
    'couponDiscount' => $couponDiscount,
    'couponCode' => $couponCode,
    'couponPercent' => $couponPercent,
    'disabledMethods' => $disabledMethods,
    'selectedMethod' => $selectedMethod,
    'shippingCosts' => $shippingCosts,
    'total' => $finalTotal
]);
