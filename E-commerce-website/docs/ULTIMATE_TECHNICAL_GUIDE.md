# 🎓 Ultimate Architect & Interview Guide: EasyCart E-Commerce

Welcome to the **Master Technical Guide** for the EasyCart project. This document is designed for both beginners and senior architects to understand every single nut and bolt of this application.

---

## 🏛️ 1. Project Architecture (Step-by-Step)

The project uses a **Modern MVC (Model-View-Controller)** pattern combined with a **Front Controller Pattern**, similar to how Enterprise frameworks like Magento or Laravel work.

### Real-World Analogy: The Restaurant
*   **The Customer (Browser)**: Sits at the table and asks for a menu.
*   **The Host (index.php)**: Greets the customer and directs them to a specific table based on what they want.
*   **The Waiter (Controller)**: Takes the order, goes to the kitchen, and brings back the food.
*   **The Chef (Model/DAO)**: Knows exactly where the ingredients (Database) are and prepares the meal.
*   **The Recipe (SQL Query)**: The instructions the Chef follows to get the ingredients.
*   **The Plating (View/Template)**: How the food is presented on the plate before the waiter brings it to you.

### Technical Flow:
1.  **Request**: User visits `localhost/products`.
2.  **Routing**: `.htaccess` sends this to `index.php?route=products`.
3.  **Bootstrapping**: `index.php` starts the session, loads the database, and registers the **Autoloader**.
4.  **Dispatching**: `index.php` looks at the route and realizes it needs `Controller_Product_Listing`.
5.  **Execution**: The controller talks to the `Model_Product_Collection` to get data from PostgreSQL.
6.  **Rendering**: The controller passes data to the `View_Default`, which fills the `.phtml` templates and sends the final HTML to the user.

---

## 📁 2. Folder Structure & Purpose

| Directory | Purpose | Analogy |
| :--- | :--- | :--- |
| **`/api`** | JSON endpoints for the frontend. | The "Takeaway" window. |
| **`/app/Design/view`** | The actual HTML templates (`.phtml`). | The "Plating Cabinet". |
| **`/controllers`** | Legacy route handlers (scripts). | Old hand-written notes. |
| **`/css`** | Styling for each specific page. | The restaurant's decor. |
| **`/database`** | SQL scripts to build the DB. | The blueprints of the kitchen. |
| **`/includes`** | Global helpers (DB link, Header, Footer). | The shared tools (spoons, forks). |
| **`/js`** | Interactive logic (Cart AJAX). | The automatic coffee machine. |
| **`/libs`** | **The Engine**: MVC Classes (Controllers, Models, Views). | The kitchen equipment. |
| **`/public`** | Images and static assets. | The display window. |
| **`/src`** | Data Access Objects (Old bridge logic). | The inventory logbook. |
| **`/views`** | View templates (older version). | The old menu cards. |

---

## 📄 3. File-by-File Line Explanation

### 🛠️ Core File: `index.php` (The Brain)
```php
<?php
2: session_start(); 
```
*   **Line 2**: `session_start()` tells PHP to start tracking the user. It creates a "cookie" in the browser so the server remembers who you are (e.g., your cart items).

```php
5: define('ROOT_PATH', __DIR__);
```
*   **Line 5**: Creates a constant named `ROOT_PATH`. `__DIR__` is the current folder. This helps us find files without getting lost in subfolders.

```php
8: require_once ROOT_PATH . '/includes/db.php';
```
*   **Line 8**: `require_once` brings in the database connection code. `once` means if we already loaded it, don't do it again (prevents errors).

```php
13: Core_Autoload::register();
```
*   **Line 13**: This is the "Magic Loader". Instead of writing 100 `include` lines, this function automatically finds the file for any class we use.

---

### 🪄 The Autoloader: `libs/Core/Autoload.php`
```php
9: $path = str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
```
*   **How it works**: If you use a class named `Model_Product_Resource`, it changes `_` to `/`. 
*   **Result**: It looks for a file at `libs/Model/Product/Resource.php`. 
*   **Why**: It keeps the project organized and professional.

