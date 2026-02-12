# 🏗️ EasyCart Full Architecture & Database Guide

This document explains how every file in the project works, how they communicate with **PostgreSQL**, and how the **Entity Relationships** are managed.

---

## 1. The Database Layer (`database/schema.sql`)
Your database follows a **Professional EAV-Lite (Entity-Attribute-Value)** architecture, similar to enterprise systems like Magento.

### **Entity Tables (Standard)**
*   **`users`**: Stores core customer data.
*   **`brands`**: A lookup table for brand names.
*   **`catalog_product_entity`**: The "Master" product table. Contains fixed data (Name, Price, SKU).
*   **`catalog_category_entity`**: The "Master" category table (Fashion, Electronics, etc.).

### **EAV / Attribute Tables (Flexible)**
*   **`catalog_product_attribute`**: Allows us to add infinite properties (Color, Size, Material) to a product without adding new columns to the database.
    *   *Relationship*: `product_id` ➡️ `catalog_product_entity(entity_id)`.

### **Relationship Tables (Linking)**
*   **`catalog_category_products`**: A **Many-to-Many** link. One product can be in "Gaming" AND "Electronics".
    *   *Relation*: Links `category_id` and `product_id`.

### **Sales & Cart Tables (Transactional)**
*   **`sales_cart`**: Stores active shopping sessions.
*   **`sale_cart_product`**: Stores items inside a specific cart.
*   **`sales_orders`**: Permanent record of a finalized purchase.
*   **`sales_order_items`**: A snapshot of products at the time of purchase.

---

## 2. The Data Access Objects (`classes/`)
These PHP classes are the "Brain" of your database communication. They use **PDO Prepared Statements** to prevent SQL Injection.

### **`ProductDAO.php`**
*   **`getAllProducts()`**: Performs a 4-table `LEFT JOIN` (Product + Link Table + Category + Brand) to get all data in one single PostgreSQL query.
*   **`getProductById()`**: Fetches core data + a second query to grab all flexible attributes from `catalog_product_attribute`.

### **`CartDAO.php`** (The Logic Hub)
*   **`getOrCreateCart()`**:
    1. Checks if `user_id` is valid.
    2. Looks for an `is_active = TRUE` cart.
    3. If none, runs an `INSERT` to create one.
*   **Persistent Logic**: Because this saves to Postgres, the cart stays alive even if the user clears their browser cookies!

### **`OrderDAO.php`**
*   **`createOrder()`**: Uses a **Database Transaction**. 
    1. Inserts the main order.
    2. Copies items from `sale_cart_product` into `sales_order_items`.
    3. Sets `is_active = FALSE` on the cart.
    4. Commits the data together.

---

## 3. The Core Bridge (`data.php`)
This file is the "Glue" of the application. It acts as a middleman.
1.  It initializes the **DAOs**.
2.  It calls `$productDAO->getAllProducts()`.
3.  It transforms the **Database Rows** into a **Clean PHP Array** (`$products`) that the frontend can easily loop through.
4.  It maps Database Slugs (e.g., `electronics`) to variables that the JavaScript filters understand.

---

## 4. The AJAX Logic (`php/ajax-cart.php`)
This file handles adding to cart **without reloading the page**.

1.  **Read Input**: Gets `product_id` and `quantity` from JavaScript.
2.  **Verify Cart**: Calls `CartDAO->getOrCreateCart()`.
3.  **Update Postgres**: Calls `CartDAO->addItem()`.
4.  **Sync Session**: Updates the `$_SESSION['cart']` so the header count updates instantly.
5.  **Return JSON**: Sends a success signal back to the browser.

---

## 5. Summary of Postgres File Connections

| File | Postgres Table Involved | Action Type |
| :--- | :--- | :--- |
| `UserDAO.php` | `users` | SELECT / INSERT |
| `ProductDAO.php` | `catalog_product_entity`, `catalog_category_products` | SELECT (JOIN) |
| `CartDAO.php` | `sales_cart`, `sale_cart_product` | SELECT / INSERT / UPDATE |
| `OrderDAO.php` | `sales_orders`, `sales_order_items` | INSERT (Transaction) |
| `data.php` | almost everything | SELECT (Read Only) |
| `db.php` | N/A | Establishing the Connection (PDO) |

---

## 6. Visual Data Flow
**USER CLICK** ➡️ **JS (ecommerce.js)** ➡️ **PHP (ajax-cart.php)** ➡️ **DAO (CartDAO.php)** ➡️ **PostgreSQL Table**
