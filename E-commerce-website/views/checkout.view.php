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
                    $isStandardDisabled = in_array('standard', $disabledMethods);
                    $isExpressDisabled = in_array('express', $disabledMethods);
                    $isWhiteGloveDisabled = in_array('white_glove', $disabledMethods);
                    $isFreightDisabled = in_array('freight', $disabledMethods);
                    ?>
                    <div
                        class="shipping-option <?php echo $selectedMethod === 'standard' ? 'selected' : ''; ?> <?php echo $isStandardDisabled ? 'disabled' : ''; ?>">
                        <input type="radio" id="standard" name="shipping" value="standard" <?php echo $selectedMethod === 'standard' ? 'checked' : ''; ?> <?php echo $isStandardDisabled ? 'disabled' : ''; ?>>
                        <label for="standard">
                            <strong>Standard Shipping - <?php echo format_price($shippingCosts['standard']); ?></strong>
                            <small><?php echo $shippingCosts['standard'] === 0 ? 'Free Delivery' : '2-5 Business Days'; ?></small>
                        </label>
                    </div>

                    <div
                        class="shipping-option <?php echo $selectedMethod === 'express' ? 'selected' : ''; ?> <?php echo $isExpressDisabled ? 'disabled' : ''; ?>">
                        <input type="radio" id="express" name="shipping" value="express" <?php echo $selectedMethod === 'express' ? 'checked' : ''; ?> <?php echo $isExpressDisabled ? 'disabled' : ''; ?>>
                        <label for="express">
                            <strong>Express Shipping - <?php echo format_price($shippingCosts['express']); ?></strong>
                            <small>Next Day Delivery</small>
                        </label>
                    </div>

                    <div
                        class="shipping-option <?php echo $selectedMethod === 'white_glove' ? 'selected' : ''; ?> <?php echo $isWhiteGloveDisabled ? 'disabled' : ''; ?>">
                        <input type="radio" id="white_glove" name="shipping" value="white_glove" <?php echo $selectedMethod === 'white_glove' ? 'checked' : ''; ?> <?php echo $isWhiteGloveDisabled ? 'disabled' : ''; ?>>
                        <label for="white_glove">
                            <strong>White Glove Delivery -
                                <?php echo format_price($shippingCosts['white_glove']); ?></strong>
                            <small>Unpacking & Setup</small>
                        </label>
                    </div>

                    <div
                        class="shipping-option <?php echo $selectedMethod === 'freight' ? 'selected' : ''; ?> <?php echo $isFreightDisabled ? 'disabled' : ''; ?>">
                        <input type="radio" id="freight" name="shipping" value="freight" <?php echo $selectedMethod === 'freight' ? 'checked' : ''; ?> <?php echo $isFreightDisabled ? 'disabled' : ''; ?>>
                        <label for="freight">
                            <strong>Freight Shipping - <?php echo format_price($shippingCosts['freight']); ?></strong>
                            <small>Heavy Item Delivery</small>
                        </label>
                    </div>
                </div>

                <!-- Payment Section -->
                <div class="form-section">
                    <h2>Payment Method</h2>
                    <div class="form-group"><label><input type="radio" name="payment" value="card" checked> Credit/Debit
                            Card</label></div>
                    <div class="form-group"><label><input type="radio" name="payment" value="upi"> UPI</label></div>
                </div>

                <button type="submit" name="place_order" class="place-order-btn">Place Order</button>
            </form>
        </div>

        <!-- Order Summary -->
        <div class="order-summary">
            <h2>Order Summary</h2>
            <div class="summary-items">
                <?php foreach ($cartItems as $item): ?>
                    <div class="summary-item">
                        <div class="item-info">
                            <strong>
                                <?php echo htmlspecialchars($item['name']); ?>
                            </strong>
                            <span class="item-qty">x
                                <?php echo $item['quantity']; ?>
                            </span>
                        </div>
                        <div class="item-total">
                            <?php echo format_price($item['price'] * $item['quantity']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="summary-divider"></div>
            <div class="summary-row"><span>Subtotal</span><span>
                    <?php echo format_price($subtotal); ?>
                </span></div>
            <?php if ($couponDiscount > 0): ?>
                <div class="summary-row"><span>Coupon (
                        <?php echo htmlspecialchars($couponCode); ?>)
                    </span><span>-
                        <?php echo format_price($couponDiscount); ?>
                    </span></div>
            <?php endif; ?>

            <div class="summary-row">
                <span>Shipping</span>
                <span id="shippingCost"><?php echo format_price($shippingCost); ?></span>
            </div>

            <div class="summary-row">
                <span>Tax (GST 18%)</span>
                <span id="taxAmount"><?php echo format_price($tax); ?></span>
            </div>

            <div class="summary-row total"><span>Total Amount</span><span id="totalAmount">
                    <?php echo format_price($total); ?>
                </span></div>

            <form method="POST" style="margin: 0;">
                <div class="promo-code">
                    <input type="text" name="coupon" placeholder="SAVE5 / SAVE10 / SAVE15"
                        value="<?php echo htmlspecialchars($couponCode); ?>">
                    <button type="submit" name="apply_coupon">Apply</button>
                </div>
            </form>
        </div>
    </div>
</div>