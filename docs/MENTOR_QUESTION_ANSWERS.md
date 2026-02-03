# Mentor Viva - Frequently Asked Questions & Answers

This document provides answers and explanations for common modification requests your mentor might ask during the project review.

---

## 1. How do I update a coupon discount (e.g., change 15% to 20%)?

### **Question:** "Your SAVE15 coupon gives 15% off. How can you change it to give 20% instead?"

### **Answer:**
To update the discount percentage, you need to modify the `$validCoupons` array in two PHP files.

**Step 1: Modify `php/checkout.php`**
Find line **72** and line **148** (inside the `POST` handling). Change the value associated with `SAVE15`.
```php
// Before
['SAVE5' => 5, 'SAVE10' => 10, 'SAVE15' => 15]

// After
['SAVE5' => 5, 'SAVE10' => 10, 'SAVE15' => 20]
```

**Step 2: Modify `php/ajax-checkout.php`**
Find line **106** and update the array there to match.
```php
$validCoupons = ['SAVE5' => 5, 'SAVE10' => 10, 'SAVE15' => 20];
```

### **Explanation:**
The system uses an associative array where the **key** is the coupon code and the **value** is the discount percentage. By changing the value, the calculation logic (which uses `($couponPercent / 100)`) automatically applies the new rate. We update both files to ensure consistency between the initial page load and AJAX updates.

---

## 2. How do I change a coupon code (e.g., change SAVE15 to SAVE12)?

### **Question:** "Can you change the coupon code 'SAVE15' to 'SAVE12' while keeping the logic same?"

### **Answer:**
You need to update the key in the `$validCoupons` array and the placeholder in the HTML.

**Step 1: Update PHP Logic**
In `php/checkout.php` (lines 72, 148) and `php/ajax-checkout.php` (line 106):
```php
// Before
['SAVE5' => 5, 'SAVE10' => 10, 'SAVE15' => 15]

// After
['SAVE5' => 5, 'SAVE10' => 10, 'SAVE12' => 15]
```

**Step 2: Update HTML Placeholder**
In `php/checkout.php` (line 412), update the input placeholder so users know the new code:
```html
<input type="text" name="coupon" placeholder="Enter promo code (SAVE5 / SAVE10 / SAVE12)">
```

### **Explanation:**
The PHP code checks if `isset($validCoupons[$couponCode])`. If you change the key from `SAVE15` to `SAVE12`, the scripts will now look for the literal string "SAVE12" entered by the user.

---

## 3. How do I change the GST percentage?

### **Question:** "Currently GST is 18%. How would you change it to 12% or 5%?"

### **Answer:**
The tax calculation is performed in two places using a multiplier.

**Step 1: Modify `php/checkout.php`**
Find line **85** and line **158**:
```php
// For 12% GST
$tax = (int) round($baseForTax * 0.12);
```

**Step 2: Modify `php/ajax-checkout.php`**
Find line **118**:
```php
$tax = (int) round($baseForTax * 0.12);
```

### **Explanation:**
The variable `$baseForTax` represents the amount after adding shipping and subtracting the coupon. Multiplying by `0.18` represents 18%. To change it, you simply update the decimal multiplier (e.g., `0.12` for 12%).

---

## 4. How do I modify Shipping Costs?

### **Question:** "How can we change the Flat Shipping fee for Standard Delivery from ₹40 to ₹60?"

### **Answer:**
Modify the `calculate_shipping_cost` function.

**Step 1: Modify `php/checkout.php`** (line 26) and **`php/ajax-checkout.php`** (line 30):
```php
case 'standard':
    return 60; // Changed from 40
```

### **Explanation:**
The shipping logic is centralized in a `switch` statement inside a helper function. This ensures that whenever "standard" shipping is selected, the returned cost is updated throughout the application.

---

## 5. How do I change a Product's Shipping Category?

### **Question:** "What if a product currently marked as 'Express' needs to be 'Freight' instead?"

### **Answer:**
Go to `data.php` and update the `shipping_type` for that specific product.

**Example in `data.php`:**
```php
// Before (Express)
'shipping_type' => 2

// After (Freight)
'shipping_type' => 1
```

### **Explanation:**
The system uses numeric IDs for shipping types: `1` for **Freight** and `2` for **Express**. The checkout logic (in `php/checkout.php`) checks these IDs to decide which shipping methods to enable or disable for the entire cart.

---

## 6. Where is the Cart data stored?

### **Question:** "Where does the application store the products added to the cart?"

### **Answer:**
It is stored in the **PHP Superglobal `$_SESSION['cart']`**.

### **Explanation:**
Since we are not using a database in this project, we use Server-Side Sessions. This allows the data to persist as the user navigates from the home page to the product page and finally to the checkout page. When `session_start()` is called, we can access this array anywhere in our PHP scripts.
