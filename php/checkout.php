<?php
session_start();
require_once __DIR__ . '/../data.php';

// Get cart data
$cartItems = $_SESSION['cart'] ?? [];

// Redirect if cart is empty (BEFORE any output)
if (empty($cartItems)) {
    header('Location: cart.php');
    exit;
}

// Calculate subtotal (default values for initial render)
$subtotal = 0;
foreach ($cartItems as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

// Helper to calculate shipping cost based on method and subtotal
function calculate_shipping_cost(string $method, float $subtotal): int {
    // Ensure non-negative subtotal
    $base = max(0, $subtotal);
    switch ($method) {
        case 'standard':
            // Flat ₹350
            return 350;
        case 'express':
            // ₹700 OR 10% of subtotal (whichever is lower)
            $percent = (int)round($base * 0.10);
            return min(700, $percent > 0 ? $percent : 700);
        case 'white_glove':
            // ₹1600 OR 5% of subtotal (whichever is lower)
            $percent = (int)round($base * 0.05);
            return min(1600, $percent > 0 ? $percent : 1600);
        case 'freight':
            // 3% of subtotal, minimum ₹2500
            $percent = (int)round($base * 0.03);
            return max(2500, $percent);
        default:
            // Fallback to standard
            return 350;
    }
}

// Default method is standard (use session if available, otherwise default)
$selectedMethod = $_SESSION['shipping_method'] ?? 'standard';
$shipping       = calculate_shipping_cost($selectedMethod, $subtotal);

// Coupon handling (for initial display, based on any stored session coupon)
$validCoupons   = ['SAVE5' => 5, 'SAVE10' => 10, 'SAVE15' => 15];
$couponCode     = $_SESSION['coupon']['code']   ?? '';
$couponPercent  = $_SESSION['coupon']['percent']?? 0;
$couponDiscount = 0;
$couponError    = '';

if ($couponCode && isset($validCoupons[$couponCode])) {
    $couponPercent  = $validCoupons[$couponCode];
    $couponDiscount = (int)round($subtotal * ($couponPercent / 100));
}

// GST 18% on (Subtotal + Shipping – Discount)
$baseForTax = max(0, $subtotal + $shipping - $couponDiscount);
$tax        = $baseForTax * 0.18;
$total      = $baseForTax + $tax;

// =========================================================
// COUPON APPLICATION (SEPARATE FROM ORDER PLACEMENT)
// =========================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_coupon'])) {
    $couponCode = strtoupper(trim((string)($_POST['coupon'] ?? '')));
    
    if ($couponCode !== '') {
        if (isset($validCoupons[$couponCode])) {
            $couponPercent  = $validCoupons[$couponCode];
            $couponDiscount = (int)round($subtotal * ($couponPercent / 100));
            $_SESSION['coupon'] = [
                'code'    => $couponCode,
                'percent' => $couponPercent,
                'amount'  => $couponDiscount,
            ];
            $_SESSION['flash_message'] = [
                'text' => "Coupon '{$couponCode}' applied! You saved " . format_price($couponDiscount),
                'type' => 'success'
            ];
        } else {
            $couponError = 'Invalid coupon code';
            unset($_SESSION['coupon']);
            $_SESSION['flash_message'] = [
                'text' => 'Invalid coupon code',
                'type' => 'error'
            ];
        }
    }
    header('Location: checkout.php');
    exit;
}

// =========================================================
// PLACE ORDER (SESSION-BASED, NO DATABASE)
// =========================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['apply_coupon'])) {
    // Basic server-side validation (never trust only JS)
    $required = ['firstName', 'lastName', 'email', 'phone', 'street', 'city', 'state', 'zip', 'country'];
    $errors = [];
    foreach ($required as $field) {
        if (!isset($_POST[$field]) || trim((string)$_POST[$field]) === '') {
            $errors[] = $field . ' is required';
        }
    }

    // Shipping selection (method string)
    $selectedMethod = isset($_POST['shipping']) ? (string)$_POST['shipping'] : 'standard';

    // Recalculate totals on server using selected shipping
    $subtotal = 0;
    $itemsCount = 0;
    foreach ($cartItems as $item) {
        $qty   = isset($item['quantity']) ? (int)$item['quantity'] : 1;
        $price = isset($item['price'])   ? (int)$item['price']   : 0;
        $subtotal   += $price * $qty;
        $itemsCount += $qty;
    }
    $shipping = calculate_shipping_cost($selectedMethod, $subtotal);

    // Use existing coupon from session if available
    if (isset($_SESSION['coupon']['code']) && isset($validCoupons[$_SESSION['coupon']['code']])) {
        $couponCode    = $_SESSION['coupon']['code'];
        $couponPercent = $validCoupons[$couponCode];
        $couponDiscount = (int)round($subtotal * ($couponPercent / 100));
        $_SESSION['coupon']['amount'] = $couponDiscount;
    } else {
        $couponDiscount = 0;
    }

    $baseForTax = max(0, $subtotal + $shipping - $couponDiscount);
    $tax        = $baseForTax * 0.18;
    $total      = $baseForTax + $tax;

    if (empty($errors)) {
        // Create an order record in session
        $order = [
            'order_id' => '#ORD-' . strtoupper(substr(uniqid('', true), -6)),
            'date' => date('M d, Y'),
            'items' => $itemsCount,
            'total' => (int)round($total),
            'status' => 'Processing',
            'shipping' => $shipping,
            'tax' => (int)round($tax),
            'subtotal' => (int)round($subtotal),
            'address' => [
                'firstName' => trim((string)($_POST['firstName'] ?? '')),
                'lastName' => trim((string)($_POST['lastName'] ?? '')),
                'email' => trim((string)($_POST['email'] ?? '')),
                'phone' => trim((string)($_POST['phone'] ?? '')),
                'street' => trim((string)($_POST['street'] ?? '')),
                'city' => trim((string)($_POST['city'] ?? '')),
                'state' => trim((string)($_POST['state'] ?? '')),
                'zip' => trim((string)($_POST['zip'] ?? '')),
                'country' => trim((string)($_POST['country'] ?? '')),
            ],
            'cart' => array_values($cartItems),
        ];

        if (!isset($_SESSION['orders']) || !is_array($_SESSION['orders'])) {
            $_SESSION['orders'] = [];
        }
        // Add newest order at top
        array_unshift($_SESSION['orders'], $order);

        // Store last used shipping method/cost in session (optional)
        $_SESSION['shipping_method'] = $selectedMethod;
        $_SESSION['shipping_cost'] = $shipping;

        // Clear cart after successful checkout
        unset($_SESSION['cart']);

        header('Location: my-orders.php');
        exit;
    }
}

