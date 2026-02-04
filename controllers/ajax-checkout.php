<?php
// AJAX Checkout Controller
header('Content-Type: application/json; charset=utf-8');

function json_response(array $payload, int $code = 200): void
{
    http_response_code($code);
    echo json_encode($payload);
    exit;
}

function read_payload(): array
{
    $raw = file_get_contents('php://input');
    if ($raw) {
        $decoded = json_decode($raw, true);
        if (is_array($decoded))
            return $decoded;
    }
    return $_POST ?? [];
}


$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    json_response(['success' => false, 'message' => 'Cart is empty'], 400);
}

$payload = read_payload();
$method = $payload['shipping'] ?? $_SESSION['shipping_method'] ?? 'standard';

// Subtotal calculation
$subtotal = 0;
$hasFreight = false;
$expressTotal = 0;
foreach ($cart as $it) {
    $p = $products[$it['product_id']] ?? null;
    if ($p) {
        $subtotal += ($p['price'] * $it['quantity']);
        if ($p['shipping_type'] == 1)
            $hasFreight = true;
        else
            $expressTotal += ($p['price'] * $it['quantity']);
    }
}

$disabled = ($hasFreight || $expressTotal > 300) ? ['standard', 'express'] : ['white_glove', 'freight'];
if (in_array($method, $disabled)) {
    $method = in_array('standard', $disabled) ? 'freight' : 'standard';
}
$_SESSION['shipping_method'] = $method;

// Final calculations
$couponDiscount = $_SESSION['coupon']['amount'] ?? 0;
if (!$couponDiscount && !empty($_SESSION['coupon']['percent'])) {
    $couponDiscount = (int) round($subtotal * ($_SESSION['coupon']['percent'] / 100));
}

$shipping = calculate_shipping_cost($method, $subtotal);
$tax = calculate_tax($subtotal - $couponDiscount);
$total = ($subtotal - $couponDiscount) + $shipping + $tax;

json_response([
    'success' => true,
    'message' => 'OK',
    'summary' => [
        'subtotal' => $subtotal,
        'shipping' => $shipping,
        'tax' => $tax,
        'total' => $total,
        'selectedMethod' => $method,
        'disabledMethods' => $disabled,
        'coupon' => [
            'code' => $_SESSION['coupon']['code'] ?? '',
            'percent' => $_SESSION['coupon']['percent'] ?? 0,
            'discount' => $couponDiscount,
        ],
    ],
]);
