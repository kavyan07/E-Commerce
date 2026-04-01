# 🚀 EasyCart E-Commerce - Full Project Documentation

This document provides an exhaustive, file-by-file explanation of the EasyCart e-commerce application. It covers the architecture, directory structure, individual file functionalities, and core business logic.

---

## 🏗️ 1. High-Level Architecture

The project follows a **Modern MVC (Model-View-Controller)** architecture with a **Front Controller Pattern**.

### The Request Flow:
1.  **User Request**: The user enters a URL (e.g., `/products`).
2.  **Entry Point (`.htaccess`)**: Apache redirects all requests to `index.php`.
3.  **Routing (`index.php`)**: The front controller starts the session, loads global data (`data.php`), and identifies the requested route.
4.  **Autoloading (`libs/Core/Autoload.php`)**: The system automatically finds and loads the necessary classes based on their names.
5.  **Controller Execution**: A controller class (e.g., `Controller_Product_Listing`) is instantiated. It:
    *   Interacts with **Models** (e.g., `Model_Product_Collection`) to fetch data.
    *   Prepares data for the view.
    *   Sets a template.
6.  **Rendering (`View_Default`)**: The view layer renders the HTML, wrapping it with `header.php` and `footer.php`.
7.  **Response**: The final HTML is sent back to the user's browser.

---

## 📁 2. Directory Structure Explained

| Directory | Description |
| :--- | :--- |
| **`/api`** | JSON-based endpoints for AJAX requests (e.g., chart data). |
| **`/app`** | Contains Design assets and view templates (older structure). |
| **`/controllers`** | Legacy route-handling scripts (transitional state). |
| **`/css`** | Page-specific stylesheets. |
| **`/database`** | SQL schema files and database initialization scripts. |
| **`/docs`** | Detailed documentation and project phase summaries. |
| **`/includes`** | Shared components: `db.php` (DB link), `header.php`, `footer.php`. |
| **`/js`** | Client-side logic (`ecommerce.js`) for cart, validation, and UI. |
| **`/libs`** | **The Core MVC Engine**: Contains Controllers, Models, and View classes. |
| **`/public`** | Publicly accessible assets (images, fonts). |
| **`/src`** | Data Access Objects (DAO) - The bridge between PHP and PostgreSQL. |
| **`/views`** | The **Presentation Layer**: HTML templates for each page. |

---

## 🛠️ 3. Core System Files

### `index.php` (The Front Controller)
*   **Purpose**: The central gateway for the entire application.
*   **How it works**:
    - Starts the PHP session (`session_start()`).
    - Loads the database connection and the autoloader.
    - Defines a `loadView()` helper function.
    - Maps URLs (routes) to specific Controller classes or legacy files.
    - If a class like `Controller_Home` is found, it calls `execute()`.

### `.htaccess` (The Router)
*   **Purpose**: Apache configuration for clean, SEO-friendly URLs.
*   **How it works**:
    - Redirection: `/cart.php` → `/cart`.
    - Blocking: Prevents users from accessing logical files directly in folders like `controllers/` or `src/`.
    - Routing: Rewrites all non-file requests to `index.php?route=$1`.

### `libs/Core/Autoload.php`
*   **Purpose**: Automatic class loading (PSR-0 style).
*   **How it works**:
    - Translates class names like `Controller_Admin_Product` to file paths like `libs/Controller/Admin/Product.php` by replacing underscores with directory separators.

### `includes/db.php`
*   **Purpose**: Singleton Database Connection.
*   **How it works**:
    - Loads environment variables from `.env`.
    - Uses PDO to connect to **PostgreSQL**.
    - Sets error modes and default fetch modes (Associative arrays).

---

## 🎮 4. Controller Layer (`libs/Controller`)

Controllers handle the logic of each page. They all inherit from `Controller_Abstract`.

### `libs/Controller/Abstract.php`
*   Base class providing shared methods like `getRequest()`, `setSession()`, and redirection helpers.

### `libs/Controller/Home.php`
*   Renders the landing page. Fetches all products and displays them in the hero/grid sections.

### `libs/Controller/Product/Listing.php`
*   Manages the product catalog. Handles searching and filtering by category/brand.

### `libs/Controller/Checkout.php`
*   The most complex controller. Handles:
    - Cart totals calculation.
    - Shipping method logic.
    - Coupon validation.
    - Order submission to the database.

---

## 📊 5. Model & Data Layer (`libs/Model` & `src/`)

The Model layer handles database persistence using the **DAO (Data Access Object)** pattern.

### `libs/Model/Abstract.php`
*   Provides a base for models to interact with the database using common CRUD (Create, Read, Update, Delete) methods.

### `src/ProductDAO.php`
*   Fetches products, featured items, and specific product details via SQL.

### `src/CartDAO.php`
*   Manages persistent shopping carts in the database. It handles "Guest Carts" (based on session) and "Member Carts" (based on user_id).

### `src/OrderDAO.php`
*   Handles saving successful checkouts and retrieving order history for the dashboard.

---

## 🎨 6. View Layer (`libs/View` & `views/`)

### `libs/View/Default.php`
*   Renders templates. It takes data passed from the controller and injects it into the `.view.php` files.

### `views/*.view.php`
*   These are the HTML templates.
    - `home.view.php`: Main dashboard.
    - `product-detail.view.php`: Individual product page.
    - `cart.view.php`: Item summary.
    - `checkout.view.php`: Payment & Shipping form.

---

## ⚙️ 7. Key Functionalities

### 🛒 Shopping Cart Flow
1.  User clicks "Add to Cart" (AJAX in `js/ecommerce.js`).
2.  `Controller_Ajax_Cart` receives the request.
3.  `CartDAO` checks if a cart exists for the session/user.
4.  Item is added to `sale_cart_product` table.
5.  Frontend updates the cart badge and displays a "Success" toast notification.

### 💳 Checkout & Shipping
1.  Checkout page calculates subtotal.
2.  Shipping rules are applied based on product type (**Express** vs **Freight**) and subtotal.
3.  User fills shipping details (saved as `sales_orders`).
4.  On success, the cart is marked as `is_active = FALSE`.

### 🛡️ Admin Dashboard
1.  Located at `/admin/dashboard`.
2.  `Controller_Admin_Dashboard` fetches sales analytics.
3.  Provides tools for **Product Export** (CSV) and **Product Import**.

---

## 📝 8. Developer Notes
*   **Database**: Always run scripts in `database/schema.sql` if you change the structure.
*   **Environment**: Ensure `.env` has correct PostgreSQL credentials.
*   **CSS**: Styles are modular; check the `css/` folder for page-specific designs.
