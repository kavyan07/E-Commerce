# E-Commerce Application Flow & Architecture

This document explains the complete flow of the **EasyCart** e-commerce application, detailing how the Frontend, Backend, and Session Management work together using PHP and AJAX.

## Project Overview

- **Technology Stack:** PHP, HTML, CSS, JavaScript (Vanilla JS with Fetch API)
- **State Management:** PHP Sessions (`$_SESSION`)
- **Interaction Model:** Hybrid (Traditional Pages + AJAX Interactions)
- **Key Feature:** No page reloads for cart actions (Add, Update, Remove)

---

## 1. User Authentication Flow

The authentication system manages user access and identity using PHP sessions.

### Signup Flow
1.  **Frontend (`signup.php`):**
    *   User fills in First Name, Last Name, Email, Phone, Password.
    *   **Client-side Validation:** JavaScript prevents submission if fields are invalid (e.g., weak password, invalid email).
2.  **Backend Processing:**
    *   Sanitizes inputs to prevent XSS.
    *   Validates data server-side (e.g., checks for duplicate email).
    *   **Session Storage:** Stores user data in `$_SESSION['users']` (Simulating a database).
    *   **Flash Message:** Sets `$_SESSION['flash_message']` = "Account created successfully".
3.  **Completion:**
    *   Redirects to `login.php`.

### Login Flow
1.  **Frontend (`login.php`):**
    *   User enters Email and Password.
2.  **Backend Processing:**
    *   Checks credentials against stored users in session.
    *   **Success:**
        *   Stores logged-in user info in `$_SESSION['user']`.
        *   Sets success flash message.
        *   Redirects to `index.php`.
    *   **Failure:** Shows error message on the same page.
3.  **Flash Messages:**
    *   `header.php` checks for `$_SESSION['flash_message']`.
    *   Outputs a JavaScript call (`showToast()`) to display the message.
    *   Removes the message from session immediately (display once).

---

## 2. Product Listing & Detail Flow

### Product Display
-   **Data Source:** `data.php` contains the array of products.
-   **Listing (`product-listing.php`):** Loops through the `$products` array to generate HTML cards.
-   **Detail (`product-detail.php?id=X`):** Loads specific product data based on the ID parameter.

### "Add to Cart" Interaction
This is where the user experience is enhanced with AJAX.

1.  **User Action:** Clicks "Add to Cart" on a product.
2.  **Frontend (AJAX Interception):**
    *   JavaScript (`ecommerce.js`) listens for the form submit event.
    *   `e.preventDefault()` stops the traditional form submission (page reload).
    *   **Fetch Request:** Sends a POST request to `ajax-cart.php` with `{ product_id, quantity }`.
3.  **Backend (`ajax-cart.php`):**
    *   Starts session.
    *   Validates product ID.
    *   **Logic:**
        *   If item exists in `$_SESSION['cart']` -> Increase quantity.
        *   If new -> Add item to `$_SESSION['cart']`.
    *   **Response:** Returns JSON with success status and new cart count.
4.  **Frontend Update:**
    *   Updates the Cart Badge (number in header) instantly.
    *   Shows a **Toast Notification**: "Product added to cart".

---

## 3. Cart Page Flow (AJAX & Dynamic Calculations)

The cart page allows managing items without reloading the entire page for every change.

### Loading the Cart
-   **Backend (`cart.php`):** Reads `$_SESSION['cart']` and renders initial HTML table.
-   **Calculations:** Computes Subtotal, Tax, Shipping, and Total server-side for initial render.

### Quantity Update (AJAX)
1.  **User Action:** Clicks (+) or (−) button.
2.  **Frontend:**
    *   JavaScript optimistically updates the input field number.
    *   Sends AJAX POST to `ajax-cart.php` (`action: update`).
3.  **Backend:**
    *   Updates `$_SESSION['cart'][id]['quantity']`.
    *   Recalculates specific Item Total and Cart Summary (Subtotal, Tax, Total).
    *   Returns new values in JSON.
4.  **Frontend Update:**
    *   Updates "Item Total" price for that row.
    *   Updates the "Order Summary" box (Subtotal, Tax, Total).

### Remove Item (AJAX)
1.  **User Action:** Clicks "Remove".
2.  **Frontend:**
    *   Fades out the row (visual feedback).
    *   Sends AJAX POST to `ajax-cart.php` (`action: remove`).
3.  **Backend:**
    *   `unset($_SESSION['cart'][id])`.
    *   Recalculates totals.
4.  **Frontend:**
    *   Removes row from DOM.
    *   Updates Summary totals.
    *   If cart becomes empty, acts accordingly (shows "Empty Cart" message).

---

## 4. Checkout & Calculation Logic

The checkout page aggregates all financial data and handles the final transaction steps.

### Calculations (Server-Side)
To ensure security and accuracy, all math is done in PHP:
1.  **Subtotal:** Sum of (Price × Quantity) for all items.
2.  **Shipping:**
    *   Standard: ₹350
    *   Express: Higher of ₹700 or 10%
3.  **Tax:** 18% of (Subtotal + Shipping).
4.  **Total:** Subtotal + Shipping + Tax.

### Coupon System
-   **Codes:** SAVE5 (5%), SAVE10 (10%), SAVE15 (15%).
-   **Flow:**
    1.  User enters code and clicks Apply.
    2.  Form POSTs to `checkout.php`.
    3.  Backend matches code.
    4.  If valid, stores coupon in `$_SESSION['coupon']`.
    5.  Recalculates Total with Discount.
    6.  Reloads page with success message.

---

## 5. Clean Architecture & Best Practices

### File Separation
-   **Views:** `index.php`, `cart.php` (Structure & Layout)
-   **Logic/Actions:** `ajax-cart.php`, `ajax-checkout.php` (Handle requests, return JSON)
-   **Shared Components:** `header.php`, `footer.php` (Reusability)
-   **Data:** `data.php` (Single source of truth for products)

### Security Measures
-   **Input Validation:** All POST data is sanitized and type-casted (e.g., `(int)$quantity`).
-   **Session Security:** `session_start()` used consistently; sensitive data not exposed in URL.
-   **CSRF Prevention:** (Implicit via internal session checks).
-   **AJAX Safety:** Backend returns 400/404 headers for invalid requests.

---

## 6. Summary of User Experience

1.  **Smooth:** User adds items and updates quantities without screen flicker (AJAX).
2.  **Feedback:** Instant visual confirmation via Toasts and Badge updates.
3.  **Persistent:** Cart items remain if user navigates away or accidentally closes tab (Session).
4.  **Secure:** Prices and totals are always master-calculated on the server, preventing client-side price tampering.
