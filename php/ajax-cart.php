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

function calc_summary(array $cart): array {
    $subtotal = 0;
    $count = 0;
    foreach ($cart as $it) {
        $qty = isset($it['quantity']) ? (int)$it['quantity'] : 0;
        $price = isset($it['price']) ? (int)$it['price'] : 0;
        $subtotal += ($price * $qty);
        $count += $qty;
    }
    $shipping = $subtotal > 0 ? 350 : 0;
    $tax = $subtotal * 0.18;
    $total = $subtotal + $shipping + $tax;
    return [
        'cartCount' => $count,
        'subtotal' => (int)round($subtotal),
        'shipping' => (int)round($shipping),
        'tax' => (int)round($tax),
        'total' => (int)round($total),
        'isEmpty' => $count <= 0,
    ];
}

$payload = read_payload();
$action = isset($payload['action']) ? (string)$payload['action'] : 'summary';
$productId = isset($payload['product_id']) ? (int)$payload['product_id'] : 0;
$qty = isset($payload['quantity']) ? (int)$payload['quantity'] : 1;

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

try {
    if ($action === 'add') {
        if ($productId <= 0 || !isset($products[$productId])) {
            json_response(['success' => false, 'message' => 'Invalid product'], 400);
        }
        $qty = max(1, $qty);
        $p = $products[$productId];
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += $qty;
        } else {
            $_SESSION['cart'][$productId] = [
                'product_id' => $productId,
                'name' => $p['name'],
                'price' => (int)$p['price'],
                'image' => $p['image'],
                'quantity' => $qty,
            ];
        }
    } elseif ($action === 'update') {
        if ($productId <= 0 || !isset($_SESSION['cart'][$productId])) {
            json_response(['success' => false, 'message' => 'Item not found'], 404);
        }
        $qty = max(1, $qty);
        $_SESSION['cart'][$productId]['quantity'] = $qty;
    } elseif ($action === 'remove') {
        if ($productId > 0 && isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }
    } elseif ($action !== 'summary') {
        json_response(['success' => false, 'message' => 'Invalid action'], 400);
    }

    $summary = calc_summary($_SESSION['cart']);
    $item = null;
    if ($productId > 0 && isset($_SESSION['cart'][$productId])) {
        $it = $_SESSION['cart'][$productId];
        $item = [
            'product_id' => (int)$it['product_id'],
            'quantity' => (int)$it['quantity'],
            'price' => (int)$it['price'],
            'itemTotal' => (int)round(((int)$it['price']) * ((int)$it['quantity'])),
            'name' => (string)$it['name'],
        ];
    }

    json_response([
        'success' => true,
        'message' => 'OK',
        'item' => $item,
        'summary' => $summary,
    ]);
} catch (Throwable $e) {
    json_response(['success' => false, 'message' => 'Server error'], 500);
}

