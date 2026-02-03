<?php
// Calculate cart totals
$subtotal = 0;
$itemCount = 0;
foreach ($cart as $item) {
    $subtotal += ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
    $itemCount += ($item['quantity'] ?? 0);
}
$total = $subtotal;
?>

<section class="page-header">
    <div class="header-content">
        <h1>Shopping Cart</h1>
    </div>
</section>

<div class="container">
    <div class="cart-layout">
        <div class="cart-section">
            <?php if (empty($cart)): ?>
                <div class="empty-cart">
                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <h2>Your cart is empty</h2>
                    <p>Start shopping to add items to your cart</p>
                    <a href="product-listing" class="checkout-btn browse-btn">Browse Products</a>
                </div>
            <?php else: ?>
                <div class="cart-items" id="cartItems">
                    <?php foreach ($cart as $item):
                        $itemName = isset($item['name']) ? htmlspecialchars($item['name']) : 'Product';
                        $itemPrice = isset($item['price']) ? (int) $item['price'] : 0;
                        $itemQty = isset($item['quantity']) ? (int) $item['quantity'] : 1;
                        $itemImage = isset($item['image']) ? htmlspecialchars($item['image']) : '';
                        $itemId = isset($item['product_id']) ? (int) $item['product_id'] : 0;
                        ?>
                        <div class="cart-item" data-item-id="<?php echo $itemId; ?>">
                            <div class="item-image">
                                <img src="<?php echo $itemImage; ?>" alt="<?php echo $itemName; ?>">
                            </div>
                            <div class="item-details">
                                <h3 class="product-name">
                                    <?php echo $itemName; ?>
                                </h3>
                                <div class="item-price" data-price="<?php echo $itemPrice; ?>">
                                    <?php echo format_price($itemPrice); ?> each
                                </div>
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

        <?php if (!empty($cart)): ?>
            <div class="cart-summary-wrapper">
                <div class="cart-summary">
                    <h3>Order Summary</h3>
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span id="cartSubtotal" data-subtotal="<?php echo $subtotal; ?>">
                            <?php echo format_price($subtotal); ?>
                        </span>
                    </div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span id="cartTotal" data-total="<?php echo $total; ?>">
                            <?php echo format_price($total); ?>
                        </span>
                    </div>
                    <a href="checkout" class="checkout-btn">Proceed to Checkout</a>
                    <a href="product-listing" class="continue-shopping-link">Continue Shopping</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>