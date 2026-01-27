<?php
session_start();
$page_title = 'Checkout - EasyCart';
$page_css = 'checkout.css';
require_once __DIR__ . '/../includes/header.php';

// Get cart data
$cartItems = $_SESSION['cart'] ?? [];

if (empty($cartItems)) {
    header('Location: cart.php');
    exit;
}

// Calculate totals
$subtotal = 0;
foreach ($cartItems as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

$tax = $subtotal * 0.18;
$defaultShipping = 100;
$shipping = $defaultShipping;
$total = $subtotal + $tax + $shipping;
?>

    <section class="page-header">
        <div class="header-content">
            <h1>Checkout</h1>
            <p>Complete your purchase securely</p>
        </div>
    </section>

    <div class="container">
        <div class="checkout-layout">
            <!-- Checkout Form -->
            <div class="checkout-form-section">
                <form class="checkout-form" method="POST">
                    <!-- Delivery Address Section -->
                    <div class="form-section">
                        <h2>Delivery Address</h2>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="firstName">First Name *</label>
                                <input type="text" id="firstName" name="firstName" placeholder="John" required>
                            </div>
                            <div class="form-group">
                                <label for="lastName">Last Name *</label>
                                <input type="text" id="lastName" name="lastName" placeholder="Doe" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" placeholder="john@example.com" required>
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number *</label>
                            <input type="tel" id="phone" name="phone" placeholder="9876543210" required>
                        </div>

                        <div class="form-group">
                            <label for="street">Street Address *</label>
                            <input type="text" id="street" name="street" placeholder="123 Main Street" required>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="city">City *</label>
                                <input type="text" id="city" name="city" placeholder="New York" required>
                            </div>
                            <div class="form-group">
                                <label for="state">State *</label>
                                <input type="text" id="state" name="state" placeholder="NY" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="zip">Postal Code *</label>
                                <input type="text" id="zip" name="zip" placeholder="10001" required>
                            </div>
                            <div class="form-group">
                                <label for="country">Country *</label>
                                <input type="text" id="country" name="country" value="India" placeholder="India" required>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Options Section -->
                    <div class="form-section">
                        <h2>Shipping Method</h2>
                        
                        <div class="shipping-option selected">
                            <input type="radio" id="standard" name="shipping" value="100" checked>
                            <label for="standard">
                                <strong>Standard Shipping - ₹100</strong>
                                <small>Delivery in 5-7 business days</small>
                            </label>
                        </div>

                        <div class="shipping-option">
                            <input type="radio" id="express" name="shipping" value="250">
                            <label for="express">
                                <strong>Express Shipping - ₹250</strong>
                                <small>Delivery in 2-3 business days</small>
                            </label>
                        </div>

                        <div class="shipping-option">
                            <input type="radio" id="overnight" name="shipping" value="500">
                            <label for="overnight">
                                <strong>Overnight Shipping - ₹500</strong>
                                <small>Next day delivery before 10 AM</small>
                            </label>
                        </div>
                    </div>

                    <!-- Payment Section (Demo) -->
                    <div class="form-section">
                        <h2>Payment Method</h2>
                        <div class="form-group">
                            <label>
                                <input type="radio" name="payment" value="card" checked>
                                Credit/Debit Card
                            </label>
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="radio" name="payment" value="upi">
                                UPI
                            </label>
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="radio" name="payment" value="wallet">
                                Digital Wallet
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="place-order-btn">Place Order</button>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="order-summary">
                <h2>Order Summary</h2>
                
                <div class="summary-items">
                    <?php foreach ($cartItems as $item): 
                        $itemName = isset($item['name']) ? htmlspecialchars($item['name']) : 'Product';
                        $itemPrice = isset($item['price']) ? (int)$item['price'] : 0;
                        $itemQty = isset($item['quantity']) ? (int)$item['quantity'] : 1;
                    ?>
                        <div class="summary-item">
                            <div class="item-info">
                                <strong><?php echo $itemName; ?></strong>
                                <span class="item-qty">x<?php echo $itemQty; ?></span>
                            </div>
                            <div class="item-price">
                                <?php echo format_price($itemPrice * $itemQty); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="summary-divider"></div>

                <div class="summary-row">
                    <span>Subtotal</span>
                    <span><?php echo format_price($subtotal); ?></span>
                </div>
                <div class="summary-row">
                    <span>Tax (18%)</span>
                    <span><?php echo format_price($tax); ?></span>
                </div>
                <div class="summary-row">
                    <span>Shipping</span>
                    <span id="shippingCost"><?php echo format_price($shipping); ?></span>
                </div>
                <div class="summary-row total">
                    <span>Total Amount</span>
                    <span id="totalAmount"><?php echo format_price($total); ?></span>
                </div>

                <div class="promo-code">
                    <input type="text" placeholder="Enter promo code (optional)">
                    <button type="button">Apply</button>
                </div>

                <div class="security-info">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                    <span>Your payment information is secure and encrypted</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Dynamic shipping cost update
        document.addEventListener('DOMContentLoaded', function() {
            const shippingRadios = document.querySelectorAll('input[name="shipping"]');
            const shippingCostEl = document.getElementById('shippingCost');
            const totalAmountEl = document.getElementById('totalAmount');
            
            const subtotal = <?php echo $subtotal; ?>;
            const tax = <?php echo $tax; ?>;

            shippingRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    const shippingCost = parseInt(this.value);
                    const newTotal = subtotal + tax + shippingCost;
                    
                    shippingCostEl.textContent = 'Rs. ' + shippingCost.toLocaleString('en-IN');
                    totalAmountEl.textContent = 'Rs. ' + newTotal.toLocaleString('en-IN');
                });
            });
        });
    </script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
