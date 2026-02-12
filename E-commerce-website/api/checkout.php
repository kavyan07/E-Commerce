<?php
/**
 * API Endpoint: Order Placement
 */
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../classes/OrderDAO.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    echo json_encode(['success' => false, 'message' => 'Cart is empty']);
    exit;
}

$dao = new OrderDAO();

// 1. Calculations
$subtotal = $dao->calculateSubtotal($cart);
$shippingCost = $input['shipping_cost'] ?? 40;
$tax = $dao->calculateTax($subtotal + $shippingCost);
$final = $dao->calculateFinalAmount($subtotal, $shippingCost, $tax);

// 2. Prepare Order Data
$orderData = [
    'order_number' => 'ORD-' . strtoupper(uniqid()),
    'user_id' => $_SESSION['user_id'] ?? null,
    'subtotal' => $subtotal,
    'shipping_type' => $input['shipping_method'] ?? 'standard',
    'shipping_cost' => $shippingCost,
    'tax' => $tax,
    'final_amount' => $final,
    'shipping_name' => $input['name'] ?? 'Guest',
    'shipping_email' => $input['email'] ?? '',
    'shipping_phone' => $input['phone'] ?? '',
    'shipping_address' => $input['address'] ?? '',
    'items' => array_values($cart) // Snapshot of items
];

$orderId = $dao->createOrder($orderData);

if ($orderId) {
    unset($_SESSION['cart']); // Clear cart
    echo json_encode([
        'success' => true,
        'message' => 'Order placed successfully!',
        'order_id' => $orderId,
        'order_number' => $orderData['order_number']
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Could not save order']);
}
