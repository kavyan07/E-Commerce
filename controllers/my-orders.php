<?php
// My Orders Controller
if (!isset($_SESSION['user_id'])) {
    $_SESSION['flash_message'] = ['text' => 'Please login to view your orders.', 'type' => 'info'];
    header('Location: login');
    exit;
}
require_once ROOT_PATH . '/src/OrderDAO.php';

$orderDAO = new OrderDAO();
$userId = $_SESSION['user_id'] ?? null;
$ordersList = $userId ? $orderDAO->getOrdersByUser($userId) : ($_SESSION['orders'] ?? []);

$page_title = 'My Orders - EasyCart';
$page_css = 'my-orders.css';

loadView('my-orders', [
    'page_title' => $page_title,
    'page_css' => $page_css,
    'orders' => $ordersList
]);
