# 🎓 SECTION 6: AJAX & API ENDPOINTS (ENDPOINT EXPLANATION)

In this section, we explain the **backend code** that handles all AJAX requests from the frontend. These files don't show HTML; they return **JSON data**.

---

## 📄 File Name: `ajax-cart.php`

### 🔹 Purpose of This File
This is the **API endpoint** for all shopping cart operations. When you click "Add to Cart" or change a quantity, JavaScript calls this file in the background. It updates the `$_SESSION['cart']` and returns the new totals.

### 🔹 Technologies Used
- **PHP** (Backend processing)
- **Sessions** (Storing cart data)
- **JSON** (Returning data to Frontend)

### 📝 Line-by-Line Code Explanation

```php
<?php
// 1. Initialize session and data
session_start();
```
**Line 2:** Start session to access `$_SESSION['cart']`.

```php
require_once __DIR__ . '/../data.php';
```
**Line 3:** Include product data to verify prices and details.

```php
header('Content-Type: application/json; charset=utf-8');
```
**Line 5:** Tell the browser "I am returning JSON, not HTML". Very important for AJAX!

```php
function json_response(array $payload, int $code = 200): void {
    http_response_code($code);
    echo json_encode($payload);
    exit;
}
```
**Lines 7-11:** Helper function to send a JSON response and stop execution.
- `http_response_code($code)`: Sets status (200 for OK, 400 for error).
- `json_encode($payload)`: Converts PHP array to JSON string.
- `exit`: Stops the script immediately.

```php
function read_payload(): array {
    $raw = file_get_contents('php://input');
    if ($raw) {
        $decoded = json_decode($raw, true);
        if (is_array($decoded)) return $decoded;
    }
    return $_POST ?? [];
}
```
**Lines 13-20:** Helper to read data sent from JavaScript.
- `file_get_contents('php://input')`: Reads raw JSON data sent via `fetch()`.
- `json_decode`: Converts JSON string back to PHP array.

```php
function calc_summary(array $cart): array {
    $subtotal = 0;
    $count = 0;
    foreach ($cart as $it) {
        $qty = isset($it['quantity']) ? (int)$it['quantity'] : 0;
        $price = isset($it['price']) ? (int)$it['price'] : 0;
        $subtotal += ($price * $qty);
        $count += $qty;
    }
    ...
    return [
        'cartCount' => $count,
        'subtotal' => (int)round($subtotal),
        ...
    ];
}
```
**Lines 22-42:** Function to recalculate the entire cart total (Subtotal, Tax, Shipping).

```php
$payload = read_payload();
$action = isset($payload['action']) ? (string)$payload['action'] : 'summary';
```
**Lines 44-45:** Get the "action" (add, update, remove, or just get summary).

```php
if ($action === 'add') {
    // Logic to add item to $_SESSION['cart']
} elseif ($action === 'update') {
    // Logic to change quantity in $_SESSION['cart']
} elseif ($action === 'remove') {
    // Logic to delete item from $_SESSION['cart']
}
```
**Lines 54-80:** The core logic that modifies the session based on what the user did.

```php
$summary = calc_summary($_SESSION['cart']);
json_response([
    'success' => true,
    'summary' => $summary,
]);
```
**Lines 85-103:** Finally, calculate new totals and send them back to the frontend.

---

## 📄 File Name: `ajax-checkout.php`

### 🔹 Purpose of This File
Handles **dynamic checkout calculations**. When you change a shipping method (Standard vs Express) on the checkout page, this file calculates the new shipping cost, tax, and final total.

### 📝 Line-by-Line Code Explanation

```php
<?php
session_start();
require_once __DIR__ . '/../data.php';
```
**Lines 2-3:** Standard session and data includes.

```php
$payload = read_payload();
$method = isset($payload['shipping']) ? (string)$payload['shipping'] : 'standard';
```
**Lines 16-17:** Read the selected shipping method (e.g., 'express') sent via AJAX.

```php
$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    json_response(['success' => false, 'message' => 'Cart is empty'], 400);
}
```
**Lines 19-22:** Security check: If the cart is empty, you can't calculate a checkout!

```php
function calculate_shipping_cost($method, $subtotal) {
    if ($method === 'express') return min(700, $subtotal * 0.1);
    if ($method === 'white_glove') return min(1600, $subtotal * 0.05);
    ...
    return 350; // Default Standard
}
```
**Lines 24-41:** Logic to calculate shipping price based on the rules.

```php
$shipping = calculate_shipping_cost($method, $subtotal);
$tax = ($subtotal + $shipping) * 0.18;
$total = $subtotal + $shipping + $tax;
```
**Lines 74-79:** Perform the final math.

```php
json_response([
    'success' => true,
    'summary' => [
        'subtotal' => $subtotal,
        'shipping' => $shipping,
        'tax' => $tax,
        'total' => $total
    ]
]);
```
**Lines 81-94:** Send the updated math back to the browser so the UI updates instantly.

---

# 🎓 SECTION 7: PAGE LOGIC (PHP + HTML)

These files use PHP to **render the page** and display data to the user.

---

