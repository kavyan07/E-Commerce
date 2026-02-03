# Phase 3 Testing Checklist

## Pre-Testing Setup

- [ ] Navigate to `http://localhost/E-commerce-website/`
- [ ] Check browser console for any JavaScript errors (F12)
- [ ] Check Network tab to confirm `ecommerce.js` and `phase3-interactions.css` are loaded
- [ ] Clear browser cache if changes not appearing

---

## 1. LOGIN PAGE VALIDATION ✅

**File**: `/php/login.php`

### Email Field Tests
- [ ] Leave email empty, click elsewhere → Error: "Email is required"
- [ ] Enter "notanemail" → Error: "Please enter a valid email address"
- [ ] Enter "test@example.com" → Error clears
- [ ] Start editing email again → Error clears immediately

### Password Field Tests
- [ ] Leave password empty, click elsewhere → Error: "Password is required"
- [ ] Enter password → Error clears
- [ ] Clear password → Error returns on blur

### Form Submission Tests
- [ ] Click Login with empty fields → Form doesn't submit, errors appear
- [ ] Enter valid email but empty password → Form doesn't submit
- [ ] Enter email and password → Form submits to server
- [ ] Test with localStorage user if signup done previously

### Visual Tests
- [ ] Invalid inputs have red border
- [ ] Invalid inputs have light red background (#fef2f2)
- [ ] Error messages appear below inputs in red text
- [ ] Error messages animate in smoothly

---

## 2. SIGNUP PAGE VALIDATION ✅

**File**: `/php/signup.php`

### First Name Field
- [ ] Leave empty, click elsewhere → Error: "First name is required"
- [ ] Enter "J", click elsewhere → Error: "First name must be at least 2 characters"
- [ ] Enter "John" → Error clears

### Last Name Field
- [ ] Leave empty, click elsewhere → Error: "Last name is required"
- [ ] Enter "D", click elsewhere → Error: "Last name must be at least 2 characters"
- [ ] Enter "Doe" → Error clears

### Email Field
- [ ] Leave empty → Error: "Email is required"
- [ ] Enter "invalidemail" → Error: "Please enter a valid email address"
- [ ] Enter "john@example.com" → Error clears
- [ ] If email exists in localStorage → Error on submit: "User with this email already exists"

### Phone Field
- [ ] Leave empty → Error: "Phone number is required"
- [ ] Enter "123" → Error: "Please enter a valid phone number (10-13 digits)"
- [ ] Enter "9876543210" → Error clears
- [ ] Enter "98-765-43210" → Error clears (dashes accepted)
- [ ] Enter "+91 98765 43210" → Error clears (spaces accepted)

### Password Field
- [ ] Leave empty → Error: "Password is required"
- [ ] Enter "abc123" (no uppercase) → Error: "Password must contain uppercase letter and number"
- [ ] Enter "Abcdef" (no number) → Error: "Password must contain uppercase letter and number"
- [ ] Enter "abcd" (too short) → Error: "Password must be at least 6 characters"
- [ ] Enter "MyPass123" → Error clears

### Confirm Password Field
- [ ] Leave empty → Error: "Please confirm password"
- [ ] Enter different password → Error: "Passwords do not match"
- [ ] Match password field → Error clears
- [ ] When both passwords change, error updates in real-time

### Terms Checkbox
- [ ] Try to submit without checking → Alert: "Please agree to the Terms and Conditions"
- [ ] Check checkbox → Can submit form

### Form Submission Tests
- [ ] All fields empty → Multiple errors appear
- [ ] Mix of valid/invalid → Only invalid fields show errors
- [ ] All valid → Form can submit
- [ ] First time signup → "Account created successfully" message
- [ ] Then redirects to login.php after 1.5 seconds

### Visual Tests
- [ ] All invalid fields have red borders
- [ ] Error messages stack correctly below each field
- [ ] Form is responsive on mobile
- [ ] Label text is clearly visible

---

## 3. CHECKOUT PAGE VALIDATION ✅

**File**: `/php/checkout.php`

### Shipping Address Section

#### Full Name Field
- [ ] Leave empty, click elsewhere → Error appears
- [ ] Enter "JD" → Error: "Name must be at least 3 characters"
- [ ] Enter "John Doe" → Error clears

#### Email Field
- [ ] Leave empty → Error appears
- [ ] Enter "notanemail" → Error: "Please enter a valid email address"
- [ ] Enter valid email → Error clears

#### Phone Field
- [ ] Leave empty → Error appears
- [ ] Enter "123" → Error: "Please enter a valid phone number (10-13 digits)"
- [ ] Enter "9876543210" → Error clears

#### Street Address Field
- [ ] Leave empty → Error appears
- [ ] Enter short text "123" → Error clears (3+ chars)
- [ ] Enter full address → No error

### Shipping Options Section

#### Visual Highlighting
- [ ] Standard Shipping has blue border and light blue background by default
- [ ] Click "Express Shipping" → Highlight moves to Express option
- [ ] Visual highlight is smooth animation
- [ ] Only one option highlighted at a time

#### Radio Button Behavior
- [ ] Standard Shipping is checked by default
- [ ] Click Express label → Express becomes checked
- [ ] Visual and checkbox state stay synchronized

### Place Order Button

#### Form Validation on Submit
- [ ] Click "Place Order" with empty fields → Form doesn't submit, shows errors
- [ ] Fill all fields correctly → Form can submit
- [ ] All invalid fields get error styling

### Visual Design
- [ ] Shipping options have adequate padding
- [ ] Text is readable on highlighted state
- [ ] Form sections are properly separated
- [ ] Mobile responsive layout

---

## 4. CART PAGE INTERACTIONS ✅

**File**: `/php/cart.php`

### Add Product to Cart First
- [ ] Navigate to `/php/product-listing.php`
- [ ] Click "View details" on a product
- [ ] Set quantity and click "Add to Cart"
- [ ] Navigate to `/php/cart.php`
- [ ] Product appears in cart

### Quantity Controls

#### Quantity Decrease Button (-)
- [ ] Item starts with quantity 1+
- [ ] Click - button → Quantity decreases by 1
- [ ] Item Total updates correctly (price × new qty)
- [ ] Subtotal updates
- [ ] Shipping updates if applicable
- [ ] Tax updates
- [ ] Total updates
- [ ] Click - until quantity is 1 → Can't go below 1
- [ ] Form submits and page reloads

#### Quantity Increase Button (+)
- [ ] Click + button → Quantity increases by 1
- [ ] Item Total updates correctly
- [ ] All totals recalculate
- [ ] Can increase to any number
- [ ] Form submits and page reloads

#### Direct Quantity Input
- [ ] Click on quantity input field
- [ ] Change number directly (e.g., 3)
- [ ] Click elsewhere or press Enter → Form submits
- [ ] Item Total updates
- [ ] All totals recalculate

### Price Recalculation

#### Item Total Calculation
- [ ] Product price: ₹1000, Qty: 2 → Item Total: ₹2000
- [ ] Change to Qty: 3 → Item Total: ₹3000
- [ ] All displayed correctly with currency symbol

#### Subtotal Update
- [ ] Sum of all item totals = Subtotal
- [ ] Add second product → Subtotal increases
- [ ] Change quantity → Subtotal updates

#### Shipping Calculation
- [ ] If Subtotal > ₹999 → Shipping: FREE
- [ ] If Subtotal ≤ ₹999 → Shipping: ₹299
- [ ] Verify correct amount displays

#### Tax Calculation
- [ ] Tax = Subtotal × 18%
- [ ] Updates when subtotal changes
- [ ] Formatted correctly

#### Grand Total
- [ ] Total = Subtotal + Shipping + Tax
- [ ] Updates when any component changes
- [ ] All amounts in currency format

### Remove Items

#### Remove Button
- [ ] Find "Remove" button on cart item
- [ ] Click "Remove" → Confirmation dialog appears
- [ ] Click "Cancel" → Item stays in cart
- [ ] Click "Remove" again → Confirmation
- [ ] Click "OK" → Form submits
- [ ] Item removed from cart display
- [ ] All totals recalculate

#### Empty Cart State
- [ ] Remove all items from cart
- [ ] "Your cart is empty" message appears
- [ ] "Start Shopping" button visible
- [ ] Click "Start Shopping" → Goes to product listing

### Visual Design

#### Item Display
- [ ] Product image displays correctly
- [ ] Product name shows
- [ ] Price shows per unit
- [ ] Quantity controls are properly spaced
- [ ] Item Total aligns to right
- [ ] Remove button is visible

#### Price Summary
- [ ] Summary section shows all calculations
- [ ] Labels and amounts aligned properly
- [ ] Currency formatting consistent
- [ ] "Proceed to Checkout" button visible and clickable

---

## 5. PRODUCT DETAIL PAGE ✅

**File**: `/php/product-detail.php`

### Quantity Controls

#### Quantity Display
- [ ] Initial quantity shows as "1"
- [ ] Quantity value displayed prominently

#### Decrease Button
- [ ] Click - button → Quantity decreases by 1
- [ ] Can't go below 1 (stays at 1)
- [ ] All UI updates correctly

#### Increase Button
- [ ] Click + button → Quantity increases by 1
- [ ] Can increase to any number
- [ ] No maximum limit

#### Direct Input (if present)
- [ ] Can type quantity directly
- [ ] Minimum value enforced

### Add to Cart

#### Add to Cart Button
- [ ] Shows correct quantity
- [ ] Clicking submits form to cart.php
- [ ] Form has action="cart.php" and action="add"
- [ ] Quantity updates hidden form field

#### Buy Now Button
- [ ] Shows correct quantity
- [ ] Clicking submits to cart.php
- [ ] Then redirects to checkout

### Visual Elements

#### Product Images
- [ ] Main image displays
- [ ] Image is clear and properly sized
- [ ] Fade transition smooth when loading

#### Product Information
- [ ] Name displays correctly
- [ ] Price shows in currency format
- [ ] Original price and discount show
- [ ] Rating stars visible
- [ ] Description visible

#### Delivery Info
- [ ] Free delivery info displays
- [ ] Correct details shown

---

## 6. CHECKOUT PAGE - SHIPPING SELECTION ✅

**File**: `/php/checkout.php`

### Visual Highlighting

#### Default State
- [ ] Standard Shipping has blue border on load
- [ ] Light blue background visible
- [ ] Text clearly readable

#### Click Express Shipping
- [ ] Blue border moves to Express option
- [ ] Light blue background moves to Express
- [ ] Standard option returns to normal
- [ ] Transition is smooth

#### Hover States
- [ ] Hovering over option shows subtle border change
- [ ] Not too bright, still readable

### Radio Button States

#### Initial State
- [ ] Standard Shipping radio is checked

#### Click Express
- [ ] Express radio becomes checked
- [ ] Standard becomes unchecked

#### Click Standard Again
- [ ] Standard radio becomes checked
- [ ] Visual highlight matches

### Styling Details

#### Padding and Spacing
- [ ] Options have comfortable padding (1.25rem)
- [ ] Gap between options (margin-bottom: 1rem)
- [ ] Mobile: reduced padding on small screens

#### Typography
- [ ] Bold text for shipping type
- [ ] Smaller text for description
- [ ] Good contrast for readability

---

## 7. PRODUCT LISTING PAGE ✅

**File**: `/php/product-listing.php`

### Product Count Display

#### Initial Load
- [ ] Product count shows (e.g., "Showing 5 products")
- [ ] Count is bold and blue
- [ ] Count matches actual visible products

### Search Filter

#### Search Input
- [ ] Type in search box
- [ ] Product count updates in real-time
- [ ] Matching products remain visible
- [ ] Non-matching products hide
- [ ] Count reflects only visible products

### Category Filter

#### Category Dropdown
- [ ] Select "Fashion" category
- [ ] Only fashion products show
- [ ] Product count updates
- [ ] Select "All Categories" → All products return

### Sort Filter

#### Sort Options
- [ ] "Featured" → Default order
- [ ] "Price: Low to High" → Products sort by price
- [ ] "Price: High to Low" → Products sort reverse
- [ ] "Newest First" → Products sort by ID descending

#### Count Updates with Sort
- [ ] Product count stays same when sorting
- [ ] Count only changes when filtering

### Combined Filters

#### Multiple Filters Together
- [ ] Search "shoe" + Category "Fashion" → Count reflects both
- [ ] Results are correct intersection
- [ ] Sort still works with filters applied

### Visual Display

#### Product Card Layout
- [ ] Cards display in grid
- [ ] All cards visible with correct info
- [ ] Responsive on mobile

#### Product Count Styling
- [ ] Number is bold
- [ ] Number is blue (#4361ee)
- [ ] No text decoration

---

## 8. GENERAL FUNCTIONALITY ✅

### Browser Console
- [ ] No JavaScript errors
- [ ] No CSS warnings
- [ ] All networks requests successful

### Network Tab
- [ ] `ecommerce.js` loads successfully
- [ ] `phase3-interactions.css` loads successfully
- [ ] Proper file sizes (should be minified in production)

### Responsive Design

#### Desktop (1024px+)
- [ ] All forms fully visible
- [ ] Input fields properly sized
- [ ] Error messages display correctly
- [ ] Shipping options well spaced

#### Tablet (768px - 1023px)
- [ ] Forms stack appropriately
- [ ] Input fields readable
- [ ] Error messages visible
- [ ] Buttons easy to click

#### Mobile (< 768px)
- [ ] Single column layout
- [ ] Input fields full width
- [ ] Error messages appear
- [ ] Buttons accessible with thumb
- [ ] Text readable without zoom

### Keyboard Navigation

#### Tab Through Forms
- [ ] Tab moves through all inputs
- [ ] Tab order is logical
- [ ] Focus is visible on each field
- [ ] Can submit with Enter key

#### Focus Indicators
- [ ] Clear focus outline visible (blue)
- [ ] Outline doesn't cover text
- [ ] Visible on all interactive elements

### Screen Reader (Optional)

#### ARIA Attributes
- [ ] Labels associated with inputs
- [ ] Error messages announced
- [ ] Form structure semantic

---

## 9. EDGE CASES ✅

### Form Validations

- [ ] Very long input (100+ chars) → Field accepts, displays normally
- [ ] Special characters in name → Accepted
- [ ] Spaces in email → Shows error
- [ ] Phone with +country code → Accepted if 10-13 digits
- [ ] Copy-paste into field → Validation works

### Cart Operations

- [ ] Quantity 0 → Not allowed, stays at 1
- [ ] Quantity 999 → Accepted
- [ ] Fast clicking +/- → Last state persists
- [ ] Page refresh with unsaved qty → Reverts to saved value

### Form Submission

- [ ] Submit empty form → All errors appear
- [ ] Submit with one field wrong → Only that field shows error
- [ ] Rapid clicking submit → Only one submission
- [ ] Press Enter in text field → Form submits

---

## 10. CROSS-BROWSER TESTING ✅

| Browser | Tested | Result |
|---------|--------|--------|
| Chrome | [ ] | Pass / Fail |
| Firefox | [ ] | Pass / Fail |
| Safari | [ ] | Pass / Fail |
| Edge | [ ] | Pass / Fail |
| Mobile Safari | [ ] | Pass / Fail |
| Chrome Mobile | [ ] | Pass / Fail |

---

## Summary

**Total Test Cases**: 150+

**Priority**: 
- 🔴 Critical: Form validation, cart interactions, checkout
- 🟡 Important: Visual design, responsive, shipping selection
- 🟢 Nice-to-have: Edge cases, accessibility, cross-browser

**Pass Criteria**: 
- ✅ All critical tests pass
- ✅ All important tests pass  
- ✅ 90%+ of nice-to-have tests pass
- ✅ No JavaScript errors
- ✅ All CSS loads properly

---

## Notes

- Test on incognito/private mode to avoid cache issues
- Clear localStorage if testing signup multiple times
- Use DevTools to inspect elements for styling verification
- Check Network tab for failed requests
- Test both with and without existing cart items

---

*Last Updated: Phase 3 Implementation*
*For bugs found, document in console output and create issue*