// Now set page variables and include header (after all redirects are handled)
$page_title = 'Checkout - EasyCart';
$page_css = 'checkout.css';
require_once __DIR__ . '/../includes/header.php';
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
                            <input type="tel" id="phone" name="phone" placeholder="9876543210" maxlength="10" required>
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
                        <?php
                            // Pre-calc costs for display based on current subtotal
                            $costStandard   = calculate_shipping_cost('standard', $subtotal);
                            $costExpress    = calculate_shipping_cost('express', $subtotal);
                            $costWhiteGlove = calculate_shipping_cost('white_glove', $subtotal);
                            $costFreight    = calculate_shipping_cost('freight', $subtotal);
                        ?>
                        <div class="shipping-option <?php echo $selectedMethod === 'standard' ? 'selected' : ''; ?>">
                            <input type="radio" id="standard" name="shipping" value="standard" data-cost="<?php echo $costStandard; ?>" <?php echo $selectedMethod === 'standard' ? 'checked' : ''; ?>>
                            <label for="standard">
                                <strong>Standard Shipping - ₹<?php echo number_format($costStandard, 0, ',', ','); ?></strong>
                                <small>Flat ₹350</small>
                            </label>
                        </div>

                        <div class="shipping-option <?php echo $selectedMethod === 'express' ? 'selected' : ''; ?>">
                            <input type="radio" id="express" name="shipping" value="express" data-cost="<?php echo $costExpress; ?>" <?php echo $selectedMethod === 'express' ? 'checked' : ''; ?>>
                            <label for="express">
                                <strong>Express Shipping - ₹<?php echo number_format($costExpress, 0, ',', ','); ?></strong>
                                <small>₹700 or 10% of subtotal (whichever is lower)</small>
                            </label>
                        </div>

                        <div class="shipping-option <?php echo $selectedMethod === 'white_glove' ? 'selected' : ''; ?>">
                            <input type="radio" id="white_glove" name="shipping" value="white_glove" data-cost="<?php echo $costWhiteGlove; ?>" <?php echo $selectedMethod === 'white_glove' ? 'checked' : ''; ?>>
                            <label for="white_glove">
                                <strong>White Glove Delivery - ₹<?php echo number_format($costWhiteGlove, 0, ',', ','); ?></strong>
                                <small>₹1600 or 5% of subtotal (whichever is lower)</small>
                            </label>
                        </div>

                        <div class="shipping-option <?php echo $selectedMethod === 'freight' ? 'selected' : ''; ?>">
                            <input type="radio" id="freight" name="shipping" value="freight" data-cost="<?php echo $costFreight; ?>" <?php echo $selectedMethod === 'freight' ? 'checked' : ''; ?>>
                            <label for="freight">
                                <strong>Freight Shipping - ₹<?php echo number_format($costFreight, 0, ',', ','); ?></strong>
                                <small>3% of subtotal, minimum ₹2500</small>
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

                <?php if (!empty($couponDiscount)): ?>
                    <div class="summary-row">
                        <span>Coupon (<?php echo htmlspecialchars($couponCode); ?> - <?php echo $couponPercent; ?>% off)</span>
                        <span>-<?php echo format_price($couponDiscount); ?></span>
                    </div>
                <?php endif; ?>

                <div class="summary-row">
                    <span>GST (18% on Subtotal + Shipping<?php echo $couponDiscount ? ' - Discount' : ''; ?>)</span>
                    <span id="taxAmount"><?php echo format_price($tax); ?></span>
                </div>
                <div class="summary-row">
                    <span>Shipping</span>
                    <span id="shippingCost"><?php echo format_price($shipping); ?></span>
                </div>
                <div class="summary-row total">
                    <span>Total Amount</span>
                    <span id="totalAmount"><?php echo format_price($total); ?></span>
                </div>

                <?php if (!empty($couponError)): ?>
                    <div class="form-error-msg" style="margin-top:0.5rem;">
                        <?php echo htmlspecialchars($couponError); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" style="margin: 0;">
                    <div class="promo-code">
                        <input type="text"
                               name="coupon"
                               id="couponInput"
                               value="<?php echo htmlspecialchars($couponCode); ?>"
                               placeholder="Enter promo code (SAVE5 / SAVE10 / SAVE15)">
                        <button type="submit" name="apply_coupon">Apply</button>
                    </div>
                </form>

                <div class="security-info">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                    <span>Your payment information is secure and encrypted</span>
                </div>
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
