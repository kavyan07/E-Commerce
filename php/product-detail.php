<?php
$page_title = 'Product Details - EasyCart';
$page_css = 'product-detail.css';
require_once __DIR__ . '/../includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = isset($products[$id]) ? $products[$id] : null;
if (!$product) {
    echo '<h2>Product not found.</h2>';
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}
?>

    <div class="container">
        <a href="product-listing.php" class="back-link">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
            Back to Products
        </a>

        <div class="product-detail">
            <div class="product-image-section">
                <img id="productImage" src="../<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>

            <div class="product-info">
                <div class="product-badge" id="productBadge"><?php echo htmlspecialchars($product['badge']); ?></div>
                <h1 id="productName"><?php echo htmlspecialchars($product['name']); ?></h1>

                <div class="rating" id="productRating">
                    <?php for ($i=0;$i<5;$i++): ?>
                        <?php if ($i < $product['rating']): ?>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="#f59e0b"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                        <?php else: ?>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>

                <div class="price-section">
                    <span class="price" id="productPrice"><?php echo format_price($product['price']); ?></span>
                    <span class="original-price" id="originalPrice"><?php echo format_price($product['originalPrice']); ?></span>
                    <span class="discount" id="discountPercent"><?php echo round((($product['originalPrice']-$product['price'])/$product['originalPrice'])*100); ?>% OFF</span>
                </div>

                <p class="description" id="productDescription"><?php echo htmlspecialchars($product['description']); ?></p>

                <div class="quantity-section">
                    <label>Quantity:</label>
                    <div class="quantity-controls">
                        <button type="button" onclick="changeQuantity(-1)">-</button>
                        <span id="quantity">1</span>
                        <button type="button" onclick="changeQuantity(1)">+</button>
                    </div>
                </div>

                <div class="action-buttons">
                    <form id="addToCartForm" method="post" action="cart.php" style="display:inline-block;">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="quantity" id="formQuantity" value="1">
                        <button type="submit" class="add-to-cart">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                            Add to Cart
                        </button>
                    </form>

                    <form method="post" action="cart.php" style="display:inline-block;">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="quantity" id="buyNowQty" value="1">
                        <button type="submit" class="buy-now">Buy Now</button>
                    </form>
                </div>

                <div class="delivery-info">
                    <div class="delivery-item">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="1" y="3" width="15" height="13"></rect>
                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                            <circle cx="5.5" cy="18.5" r="2.5"></circle>
                            <circle cx="18.5" cy="18.5" r="2.5"></circle>
                        </svg>
                        <div>
                            <strong>Free Delivery</strong>
                            <p>Orders above Rs. 999</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Quantity control for product detail page
        // This works alongside EasyCart.initProductDetailInteractions()
        let quantity = 1;
        
        function changeQuantity(delta) {
            quantity = Math.max(1, quantity + delta);
            document.getElementById('quantity').textContent = quantity;
            document.getElementById('formQuantity').value = quantity;
            document.getElementById('buyNowQty').value = quantity;
        }
    </script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
