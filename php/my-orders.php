<?php
session_start();
$page_title = 'My Orders - EasyCart';
$page_css = 'my-orders.css';
require_once __DIR__ . '/../includes/header.php';

// Sample orders data
$orders = [
    ['order_id' => '#ORD001', 'date' => '2024-01-15', 'items' => 2, 'total' => 8999, 'status' => 'Delivered'],
    ['order_id' => '#ORD002', 'date' => '2024-01-10', 'items' => 1, 'total' => 4999, 'status' => 'Shipped'],
    ['order_id' => '#ORD003', 'date' => '2024-01-05', 'items' => 3, 'total' => 12499, 'status' => 'Processing']
];
?>

    <div class="container">
        <h1>📋 My Orders</h1>

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
                        <td><button class="action-btn">View</button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    </div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
