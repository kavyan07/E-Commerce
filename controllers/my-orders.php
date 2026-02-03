<?php
// My Orders Controller
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
