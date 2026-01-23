<?php
// Common header include
// Expects optional variables: $page_title (string), $page_css (string)
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../data.php';

$page_title = isset($page_title) ? $page_title : 'EasyCart';
$page_css = isset($page_css) ? $page_css : '';

// Calculate cart count from session
$cartCount = 0;
if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += isset($item['quantity']) ? (int)$item['quantity'] : 0;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <!-- Phase 3: Client-Side Interactions CSS -->
    <link rel="stylesheet" href="../css/phase3-interactions.css">
   <?php if ($page_css): ?>
    <link rel="stylesheet" href="../css/<?php echo htmlspecialchars($page_css); ?>">
<?php endif; ?>

</head>
<body>
    <nav>
        <div class="logo">
            <span class="logo-icon">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
            </span>
            EasyCart
        </div>
        <ul>
            <li><a href="index.php" class="<?php if(basename($_SERVER['PHP_SELF'])==='index.php') echo 'active'; ?>">Home</a></li>
            <li><a href="product-listing.php" class="<?php if(basename($_SERVER['PHP_SELF'])==='product-listing.php') echo 'active'; ?>">Products</a></li>
            <li><a href="cart.php" class="<?php if(basename($_SERVER['PHP_SELF'])==='cart.php') echo 'active'; ?>">Cart <span class="cart-count" id="cartCount"><?php echo $cartCount; ?></span></a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="my-orders.php">My Orders</a></li>
        </ul>
        <div class="mobile-menu">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </nav>
