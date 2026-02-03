# Shipping AJAX Troubleshooting Guide

## Issue: JSON data not appearing in Network tab when changing shipping

### Step-by-Step Debugging:

#### 1. **Check if Cart is Empty**
**Problem:** AJAX endpoint returns error if cart is empty
**Solution:**
- Add items to cart first
- Navigate to checkout
- The cart must have at least 1 item

```bash
# Test: Open browser console and check:
sessionStorage.getItem('cart')  # Should NOT be null
```

#### 2. **Verify AJAX File Path**
**Problem:** Wrong file path to `ajax-checkout.php`
**Current:** `ajax-checkout.php` (relative path)
**Location:** `php/ajax-checkout.php`

**Test:** Open this URL directly:
```
http://localhost/E-commerce-website/php/ajax-checkout.php
```

You should see: **JSON error about empty cart** (if cart is empty)

#### 3. **Check Console for JavaScript Errors**
**Press F12 → Console tab**

Look for:
- ❌ "Uncaught ReferenceError"
- ❌ "Failed to fetch"
- ❌ "404 Not Found"
- ✅ "Initializing shipping options" (Good!)
- ✅ "Shipping option changed to: express" (Good!)

#### 4. **Verify Network Tab**
**Press F12 → Network tab → Filter by Fetch/XHR**

When you change shipping, you should see:
- **Request URL:** `.../ajax-checkout.php`
- **Method:** `POST`
- **Status:** `200` (or `400` if error)
- **Response:** JSON data

If you DON'T see any request, the event listener is not firing!

#### 5. **Check Browser Console Output**

Expected output when page loads:
```javascript
Initializing shipping options, found: 4 radio buttons
Initial shipping option checked: standard
Updating shipping method to: standard
```

Expected output when changing shipping:
```javascript
Shipping option changed to: express
Updating shipping method to: express
Received shipping update response: {success: true, summary: {...}}
```

---

## Quick Fixes:

### Fix 1: Cart is Empty
```php
// Before testing, add items to cart
// Go to product listing, add at least 1 product
```

### Fix 2: Session Not Started
```php
// Check if checkout.php has this at the top:
session_start();
```

### Fix 3: AJAX File Not Found
**Verify file exists:**
```
c:\xampp\htdocs\E-commerce-website\php\ajax-checkout.php
```

### Fix 4: JavaScript Not Loaded
**Check footer.php has:**
```html
<script src="../js/ecommerce.js"></script>
```

---

## Testing Steps:

### Test 1: Manual AJAX Call
Open browser console on checkout page and run:

```javascript
fetch('ajax-checkout.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ shipping: 'express' })
})
.then(r => r.json())
.then(data => console.log('SUCCESS:', data))
.catch(err => console.error('ERROR:', err));
```

**Expected Result:**
- Success: `{success: true, summary: {...}}`
- Error: `{success: false, message: "Cart is empty"}`

### Test 2: Check Event Listener
Open console and run:

```javascript
const radios = document.querySelectorAll('input[name="shipping"]');
console.log('Found', radios.length, 'shipping options');

radios.forEach((r, i) => {
    console.log(`Radio ${i}:`, r.value, r.checked);
});
```

**Expected:** Should show 4 shipping options

### Test 3: Trigger Change Manually
```javascript
const express = document.getElementById('express');
if (express) {
    express.click();
    console.log('Clicked express shipping');
} else {
    console.error('Express radio not found!');
}
```

---

## Most Likely Causes:

1. **🛒 CART IS EMPTY** ← 80% chance this is the issue!
2. **📂 Wrong file path** in AJAX call
3. **🔧 Session not started** 
4. **❌ JavaScript error** preventing execution
5. **🌐 AJAX blocked by CORS** (unlikely on localhost)

---

## Final Verification:

### ✅ Checklist:
- [ ] Cart has at least 1 item
- [ ] On checkout page (`php/checkout.php`)
- [ ] Browser console open (F12)
- [ ] See "Initializing shipping options" message
- [ ] Click different shipping option
- [ ] See "Shipping option changed" message
- [ ] Network tab shows POST request
- [ ] Response shows JSON data
- [ ] Prices update on page

---

## Need More Help?

1. **Check browser console output** and share the messages
2. **Check Network tab** - is there a request to `ajax-checkout.php`?
3. **Verify cart has items** - add products first
4. **Take a screenshot** of console + network tab

---

**Created:** 2026-01-29
**File:** SHIPPING_DEBUG_GUIDE.md
