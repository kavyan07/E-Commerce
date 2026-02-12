<section class="page-header">
    <div class="header-content">
        <h1>My Orders</h1>
        <p>Track and manage your purchases</p>
    </div>
</section>

<div class="container">
    <?php if (empty($orders)): ?>
        <div class="empty-orders">
            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path>
                <path d="M3 6h18"></path>
                <path d="M16 10a4 4 0 0 1-8 0"></path>
            </svg>
            <h2>No orders yet</h2>
            <p>Looks like you haven't placed any orders yet.</p>
            <a href="product-listing" class="browse-btn">Start Shopping</a>
        </div>
    <?php else: ?>
        <div class="orders-list">
            <?php foreach ($orders as $order): ?>
                <div class="order-card">
                    <div class="order-header">
                        <div class="order-id">
                            <span
                                class="id-label">#</span><?php echo htmlspecialchars($order['order_number'] ?? $order['entity_id']); ?>
                        </div>
                        <div class="order-status status-<?php echo strtolower($order['status'] ?? 'processing'); ?>">
                            <?php echo htmlspecialchars($order['status'] ?? 'Processing'); ?>
                        </div>
                    </div>

                    <div class="order-summary-grid">
                        <div class="summary-item">
                            <span class="label">Date</span>
                            <span class="value"><?php echo date('M d, Y', strtotime($order['date'])); ?></span>
                        </div>
                        <div class="summary-item">
                            <span class="label">Shipping Type</span>
                            <span class="value"><?php echo ucwords(str_replace('_', ' ', $order['shipping_type'])); ?></span>
                        </div>
                        <div class="summary-item">
                            <span class="label">Final Amount</span>
                            <span class="value total-value"><?php echo format_price($order['final_amount']); ?></span>
                        </div>
                    </div>

                    <div class="order-actions">
                        <button class="toggle-details-btn" onclick="toggleOrderDetails(this)">
                            View Order Details
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 9l6 6 6-6"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="order-details-expanded" style="display: none;">
                        <div class="details-content">
                            <div class="items-section">
                                <h3>Products Included</h3>
                                <div class="order-items-list">
                                    <?php if (!empty($order['items'])): ?>
                                        <?php foreach ($order['items'] as $item): ?>
                                            <div class="order-item">
                                                <div class="item-info">
                                                    <span class="item-name"><?php echo htmlspecialchars($item['name']); ?></span>
                                                    <span class="item-qty">Qty: <?php echo $item['quantity']; ?></span>
                                                </div>
                                                <div class="item-price">
                                                    <?php echo format_price($item['total']); ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p class="no-items">No item details available.</p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="price-breakup-section">
                                <h3>Price Breakup</h3>
                                <div class="breakup-table">
                                    <div class="breakup-row">
                                        <span>Subtotal</span>
                                        <span><?php echo format_price($order['subtotal']); ?></span>
                                    </div>
                                    <div class="breakup-row">
                                        <span>Shipping</span>
                                        <span><?php echo format_price($order['shipping_cost']); ?></span>
                                    </div>
                                    <div class="breakup-row">
                                        <span>Tax (18%)</span>
                                        <span><?php echo format_price($order['tax']); ?></span>
                                    </div>
                                    <div class="breakup-row total-row">
                                        <span>Total</span>
                                        <span><?php echo format_price($order['final_amount']); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="shipping-info-section">
                            <h3>Shipping Address</h3>
                            <p class="address-text"><?php echo htmlspecialchars($order['shipping_address']); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>