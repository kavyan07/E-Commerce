<?php
$page_title = 'Checkout - EasyCart';
$page_css = 'checkout.css';
require_once __DIR__ . '/../includes/header.php';

$cart = $_SESSION['cart'] ?? [];
$subtotal = $_SESSION['cart_subtotal'] ?? 0;
$shipping = $subtotal > 999 ? 0 : ($subtotal?299:0); // default shown in UI
$tax = round($subtotal * 0.18);
$total = $subtotal + $shipping + $tax;
?>

    <div class="container">
        <h1>🛍️ Checkout</h1>

        <div class="checkout-layout">
            <form class="checkout-form">
                <!-- Shipping Address -->
                <div class="form-section">
                    <h2>Shipping Address</h2>
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" placeholder="Enter your full name" required>
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="tel" placeholder="Enter your phone number" required>
                    </div>
                    <div class="form-group">
                        <label>Street Address</label>
                        <input type="text" placeholder="Enter your street address" required>
                    </div>
                </div>

                <!-- Shipping Options -->
                <div class="form-section">
                    <h2>Shipping Options</h2>
                    <div class="shipping-option">
                        <input type="radio" id="standard" name="shipping" value="standard" checked>
                        <label for="standard">
                            <strong>Standard Shipping - ₹299</strong><br>
                            <small>Delivery in 5-7 business days</small>
                        </label>
                    </div>
                    <div class="shipping-option">
                        <input type="radio" id="express" name="shipping" value="express">
                        <label for="express">
                            <strong>Express Shipping - ₹799</strong><br>
                            <small>Delivery in 2-3 business days</small>
                        </label>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="form-section">
                    <h2>Payment Method</h2>
                    <div class="form-group">
                        <label>Select Payment Method</label>
                        <select required>
                            <option value="">-- Choose a payment method --</option>
                            <option value="card">Credit/Debit Card</option>
                            <option value="upi">UPI</option>
                            <option value="wallet">Digital Wallet</option>
                            <option value="cod">Cash on Delivery</option>
                        </select>
                    </div>
                </div>
            </form>

            <div class="order-summary">
                <h2>Order Summary</h2>

                <?php if (empty($cart)): ?>
                    <p>No items in cart.</p>
                <?php else: ?>
                    <?php foreach ($cart as $id => $it): $p = $products[(int)$id]; ?>
                        <div class="summary-item"><span><?php echo htmlspecialchars($p['name']) . ' (x' . $it['quantity'] . ')'; ?></span><span><?php echo format_price($p['price'] * $it['quantity']); ?></span></div>
                    <?php endforeach; ?>

                    <div class="summary-item"><span>Subtotal</span><span><?php echo format_price($subtotal); ?></span></div>
                    <div class="summary-item"><span>Shipping</span><span><?php echo $shipping===0 ? 'FREE' : format_price($shipping); ?></span></div>
                    <div class="summary-item"><span>Tax</span><span><?php echo format_price($tax); ?></span></div>

                    <div class="summary-total"><span>Total Amount:</span><span><?php echo format_price($total); ?></span></div>

                    <button class="place-order-btn">Place Order</button>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
