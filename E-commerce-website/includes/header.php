<?php
// Get the base directory and require data
if (session_status() === PHP_SESSION_NONE)
    session_start();

// Use global $route if available
$currentRoute = $route ?? 'home';

// Get cart count
$cartCount = 0;
if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += isset($item['quantity']) ? (int) $item['quantity'] : 0;
    }
}

$page_title = isset($page_title) ? $page_title : 'EasyCart';
$page_css = isset($page_css) ? $page_css : '';
?>
<!DOCTYPE html>
<!-- Controller: <?php echo isset($debug_controller) ? $debug_controller : 'UNKNOWN'; ?> -->
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/E-commerce-website/css/phase3-interactions.css">
    <link rel="stylesheet" href="/E-commerce-website/css/index.css">
    <?php if ($page_css): ?>
        <link rel="stylesheet" href="/E-commerce-website/css/<?php echo htmlspecialchars($page_css); ?>">
    <?php endif; ?>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }

        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10000;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .toast {
            min-width: 300px;
            max-width: 400px;
            padding: 16px 20px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideInRight 0.3s ease-out;
            font-size: 14px;
            line-height: 1.5;
            backdrop-filter: blur(8px);
        }

        .toast-success {
            background: rgba(34, 197, 94, 0.9);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .toast-error {
            background: rgba(239, 68, 68, 0.9);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .toast-info {
            background: rgba(59, 130, 246, 0.9);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <!-- Toast Notification Container -->
    <div class="toast-container" id="toastContainer"></div>
    <nav>
        <div class="container nav-container"
            style="padding: 0; display: flex; justify-content: space-between; align-items: center;">
            <a href="/E-commerce-website/home" class="logo">
                <span class="logo-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                </span>
                EasyCart
            </a>

            <ul>
                <li><a href="/E-commerce-website/home" class="<?php if ($currentRoute === 'home')
                    echo 'active'; ?>">Home</a></li>
                <li><a href="/E-commerce-website/product-listing" class="<?php if ($currentRoute === 'product-listing')
                    echo 'active'; ?>">Products</a></li>
                <li>
                    <a href="/E-commerce-website/cart" class="<?php if ($currentRoute === 'cart')
                        echo 'active'; ?>">
                        Cart
                        <span class="cart-count" id="cartCount" <?php if ($cartCount <= 0)
                            echo 'style="display:none;"'; ?>>
                            <?php echo $cartCount; ?>
                        </span>
                    </a>
                </li>
                <?php if (empty($_SESSION['user'])): ?>
                    <li><a href="/E-commerce-website/login" class="<?php if ($currentRoute === 'login')
                        echo 'active'; ?>">Login</a></li>
                <?php else: ?>
                    <li><a href="/E-commerce-website/dashboard" class="<?php if ($currentRoute === 'dashboard')
                        echo 'active'; ?>">Dashboard</a></li>
                    <li><a href="/E-commerce-website/my-orders" class="<?php if ($currentRoute === 'my-orders')
                        echo 'active'; ?>">Orders</a></li>
                    <li><a href="/E-commerce-website/logout">Logout</a></li>
                <?php endif; ?>
            </ul>
            <div class="mobile-menu">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>