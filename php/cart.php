<?php
// cart actions: add, update, remove
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../data.php';

// initialize cart if needed
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Helper to recalc subtotal and save to session
function recalc_cart_subtotal() {
    global $products;
    $subtotal = 0;
    foreach ($_SESSION['cart'] as $id => $item) {
        $pid = (int)$id;
        if (isset($products[$pid])) {
            $subtotal += $products[$pid]['price'] * (int)$item['quantity'];
        }
    }
    $_SESSION['cart_subtotal'] = $subtotal;
}

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    if ($action === 'add') {
        $id = (int)($_POST['id'] ?? 0);
        $qty = max(1, (int)($_POST['quantity'] ?? 1));
        if ($id && isset($products[$id])) {
            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['quantity'] += $qty;
            } else {
                $_SESSION['cart'][$id] = [
                    'id' => $id,
                    'quantity' => $qty
                ];
            }
        }
    } elseif ($action === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        $qty = max(1, (int)($_POST['quantity'] ?? 1));
        if ($id && isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] = $qty;
        }
    } elseif ($action === 'remove') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id && isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
    }

    // Recalculate subtotal and redirect (Post/Redirect/Get)
    recalc_cart_subtotal();
    header('Location: cart.php');
    exit;
}

$page_title = 'Cart - EasyCart';
$page_css = 'cart.css';
require_once __DIR__ . '/../includes/header.php';

// Render cart
$cart = $_SESSION['cart'];
?>

    <div class="container">
        <div class="page-header">
            <h1>
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                Shopping Cart
            </h1>
            <span class="item-count"><?php echo array_sum(array_column($cart, 'quantity')) ?: 0; ?> items</span>
        </div>

        <div class="cart-layout">
            <div class="cart-items" id="cartItems">
                <?php if (empty($cart)): ?>
                    <div class="empty-cart" style="display:flex;">
                        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                        <div>
                            <h2>Your cart is empty</h2>
                            <p>Looks like you haven't added anything to your cart yet.</p>
                            <a href="product-listing.php" class="shop-now-btn">Start Shopping</a>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($cart as $id => $item):
                        $p = $products[(int)$id];
                    ?>
                        <div class="cart-item">
                            <div class="item-image"><img src="../<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>"></div>
                            <div class="item-details">
                                <h3><?php echo htmlspecialchars($p['name']); ?></h3>
                                <p class="item-variant">Premium Quality</p>
                                <p class="item-price-mobile"><?php echo format_price($p['price']); ?></p>
                            </div>
                            <div class="quantity">
                                <form method="post" style="display:inline-block;">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                                    <button type="submit" onclick="this.form.quantity.value=Math.max(1,parseInt(this.form.quantity.value)-1); return true;">-</button>
                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" style="width:52px; text-align:center;">
                                    <button type="submit" onclick="this.form.quantity.value=parseInt(this.form.quantity.value)+1; return true;">+</button>
                                </form>
                            </div>
                            <div class="item-price"><?php echo format_price($p['price'] * $item['quantity']); ?></div>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                                <button type="submit" class="remove-btn" title="Remove item">Remove</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="price-summary" id="priceSummary">
                <h2>Order Summary</h2>

                <?php $subtotal = $_SESSION['cart_subtotal'] ?? 0; $shipping = $subtotal > 999 ? 0 : ($subtotal?99:0); $tax = round($subtotal * 0.18); $total = $subtotal + $shipping + $tax; ?>
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span id="subtotal"><?php echo format_price($subtotal); ?></span>
                </div>

                <div class="summary-row">
                    <span>Shipping:</span>
                    <span id="shipping"><?php echo $shipping === 0 ? 'FREE' : format_price($shipping); ?></span>
                </div>

                <div class="summary-row">
                    <span>Tax (18%):</span>
                    <span id="tax"><?php echo format_price($tax); ?></span>
                </div>

                <div class="summary-row total">
                    <span>Total:</span>
                    <span id="total"><?php echo format_price($total); ?></span>
                </div>

                <a href="checkout.php" class="checkout-btn" id="checkoutBtn">Proceed to Checkout</a>

                <a href="product-listing.php" class="continue-shopping">Continue Shopping</a>
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
