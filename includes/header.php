<?php
// Get the base directory and require data
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../data.php';

// Get cart count
$cartCount = 0;
if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += isset($item['quantity']) ? (int)$item['quantity'] : 0;
    }
}

$page_title = isset($page_title) ? $page_title : 'EasyCart';
$page_css = isset($page_css) ? $page_css : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <link rel="stylesheet" href="../css/phase3-interactions.css">
    <link rel="stylesheet" href="../css/index.css">
    <?php if ($page_css): ?>
        <link rel="stylesheet" href="../css/<?php echo htmlspecialchars($page_css); ?>">
    <?php endif; ?>
    <!-- Toast Notification Styles -->
    <style>
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
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideInRight 0.3s ease-out;
            font-size: 14px;
            line-height: 1.5;
        }
        .toast-success {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: white;
        }
        .toast-error {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }
        .toast-info {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }
        .toast-icon {
            flex-shrink: 0;
            width: 24px;
            height: 24px;
        }
        .toast-message {
            flex: 1;
        }
        .toast-close {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 20px;
            line-height: 1;
            padding: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0.8;
            transition: opacity 0.2s;
        }
        .toast-close:hover {
            opacity: 1;
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
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        .toast.hiding {
            animation: slideOutRight 0.3s ease-in forwards;
        }
    </style>
</head>
<body>
    <!-- Toast Notification Container -->
    <div class="toast-container" id="toastContainer"></div>
    <nav>
        <a href="index.php" class="logo">
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
            <li><a href="index.php" class="<?php if(basename($_SERVER['PHP_SELF'])==='index.php') echo 'active'; ?>">Home</a></li>
            <li><a href="product-listing.php" class="<?php if(basename($_SERVER['PHP_SELF'])==='product-listing.php') echo 'active'; ?>">Products</a></li>

            <li>
                <a href="cart.php" class="<?php if(basename($_SERVER['PHP_SELF'])==='cart.php') echo 'active'; ?>">
                    Cart
                    <?php if ($cartCount > 0): ?>
                        <span class="cart-count" id="cartCount"><?php echo $cartCount; ?></span>
                    <?php endif; ?>
                </a>
            </li>

            <?php if (empty($_SESSION['user'])): ?>
                <li><a href="login.php">Login</a></li>
            <?php else: ?>
                <li>
                    <a href="my-orders.php">
                        <?php echo htmlspecialchars($_SESSION['user']['firstName'] ?? 'Profile'); ?>
                    </a>
                </li>
                <li><a href="logout.php">Logout</a></li>
            <?php endif; ?>
        </ul>
        <div class="mobile-menu">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </nav>
