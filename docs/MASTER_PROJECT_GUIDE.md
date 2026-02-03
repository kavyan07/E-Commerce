# Master Project Guide: EasyCart E-commerce Refactored

This document provides a comprehensive explanation of the EasyCart project structure, its files, code patterns, and the PostgreSQL database architecture.

---

## 1. Project Overview & Architecture
The project has been refactored from a flat structure into a **Front Controller Pattern** (MVC-inspired). This separates logic (PHP), presentation (HTML), and data access (DAO).

### Core Technologies
*   **Backend**: PHP 8.x
*   **Frontend**: HTML5, Vanilla CSS3, Javascript (ES6+)
*   **Database**: PostgreSQL
*   **Routing**: Apache `.htaccess` + `index.php`

---

## 2. Directory Structure Explaination

| Folder | Purpose |
| :--- | :--- |
| `controllers/` | **Logic Layer**: Handles user requests, processes data via DAOs, and prepares variables for the views. |
| `views/` | **Presentation Layer**: HTML templates with embedded PHP for echoing data. Files end in `.view.php`. |
| `src/` | **Data Access Layer (DAO)**: Contains classes that communicate directly with the PostgreSQL database. |
| `includes/` | **Common Blocks**: Shared files like `header.php`, `footer.php`, and `db.php` (DB connection). |
| `api/` | **AJAX Endpoints**: JSON-based PHP files for dynamic frontend interactions. |
| `js/` | **Client-side Logic**: `ecommerce.js` handles cart AJAX, form validation, and UI effects. |
| `css/` | **Styling**: Modular CSS files for each specific page/component. |
| `images/` | **Static Assets**: Product, category, and brand images organized by type. |
| `docs/` | **Documentation**: Guides, explanation files, and previous phase summaries. |

---

## 3. Core File Breakdown

### Root Files
*   **`.htaccess`**: The "Gatekeeper". It removes `.php` from URLs and redirects all traffic to `index.php`. It also blocks direct access to logical files for security.
*   **`index.php`**: The "Router". It parses the URL, initializes the database connection, and `requires` the appropriate controller based on the requested route.
*   **`data.php`**: The "Data Bridge". It initializes the DAOs and fetches global data (like the product list) used across multiple pages.

### Key Controllers (`controllers/`)
*   **`home.php`**: Fetches featured products and renders the landing page.
*   **`product-listing.php`**: Handles searching, filtering, and sorting of the product grid.
*   **`cart.php`**: Manages the shopping cart session and calculation of totals.
*   **`checkout.php`**: Processes the order form, applies coupons, and saves the final order to the database.

### Key Views (`views/`)
*   **`home.view.php`**: Hero sections, advertisements, and category cards.
*   **`product-listing.view.php`**: Dynamic product grid with AJAX filter integration.
*   **`checkout.view.php`**: Secure form for shipping and payment.

---

## 4. PostgreSQL Database Structure
The project uses a Magento-inspired schema for scalability.

### Tables
1.  **`catalog_product_entity`**: Stores product details (name, price, image, shipping type).
2.  **`catalog_category_entity`**: Stores category names and slugs.
3.  **`catalog_category_products`**: Link table (Many-to-Many) between products and categories.
4.  **`brands`**: Managed brand information.
5.  **`sales_cart`**: Stores active and inactive carts (linked to Session ID or User ID).
6.  **`sale_cart_product`**: Items stored inside a cart.
7.  **`sales_orders`**: Final order headers (totals, shipping address).
8.  **`sales_order_items`**: Line items for each completed order.
9.  **`users`**: User account credentials and profile info.

---

## 5. Code Line Structure & Logic Patterns

### Controller Pattern (e.g., `controllers/home.php`)
1.  **Preparation**: Fetch data using DAOs (e.g., `$products = $pDAO->getAll()`).
2.  **Logic**: Handle any POST requests or data filtering.
3.  **Variable Setup**: Define `$page_title` and `$page_css`.
4.  **Load View**: Call `loadView('home', [...data...])`.

### View Pattern (e.g., `views/home.view.php`)
1.  **Template**: Pure HTML with unique IDs for CSS/JS targeting.
2.  **Data Output**: Uses `<?= $variable ?>` or `<?php foreach($items as $i): ?>` for dynamic content.
3.  **Clean Links**: Uses routes like `<a href="cart">` instead of `cart.php`.

### DAO Pattern (e.g., `src/ProductDAO.php`)
1.  **PDO Connection**: Injected via constructor using `getDb()`.
2.  **Prepared Statements**: Every query uses `?` or `:name` placeholders to prevent **SQL Injection**.
3.  **Methods**: Standardized functions like `getById($id)`, `getAll()`, etc.

### AJAX Pattern (`js/ecommerce.js`)
1.  **Intercept**: Prevents default form submission.
2.  **Fetch**: Sends a POST request to a clean URL (routed to an AJAX controller).
3.  **Update**: Updates the DOM (e.g., cart count badge) without reloading the page.

---

## 6. Security Features
*   **SQL Injection Prevention**: Forced use of PDO prepared statements in all DAOs.
*   **XSS Protection**: Use of `htmlspecialchars()` when rendering user-submitted or DB data in views.
*   **Protected Files**: Logical PHP files in `/controllers`, `/src`, and `/includes` cannot be accessed by URL; only the system can `include` them.
*   **Session Management**: Secure check for `user_id` vs `guest_id` in cart management.

---

*This document is the single source of truth for the project topology as of the Phase 6 refactor.*