---

### 🏗️ The Base Controller: `libs/Controller/Abstract.php`
*   **`abstract class`**: This is a "Blueprint". You cannot create a `Controller_Abstract` object. You MUST create a specific class (like `Controller_Home`) that **extends** it.
*   **`execute()`**: Every controller must have this. It's the "Start" button for that page.
*   **`redirect($url)`**: A helper to jump to another page (e.g., after login).

---

### 📦 The Data Model: `libs/Model/Abstract.php`
*   **`getData($key)`**: A way to get info from the model.
*   **`__call()` (Magic Method)**: This is "black magic" in PHP. It allows you to call functions like `getName()` even if they don't exist! It automatically looks for a key called 'name' in the data array.

---

## 💾 4. Database & SQL (PostgreSQL)

### Connection Logic (`includes/db.php`)
We use **PDO (PHP Data Objects)**. 
*   **Why?**: It's safer. It prevents **SQL Injection** (when hackers try to type code into your search bar).
*   **DSN**: The connection string that tells PHP: "Go to this IP, use this Port, and open this Database".

### Common Queries:
1.  **SELECT**: "Hey DB, show me all products where category = 'Shoes'."
2.  **INSERT**: "Hey DB, save this new Order with these 3 items."
3.  **UPDATE**: "Hey DB, change the price of Item #5 to 999."
4.  **DELETE**: "Hey DB, remove the item from the cart."

---

## 🔐 5. Session & Authentication

1.  **Signup**: User enters details → `password_hash()` encrypts it → Saved to `users` table.
2.  **Login**: User enters email/password → `password_verify()` checks if they match the encrypted version.
3.  **Session**: If match, we save `$_SESSION['user_id'] = 123`. 
    - On every page, we check if `user_id` is set. If not, they are a "Guest".

---

## 🌐 6. API & JavaScript Flow

### AJAX (Asynchronous JavaScript and XML)
*   When you click "Add to Cart", the page **does not reload**.
*   **JS (`ecommerce.js`)**: Sends a "background message" (Fetch) to `ajax-cart`.
*   **Controller**: Updates the database.
*   **JS**: Receives a "Success" message and updates the little "Cart Count" bubble.

---

## 🚀 7. Interview Preparation

### How to describe this project:
"I built a scalable e-commerce platform using PHP 8 and PostgreSQL. I implemented a custom MVC framework featuring a Front Controller and a PSR-compliant Autoloader. For data persistence, I followed the DAO (Data Access Object) pattern and utilized PDO for secure database interactions. The frontend features a responsive design with AJAX-driven cart management to ensure a smooth user experience."

### 5 Common Interview Questions:
1.  **Q: What is a Front Controller?** 
    - *A: It's a single entry point (index.php) that handles all requests, making the app more secure and easier to manage.*
2.  **Q: Why use `password_hash()` instead of MD5?**
    - *A: MD5 is weak and fast to crack. `password_hash()` uses bcrypt/argon2 which is slow and includes a 'salt' to stop rainbow table attacks.*
3.  **Q: What is the benefit of an Abstract class?**
    - *A: It allows us to define shared logic (like `redirect`) once, and force child classes to implement specific methods (like `execute`).*
4.  **Q: How do you prevent SQL Injection?**
    - *A: By using **Prepared Statements** with PDO. We send the query and the data separately so the data is never treated as code.*
5.  **Q: What is the difference between `require` and `include`?**
    - *A: `require` will stop the script if the file is missing (fatal error). `include` only gives a warning.*

---

## 🏗️ 8. Improvements for Production
1.  **Caching**: Use Redis to store product lists so we don't hit the DB every time.
2.  **Validation**: Add server-side validation for all forms (not just JS).
3.  **JWT**: Use JSON Web Tokens for API security instead of just sessions.
4.  **Error Logging**: Use a tool like Sentry to track bugs in real-time.
5.  **Docker**: Wrap the app in a container so it runs exactly the same on any server.
