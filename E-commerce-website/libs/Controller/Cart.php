<?php

class Controller_Cart extends Controller_Abstract
{
    public function execute()
    {
        require_once ROOT_PATH . '/src/CartDAO.php';
        $cartDAO = new CartDAO();

        $userId = !empty($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
        $guestId = !empty($_SESSION['guest_id']) ? (string) $_SESSION['guest_id'] : null;
        $sessionId = session_id();

        // Get Cart ID
        $cartId = $_SESSION['cart_id'] ?? $cartDAO->getOrCreateCart($sessionId, $userId, $guestId);
        $_SESSION['cart_id'] = $cartId;

        // Fetch items directly from DB to ensure consistency
        $dbItems = $cartDAO->getItems($cartId);

        // Sync session (useful if page reloads)
        $sessionCart = [];
        $subtotal = 0;
        foreach ($dbItems as $it) {
            $subtotal += ($it['price'] * $it['quantity']);
            $sessionCart[$it['product_id']] = [
                'product_id' => $it['product_id'],
                'name' => $it['name'],
                'price' => (int) $it['price'],
                'image' => $it['image'],
                'quantity' => $it['quantity']
            ];
        }
        $_SESSION['cart'] = $sessionCart;

        $view = new View_Default();
        $view->setTemplate('cart/main');
        $view->debug_controller = 'Controller_Cart_New';
        $view->page_title = 'Your Shopping Cart - EasyCart';
        $view->page_css = 'cart.css';
        $view->cartItems = $dbItems;
        $view->subtotal = $subtotal;

        echo $view->toHtml();
    }
}
