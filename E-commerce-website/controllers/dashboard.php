<?php
// User Dashboard Controller
if (!isset($_SESSION['user_id'])) {
    $_SESSION['flash_message'] = ['text' => 'Please login to access your dashboard.', 'type' => 'info'];
    header('Location: login');
    exit;
}

require_once ROOT_PATH . '/src/OrderDAO.php';
$orderDAO = new OrderDAO();
$userId = $_SESSION['user_id'];

// Get Stats
$stats = $orderDAO->getDashboardStats($userId);

// Get Recent Orders (reuse existing method)
$recentOrders = $orderDAO->getOrdersByUser($userId);
$recentOrders = array_slice($recentOrders, 0, 5); // Just top 5

$page_title = 'Dashboard - EasyCart';
$page_css = 'dashboard.css';

loadView('dashboard', [
    'page_title' => $page_title,
    'page_css' => $page_css,
    'stats' => $stats,
    'recentOrders' => $recentOrders
]);
