<?php
$page_title = "EasyCart - Home";
$page_css = "index.css";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <!-- EXACT copy from working index.html -->
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>
<?php require_once __DIR__ . '/../includes/header.php'; // Navbar only ?>
<div class="container">

    <!-- Hero section -->
    <section class="hero">
        <div class="hero-left">
            <div class="hero-tag">New Year Sale - Up to 50% Off</div>
            <h1>Shop everything you love in one place with EasyCart.</h1>
            <p>Discover trending fashion, powerful electronics, stylish home essentials and more at amazing prices. Start your shopping journey now.</p>
            <div class="hero-buttons">
                <a href="product-listing.php" class="btn-primary">Start Shopping</a>
                <a href="signup.php" class="btn-secondary">Create Account</a>
            </div>
        </div>
        <div class="hero-right">
            <div class="hero-image">
                <img src="../public/images/products/sneakers.jpg" alt="Featured Product">
            </div>
            <div class="hero-stats">
                <div class="stat-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 6L9 17l-5-5"></path>
                    </svg>
                    <span>100+ products</span>
                </div>
                <div class="stat-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 6L9 17l-5-5"></path>
                    </svg>
                    <span>Fast delivery options</span>
                </div>
                <div class="stat-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 6L9 17l-5-5"></path>
                    </svg>
                    <span>Secure checkout</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Advertisements Banner -->
    <section class="ads-banner">
        <div class="ad-card">
            <div class="ad-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                </svg>
            </div>
            <h3>Flash Sale</h3>
            <div class="price">₹999</div>
            <div class="was-price">Was ₹1,999</div>
            <div class="save">Save 50%</div>
        </div>
        <div class="ad-card ad-shipping">
            <div class="ad-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="1" y="3" width="15" height="13"></rect>
                    <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                    <circle cx="5.5" cy="18.5" r="2.5"></circle>
                    <circle cx="18.5" cy="18.5" r="2.5"></circle>
                </svg>
            </div>
            <h3>Free Shipping</h3>
            <div class="price">On orders ₹999+</div>
            <div class="was-price">Limited time offer</div>
            <div class="save">Today only</div>
        </div>
        <div class="ad-card ad-rated">
            <div class="ad-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                </svg>
            </div>
            <h3>Top Rated</h3>
            <div class="price">4.9/5 Stars</div>
            <div class="was-price">1,234 reviews</div>
            <div class="save">Best sellers</div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="why-choose">
        <h2>Why Choose EasyCart?</h2>
        <div class="why-grid">
            <div class="feature">
                <div class="feature-icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="1" y="3" width="15" height="13"></rect>
                        <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                        <circle cx="5.5" cy="18.5" r="2.5"></circle>
                        <circle cx="18.5" cy="18.5" r="2.5"></circle>
                    </svg>
                </div>
                <h3>Fast Delivery</h3>
                <p>Delivered within 2-5 business days across India</p>
            </div>
            <div class="feature">
                <div class="feature-icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                </div>
                <h3>Secure Payments</h3>
                <p>SSL encryption & trusted payment gateways</p>
            </div>
            <div class="feature">
                <div class="feature-icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="1" x2="12" y2="23"></line>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                </div>
                <h3>Easy Returns</h3>
                <p>30-day hassle-free return policy</p>
            </div>
            <div class="feature">
                <div class="feature-icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                    </svg>
                </div>
                <h3>Customer First</h3>
                <p>24/7 support & 100% satisfaction guarantee</p>
            </div>
        </div>
    </section>

    <!-- Featured products section -->
    <section class="featured-section">
        <div class="section-header">
            <h2>Featured Products</h2>
            <a href="product-listing.php" class="view-all">View All</a>
        </div>
        <div class="card-grid">
            <?php foreach (array_slice($products, 0, 4) as $p): ?>
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
                        <div class="product-meta"><?php echo htmlspecialchars($p['description']); ?></div>
                        <a href="product-detail.php?id=<?php echo $p['id']; ?>" class="view-btn">View details</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Popular categories section -->
    <section class="categories-section">
        <div class="section-header">
            <h2>Popular Categories</h2>
            <a href="product-listing.php" class="view-all">Browse All</a>
        </div>
        <div class="category-grid">
            <a href="product-listing.php" class="category-card">
                <div class="category-image">
                    <img src="../public/images/categories/fashion.jpg" alt="Fashion">
                </div>
                <div class="category-overlay">
                    <h3>Fashion</h3>
                    <span>50+ Products</span>
                </div>
            </a>
            <a href="product-listing.php" class="category-card">
                <div class="category-image">
                    <img src="../public/images/categories/electronics.jpg" alt="Electronics">
                </div>
                <div class="category-overlay">
                    <h3>Electronics</h3>
                    <span>30+ Products</span>
                </div>
            </a>
            <a href="product-listing.php" class="category-card">
                <div class="category-image">
                    <img src="../public/images/categories/home.jpg" alt="Home & Kitchen">
                </div>
                <div class="category-overlay">
                    <h3>Home & Kitchen</h3>
                    <span>25+ Products</span>
                </div>
            </a>
            <a href="product-listing.php" class="category-card">
                <div class="category-image">
                    <img src="../public/images/categories/sports.jpg" alt="Sports & Fitness">
                </div>
                <div class="category-overlay">
                    <h3>Sports & Fitness</h3>
                    <span>20+ Products</span>
                </div>
            </a>
        </div>
    </section>

    <!-- Popular brands section -->
    <section class="brands-section">
        <div class="section-header">
            <h2>Popular Brands</h2>
        </div>
        <div class="brand-grid">
            <a href="product-listing.php" class="brand-card">
                <div class="brand-image">
                    <img src="../public/images/brands/nike.jpg" alt="Nike">
                </div>
                <div class="brand-name">Nike</div>
            </a>
            <a href="product-listing.php" class="brand-card">
                <div class="brand-image">
                    <img src="../public/images/brands/apple.jpg" alt="Apple">
                </div>
                <div class="brand-name">Apple</div>
            </a>
            <a href="product-listing.php" class="brand-card">
                <div class="brand-image">
                    <img src="../public/images/brands/samsung.jpg" alt="Samsung">
                </div>
                <div class="brand-name">Samsung</div>
            </a>
            <a href="product-listing.php" class="brand-card">
                <div class="brand-image">
                    <img src="../public/images/brands/sony.jpg" alt="Sony">
                </div>
                <div class="brand-name">Sony</div>
            </a>
        </div>
    </section>

    <!-- Newsletter Signup -->
    <section class="newsletter">
        <div class="newsletter-content">
            <div class="newsletter-icon">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
            </div>
            <h2>Stay Updated</h2>
            <p>Get exclusive deals, new product alerts and more delivered to your inbox.</p>
            <form class="newsletter-form">
                <input type="email" class="newsletter-input" placeholder="Enter your email">
                <button type="submit" class="newsletter-btn">Subscribe</button>
            </form>
        </div>
    </section>

    </div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
