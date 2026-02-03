# AJAX Data Flow Explanation

This document explains exactly how data travels from your browser (JavaScript) to your server (PHP) and back again without reloading the page.

---

## The High-Level Flow (The "Round Trip")

1.  **Trigger**: User clicks a button (e.g., "Add to Cart") or selects a shipping option.
2.  **Request**: JavaScript captures the data, packs it into a **JSON string**, and sends it via `fetch()`.
3.  **Receiver**: The PHP file reads the raw input, decodes the JSON, and performs logic (like updating the Session).
4.  **Response**: The PHP file packs the result (new totals, success message) into a **JSON string** and sends it back.
5.  **Update**: JavaScript receives the response and updates the HTML (DOM) dynamically.

---

## 1. The Browser Side (JavaScript)
In `js/ecommerce.js`, we use a helper function called `postJson` to handle all our AJAX requests.

### **Example: Updating Shipping in Checkout**
**When:** You select a different shipping radio button.
**Code:**
```javascript
// Step A: Capture the selection
const method = selectedRadio.value; 

// Step B: Send data to server
postJson('ajax-checkout.php', { shipping: method })
    .then((json) => {
        // Step E: Update the UI with response data
        shippingEl.textContent = formatPrice(json.summary.shipping);
        totalEl.textContent = formatPrice(json.summary.total);
    });
```

**What exactly is sent?**
The browser sends a POST request with a "Payload" that looks like this:
`{"shipping": "express"}`

---

## 2. The Transmission (The Bridge)
*   **Method**: `POST` (used because we are sending/modifying data).
*   **Headers**: `Content-Type: application/json`. This tells PHP, "I'm not sending a regular form; I'm sending a raw JSON object."

---

## 3. The Server Side (PHP)
Since we sent **JSON** instead of a regular form, PHP's `$_POST` array will be empty. We have to read the raw input.

### **Reading the Data (`php/ajax-cart.php` or `php/ajax-checkout.php`)**
We use a special function to read the data:
```php
function read_payload(): array {
    // 1. Get the raw text from the "input stream"
    $raw = file_get_contents('php://input'); 
    
    // 2. Turn the JSON string back into a PHP Array
    return json_decode($raw, true) ?? []; 
}

$payload = read_payload(); // Now contains ['shipping' => 'express']
```

---

## 4. The Processing & Response (PHP)
After reading the data, PHP updates the **Session** and calculates new totals.

**Sending the Response:**
```php
// Step D: Pack data for the browser
$response = [
    'success' => true,
    'summary' => [
        'subtotal' => 1000,
        'shipping' => 80,
        'total' => 1080
    ]
];

// Convert to JSON and send
echo json_encode($response);
```

---

## 5. Completing the Cycle (JavaScript)
Back in `js/ecommerce.js`, the `.then()` block receives the `$response` object.

```javascript
.then((json) => {
    // Accessing the data PHP sent us
    const s = json.summary; 
    
    // Changing the text on the screen
    document.getElementById('totalAmount').textContent = "Rs. " + s.total;
    
    // Showing a notification
    showToast("Total Updated!", "success");
})
```

---

## Summary Table for Viva

| Feature | Browser (JS) | Server (PHP) |
| :--- | :--- | :--- |
| **Data Format** | JSON String | PHP Array |
| **Tool Used** | `fetch()` | `file_get_contents('php://input')` |
| **Logic** | Event Listeners | Session & Calculations |
| **Output** | DOM Manipulation | `json_encode()` |
| **Efficiency** | No Page Reload | Fast JSON processing |

### **Why use AJAX?**
If we didn't use AJAX, every time a user clicked "Add to Cart" or changed a shipping option, the whole page would flicker and reload. AJAX makes the website feel like a modern "App" (Smooth and Instant).
