<?php
$page_title = 'EasyCart - Products';
$page_css = 'product-listing.css';
require_once __DIR__ . '/../includes/header.php';

// Simple server-side filters (optional)
$q = isset($_GET['q']) ? strtolower(trim($_GET['q'])) : '';
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';

$productList = $products; // from data.php

// Filter by query and category
if ($q !== '') {
    $productList = array_filter($productList, function($p) use ($q) {
        return strpos(strtolower($p['name']), $q) !== false || strpos(strtolower($p['description']), $q) !== false;
    });
}
if ($categoryFilter !== '') {
    $productList = array_filter($productList, function($p) use ($categoryFilter) {
        return $p['category'] === $categoryFilter;
    });
}

// Sorting
if ($sort === 'price-low') {
    usort($productList, function($a, $b){ return $a['price'] - $b['price']; });
} elseif ($sort === 'price-high') {
    usort($productList, function($a, $b){ return $b['price'] - $a['price']; });
} elseif ($sort === 'newest') {
    usort($productList, function($a, $b){ return $b['id'] - $a['id']; });
}
?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="header-content">
            <h1>All Products</h1>
            <p>Discover our collection of premium products</p>
            <div class="breadcrumb">
                <a href="index.php">Home</a>
                <span>/</span>
                <span>Products</span>
            </div>
        </div>
    </section>

    <div class="container">
        <!-- Filter Bar -->
        <div class="filter-bar">
            <div class="filter-left">
                <div class="search-box">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                    <input type="text" id="searchInput" name="q" placeholder="Search products..." value="<?php echo htmlspecialchars(isset($_GET['q'])?$_GET['q']:''); ?>">
                </div>
                <span class="result-count">Showing <span id="productCount"><?php echo count($productList); ?></span> products</span>
            </div>
            <div class="filter-right">
                <div class="filter-dropdown">
                    <label>Category:</label>
                    <select id="categoryFilter" name="category">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $key => $label): ?>
                            <option value="<?php echo $key; ?>" <?php if($key===$categoryFilter) echo 'selected'; ?>><?php echo htmlspecialchars($label); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-dropdown">
                    <label>Sort by:</label>
                    <select id="sortFilter" name="sort">
                        <option value="featured">Featured</option>
                        <option value="price-low" <?php if($sort==='price-low') echo 'selected'; ?>>Price: Low to High</option>
                        <option value="price-high" <?php if($sort==='price-high') echo 'selected'; ?>>Price: High to Low</option>
                        <option value="newest" <?php if($sort==='newest') echo 'selected'; ?>>Newest First</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="product-grid" id="productGrid">
            <?php foreach ($productList as $product): ?>
                <?php $stars = str_repeat('★', $product['rating']) . str_repeat('☆', 5 - $product['rating']); ?>
                <div class="card">
                    <?php if (!empty($product['badge'])): ?>
                        <div class="product-badge <?php echo strtolower(str_replace(' ', '-', $product['badge'])); ?>"><?php echo htmlspecialchars($product['badge']); ?></div>
                    <?php endif; ?>
                    <div class="product-image">
                        <img src="../<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    </div>
                    <div class="card-content">
                        <div class="product-name"><?php echo htmlspecialchars($product['name']); ?></div>
                        <div class="product-price"><?php echo format_price($product['price']); ?></div>
                        <div class="product-meta"><?php echo htmlspecialchars($product['description']); ?></div>
                        <a href="product-detail.php?id=<?php echo $product['id']; ?>" class="view-btn">View details</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <button class="page-btn prev" disabled>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
                Previous
            </button>
            <div class="page-numbers">
                <button class="page-num active">1</button>
                <button class="page-num">2</button>
                <button class="page-num">3</button>
            </div>
            <button class="page-btn next">
                Next
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>
        </div>
    </div>

    <script>
        // Basic client-side filtering to improve UX (keeps server-side stable)
        (function(){
            const searchInput = document.getElementById('searchInput');
            const categoryFilter = document.getElementById('categoryFilter');
            const sortFilter = document.getElementById('sortFilter');
            const productGrid = document.getElementById('productGrid');

            function applyFilters(){
                const q = searchInput.value.toLowerCase();
                const cat = categoryFilter.value;
                const items = productGrid.querySelectorAll('.card');
                let visible = 0;
                items.forEach(item => {
                    const name = item.querySelector('.product-name').textContent.toLowerCase();
                    const desc = item.querySelector('.product-meta').textContent.toLowerCase();
                    const matchesSearch = name.includes(q) || desc.includes(q);
                    const matchesCat = !cat || (item.querySelector('.product-meta').textContent.toLowerCase().includes(cat) || item.querySelector('.product-name').textContent.toLowerCase().includes(cat));
                    if (matchesSearch && matchesCat) { item.style.display = ''; visible++; } else { item.style.display = 'none'; }
                });
                document.getElementById('productCount').textContent = visible;
            }

            searchInput.addEventListener('input', applyFilters);
            categoryFilter.addEventListener('change', applyFilters);
            sortFilter.addEventListener('change', function(){
                // simple client-side sort by price
                const items = Array.from(productGrid.querySelectorAll('.card'));
                if (this.value === 'price-low') {
                    items.sort((a,b)=> parseInt(a.querySelector('.product-price').textContent.replace(/[^0-9]/g,'')) - parseInt(b.querySelector('.product-price').textContent.replace(/[^0-9]/g,'')));
                } else if (this.value === 'price-high') {
                    items.sort((a,b)=> parseInt(b.querySelector('.product-price').textContent.replace(/[^0-9]/g,'')) - parseInt(a.querySelector('.product-price').textContent.replace(/[^0-9]/g,'')));
                }
                items.forEach(i=>productGrid.appendChild(i));
            });
        })();
    </script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
