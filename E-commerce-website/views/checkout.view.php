<section class="page-header" style="background:linear-gradient(135deg,#6c5ce7 0%,#a29bfe 100%);padding:2.5rem;text-align:center;margin-bottom:0;">
    <h1 style="color:white;font-size:2rem;margin-bottom:0.4rem;">Secure Checkout</h1>
    <p style="color:rgba(255,255,255,0.85);font-size:0.95rem;">🔒 256-bit SSL Encrypted · Safe & Secure Payment</p>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</section>

<div class="container">
    <div class="checkout-layout">
        <!-- Checkout Form -->
        <div class="checkout-form-section">
            <form class="checkout-form" id="checkoutForm" method="POST">

                <!-- Delivery Address -->
                <div class="form-section" style="background:#fff;border-radius:14px;padding:1.75rem;margin-bottom:1.25rem;box-shadow:0 2px 12px rgba(0,0,0,0.06);">
                    <h2 style="font-size:1.1rem;font-weight:700;margin-bottom:1.25rem;display:flex;align-items:center;gap:0.5rem;">
                        📍 Delivery Address
                    </h2>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">First Name *</label>
                            <input type="text" id="firstName" name="firstName" placeholder="John" required
                                value="<?= isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']['first_name'] ?? '') : '' ?>">
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name *</label>
                            <input type="text" id="lastName" name="lastName" placeholder="Doe" required
                                value="<?= isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']['last_name'] ?? '') : '' ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" placeholder="john@example.com" required
                            value="<?= isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']['email'] ?? '') : '' ?>">
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
                            <input type="text" id="city" name="city" placeholder="Mumbai" required>
                        </div>
                        <div class="form-group">
                            <label for="state">State *</label>
                            <input type="text" id="state" name="state" placeholder="Maharashtra" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="zip">PIN Code *</label>
                            <input type="text" id="zip" name="zip" placeholder="400001" required>
                        </div>
                        <div class="form-group">
                            <label for="country">Country *</label>
                            <input type="text" id="country" name="country" value="India" placeholder="India" required>
                        </div>
                    </div>
                </div>

                <!-- Shipping Method -->
                <div class="form-section" style="background:#fff;border-radius:14px;padding:1.75rem;margin-bottom:1.25rem;box-shadow:0 2px 12px rgba(0,0,0,0.06);">
                    <h2 style="font-size:1.1rem;font-weight:700;margin-bottom:1.25rem;">🚚 Shipping Method</h2>
                    <?php
                    $isStandardDisabled = in_array('standard', $disabledMethods);
                    $isExpressDisabled = in_array('express', $disabledMethods);
                    $isWhiteGloveDisabled = in_array('white_glove', $disabledMethods);
                    $isFreightDisabled = in_array('freight', $disabledMethods);
                    ?>
                    <div class="shipping-option <?= $selectedMethod === 'standard' ? 'selected' : '' ?> <?= $isStandardDisabled ? 'disabled' : '' ?>">
                        <input type="radio" id="standard" name="shipping" value="standard"
                            <?= $selectedMethod === 'standard' ? 'checked' : '' ?>
                            <?= $isStandardDisabled ? 'disabled' : '' ?> onchange="updateShipping()">
                        <label for="standard">
                            <strong>Standard Shipping — <?= format_price($shippingCosts['standard']) ?></strong>
                            <small><?= $shippingCosts['standard'] === 0 ? 'Free Delivery' : '2-5 Business Days' ?></small>
                        </label>
                    </div>
                    <div class="shipping-option <?= $selectedMethod === 'express' ? 'selected' : '' ?> <?= $isExpressDisabled ? 'disabled' : '' ?>">
                        <input type="radio" id="express" name="shipping" value="express"
                            <?= $selectedMethod === 'express' ? 'checked' : '' ?>
                            <?= $isExpressDisabled ? 'disabled' : '' ?> onchange="updateShipping()">
                        <label for="express">
                            <strong>Express Shipping — <?= format_price($shippingCosts['express']) ?></strong>
                            <small>Next Day Delivery</small>
                        </label>
                    </div>
                    <div class="shipping-option <?= $selectedMethod === 'white_glove' ? 'selected' : '' ?> <?= $isWhiteGloveDisabled ? 'disabled' : '' ?>">
                        <input type="radio" id="white_glove" name="shipping" value="white_glove"
                            <?= $selectedMethod === 'white_glove' ? 'checked' : '' ?>
                            <?= $isWhiteGloveDisabled ? 'disabled' : '' ?> onchange="updateShipping()">
                        <label for="white_glove">
                            <strong>White Glove — <?= format_price($shippingCosts['white_glove']) ?></strong>
                            <small>Unpacking &amp; Setup</small>
                        </label>
                    </div>
                    <div class="shipping-option <?= $selectedMethod === 'freight' ? 'selected' : '' ?> <?= $isFreightDisabled ? 'disabled' : '' ?>">
                        <input type="radio" id="freight" name="shipping" value="freight"
                            <?= $selectedMethod === 'freight' ? 'checked' : '' ?>
                            <?= $isFreightDisabled ? 'disabled' : '' ?> onchange="updateShipping()">
                        <label for="freight">
                            <strong>Freight Shipping — <?= format_price($shippingCosts['freight']) ?></strong>
                            <small>Heavy Item Delivery</small>
                        </label>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="form-section" style="background:#fff;border-radius:14px;padding:1.75rem;margin-bottom:1.25rem;box-shadow:0 2px 12px rgba(0,0,0,0.06);">
                    <h2 style="font-size:1.1rem;font-weight:700;margin-bottom:1.25rem;">💳 Payment Method</h2>

                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:0.75rem;margin-bottom:1.25rem;">
                        <label class="payment-option-card" id="pay-card-label" onclick="selectPayment('card')">
                            <input type="radio" name="payment" value="card" id="pay-card" checked style="display:none;">
                            <div class="pay-icon">💳</div>
                            <div class="pay-name">Card</div>
                        </label>
                        <label class="payment-option-card" id="pay-upi-label" onclick="selectPayment('upi')">
                            <input type="radio" name="payment" value="upi" id="pay-upi" style="display:none;">
                            <div class="pay-icon">📲</div>
                            <div class="pay-name">UPI</div>
                        </label>
                        <label class="payment-option-card" id="pay-cod-label" onclick="selectPayment('cod')">
                            <input type="radio" name="payment" value="cod" id="pay-cod" style="display:none;">
                            <div class="pay-icon">💵</div>
                            <div class="pay-name">Cash on Delivery</div>
                        </label>
                    </div>

                    <!-- Card Details -->
                    <div id="card-details" style="border:1px solid #e2e8f0;border-radius:12px;padding:1.25rem;">
                        <div class="form-group">
                            <label>Card Number</label>
                            <input type="text" id="cardNumber" placeholder="1234 5678 9012 3456"
                                maxlength="19" oninput="formatCardNumber(this)"
                                style="font-family:monospace;letter-spacing:2px;">
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Expiry Date</label>
                                <input type="text" id="cardExpiry" placeholder="MM/YY" maxlength="5" oninput="formatExpiry(this)">
                            </div>
                            <div class="form-group">
                                <label>CVV</label>
                                <input type="text" id="cardCvv" placeholder="123" maxlength="4">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Cardholder Name</label>
                            <input type="text" id="cardName" placeholder="John Doe">
                        </div>
                        <div style="display:flex;gap:0.5rem;margin-top:0.5rem;align-items:center;">
                            <span style="font-size:1.2rem;">🔒</span>
                            <small style="color:#718096;">Your card details are encrypted and secure. We do not store your card information.</small>
                        </div>
                    </div>

                    <!-- UPI Details -->
                    <div id="upi-details" style="display:none;border:1px solid #e2e8f0;border-radius:12px;padding:1.25rem;">
                        <div class="form-group">
                            <label>UPI ID</label>
                            <input type="text" id="upiId" placeholder="yourname@upi">
                        </div>
                        <p style="font-size:0.85rem;color:#718096;">Enter your UPI ID (e.g., name@okicici, name@paytm)</p>
                    </div>

                    <!-- COD Details -->
                    <div id="cod-details" style="display:none;border:1px solid #e2e8f0;border-radius:12px;padding:1.25rem;text-align:center;">
                        <div style="font-size:2.5rem;margin-bottom:0.75rem;">💵</div>
                        <h4 style="color:#2d3748;margin-bottom:0.5rem;">Cash on Delivery</h4>
                        <p style="font-size:0.9rem;color:#718096;">Pay in cash when your order arrives. The delivery partner will collect the payment.</p>
                        <div style="background:#f0fff4;border:1px solid #c6f6d5;border-radius:8px;padding:0.75rem;margin-top:1rem;">
                            <small style="color:#2f855a;">✅ No additional charges for COD.</small>
                        </div>
                    </div>
                </div>

                <button type="button" class="place-order-btn" id="placeOrderBtn" onclick="initiatePayment()"
                    style="width:100%;padding:1.1rem;background:linear-gradient(135deg,#6c5ce7,#a29bfe);color:white;border:none;border-radius:14px;font-size:1.05rem;font-weight:700;cursor:pointer;transition:all 0.3s;letter-spacing:0.5px;">
                    🔒 Proceed to Pay — <?= format_price($total) ?>
                </button>

                <!-- Hidden submit trigger -->
                <input type="hidden" name="place_order" value="1">
            </form>
        </div>

        <!-- Order Summary -->
        <div class="order-summary">
            <h2>Order Summary</h2>
            <div class="summary-items">
                <?php foreach ($cartItems as $item): ?>
                    <div class="summary-item">
                        <div class="item-info">
                            <strong><?= htmlspecialchars($item['name']) ?></strong>
                            <span class="item-qty">x<?= $item['quantity'] ?></span>
                        </div>
                        <div class="item-total"><?= format_price($item['price'] * $item['quantity']) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="summary-divider"></div>
            <div class="summary-row"><span>Subtotal</span><span><?= format_price($subtotal) ?></span></div>
            <?php if ($couponDiscount > 0): ?>
                <div class="summary-row" style="color:#00b894;">
                    <span>Coupon (<?= htmlspecialchars($couponCode) ?>)</span>
                    <span>−<?= format_price($couponDiscount) ?></span>
                </div>
            <?php endif; ?>
            <div class="summary-row"><span>Shipping</span><span id="shippingCost"><?= format_price($shippingCost) ?></span></div>
            <div class="summary-row"><span>Tax (GST 18%)</span><span id="taxAmount"><?= format_price($tax) ?></span></div>
            <div class="summary-row total"><span>Total Amount</span><span id="totalAmount"><?= format_price($total) ?></span></div>

            <form method="POST" style="margin:0 0 1rem 0;">
                <div class="promo-code">
                    <input type="text" name="coupon" placeholder="SAVE5 / SAVE10 / SAVE15"
                        value="<?= htmlspecialchars($couponCode) ?>">
                    <button type="submit" name="apply_coupon">Apply</button>
                </div>
            </form>

            <div style="background:#f7fafc;border-radius:10px;padding:1rem;font-size:0.8rem;color:#718096;line-height:1.7;">
                🔒 Secure payment · ↩️ Easy returns · 🚚 Fast delivery
            </div>
        </div>
    </div>
