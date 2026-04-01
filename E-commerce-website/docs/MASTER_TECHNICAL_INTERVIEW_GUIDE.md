# 🎓 Master Technical Interview Guide: EasyCart E-Commerce

This guide is designed for a Senior Architect's deep dive into the EasyCart project. It explains the "How" and "Why" behind every architectural decision, line by line.

---

## 🏛️ 1. Project Architecture (The Engine)

The project is built using a **Custom MVC (Model-View-Controller) Framework**. Unlike basic PHP projects, this uses an **Enterprise Design Pattern** that separates responsibilities:

### 🔄 The Flow of a Request
1.  **Entry**: User requests `http://localhost/products`.
2.  **Routing**: `.htaccess` (The Gatekeeper) translates this to `index.php?route=products`.
3.  **Bootstrap**: `index.php` (The Brain) starts the session and registers the **Autoloader**.
4.  **Router**: `index.php` looks at the route and decides: "I need to call `Controller_Product_Listing`".
5.  **Execution**: The Controller is instantiated and its `execute()` method is called.
6.  **Data Fetching**: The Controller asks the **Model/Resource Layer** for data (e.g., `SELECT * FROM products`).
7.  **View Binding**: The Controller passes data to the **View Layer**.
8.  **Parsing**: The View Layer reads `.phtml` templates, replaces placeholders with real data, and wraps them in `header.php` and `footer.php`.
9.  **Output**: The final HTML is sent back to the browser.

---

## 📁 2. Folder Structure: What and Why?

| Directory | Purpose | Why it's needed? |
| :--- | :--- | :--- |
| `/api` | RESTful JSON endpoints. | For frontend AJAX calls (like dashboard charts). |
| `/app/Design/view` | Modern `.phtml` templates. | Separates HTML design from core PHP logic. |
| `/controllers` | Legacy script-based controllers. | Backward compatibility during refactoring. |
| `/includes` | Shared global utilities. | Centralizes DB connection and HTML components. |
| `/js` & `/css` | Static assets. | Handles client-side interactivity and design. |
| `/libs/Core` | Framework core (Autoload, DB). | The "Operating System" of your app. |
| `/libs/Controller` | Class-based logic (Inheritance). | Allows sharing logic between different pages. |
| `/libs/Model` | Business logic entities. | Represents things like "Product" or "User". |
| `/src` | DAO (Data Access Objects). | Decouples SQL queries from the rest of the app. |

---

## 📄 3. Line-by-Line Breakdown: The Core Engine

### 🧩 `index.php` (The Front Controller)
This is the single entry point for all requests.

```php
1: <?php
2: session_start();
```
*   **What**: Starts the PHP session.
*   **Why**: To store variables like `user_id` or `cart_items` that persist across different pages.

```php
12: require_once ROOT_PATH . '/libs/Core/Autoload.php';
13: Core_Autoload::register();
```
*   **What**: Registers a magic function that looks for classes automatically.
*   **Why**: Without this, you'd need to manually `include` every single class file. It makes scaling easy.

---

### 🪄 `libs/Core/Autoload.php` (The Magic Loader)
```php
9: $path = str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
```
*   **What**: Converts a class name like `Model_Product_Resource` into a file path like `libs/Model/Product/Resource.php`.
*   **How**: It replaces underscores with slashes.
*   **Why**: It follows the **PSR-0/4** industry standard, keeping files organized.

---

### 🏛️ `libs/Controller/Abstract.php` (The Mother of Controllers)
```php
3: abstract class Controller_Abstract
```
*   **What**: An `abstract` class is a template. You cannot create a "Generic" controller; you must create a specific one.
*   **Inheritance (`extends`)**: When `Controller_Home` extends `Abstract`, it gets all the parent's tools (like `redirect`) for free.

---

### 🎨 `libs/View/Abstract.php` (The Presentation Manager)
```php
39: $file = ROOT_PATH . '/app/Design/view/' . $template . '.phtml';
```
*   **What**: Locates the `.phtml` template file.
*   **Why**: `.phtml` (PHP-HTML) is a industry convention for templates.
*   **How it works**: It uses `ob_start()` (Object Buffering) to "catch" the HTML, inject the variables, and then return it as one big string.

---

## 💾 4. Database & SQL Deep Dive (PostgreSQL)

### 🔌 Connection (`includes/db.php`)
We use **PDO (PHP Data Objects)**.
*   **Why?**: It supports "Prepared Statements", which protect against **SQL Injection**.
*   **Logic**: It reads `.env` for security, so database passwords are never hardcoded in the public files.

### 📝 SQL Queries Explained
1.  **SELECT**: Used in `ProductDAO.php` to fetch the catalog.
    - `SELECT * FROM products WHERE id = ?`
    - The `?` is a placeholder. PDO safely injects the ID here later.
2.  **INSERT**: Used in `UserDAO.php` to register users.
    - `INSERT INTO users (email, password) VALUES (?, ?)`
3.  **UPDATE**: Used in `CartDAO.php` to change quantities.
4.  **DELETE**: Used during checkout to clear the cart after a successful order.

---

## 🔐 5. Security & Authentication

1.  **Hashing**: We never store plain passwords. Use `password_hash()` for encryption and `password_verify()` for login.
2.  **Validation**: `libs/Core/Validation.php` checks if emails are valid and if records exist in the DB before performing actions.
3.  **Transactions**: In `OrderDAO.php`, we use `BEGIN` and `COMMIT`.
    - **Why?**: If payment fails halfway, the entire order is "rolled back" so the user isn't double-charged.

---

## 🌐 6. Frontend to Backend (AJAX Flow)

1.  **User Action**: Clicks "Add to Cart" in `views/product-detail.view.php`.
2.  **JavaScript**: `ecommerce.js` catches the click, prevents page reload, and uses `fetch()` to send data to `/ajax-cart`.
3.  **Controller**: `Controller_Ajax_Cart` processes the request and sends a JSON response: `{"success": true}`.
4.  **Browser**: JS receives the response and updates the "Cart Count" badge instantly.

---

## 🎓 7. Technical Interview Q&A

### Q1: What is the benefit of your MVC structure?
> *Answer*: It separates the **Business Logic** (Controllers) from the **Data** (Models) and the **UI** (Views). This makes the code modular, easier to test, and allows designers and developers to work on same project without stepping on each other's toes.

### Q2: How do you handle scalability?
> *Answer*: By using an **Autoloader** and **Class-based Controllers**, adding a new feature is as simple as creating one new class file. The engine handles the rest automatically.

### Q3: Why PostgreSQL over MySQL?
> *Answer*: PostgreSQL handles complex relationships and large-scale analytical queries (like my dashboard charts) more efficiently. It also has better support for JSON and advanced data types.

---

## 🚀 8. Production-Ready Improvements
1.  **Middleware**: Add a layer to check if a user is logged in before they even reach the controller.
2.  **Asset Minification**: Combine all CSS/JS into one file to speed up loading.
3.  **Service Layer**: Move heavy calculations (like Tax and Shipping) out of the Controller into a separate "Service" class.
4.  **Unit Testing**: Use PHPUnit to test calculation logic automatically.
5.  **Environment Sync**: Use Docker to ensure the app runs the same way on every machine.
