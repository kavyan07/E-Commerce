<?php
session_start();
require_once __DIR__ . '/../data.php';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = $products[$product_id] ?? null;

if (!$product) {
    echo '<div class="container"><p>Product not found</p></div>';
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

$page_title = htmlspecialchars($product['name']) . ' - EasyCart';
$page_css = 'product-detail.css';
require_once __DIR__ . '/../includes/header.php';

// Use actual product images from data
$productImages = $product['images'] ?? [$product['image']];
?>

    <section class="page-header">
        <div class="header-content">
            <h1><?php echo htmlspecialchars($product['name']); ?></h1>
            <div class="breadcrumb">
                <a href="index.php">Home</a>
                <span>/</span>
                <a href="product-listing.php">Products</a>
                <span>/</span>
                <span><?php echo htmlspecialchars($product['name']); ?></span>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="product-detail-layout">
            <div class="product-images">
                <div class="main-image">
                    <img id="productImage" src="../<?php echo htmlspecialchars($product['image']); ?>" 
                         alt="<?php echo htmlspecialchars($product['name']); ?>">
                </div>
                <div class="thumbnail-gallery">
                    <?php foreach ($productImages as $index => $img): ?>
                        <img class="product-thumbnail <?php echo $index === 0 ? 'active' : ''; ?>" 
                             src="../<?php echo htmlspecialchars($img); ?>" 
                             data-image="../<?php echo htmlspecialchars($img); ?>"
                             alt="Product image <?php echo $index + 1; ?>"
                             style="cursor: pointer; border-radius: 8px;">
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="product-info">
                <?php if (!empty($product['badge'])): ?>
                    <span class="product-badge"><?php echo htmlspecialchars($product['badge']); ?></span>
                <?php endif; ?>
                
                <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                
                <div class="rating">
                    <div class="stars">
                        <?php echo str_repeat('★', $product['rating']) . str_repeat('☆', 5 - $product['rating']); ?>
                    </div>
                    <span class="reviews">(<?php echo $product['reviews']; ?> reviews)</span>
                </div>

                <div class="price-section">
                    <span class="current-price"><?php echo format_price($product['price']); ?></span>
                    <span class="original-price"><?php echo format_price($product['originalPrice']); ?></span>
                    <span class="discount">
                        <?php 
                        $discount = round((($product['originalPrice'] - $product['price']) / $product['originalPrice']) * 100);
                        echo $discount . '% OFF';
                        ?>
                    </span>
                </div>

                <p class="description"><?php echo htmlspecialchars($product['description']); ?></p>

                <form method="POST" action="cart.php" class="add-to-cart-form" onsubmit="return validateQuantity(this)">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <div class="quantity">
                            <button type="button" class="qty-decrease" onclick="decreaseQty(event)">−</button>
                            <input type="number" id="quantity" name="quantity" value="1" min="1" max="100" readonly>
                            <button type="button" class="qty-increase" onclick="increaseQty(event)">+</button>
                        </div>
                    </div>

                    <button type="submit" class="add-to-cart-btn">Add to Cart</button>
                </form>

                <div class="product-features">
                    <div class="feature">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                        <span>Secure & Safe</span>
                    </div>
                    <div class="feature">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="1" y="3" width="15" height="13"></rect>
                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                            <circle cx="5.5" cy="18.5" r="2.5"></circle>
                            <circle cx="18.5" cy="18.5" r="2.5"></circle>
                        </svg>
                        <span>Fast Delivery</span>
                    </div>
                    <div class="feature">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 6L9 17l-5-5"></path>
                        </svg>
                        <span>30-day Returns</span>
                    </div>
                </div>
            </div>
        </div>

        <section class="related-products">
            <h2>You Might Also Like</h2>
            <div class="product-grid">
                <?php 
                $relatedCount = 0;
                foreach ($products as $p): 
                    if ($p['id'] !== $product['id'] && $relatedCount < 4):
                        $relatedCount++;
                ?>
                    <div class="card">
                        <?php if (!empty($p['badge'])): ?>
                            <div class="product-badge"><?php echo htmlspecialchars($p['badge']); ?></div>
                        <?php endif; ?>
                        <div class="product-image">
                            <img src="../<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
                        </div>
                        <div class="card-content">
                            <div class="product-name"><?php echo htmlspecialchars($p['name']); ?></div>
                            <div class="product-price"><?php echo format_price($p['price']); ?></div>
                            <a href="product-detail.php?id=<?php echo $p['id']; ?>" class="view-btn">View details</a>
                        </div>
                    </div>
                <?php 
                    endif;
                endforeach; 
                ?>
            </div>
        </section>
    </div>

    <script>
        // Local quantity control functions for product detail page
        function decreaseQty(e) {
            e.preventDefault();
            const input = document.getElementById('quantity');
            const current = parseInt(input.value) || 1;
            input.value = Math.max(1, current - 1);
        }

        function increaseQty(e) {
            e.preventDefault();
            const input = document.getElementById('quantity');
            const current = parseInt(input.value) || 1;
            input.value = current + 1;
        }

       function validateQuantity(form) {
    const qtyInput = document.getElementById('quantity');
    const qty = parseInt(qtyInput.value) || 0;
    if (qty < 1 || isNaN(qty)) {
        alert('Please select a valid quantity (minimum 1)');
        qtyInput.focus();
        qtyInput.select();  // Highlight for easy edit
        return false;
    }
    return true;
}


        // Initialize image gallery when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            initProductGallery();
        });

        function initProductGallery() {
            // Thumbnail image switching
            document.querySelectorAll('.product-thumbnail').forEach(thumb => {
                thumb.addEventListener('click', function() {
                    document.querySelectorAll('.product-thumbnail').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    document.getElementById('productImage').src = this.getAttribute('data-image');
                });
            });
        }
    </script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
