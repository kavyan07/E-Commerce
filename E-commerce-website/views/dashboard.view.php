<?php
// User Dashboard View
?>

<section class="dashboard-header">
    <div class="header-content">
        <h1>Dashboard</h1>
        <p>Welcome back, <span
                class="user-highlight"><?php echo htmlspecialchars($_SESSION['user']['firstName'] ?? 'User'); ?></span>!
            Here's your account overview.</p>
    </div>
</section>

<div class="container dashboard-wrapper">
    <!-- Top Stats Row -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon orders">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path>
                    <path d="M3 6h18"></path>
                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                </svg>
            </div>
            <div class="stat-info">
                <h3>Total Orders</h3>
                <div class="value"><?php echo $stats['total_orders']; ?></div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon spendings">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="1" x2="12" y2="23"></line>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
            </div>
            <div class="stat-info">
                <h3>Total Spent</h3>
                <div class="value"><?php echo format_price($stats['total_spent']); ?></div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon items">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <div class="stat-info">
                <h3>Account Type</h3>
                <div class="value">Premium</div>
            </div>
        </div>
    </div>

    <div class="dashboard-main-content">
        <!-- Chart Section -->
        <div class="chart-section card">
            <div class="card-header">
                <h2>Spendings Trend</h2>
                <div class="card-actions">
                    <span class="period-badge">Last 30 Days</span>
                </div>
            </div>
            <div class="chart-canvas-wrapper">
                <canvas id="ordersChart"></canvas>
            </div>
        </div>

        <!-- Recent Orders Table -->
        <div class="recent-orders-section card">
            <div class="card-header">
                <h2>Recent Orders</h2>
                <a href="my-orders" class="btn-link">
                    View History
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <div class="table-container">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentOrders)): ?>
                            <tr>
                                <td colspan="4" class="empty-row">No recent orders found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentOrders as $order): ?>
                                <tr>
                                    <td class="order-id">#<?php echo htmlspecialchars($order['order_number']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($order['date'])); ?></td>
                                    <td class="amount-cell"><?php echo format_price($order['final_amount']); ?></td>
                                    <td>
                                        <span class="status-pill <?php echo strtolower($order['status']); ?>">
                                            <?php echo htmlspecialchars($order['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>