<?php
session_start();
require_once __DIR__ . '/../data.php';

header('Content-Type: application/json; charset=utf-8');

function json_response(array $payload, int $code = 200): void {
    http_response_code($code);
    echo json_encode($payload);
    exit;
}

function read_payload(): array {
    $raw = file_get_contents('php://input');
    if ($raw) {
        $decoded = json_decode($raw, true);
        if (is_array($decoded)) return $decoded;
    }
    return $_POST ?? [];
}

function calculate_shipping_cost(string $method, float $subtotal): int {
    $base = max(0, $subtotal);
    switch ($method) {
        case 'standard':
            return 350;
        case 'express':
            $percent = (int)round($base * 0.10);
            return min(700, $percent > 0 ? $percent : 700);
        case 'white_glove':
            $percent = (int)round($base * 0.05);
            return min(1600, $percent > 0 ? $percent : 1600);
        case 'freight':
            $percent = (int)round($base * 0.03);
            return max(2500, $percent);
        default:
            return 350;
    }
}

function calc_subtotal(array $cart): int {
    $subtotal = 0;
    foreach ($cart as $it) {
        $qty = isset($it['quantity']) ? (int)$it['quantity'] : 0;
        $price = isset($it['price']) ? (int)$it['price'] : 0;
        $subtotal += ($price * $qty);
    }
    return (int)round($subtotal);
}

$payload = read_payload();
$method = isset($payload['shipping']) ? (string)$payload['shipping'] : 'standard';

$cart = $_SESSION['cart'] ?? [];
if (!is_array($cart) || empty($cart)) {
    json_response(['success' => false, 'message' => 'Cart is empty'], 400);
}

// store selected method in session
$_SESSION['shipping_method'] = $method;

$subtotal = calc_subtotal($cart);
$shipping = calculate_shipping_cost($method, $subtotal);

// coupon from session only (one coupon per order)
$validCoupons = ['SAVE5' => 5, 'SAVE10' => 10, 'SAVE15' => 15];
$couponDiscount = 0;
$couponCode = '';
$couponPercent = 0;
if (isset($_SESSION['coupon']['code']) && isset($validCoupons[$_SESSION['coupon']['code']])) {
    $couponCode = (string)$_SESSION['coupon']['code'];
    $couponPercent = (int)$validCoupons[$couponCode];
    $couponDiscount = (int)round($subtotal * ($couponPercent / 100));
    $_SESSION['coupon']['amount'] = $couponDiscount;
}

$baseForTax = max(0, $subtotal + $shipping - $couponDiscount);
$tax = (int)round($baseForTax * 0.18);
$total = (int)round($baseForTax + $tax);

json_response([
    'success' => true,
    'message' => 'OK',
    'summary' => [
        'subtotal' => $subtotal,
        'shipping' => $shipping,
        'tax' => $tax,
        'total' => $total,
        'coupon' => [
            'code' => $couponCode,
            'percent' => $couponPercent,
            'discount' => $couponDiscount,
        ],
    ],
]);

