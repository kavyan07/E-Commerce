<?php
session_start();

// Define root path
define('ROOT_PATH', __DIR__);

// Load database and constants
require_once ROOT_PATH . '/includes/db.php';
require_once ROOT_PATH . '/data.php';

// Initialize MVC Autoloader
require_once ROOT_PATH . '/libs/Core/Autoload.php';
Core_Autoload::register();

// Global Session Validation (Verify if user still exists in DB)
if (isset($_SESSION['user_id'])) {
    if (!Core_Validation::validateExists('users', 'id', $_SESSION['user_id'])) {
        // User was deleted from DB, clear session
        session_destroy();
        header("Location: home");
        exit;
    }
}

/**
 * Helper to load views
 */
function loadView($viewName, $data = [])
{
    extract($data);
    $viewPath = ROOT_PATH . '/views/' . $viewName . '.view.php';
    if (file_exists($viewPath)) {
        require_once ROOT_PATH . '/includes/header.php';
        require_once $viewPath;
        require_once ROOT_PATH . '/includes/footer.php';
    } else {
        echo "View $viewName not found.";
    }
}

// Get the requested route
$route = isset($_GET['route']) && $_GET['route'] !== '' ? $_GET['route'] : 'home';

// Map routes to controllers
$routes = [
    'home' => 'home.php',
    'search' => 'home.php',
    'product-listing' => 'product-listing.php',
    'products' => 'product-listing.php',
    'product-detail' => 'product-detail.php',
    'cart' => 'cart.php',
    'checkout' => 'checkout.php',
    'login' => 'login.php',
    'signup' => 'signup.php',
    'logout' => 'logout.php',
    'my-orders' => 'my-orders.php',
    'dashboard' => 'dashboard.php',
    'ajax-cart' => 'ajax-cart.php',
    'ajax-checkout' => 'ajax-checkout.php'
];

// Determine the controller
$controller = isset($routes[$route]) ? $routes[$route] : 'home.php';

// Check if it's an API request
if (strpos($route, 'api/') === 0) {
    $apiFile = str_replace('api/', '', $route) . '.php';
    if (file_exists(ROOT_PATH . '/api/' . $apiFile)) {
        require_once ROOT_PATH . '/api/' . $apiFile;
        exit;
    }
}

// 1. Determine Class-based Route
$controllerClass = null;
$params = [];

if ($route === 'home' || $route === '') {
    $controllerClass = 'Controller_Home';
} elseif (strpos($route, 'products/') === 0) {
    $controllerClass = 'Controller_Product_Detail';
    $_REQUEST['key'] = str_replace('products/', '', $route);
} elseif ($route === 'product-listing' || $route === 'products') {
    $controllerClass = 'Controller_Product_Listing';
} elseif ($route === 'checkout') {
    $controllerClass = 'Controller_Checkout';
} elseif ($route === 'cart') {
    $controllerClass = 'Controller_Cart';
} elseif ($route === 'ajax-cart') {
    $controllerClass = 'Controller_Ajax_Cart';
} elseif ($route === 'admin/login') {
    $controllerClass = 'Controller_Admin_Login';
} elseif ($route === 'admin/dashboard') {
    $controllerClass = 'Controller_Admin_Dashboard';
} elseif ($route === 'admin/products') {
    $controllerClass = 'Controller_Admin_Products';
} elseif ($route === 'admin/product/add' || $route === 'admin/product/edit') {
    $controllerClass = 'Controller_Admin_Product_Form';
} elseif ($route === 'admin/product/save') {
    $controllerClass = 'Controller_Admin_Product_Save';
} elseif ($route === 'admin/product/delete') {
    $controllerClass = 'Controller_Admin_Product_Delete';
} elseif ($route === 'admin/orders') {
    $controllerClass = 'Controller_Admin_Orders';
} elseif ($route === 'admin/order/update-status') {
    $controllerClass = 'Controller_Admin_Order_UpdateStatus';
} elseif ($route === 'admin/categories') {
    $controllerClass = 'Controller_Admin_Categories';
} elseif ($route === 'admin/users') {
    $controllerClass = 'Controller_Admin_Users';
} elseif ($route === 'admin/categories') {
    $controllerClass = 'Controller_Admin_Categories';
} elseif ($route === 'admin/category/add' || $route === 'admin/category/edit') {
    $controllerClass = 'Controller_Admin_Category_Form';
} elseif ($route === 'admin/category/save') {
    $controllerClass = 'Controller_Admin_Category_Save';
} elseif ($route === 'admin/category/delete') {
    $controllerClass = 'Controller_Admin_Category_Delete';
} elseif ($route === 'admin/product/import') {
    $controllerClass = 'Controller_Admin_Product_Import';
} elseif ($route === 'admin/product/export') {
    $controllerClass = 'Controller_Admin_Product_Export';
} elseif ($route === 'admin/logout') {
    unset($_SESSION['admin_user']);
    header('Location: /E-commerce-website/admin/login');
    exit;
}

if ($controllerClass && class_exists($controllerClass)) {
    $controllerInstance = new $controllerClass();
    $controllerInstance->execute();
    exit;
}
// Fallback if class doesn't exist yet
$controllerPath = ROOT_PATH . '/controllers/' . $controller;
if (file_exists($controllerPath)) {
    require_once $controllerPath;
} else {
    header("HTTP/1.0 404 Not Found");
    echo "<h1>404 Not Found</h1>";
}