</div>

<!-- Payment Processing Modal -->
<div id="paymentModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.75);z-index:9999;display:none;align-items:center;justify-content:center;backdrop-filter:blur(6px);">
    <div style="background:white;border-radius:20px;padding:2.5rem;max-width:420px;width:90%;text-align:center;box-shadow:0 25px 60px rgba(0,0,0,0.4);">
        <div id="paymentStep1">
            <div style="font-size:3.5rem;margin-bottom:1rem;">🔐</div>
            <h3 style="color:#1a1a2e;font-size:1.4rem;margin-bottom:0.5rem;">Confirm Payment</h3>
            <p style="color:#718096;margin-bottom:1.5rem;font-size:0.9rem;">You are about to pay</p>
            <div style="font-size:2.2rem;font-weight:800;color:#6c5ce7;margin-bottom:1.5rem;" id="modalAmount"><?= format_price($total) ?></div>
            <div id="paymentMethodInfo" style="background:#f7fafc;border-radius:10px;padding:0.9rem;margin-bottom:1.5rem;font-size:0.85rem;color:#4a5568;"></div>
            <div style="display:flex;gap:0.75rem;">
                <button onclick="closePaymentModal()" style="flex:1;padding:0.85rem;border:2px solid #e2e8f0;border-radius:10px;background:white;cursor:pointer;font-weight:600;color:#718096;">Cancel</button>
                <button onclick="processPayment()" id="confirmPayBtn" style="flex:2;padding:0.85rem;background:linear-gradient(135deg,#6c5ce7,#a29bfe);color:white;border:none;border-radius:10px;font-weight:700;cursor:pointer;font-size:0.95rem;">✓ Confirm Payment</button>
            </div>
        </div>

        <div id="paymentStep2" style="display:none;">
            <div style="font-size:3.5rem;margin-bottom:1rem;" id="processingIcon">⏳</div>
            <h3 style="color:#1a1a2e;font-size:1.3rem;margin-bottom:0.5rem;" id="processingTitle">Processing Payment...</h3>
            <p style="color:#718096;font-size:0.9rem;" id="processingMsg">Please wait, do not close this window.</p>
            <div id="progressBar" style="width:100%;background:#f0f0f0;border-radius:10px;height:6px;margin-top:1.5rem;overflow:hidden;">
                <div id="progressFill" style="width:0%;height:100%;background:linear-gradient(90deg,#6c5ce7,#a29bfe);border-radius:10px;transition:width 0.3s;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Extra CSS for payment cards -->
