<?php
// AJAX Cart Controller
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

require_once ROOT_PATH . '/src/CartDAO.php';
$cartDAO = new CartDAO();

if (!isset($_SESSION['guest_id']) && empty($_SESSION['user_id'])) {
    $_SESSION['guest_id'] = 'GUEST-' . bin2hex(random_bytes(8));
}

$userId = !empty($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
$guestId = !empty($_SESSION['guest_id']) ? (string) $_SESSION['guest_id'] : null;
$sessionId = session_id();

if (empty($_SESSION['cart_id'])) {
    $cartId = $cartDAO->getOrCreateCart($sessionId, $userId, $guestId);
    $_SESSION['cart_id'] = $cartId;
} else {
    $cartId = $_SESSION['cart_id'];
}

$payload = read_payload();
$action = isset($payload['action']) ? (string) $payload['action'] : 'summary';
$productId = isset($payload['product_id']) ? (int) $payload['product_id'] : 0;
$qty = isset($payload['quantity']) ? (int) $payload['quantity'] : 1;

try {
    if ($action === 'add') {
        $p = $products[$productId] ?? null;
        if ($p) {
            $cartDAO->addItem($cartId, $productId, $qty, $p['price']);
        }
    } elseif ($action === 'update') {
        $cartDAO->updateItem($cartId, $productId, $qty);
    } elseif ($action === 'remove') {
        $cartDAO->removeItem($cartId, $productId);
    }

    $dbItems = $cartDAO->getItems($cartId);
    $sessionCart = [];
    $totalCount = 0;
    $totalSubtotal = 0;
    foreach ($dbItems as $it) {
        $totalCount += $it['quantity'];
        $totalSubtotal += ($it['price'] * $it['quantity']);
        $sessionCart[$it['product_id']] = [
            'product_id' => $it['product_id'],
            'name' => $it['name'],
            'price' => (int) $it['price'],
            'image' => $it['image'],
            'quantity' => $it['quantity']
        ];
    }
    $_SESSION['cart'] = $sessionCart;

    json_response([
        'success' => true,
        'message' => 'OK',
        'summary' => [
            'cartCount' => $totalCount,
            'subtotal' => $totalSubtotal,
            'isEmpty' => $totalCount <= 0,
        ]
    ]);
} catch (Throwable $e) {
    json_response(['success' => false, 'message' => $e->getMessage()], 500);
}
