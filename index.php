<?php
session_start();

// Define root path
define('ROOT_PATH', __DIR__);

// Load database and constants
require_once ROOT_PATH . '/includes/db.php';
require_once ROOT_PATH . '/data.php';

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

// Load the controller
$controllerPath = ROOT_PATH . '/controllers/' . $controller;

if (file_exists($controllerPath)) {
    require_once $controllerPath;
} else {
    // 404
    header("HTTP/1.0 404 Not Found");
    echo "<h1>404 Not Found</h1>";
    echo "The page you are looking for does not exist.";
}
