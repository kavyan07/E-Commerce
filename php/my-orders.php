<?php
session_start();
$page_title = 'My Orders - EasyCart';
$page_css = 'my-orders.css';

// Restrict access to logged-in users only
if (empty($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../includes/header.php';

// Orders are stored in session after checkout (no database)
$orders = $_SESSION['orders'] ?? [];
?>

    <div class="container">
        <h1>📋 My Orders</h1>

        <?php if (empty($orders)): ?>
            <p style="color: rgba(255,255,255,0.8); margin-top: 1rem;">
                No orders yet. Place an order from the cart to see it here.
            </p>
        <?php else: ?>
        <div class="orders-table">
            <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $o): ?>
                    <tr>
                        <td class="order-id"><?php echo htmlspecialchars($o['order_id']); ?></td>
                        <td class="order-date"><?php echo htmlspecialchars($o['date']); ?></td>
                        <td class="order-items"><?php echo htmlspecialchars($o['items']) . ' item' . ($o['items']>1? 's':''); ?></td>
                        <td class="order-total"><?php echo format_price($o['total']); ?></td>
                        <td><span class="status"><?php echo htmlspecialchars($o['status']); ?></span></td>
                        <td><button class="action-btn" type="button">View</button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
        <?php endif; ?>
    </div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
