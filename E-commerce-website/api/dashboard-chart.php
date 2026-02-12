<?php
// API for Dashboard Chart Data
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

require_once ROOT_PATH . '/src/OrderDAO.php';
$orderDAO = new OrderDAO();
$userId = $_SESSION['user_id'];

try {
    $trendData = $orderDAO->getOrderTrend($userId);

    $labels = [];
    $data = [];

    foreach ($trendData as $row) {
        // Use full timestamp or separate time if needed, but for now just date is fine
        // Chart.js will plot multiple points with same label if we push them
        $labels[] = date('M d, H:i', strtotime($row['order_timestamp']));
        $data[] = (float) $row['final_amount'];
    }

    echo json_encode([
        'success' => true,
        'labels' => $labels,
        'data' => $data
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
