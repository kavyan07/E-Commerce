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
                        <div class="order-id">Order
                            <?php echo htmlspecialchars($order['order_number'] ?? $order['order_id']); ?>
                        </div>
                        <div class="order-status status-<?php echo strtolower($order['status'] ?? 'processing'); ?>">
                            <?php echo htmlspecialchars($order['status'] ?? 'Processing'); ?>
                        </div>
                    </div>
                    <div class="order-details">
                        <div class="detail-item">
                            <span class="label">Date:</span>
                            <span class="value">
                                <?php echo htmlspecialchars($order['date'] ?? date('M d, Y')); ?>
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Total:</span>
                            <span class="value">
                                <?php echo format_price($order['final_amount'] ?? $order['total']); ?>
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Shipping:</span>
                            <span class="value">
                                <?php echo htmlspecialchars($order['shipping_type']); ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>