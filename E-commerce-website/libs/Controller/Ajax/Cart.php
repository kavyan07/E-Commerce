<?php

class Controller_Ajax_Cart extends Controller_Abstract
{
    public function execute()
    {
        header('Content-Type: application/json; charset=utf-8');

        require_once ROOT_PATH . '/src/CartDAO.php';
        $cartDAO = new CartDAO();

        // Initialize Guest ID if not present
        if (!isset($_SESSION['user_id']) && !isset($_SESSION['guest_id'])) {
            $_SESSION['guest_id'] = 'GUEST-' . bin2hex(random_bytes(8));
        }

        $userId = !empty($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
        $guestId = !empty($_SESSION['guest_id']) ? (string) $_SESSION['guest_id'] : null;
        $sessionId = session_id();

        // Get or Create Cart ID
        if (empty($_SESSION['cart_id'])) {
            $cartId = $cartDAO->getOrCreateCart($sessionId, $userId, $guestId);
            $_SESSION['cart_id'] = $cartId;
        } else {
            $cartId = $_SESSION['cart_id'];
        }

        $payload = $this->readPayload();
        $action = isset($payload['action']) ? (string) $payload['action'] : 'summary';
        $productId = isset($payload['product_id']) ? (int) $payload['product_id'] : 0;
        $qty = isset($payload['quantity']) ? (int) $payload['quantity'] : 1;

        try {
            if ($action === 'add' && $productId > 0) {
                // Fetch product price dynamically
                $db = Core_Connection::getInstance();
                $stmt = $db->prepare("SELECT price FROM catalog_product_entity WHERE entity_id = ?");
                $stmt->execute([$productId]);
                $price = $stmt->fetchColumn();

                if ($price !== false) {
                    $cartDAO->addItem($cartId, $productId, $qty, $price);
                }
            } elseif ($action === 'update') {
                $cartDAO->updateItem($cartId, $productId, $qty);
            } elseif ($action === 'remove') {
                $cartDAO->removeItem($cartId, $productId);
            }

            // Sync with session for UI consistency across pages
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

            echo json_encode([
                'success' => true,
                'message' => 'Cart updated successfully',
                'summary' => [
                    'cartCount' => $totalCount,
                    'subtotal' => $totalSubtotal,
                    'isEmpty' => $totalCount <= 0,
                ]
            ]);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    private function readPayload()
    {
        $raw = file_get_contents('php://input');
        if ($raw) {
            $decoded = json_decode($raw, true);
            if (is_array($decoded))
                return $decoded;
        }
        return $_POST ?? [];
    }
}
