# 🛒 EasyCart E-Commerce Application - Complete Documentation

## 📚 Table of Contents

1. [Project Overview](#project-overview)
2. [Architecture & Design Patterns](#architecture--design-patterns)
3. [Directory Structure](#directory-structure)
4. [Database Schema](#database-schema)
5. [Core Files Explained](#core-files-explained)
   - [Entry Point (index.php)](#1-entry-point-indexphp)
   - [URL Routing (.htaccess)](#2-url-routing-htaccess)
   - [Database Connection (includes/db.php)](#3-database-connection-includesdbphp)
   - [Data Bridge (data.php)](#4-data-bridge-dataphp)
6. [Data Access Objects (DAO)](#data-access-objects-dao)
   - [UserDAO](#userdaophp)
   - [ProductDAO](#productdaophp)
   - [CartDAO](#cartdaophp)
   - [OrderDAO](#orderdaophp)
   - [CommonDAO](#commondaophp)
7. [Controllers Explained](#controllers-explained)
8. [API Endpoints](#api-endpoints)
9. [Views & Templates](#views--templates)
10. [JavaScript Frontend (ecommerce.js)](#javascript-frontend-ecommercejs)
11. [Session Management & Security](#session-management--security)
12. [Shipping Logic](#shipping-logic)
13. [Setup Instructions](#setup-instructions)

---

## Project Overview

**EasyCart** is a full-featured e-commerce web application built using:

- **Backend**: PHP 8.x with PDO for database operations
- **Database**: PostgreSQL with Magento-inspired schema
- **Frontend**: Vanilla JavaScript with AJAX, CSS3
- **Architecture**: MVC (Model-View-Controller) pattern with DAO layer

### Key Features

- ✅ User registration & authentication (secure password hashing)
- ✅ Product catalog with categories and brands
- ✅ Persistent shopping cart (database-backed)
- ✅ Multi-tier shipping options with rules
- ✅ Coupon/discount system
- ✅ Order management and history
- ✅ User dashboard with spending charts
- ✅ AJAX-powered cart updates (no page reloads)
- ✅ Clean URL routing (SEO-friendly)
- ✅ Toast notifications for user feedback

---

## Architecture & Design Patterns

### MVC Architecture

```
┌─────────────┐     ┌─────────────┐     ┌─────────────┐
│    Views    │ ←── │ Controllers │ ←── │    DAOs     │
│  (.view.php)│     │   (.php)    │     │  (src/*.php)│
└─────────────┘     └─────────────┘     └─────────────┘
      ↑                    ↑                    ↑
      │                    │                    │
   HTML/CSS            Business           Database
   Rendering           Logic             Operations
```

### Front Controller Pattern

All requests are routed through `index.php`, which:
1. Starts the session
2. Loads common dependencies
3. Parses the route from URL
4. Dispatches to the appropriate controller
5. Controller prepares data and loads a view

### Data Access Object (DAO) Pattern

Each entity (User, Product, Cart, Order) has its own DAO class that:
- Encapsulates all database operations
- Provides a clean API for CRUD operations
- Handles prepared statements to prevent SQL injection

---

## Directory Structure

```
E-commerce-website/
├── .htaccess              # URL rewriting rules
├── index.php              # Front controller (entry point)
├── data.php               # Data bridge (DAO → old format)
├── .env                   # Environment variables (DB credentials)
│
├── api/                   # REST API endpoints
│   ├── checkout.php       # Order placement API
│   ├── dashboard-chart.php # Chart data API
│   └── products.php       # Products API
│
├── controllers/           # Route handlers (business logic)
│   ├── home.php
│   ├── login.php
│   ├── signup.php
│   ├── logout.php
│   ├── cart.php
│   ├── checkout.php
│   ├── product-listing.php
│   ├── product-detail.php
│   ├── my-orders.php
│   ├── dashboard.php
│   ├── ajax-cart.php      # AJAX cart operations
│   └── ajax-checkout.php  # AJAX checkout calculations
│
├── css/                   # Stylesheets
│
├── database/
│   └── schema.sql         # PostgreSQL schema & seed data
│
├── includes/              # Shared PHP components
│   ├── db.php             # Database connection class
│   ├── header.php         # HTML header template
│   └── footer.php         # HTML footer template
│
├── js/
│   └── ecommerce.js       # All client-side JavaScript
│
├── src/                   # DAO classes (Model layer)
│   ├── UserDAO.php
│   ├── ProductDAO.php
│   ├── CartDAO.php
│   ├── OrderDAO.php
│   └── CommonDAO.php      # CategoryDAO & BrandDAO
│
└── views/                 # View templates
    ├── home.view.php
    ├── login.view.php
    ├── signup.view.php
    ├── cart.view.php
    ├── checkout.view.php
    ├── product-listing.view.php
    ├── product-detail.view.php
    ├── my-orders.view.php
    └── dashboard.view.php
```

---

## Database Schema

The database follows **Magento-style naming conventions** for scalability.

### Tables Overview

| Table | Purpose |
|-------|---------|
| `users` | Customer accounts |
| `brands` | Product brands |
| `catalog_product_entity` | Products (main data) |
| `catalog_product_attribute` | Product attributes (color, size, etc.) |
| `catalog_category_entity` | Product categories |
| `catalog_category_attribute` | Category variations |
| `catalog_category_products` | Category-Product relationships |
| `sales_cart` | Shopping carts (persistent) |
| `sale_cart_product` | Cart items |
| `sales_orders` | Orders |
| `sales_order_items` | Order line items |

### Entity Relationship Diagram

```
users (1) ──────────── (many) sales_cart
                              │
                              └───── (many) sale_cart_product
                                             │
                                             └──── (1) catalog_product_entity
                                                        │
                                                        ├──── brands
                                                        └──── catalog_category_products
                                                                      │
                                                                      └──── catalog_category_entity

users (1) ──────────── (many) sales_orders
                              │
                              └───── (many) sales_order_items
                                             │
                                             └──── catalog_product_entity
```

---

## Core Files Explained

### 1. Entry Point (`index.php`)

This is the **front controller** — the single entry point for all HTTP requests.

```php
<?php
session_start();   // Line 2: Start PHP session for user state

define('ROOT_PATH', __DIR__);  // Line 5: Define root path constant for includes

require_once ROOT_PATH . '/includes/db.php';  // Line 8: Load database connection
require_once ROOT_PATH . '/data.php';          // Line 9: Load data bridge (products, categories)
```

**Explanation:**
- `session_start()` initializes the session system, enabling `$_SESSION` for storing user data, cart, etc.
- `ROOT_PATH` is defined once here so all files can use absolute paths reliably.
- Database and data are loaded early because controllers need them.

```php
function loadView($viewName, $data = [])
{
    extract($data);  // Line 16: Convert array keys to variables
    $viewPath = ROOT_PATH . '/views/' . $viewName . '.view.php';
    if (file_exists($viewPath)) {
        require_once ROOT_PATH . '/includes/header.php';  // Line 19: Include header
        require_once $viewPath;                           // Line 20: Include the view
        require_once ROOT_PATH . '/includes/footer.php';  // Line 21: Include footer
    }
}
```

**Explanation:**
- `extract($data)` takes an associative array and creates local variables. For example, `['products' => [...]]` becomes `$products`.
- This function wraps every view with consistent header and footer templates.

```php
$route = isset($_GET['route']) && $_GET['route'] !== '' ? $_GET['route'] : 'home';  // Line 28

$routes = [
    'home' => 'home.php',
    'product-listing' => 'product-listing.php',
    'cart' => 'cart.php',
    'checkout' => 'checkout.php',
    'login' => 'login.php',
    'signup' => 'signup.php',
    'logout' => 'logout.php',
    'my-orders' => 'my-orders.php',
    'dashboard' => 'dashboard.php',
    'ajax-cart' => 'ajax-cart.php',
    'ajax-checkout' => 'ajax-checkout.php'
];  // Lines 31-46
```

**Explanation:**
- The `$route` is extracted from the URL query parameter `?route=xxx` (set by `.htaccess`).
- The `$routes` array maps clean URLs to actual controller files.

```php
$controller = isset($routes[$route]) ? $routes[$route] : 'home.php';  // Line 49

if (strpos($route, 'api/') === 0) {  // Line 52: Check for API routes
    $apiFile = str_replace('api/', '', $route) . '.php';
    if (file_exists(ROOT_PATH . '/api/' . $apiFile)) {
        require_once ROOT_PATH . '/api/' . $apiFile;
        exit;
    }
}

// Load the controller
$controllerPath = ROOT_PATH . '/controllers/' . $controller;
if (file_exists($controllerPath)) {
    require_once $controllerPath;  // Line 64: Execute the controller
} else {
    header("HTTP/1.0 404 Not Found");
    echo "<h1>404 Not Found</h1>";
}
```

**Explanation:**
- First checks if the route is an API request (starts with `api/`).
- Then loads the appropriate controller file, which handles the request logic.
- If no matching route is found, returns a 404 error.

---

### 2. URL Routing (`.htaccess`)

This Apache configuration enables clean URLs.

```apache
RewriteEngine On  # Line 1: Enable rewrite engine

# Redirect .php URLs to clean URLs (301 redirect)
RewriteCond %{THE_REQUEST} \s/+(.+)\.php[\s?] [NC]
RewriteCond %{REQUEST_URI} !index\.php [NC]
RewriteRule ^ /%1 [R=301,L]  # Lines 3-6
```

**Explanation:**
- If someone accesses `/cart.php`, redirect them to `/cart` with a 301 (permanent) redirect.
- Exception: `index.php` is allowed directly.

```apache
# Block direct access to .php files (except index.php)
RewriteCond %{REQUEST_URI} \.php$
RewriteCond %{REQUEST_URI} !index\.php$
RewriteRule .* - [F,L]  # Lines 8-11
```

**Explanation:**
- For security, direct access to PHP files (like `/controllers/cart.php`) returns 403 Forbidden.
- Only `index.php` is accessible directly.

```apache
# Route all requests through index.php
RewriteCond %{REQUEST_FILENAME} !-f  # If not a real file
RewriteCond %{REQUEST_FILENAME} !-d  # If not a real directory
RewriteRule ^(.*)$ index.php?route=$1 [L,QSA]  # Lines 13-16
```

**Explanation:**
- Any request that doesn't match a real file/folder is sent to `index.php`.
- The original path becomes the `route` query parameter.
- Example: `/product-listing` → `index.php?route=product-listing`

---

### 3. Database Connection (`includes/db.php`)

```php
<?php
// Simple .env loader
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;  // Skip comments
        list($name, $value) = explode('=', $line, 2);
        putenv(trim($name) . '=' . trim($value));  // Line 16: Set environment variable
    }
}  // Lines 10-18
```

**Explanation:**
- Reads the `.env` file line by line.
- Each line like `DB_HOST=localhost` is parsed and set as an environment variable using `putenv()`.
- This keeps sensitive credentials out of the codebase.

```php
class Database
{
    private $host;      // Database server address
    private $port;      // PostgreSQL port (usually 5432)
    private $db_name;   // Database name
    private $username;  // Database user
    private $password;  // Database password
    private $conn = null;  // Connection object (cached)

    public function __construct()
    {
        $this->host = getenv('DB_HOST') ?: 'localhost';
        $this->port = getenv('DB_PORT') ?: '5432';
        $this->db_name = getenv('DB_NAME') ?: 'ecommerce_db2';
        $this->username = getenv('DB_USER') ?: 'postgres';
        $this->password = getenv('DB_PASS') ?: 'postgres';
    }  // Lines 29-36
```

**Explanation:**
- Constructor reads credentials from environment variables.
- Falls back to default values if environment variables are not set.

```php
    public function connect()
    {
        if ($this->conn !== null) {
            return $this->conn;  // Return existing connection (singleton)
        }

        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name}";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Line 49
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);  // Line 52
            
            return $this->conn;
        } catch (PDOException $e) {
            error_log("Connection Error: " . $e->getMessage());
            die("Database connection failed: " . $e->getMessage());
        }
    }
}  // Lines 38-60
```

**Explanation:**
- Uses PDO (PHP Data Objects) for database abstraction.
- `ERRMODE_EXCEPTION` makes PDO throw exceptions on errors instead of silent failures.
- `FETCH_ASSOC` returns rows as associative arrays (key-value pairs).
- Connection is cached to avoid reconnecting multiple times.

```php
function getDb()
{
    static $db = null;  // Static variable persists across calls
    if ($db === null) {
        $database = new Database();
        $db = $database->connect();
    }
    return $db;
}  // Lines 62-71
```

**Explanation:**
- Global helper function for easy database access.
- Uses a static variable to implement the Singleton pattern.
- Any file can call `getDb()` to get the PDO connection.

---

### 4. Data Bridge (`data.php`)

This file bridges the new DAO pattern with the old array-based structure.

```php
<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/src/ProductDAO.php';
require_once __DIR__ . '/src/CommonDAO.php';

// Initialize DAOs
$productDAO = new ProductDAO();
$categoryDAO = new CategoryDAO();
$brandDAO = new BrandDAO();  // Lines 1-13
```

**Explanation:**
- Loads all necessary DAOs.
- Creates instances for fetching data.

```php
// Fetch Products from Database
$dbProducts = $productDAO->getAllProducts();
$products = [];
foreach ($dbProducts as $p) {
    // Cleanup image path
    $imagePath = str_replace(['public/', '../public/'], '', $p['image_main']);
    if (strpos($imagePath, 'images/') !== 0) {
        $imagePath = 'images/' . $imagePath;
    }

    $products[$p['id']] = [
        'id' => $p['id'],
        'name' => $p['name'],
        'price' => $p['price'],
        'originalPrice' => $p['original_price'],
        'image' => $imagePath,
        'description' => $p['description'],
        'badge' => $p['badge'],
        'shipping_type' => $p['shipping_type'],
        'rating' => (int) ($p['rating'] ?? 0),
        'reviews' => (int) ($p['reviews_count'] ?? 0),
        'category' => $p['category_slug'] ?? 'general',
        'brand' => $p['brand_name'] ?? 'EasyCart'
    ];
}  // Lines 15-39
```

**Explanation:**
- Fetches all products from the database.
- Transforms the database structure into the format used by views.
- Indexed by product ID for easy lookup (`$products[$id]`).

```php
// Helper functions
function format_price($amount)
{
    return 'Rs. ' . number_format($amount, 0, ',', ',');
}  // Lines 58-64

function calculate_shipping_cost($method, $subtotal)
{
    $base = max(0, $subtotal);
    switch ($method) {
        case 'standard':
            return 350;  // Fixed ₹350
        case 'express':
            $percent = (int) round($base * 0.10);  // 10% of subtotal
            return (int) min(700, $percent > 0 ? $percent : 700);  // Max ₹700
        case 'white_glove':
            $percent = (int) round($base * 0.05);  // 5% of subtotal
            return (int) min(1600, $percent > 0 ? $percent : 1600);  // Max ₹1600
        case 'freight':
            $percent = (int) round($base * 0.03);  // 3% of subtotal
            return (int) max(2500, $percent);  // Min ₹2500
        default:
            return 350;
    }
}  // Lines 68-87

function calculate_tax($amount)
{
    return (int) round($amount * 0.18);  // 18% GST
}  // Lines 91-95
```

**Explanation:**
- `format_price()` - Formats numbers as Indian Rupee format.
- `calculate_shipping_cost()` - Calculates shipping based on method and subtotal.
- `calculate_tax()` - Applies 18% GST (Goods and Services Tax).

---

## Data Access Objects (DAO)

### UserDAO.php

Handles user registration and authentication.

```php
<?php
require_once __DIR__ . '/../includes/db.php';

class UserDAO
{
    private $db;

    public function __construct()
    {
        $this->db = getDb();  // Get database connection
    }
```

**Explanation:**
- Each DAO has a private `$db` property holding the PDO connection.
- The connection is obtained via `getDb()` helper.

```php
    public function createUser($data)
    {
        try {
            // Check if email already exists
            $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$data['email']]);
            if ($stmt->fetch()) {
                return ['success' => false, 'message' => 'Email already registered.'];
            }

            $sql = "INSERT INTO users (first_name, last_name, email, password, phone, created_at) 
                    VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP)";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $data['first_name'],
                $data['last_name'],
                $data['email'],
                password_hash($data['password'], PASSWORD_DEFAULT),  // SECURE HASHING
                $data['phone']
            ]);

            return ['success' => true, 'id' => $this->db->lastInsertId()];
        } catch (Exception $e) {
            error_log($e->getMessage());
            return ['success' => false, 'message' => 'Database error.'];
        }
    }
```

**Explanation:**
- **Duplicate check**: First queries the database to check if email exists.
- **Prepared statements**: Uses `?` placeholders to prevent SQL injection.
- **Password hashing**: Uses `password_hash()` with `PASSWORD_DEFAULT` (currently bcrypt).
- **Returns**: An associative array with `success` status and either `id` or `message`.

```php
    public function login($email, $password)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);  // Don't keep hash in session
            return ['success' => true, 'user' => $user];
        }

        return ['success' => false, 'message' => 'Invalid email or password.'];
    }
}
```

**Explanation:**
- **Fetches user by email** from database.
- **Verifies password** using `password_verify()` (compares plain text with hash).
- **Security**: Removes password hash from user array before returning.
- **Note**: Generic error message ("Invalid email or password") prevents username enumeration.

---

### ProductDAO.php

Handles product catalog operations.

```php
    public function getAllProducts()
    {
        $sql = "SELECT p.entity_id as id, p.name, p.description, p.price, p.original_price, 
                       p.image_main, p.badge, p.shipping_type,
                       c.name as category_name, c.slug as category_slug, b.name as brand_name
                FROM catalog_product_entity p
                LEFT JOIN catalog_category_products ccp ON p.entity_id = ccp.product_id
                LEFT JOIN catalog_category_entity c ON ccp.category_id = c.entity_id
                LEFT JOIN brands b ON p.brand_id = b.id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
```

**Explanation:**
- **Complex JOIN query**: Combines product, category, and brand tables.
- **LEFT JOIN**: Ensures products are returned even if they don't have a category or brand.
- **Aliasing**: `p.entity_id as id` renames the column for easier use.

```php
    public function getProductById($id)
    {
        // Main details
        $sql = "SELECT p.*, c.name as category_name, b.name as brand_name
                FROM catalog_product_entity p
                LEFT JOIN catalog_category_products ccp ON p.entity_id = ccp.product_id
                LEFT JOIN catalog_category_entity c ON ccp.category_id = c.entity_id
                LEFT JOIN brands b ON p.brand_id = b.id
                WHERE p.entity_id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            // Fetch attributes (color, size, etc.)
            $attrSql = "SELECT attribute_code, value FROM catalog_product_attribute WHERE product_id = ?";
            $attrStmt = $this->db->prepare($attrSql);
            $attrStmt->execute([$id]);
            $product['attributes'] = $attrStmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $product;
    }
```

**Explanation:**
- **Two queries**: First gets main product data, second gets attributes.
- **Attributes pattern**: Similar to Magento's EAV (Entity-Attribute-Value) model.
- **Returns null** if product not found (when `fetch()` returns false).

---

### CartDAO.php

Handles persistent shopping cart operations.

```php
    public function getOrCreateCart($sessionId, $userId = null, $guestId = null)
    {
        // Verify user exists in database (handles DB rebuilds)
        if ($userId) {
            $checkUser = $this->db->prepare("SELECT id FROM users WHERE id = ?");
            $checkUser->execute([(int) $userId]);
            if (!$checkUser->fetch()) {
                $userId = null;  // User doesn't exist anymore
                if (isset($_SESSION['user_id'])) unset($_SESSION['user_id']);
                if (isset($_SESSION['user'])) unset($_SESSION['user']);
            }
        }

        // Check for existing active cart
        if ($userId) {
            $stmt = $this->db->prepare("SELECT cart_id FROM sales_cart WHERE user_id = ? AND is_active = TRUE ORDER BY created_at DESC LIMIT 1");
            $stmt->execute([(int) $userId]);
        } else {
            $stmt = $this->db->prepare("SELECT cart_id FROM sales_cart WHERE session_id = ? AND is_active = TRUE ORDER BY created_at DESC LIMIT 1");
            $stmt->execute([$sessionId]);
        }

        $cartRecord = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cartRecord && $cartRecord['cart_id'] > 0) {
            return (int) $cartRecord['cart_id'];  // Return existing cart
        }

        // Create new cart
        $sql = "INSERT INTO sales_cart (session_id, user_id, guest_id) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$sessionId, $userId, $guestId]);
        return (int) $this->db->lastInsertId();
    }
```

**Explanation:**
- **User validation**: Checks if the user ID in session actually exists in database.
- **Cart lookup priority**: For logged-in users, looks up by user_id; for guests, by session_id.
- **Creates new cart**: If no active cart exists, creates one.
- **Returns cart_id**: Integer ID used for all cart operations.

```php
    public function addItem($cartId, $productId, $qty, $price)
    {
        // Check if item already in cart
        $stmt = $this->db->prepare("SELECT entity_id, quantity FROM sale_cart_product WHERE cart_id = ? AND product_id = ?");
        $stmt->execute([(int) $cartId, (int) $productId]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            // Update quantity
            $newQty = (int) $existing['quantity'] + (int) $qty;
            $stmt = $this->db->prepare("UPDATE sale_cart_product SET quantity = ? WHERE entity_id = ?");
            return $stmt->execute([$newQty, (int) $existing['entity_id']]);
        } else {
            // Insert new item
            $stmt = $this->db->prepare("INSERT INTO sale_cart_product (cart_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            return $stmt->execute([(int) $cartId, (int) $productId, (int) $qty, $price]);
        }
    }
```

**Explanation:**
- **Upsert pattern**: First checks if item exists, then either updates or inserts.
- **Quantity accumulation**: If adding same product twice, increases quantity.
- **Price stored**: Cart stores price at time of adding (for price lock).

---

### OrderDAO.php

Handles order creation and retrieval.

```php
    public function createOrder($data)
    {
        try {
            $this->db->beginTransaction();  // Start transaction

            // Insert order header
            $sql = "INSERT INTO sales_orders (...) VALUES (?, ?, ?, ...)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([...]);
            $orderId = $this->db->lastInsertId();

            // Get items from cart
            $cartItemsStmt = $this->db->prepare("
                SELECT cp.product_id, p.name, cp.price, cp.quantity 
                FROM sale_cart_product cp
                JOIN catalog_product_entity p ON cp.product_id = p.entity_id
                WHERE cp.cart_id = ?
            ");
            $cartItemsStmt->execute([$data['cart_id']]);
            $dbItems = $cartItemsStmt->fetchAll(PDO::FETCH_ASSOC);

            // Transfer items to order_items
            $itemSql = "INSERT INTO sales_order_items (order_id, product_id, name, price, quantity, total) VALUES (?, ?, ?, ?, ?, ?)";
            $itemStmt = $this->db->prepare($itemSql);
            foreach ($dbItems as $item) {
                $itemStmt->execute([
                    $orderId,
                    $item['product_id'],
                    $item['name'],
                    $item['price'],
                    $item['quantity'],
                    $item['price'] * $item['quantity']
                ]);
            }

            // Mark cart as inactive
            $updCart = $this->db->prepare("UPDATE sales_cart SET is_active = FALSE WHERE cart_id = ?");
            $updCart->execute([$data['cart_id']]);

            $this->db->commit();  // Commit transaction
            return $orderId;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            throw $e;
        }
    }
```

**Explanation:**
- **Transaction**: Uses `beginTransaction()`, `commit()`, and `rollBack()` for atomicity.
- **Order creation flow**:
  1. Insert order header
  2. Copy cart items to order items
  3. Close the cart
- **Rollback on error**: If any step fails, the entire operation is undone.

```php
    public function getOrderTrend($userId)
    {
        $sql = "SELECT created_at as order_timestamp, final_amount, order_number 
                FROM sales_orders 
                WHERE user_id = ? 
                ORDER BY created_at ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
```

**Explanation:**
- Returns orders sorted by date for chart visualization.
- Each order is a separate data point (not aggregated by day).

---

### CommonDAO.php

Contains simple DAOs for categories and brands.

```php
class CategoryDAO
{
    private $db;
    public function __construct() { $this->db = getDb(); }

    public function getAllCategories()
    {
        return $this->db->query("SELECT * FROM catalog_category_entity ORDER BY name ASC")->fetchAll();
    }
}

class BrandDAO
{
    private $db;
    public function __construct() { $this->db = getDb(); }

    public function getAllBrands()
    {
        return $this->db->query("SELECT * FROM brands ORDER BY name ASC")->fetchAll();
    }
}
```

**Explanation:**
- Simple read-only DAOs for dropdown data.
- Uses `query()` instead of `prepare()` since no user input is involved.

---

## Controllers Explained

### Login Controller (`controllers/login.php`)

```php
<?php
require_once ROOT_PATH . '/src/UserDAO.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $userDAO = new UserDAO();
    $result = $userDAO->login($email, $password);

    if ($result['success']) {
        $user = $result['user'];
        $_SESSION['user'] = [
            'firstName' => $user['first_name'],
            'email' => $user['email']
        ];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['flash_message'] = ['text' => 'Welcome back, ' . $user['first_name'] . '!', 'type' => 'success'];
        header('Location: home');
        exit;
    } else {
        $error = $result['message'];
    }
}

$page_title = 'Login - EasyCart';
$page_css = 'login.css';

loadView('login', [
    'page_title' => $page_title,
    'page_css' => $page_css,
    'error' => $error
]);
```

**Flow:**
1. On POST request, validate credentials via UserDAO
2. On success: Store user in session, set flash message, redirect
3. On failure: Set error message, render login form again

---

### AJAX Cart Controller (`controllers/ajax-cart.php`)

```php
<?php
header('Content-Type: application/json; charset=utf-8');

// Auth check
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Please login to use the cart.']);
    exit;
}

// Helper functions
function json_response(array $payload, int $code = 200): void {
    http_response_code($code);
    echo json_encode($payload);
    exit;
}

function read_payload(): array {
    $raw = file_get_contents('php://input');
    if ($raw) {
        $decoded = json_decode($raw, true);
        if (is_array($decoded)) return $decoded;
    }
    return $_POST ?? [];
}

// Cart operations
require_once ROOT_PATH . '/src/CartDAO.php';
$cartDAO = new CartDAO();

// Get or create cart
$cartId = $cartDAO->getOrCreateCart(session_id(), $_SESSION['user_id'] ?? null, $_SESSION['guest_id'] ?? null);
$_SESSION['cart_id'] = $cartId;

$payload = read_payload();
$action = $payload['action'] ?? 'summary';
$productId = (int) ($payload['product_id'] ?? 0);
$qty = (int) ($payload['quantity'] ?? 1);

// Execute action
if ($action === 'add') {
    $p = $products[$productId] ?? null;
    if ($p) {
        $cartDAO->addItem($cartId, $productId, $qty, $p['price']);
    }
} elseif ($action === 'update') {
    $cartDAO->updateItem($cartId, $productId, $qty);
} elseif ($action === 'remove') {
    $cartDAO->removeItem($cartId, $productId);
}

// Sync session cart with database
$dbItems = $cartDAO->getItems($cartId);
$sessionCart = [];
$totalCount = 0;
$totalSubtotal = 0;
foreach ($dbItems as $it) {
    $totalCount += $it['quantity'];
    $totalSubtotal += ($it['price'] * $it['quantity']);
    $sessionCart[$it['product_id']] = [...];
}
$_SESSION['cart'] = $sessionCart;

// Return JSON response
json_response([
    'success' => true,
    'summary' => [
        'cartCount' => $totalCount,
        'subtotal' => $totalSubtotal,
        'isEmpty' => $totalCount <= 0,
    ]
]);
```

**Explanation:**
- Returns JSON instead of HTML.
- Reads JSON payload from request body.
- Performs cart operations via CartDAO.
- Syncs `$_SESSION['cart']` with database cart.
- Returns cart summary for UI updates.

---

## API Endpoints

### Dashboard Chart API (`api/dashboard-chart.php`)

```php
<?php
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

require_once ROOT_PATH . '/src/OrderDAO.php';
$orderDAO = new OrderDAO();
$trendData = $orderDAO->getOrderTrend($_SESSION['user_id']);

$labels = [];
$data = [];
foreach ($trendData as $row) {
    $labels[] = date('M d, H:i', strtotime($row['order_timestamp']));
    $data[] = (float) $row['final_amount'];
}

echo json_encode([
    'success' => true,
    'labels' => $labels,  // ["Feb 01, 14:30", "Feb 02, 09:15", ...]
    'data' => $data       // [1500, 2300, ...]
]);
```

**Explanation:**
- Returns data formatted for Chart.js.
- `labels` = date/time strings for x-axis.
- `data` = order amounts for y-axis.

---

## JavaScript Frontend (`ecommerce.js`)

### Initialization

```javascript
document.addEventListener('DOMContentLoaded', function () {
    initializeApp();
});

function initializeApp() {
    initFormValidations();      // Form validation
    initCartInteractions();     // Cart page quantity controls
    initAddToCartAjax();        // AJAX add-to-cart
    initProductGallery();       // Product image switching
    initShippingOptions();      // Checkout shipping selection
    initProductListingEnhancements();  // Product count updates
    initDashboard();            // Chart.js dashboard
}
```

### AJAX Helper

```javascript
async function postJson(url, data) {
    const res = await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    });
    const json = await res.json().catch(() => null);
    if (!res.ok || !json) {
        throw new Error((json && json.message) ? json.message : 'Server error');
    }
    return json;
}
```

**Explanation:**
- Uses `fetch()` API for HTTP requests.
- Sends JSON body, expects JSON response.
- Throws error on non-200 responses.

### Form Validation

```javascript
function validateEmailField(input) {
    const value = input.value.trim();
    if (!value) {
        showInputError(input, 'Email is required');
        return false;
    }
    if (!isValidEmail(value)) {
        showInputError(input, 'Please enter a valid email address');
        return false;
    }
    clearInputError(input);
    return true;
}

function showInputError(input, message) {
    clearInputError(input);
    input.classList.add('input-error');
    input.style.borderColor = '#d32f2f';

    const errorMsg = document.createElement('span');
    errorMsg.className = 'field-error-msg';
    errorMsg.textContent = message;
    // ... styling and DOM insertion
}
```

**Explanation:**
- Validates on `blur` (when user leaves field) and on submit.
- Displays inline error messages.
- Prevents form submission if validation fails.

### Cart AJAX Operations

```javascript
// Add to cart click handler
form.addEventListener('submit', function (e) {
    e.preventDefault();  // Don't reload page

    const productId = parseInt(form.querySelector('input[name="product_id"]').value);
    const qty = parseInt(form.querySelector('input[name="quantity"]').value) || 1;
    const btn = form.querySelector('button[type="submit"]');

    setLoading(btn, true);  // Show loading state
    
    postJson('ajax-cart', { action: 'add', product_id: productId, quantity: qty })
        .then((json) => {
            setCartBadge(json.summary.cartCount);  // Update cart count in header
            showToast('Added to cart', 'success');
        })
        .catch((err) => {
            showToast(err.message || 'Failed to add to cart', 'error');
        })
        .finally(() => setLoading(btn, false));
});
```

**Explanation:**
- Intercepts form submit event.
- Sends AJAX request to `ajax-cart` endpoint.
- Updates UI without page reload.
- Shows toast notification for feedback.

### Dashboard Chart

```javascript
function renderDashboardChart(ctx) {
    fetch('api/dashboard-chart')
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                new Chart(ctx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: res.labels,
                        datasets: [{
                            label: 'Spendings (Rs.)',
                            data: res.data,
                            borderColor: '#4f46e5',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            tension: 0.4,  // Smooth curves
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            }
        });
}
```

**Explanation:**
- Fetches order data from API.
- Renders a line chart using Chart.js.
- Each order is a separate data point.

---

## Session Management & Security

### Session Variables Used

| Key | Type | Purpose |
|-----|------|---------|
| `$_SESSION['user']` | array | User info (firstName, email) |
| `$_SESSION['user_id']` | int | Database user ID |
| `$_SESSION['cart']` | array | Cart items (synced from DB) |
| `$_SESSION['cart_id']` | int | Database cart ID |
| `$_SESSION['guest_id']` | string | Guest identifier |
| `$_SESSION['shipping_method']` | string | Selected shipping option |
| `$_SESSION['coupon']` | array | Applied coupon info |
| `$_SESSION['flash_message']` | array | One-time notification |

### Security Measures

1. **Password Hashing**
   ```php
   password_hash($password, PASSWORD_DEFAULT)  // Signup
   password_verify($password, $user['password'])  // Login
   ```

2. **SQL Injection Prevention**
   ```php
   $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
   $stmt->execute([$email]);  // Parameterized query
   ```

3. **XSS Prevention**
   ```php
   echo htmlspecialchars($page_title);  // Escape output
   ```

4. **Direct PHP Access Prevention**
   ```apache
   RewriteRule .* - [F,L]  # Block direct .php access
   ```

---

## Shipping Logic

### Shipping Types

| Type | Calculation | Notes |
|------|-------------|-------|
| Standard | Fixed ₹350 | Always available for Express products |
| Express | 10% of subtotal (max ₹700) | For Express products under ₹300 subtotal |
| White Glove | 5% of subtotal (max ₹1600) | For Freight products or Express > ₹300 |
| Freight | 3% of subtotal (min ₹2500) | For large items |

### Availability Rules

```php
$disabledMethods = ($hasFreight || $expressTotal > 300) 
    ? ['standard', 'express']      // Only white_glove, freight available
    : ['white_glove', 'freight'];  // Only standard, express available
```

---

## Setup Instructions

### Prerequisites

- XAMPP or similar (Apache + PHP 8.x)
- PostgreSQL 13+
- Composer (optional)

### Installation

1. **Clone to htdocs**
   ```bash
   cd C:\xampp\htdocs
   git clone <repo-url> E-commerce-website
   ```

2. **Create Database**
   ```sql
   CREATE DATABASE ecommerce_db2;
   ```

3. **Run Schema**
   ```bash
   psql -U postgres -d ecommerce_db2 -f database/schema.sql
   ```

4. **Configure Environment**
   ```bash
   cp .env.example .env
   # Edit .env with your database credentials
   ```

5. **Start Apache**
   - Start XAMPP Control Panel
   - Start Apache

6. **Access**
   - Open `http://localhost/E-commerce-website/`

---

## Summary

This e-commerce application demonstrates:

- **Clean MVC architecture** with separation of concerns
- **DAO pattern** for database abstraction
- **Secure authentication** with password hashing
- **Persistent carts** stored in database
- **AJAX interactions** for smooth UX
- **Clean URLs** via Apache rewriting
- **Modern PHP** practices (PDO, prepared statements, sessions)

The codebase is designed for maintainability and scalability, following industry-standard patterns used by platforms like Magento.