<style>
.payment-option-card {
    border: 2px solid #e2e8f0; border-radius: 12px; padding: 1rem;
    text-align: center; cursor: pointer; transition: all 0.2s;
    display: flex; flex-direction: column; align-items: center; gap: 0.4rem;
}
.payment-option-card:hover { border-color: #6c5ce7; background: #f5f3ff; }
.payment-option-card.selected { border-color: #6c5ce7; background: #f5f3ff; }
.pay-icon { font-size: 1.6rem; }
.pay-name { font-size: 0.78rem; font-weight: 600; color: #4a5568; }
input { width: 100%; padding: 0.75rem 1rem; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 0.9rem; outline: none; transition: border-color 0.2s; }
input:focus { border-color: #6c5ce7; box-shadow: 0 0 0 3px rgba(108,92,231,0.1); }
#placeOrderBtn:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(108,92,231,0.4); }
#paymentModal { display: flex; }
</style>

<script>
const shippingCosts = <?= json_encode($shippingCosts) ?>;
const subtotal = <?= $subtotal ?>;
const couponDiscount = <?= $couponDiscount ?>;
let selectedPaymentMethod = 'card';
let orderTotal = <?= $total ?>;

function selectPayment(method) {
    selectedPaymentMethod = method;
    ['card','upi','cod'].forEach(m => {
        document.getElementById('pay-' + m + '-label').classList.remove('selected');
        document.getElementById(m + '-details').style.display = 'none';
    });
    document.getElementById('pay-' + method + '-label').classList.add('selected');
    document.getElementById(method + '-details').style.display = 'block';
    document.getElementById('pay-' + method).checked = true;
}
selectPayment('card');

function formatCardNumber(input) {
    let v = input.value.replace(/\D/g,'').substring(0,16);
    input.value = v.replace(/(.{4})/g,'$1 ').trim();
}
function formatExpiry(input) {
    let v = input.value.replace(/\D/g,'');
    if (v.length >= 2) v = v.substring(0,2) + '/' + v.substring(2,4);
    input.value = v;
}

function updateShipping() {
    const sel = document.querySelector('input[name="shipping"]:checked');
    if (sel && shippingCosts[sel.value] !== undefined) {
        const s = shippingCosts[sel.value];
        const tax = Math.round((subtotal - couponDiscount) * 0.18);
        const total = (subtotal - couponDiscount) + s + tax;
        document.getElementById('shippingCost').textContent = '₹' + s.toLocaleString('en-IN');
        document.getElementById('taxAmount').textContent = '₹' + tax.toLocaleString('en-IN');
        document.getElementById('totalAmount').textContent = '₹' + total.toLocaleString('en-IN');
        orderTotal = total;
        document.getElementById('placeOrderBtn').textContent = '🔒 Proceed to Pay — ₹' + total.toLocaleString('en-IN');
    }
}

function initiatePayment() {
    const form = document.getElementById('checkoutForm');
    if (!form.reportValidity()) return;

    if (selectedPaymentMethod === 'cod') {
        // Show confirmation modal for COD
        const modal = document.getElementById('paymentModal');
        document.getElementById('modalAmount').textContent = '₹' + orderTotal.toLocaleString('en-IN');
        document.getElementById('paymentMethodInfo').textContent = 'Payment via: 💵 Cash on Delivery';
        document.getElementById('paymentStep1').style.display = 'block';
        document.getElementById('paymentStep2').style.display = 'none';
        modal.style.display = 'flex';
    } else {
        // Online Payment via Razorpay
        var options = {
            "key": "rzp_test_YourKeyHere", // Enter your Key ID here
            "amount": Math.round(orderTotal * 100), // Amount in paise
            "currency": "INR",
            "name": "EasyCart",
            "description": "Purchase from EasyCart",
            "image": "/E-commerce-website/public/favicon.ico",
            "handler": function (response){
                // On success
                const rzpId = document.createElement('input');
                rzpId.type = 'hidden'; rzpId.name = 'razorpay_payment_id'; rzpId.value = response.razorpay_payment_id;
                form.appendChild(rzpId);
                
                const rzpOrder = document.createElement('input');
                rzpOrder.type = 'hidden'; rzpOrder.name = 'razorpay_order_id'; rzpOrder.value = response.razorpay_order_id || '';
                form.appendChild(rzpOrder);

                const pm = document.createElement('input');
                pm.type = 'hidden'; pm.name = 'payment'; pm.value = 'razorpay';
                form.appendChild(pm);

                form.submit();
            },
            "prefill": {
                "name": document.getElementById('firstName').value + ' ' + document.getElementById('lastName').value,
                "email": document.getElementById('email').value,
                "contact": document.getElementById('phone').value
            },
            "theme": { "color": "#6c5ce7" }
        };
        var rzp1 = new Razorpay(options);
        rzp1.on('payment.failed', function (response){
            alert("Payment Failed: " + response.error.description);
        });
        rzp1.open();
    }
}

function closePaymentModal() {
    document.getElementById('paymentModal').style.display = 'none';
}

function processPayment() {
    document.getElementById('paymentStep1').style.display = 'none';
    document.getElementById('paymentStep2').style.display = 'block';

    // Simulate payment processing
    let progress = 0;
    const fill = document.getElementById('progressFill');
    const msgs = ['Encrypting payment...', 'Verifying card details...', 'Processing transaction...', 'Payment successful!'];
    let step = 0;

    const interval = setInterval(() => {
        progress += 2;
        fill.style.width = progress + '%';
        if (progress % 25 === 0 && step < msgs.length) {
            document.getElementById('processingMsg').textContent = msgs[step++];
        }
        if (progress >= 100) {
            clearInterval(interval);
            document.getElementById('processingIcon').textContent = '✅';
            document.getElementById('processingTitle').textContent = 'Payment Successful!';
            document.getElementById('processingMsg').textContent = 'Placing your order...';
            setTimeout(() => {
                // Set payment method and submit
                const pm = document.createElement('input');
                pm.type = 'hidden'; pm.name = 'payment'; pm.value = selectedPaymentMethod;
                document.getElementById('checkoutForm').appendChild(pm);
                document.getElementById('checkoutForm').submit();
            }, 800);
        }
    }, 30);
}

// Close modal on background click
document.getElementById('paymentModal').addEventListener('click', function(e) {
    if (e.target === this) closePaymentModal();
});
</script>