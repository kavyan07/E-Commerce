<?php
session_start();
require_once __DIR__ . '/../data.php';

// Handle cart operations BEFORE any output
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $productId = (int)($_POST['product_id'] ?? 0);
    
    if ($action === 'update' && isset($_POST['quantity'])) {
        $quantity = max(1, (int)$_POST['quantity']);
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] = $quantity;
        }
        header('Location: cart.php');
        exit;
    } elseif ($action === 'remove') {
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }
        header('Location: cart.php');
        exit;
    } elseif ($action === 'add' && isset($_POST['quantity'])) {
        $quantity = max(1, (int)$_POST['quantity']);
        $product = $products[$productId] ?? null;
        
        if ($product) {
            if (isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$productId] = [
                    'product_id' => $productId,
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'image' => $product['image'],
                    'quantity' => $quantity
                ];
            }
        }
        header('Location: cart.php');
        exit;
    }
}

// Now set page variables and include header (after all redirects are handled)
$page_title = 'Shopping Cart - EasyCart';
$page_css = 'cart.css';
require_once __DIR__ . '/../includes/header.php';

// Calculate cart totals
$cartItems = $_SESSION['cart'] ?? [];
$subtotal = 0;
$itemCount = 0;

foreach ($cartItems as $item) {
    $subtotal += $item['price'] * $item['quantity'];
    $itemCount += $item['quantity'];
}

$shipping = !empty($cartItems) ? 350 : 0;
$tax = $subtotal * 0.18;
$total = $subtotal + $tax + $shipping;
?>

<section class="page-header">
    <div class="header-content">
        <h1>Shopping Cart</h1>
    </div>
</section>

<div class="container">
    <div class="cart-layout">
        <div class="cart-section">
            <?php if (empty($cartItems)): ?>
                <div class="empty-cart">
                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <h2>Your cart is empty</h2>
                    <p>Start shopping to add items to your cart</p>
                    <a href="product-listing.php" class="checkout-btn browse-btn">Browse Products</a>
                </div>
            <?php else: ?>
                <div class="cart-items" id="cartItems">
                    <?php foreach ($cartItems as $item): 
                        $itemName = isset($item['name']) ? htmlspecialchars($item['name']) : 'Product';
                        $itemPrice = isset($item['price']) ? (int)$item['price'] : 0;
                        $itemQty = isset($item['quantity']) ? (int)$item['quantity'] : 1;
                        $itemImage = isset($item['image']) ? htmlspecialchars($item['image']) : '';
                        $itemId = isset($item['product_id']) ? (int)$item['product_id'] : 0;
                    ?>
                        <div class="cart-item" data-item-id="<?php echo $itemId; ?>">
                            <div class="item-image">
                                <img src="../<?php echo $itemImage; ?>" alt="<?php echo $itemName; ?>">
                            </div>
                            <div class="item-details">
                                <h3 class="product-name"><?php echo $itemName; ?></h3>
                                <div class="item-price" data-price="<?php echo $itemPrice; ?>"><?php echo format_price($itemPrice); ?> each</div>
                            </div>
                            <div class="item-actions">
                                <form method="POST" class="quantity-form">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="product_id" value="<?php echo $itemId; ?>">
                                    <div class="quantity">
                                        <button type="button" class="qty-decrease">−</button>
                                        <input type="number" name="quantity" value="<?php echo $itemQty; ?>" min="1" readonly>
                                        <button type="button" class="qty-increase">+</button>
                                    </div>
                                </form>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="product_id" value="<?php echo $itemId; ?>">
                                    <button type="submit" class="remove-btn">Remove</button>
                                </form>
                            </div>
                            <div class="item-total" data-total="<?php echo $itemPrice * $itemQty; ?>">
                                <?php echo format_price($itemPrice * $itemQty); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($cartItems)): ?>
        <div class="cart-summary-wrapper">
            <div class="cart-summary">
                <h3>Order Summary</h3>
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span id="cartSubtotal" data-subtotal="<?php echo $subtotal; ?>"><?php echo format_price($subtotal); ?></span>
                </div>
                <div class="summary-row">
                    <span>Tax (18%)</span>
                    <span id="cartTax" data-tax="<?php echo $tax; ?>"><?php echo format_price($tax); ?></span>
                </div>
                <div class="summary-row">
                    <span>Shipping</span>
                    <span id="cartShipping" data-shipping="<?php echo $shipping; ?>"><?php echo format_price($shipping); ?></span>
                </div>
                <div class="summary-row total">
                    <span>Total</span>
                    <span id="cartTotal" data-total="<?php echo $total; ?>"><?php echo format_price($total); ?></span>
                </div>
                <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
                <a href="product-listing.php" class="continue-shopping-link">Continue Shopping</a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Cart interactions are handled by ecommerce.js -->

<?php require_once __DIR__ . '/../includes/footer.php'; ?>