## 📄 File Name: `product-detail.php`

### 🔹 Purpose of This File
Displays details for a **single product**. It takes an `ID` from the URL, finds that product in `data.php`, and shows its images, price, and description.

### 📝 Line-by-Line Code Explanation

```php
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
```
**Line 5:** Get product ID from URL (e.g., `product-detail.php?id=2`).
- `(int)`: Forces it to be a number (Security: prevents SQL injection/XSS).

```php
$product = $products[$product_id] ?? null;
```
**Line 6:** Find the product in our `$products` array using that ID.

```php
if (!$product) {
    echo '<p>Product not found</p>';
    exit;
}
```
**Lines 8-12:** Error handling: If ID doesn't exist, stop and show error.

```php
$productImages = $product['images'] ?? [$product['image']];
```
**Line 19:** Prepare the gallery images. If a product has many images, use them; otherwise, use the main one.

```php
<form method="POST" action="cart.php" class="add-to-cart-form">
    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
    ...
    <button type="submit">Add to Cart</button>
</form>
```
**Lines 80-94:** The shopping form. 
- **Wait!** Even though this is a standard form, our `ecommerce.js` will intercept this submission and turn it into an **AJAX** request!

---

## 📄 File Name: `cart.php`

### 🔹 Purpose of This File
Shows the user's shopping cart. It handles **fallback functionality** (if JavaScript is off, the cart still works using standard PHP POST).

### 📝 Line-by-Line Code Explanation

```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Handle add/update/remove
    // 2. Redirect back to cart.php
}
```
**Lines 5-43:** **Backend Logic.** If you click update/remove and JS fails, this PHP code handles the request and refreshes the page. This is called **Graceful Degradation**.

```php
foreach ($cartItems as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
```
**Lines 55-58:** Calculate totals to display on page load.

---

## 📄 File Name: `checkout.php`

### 🔹 Purpose of This File
The most complex page. It handles:
1. Shipping address collection.
2. Coupon application.
3. Order summarizes.
4. Saving the final order to `$_SESSION['orders']`.

---

# 🎓 SECTION 8: THE ENGINE (`ecommerce.js`)

**This file is 1,079 lines long.** It is the "brain" of your project.

### 🔹 Complete Logic Breakdown

#### 1. The Global Entry Point
```javascript
document.addEventListener('DOMContentLoaded', function () {
    initializeApp();
});
```
**Line 6:** Wait for the page to load, then start the app.

#### 2. The AJAX Generic Function
```javascript
async function postJson(url, data) {
    const res = await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    });
    return await res.json();
}
```
**Lines 24-35:** A reusable helper. It sends data to PHP and waits for the JSON response.

#### 3. "Add to Cart" Interception
```javascript
function initAddToCartAjax() {
    const forms = document.querySelectorAll('form.add-to-cart-form');
    forms.forEach((form) => {
        form.addEventListener('submit', function (e) {
            e.preventDefault(); // STOP the page from reloading
            ...
            postJson('ajax-cart.php', data); // Do it in background
        });
    });
}
```
**Lines 794-823:** This is how we make "Add to cart" feel instant. We **hijack** the HTML form, stop the reload (`e.preventDefault()`), and send the data via AJAX instead.

#### 4. Cart Page Live Updates
```javascript
if (e.target.classList.contains('qty-increase')) {
    const newQty = currentQty + 1;
    updateCartTotals(); // Visual update immediately
    postJson('ajax-cart.php', { action: 'update', ... }); // Tell server
}
```
**Lines 552-661:** When you click `+` or `-` in the cart:
1. **Frontend:** Updates the number instantly so user sees it.
2. **Backend:** AJAX tells PHP to update the session.
3. **Sync:** Both are now in sync.

#### 5. Real-Time Form Validation
```javascript
function validateEmailField(input) {
    if (!isValidEmail(input.value)) {
        showInputError(input, 'Please enter a valid email');
    }
}
```
**Lines 348-546:** Checks your email/password **while you type**. If it's wrong, it shows a red message instantly.

#### 6. Shipping Recalculation (Checkout)
```javascript
function updateOrderTotalAjax() {
    postJson('ajax-checkout.php', { shipping: method })
        .then(json => {
            taxEl.textContent = formatPrice(json.summary.tax);
            totalEl.textContent = formatPrice(json.summary.total);
        });
}
```
**Lines 898-934:** When you click a different shipping radio button, this function runs. It asks `ajax-checkout.php` for new math and updates the labels on the screen.

---

# 🎓 FINAL INTERVIEW TIPS

1. **Explain the Flow:** Always start with "The user clicks X, JavaScript intercepts it, sends an AJAX request to PHP, PHP updates the session, returns JSON, and JavaScript updates the UI."
2. **Mention Security:** Talk about `htmlspecialchars()` and `(int)` casting for product IDs.
3. **Mention UX:** Explain that AJAX is used to prevent page reloads, making the site feel fast (like an app).
4. **Graceful Degradation:** Mention that the `cart.php` still works with standard HTML POST if JavaScript is disabled.

---
**END OF PROJECT EXPLANATION**
