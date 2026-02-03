<?php
/**
 * API Endpoint: Products
 */
header('Content-Type: application/json');
require_once __DIR__ . '/../src/ProductDAO.php';

$dao = new ProductDAO();
$action = $_GET['action'] ?? 'all';

try {
    switch ($action) {
        case 'all':
            $data = $dao->getAllProducts();
            break;
        case 'category':
            $slug = $_GET['slug'] ?? '';
            $data = $dao->getProductsByCategory($slug);
            break;
        case 'single':
            $id = $_GET['id'] ?? 0;
            $data = $dao->getProductById($id);
            break;
        default:
            throw new Exception("Invalid action");
    }

    echo json_encode(['success' => true, 'data' => $data]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
