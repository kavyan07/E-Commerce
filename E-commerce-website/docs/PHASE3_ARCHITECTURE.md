# Phase 3 Architecture & Flow Diagrams

## System Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                       BROWSER (Client Side)                  │
├─────────────────────────────────────────────────────────────┤
│                                                               │
│  ┌──────────────────────────────────────────────────────┐   │
│  │          HTML Pages (PHP Rendered)                   │   │
│  ├──────────────────────────────────────────────────────┤   │
│  │ ├─ login.php         (Form with IDs)                │   │
│  │ ├─ signup.php        (Form with IDs)                │   │
│  │ ├─ checkout.php      (Form with IDs)                │   │
│  │ ├─ cart.php          (Quantity controls)            │   │
│  │ ├─ product-detail.php (Qty + image structure)       │   │
│  │ └─ product-listing.php (Product grid + filters)     │   │
│  └──────────────────────────────────────────────────────┘   │
│                            ↓                                 │
│  ┌──────────────────────────────────────────────────────┐   │
│  │      JavaScript Module (ecommerce.js)                │   │
│  ├──────────────────────────────────────────────────────┤   │
│  │ ┌────────────────────────────────────────────────┐  │   │
│  │ │ Validation Layer                               │  │   │
│  │ │ ├─ initLoginValidation()                       │  │   │
│  │ │ ├─ initSignupValidation()                      │  │   │
│  │ │ └─ initCheckoutValidation()                    │  │   │
│  │ └────────────────────────────────────────────────┘  │   │
│  │ ┌────────────────────────────────────────────────┐  │   │
│  │ │ Interaction Layer                              │  │   │
│  │ │ ├─ initCartInteractions()                      │  │   │
│  │ │ ├─ initProductDetailInteractions()             │  │   │
│  │ │ ├─ initShippingSelection()                     │  │   │
│  │ │ └─ initProductListingEnhancements()            │  │   │
│  │ └────────────────────────────────────────────────┘  │   │
│  │ ┌────────────────────────────────────────────────┐  │   │
│  │ │ Utility Layer                                  │  │   │
│  │ │ ├─ formatPrice()                               │  │   │
│  │ │ ├─ showError() / clearError()                  │  │   │
│  │ │ ├─ isValidEmail()                              │  │   │
│  │ │ ├─ isValidPhone()                              │  │   │
│  │ │ └─ isValidPassword()                           │  │   │
│  │ └────────────────────────────────────────────────┘  │   │
│  └──────────────────────────────────────────────────────┘   │
│                            ↓                                 │
│  ┌──────────────────────────────────────────────────────┐   │
│  │           CSS Styling (phase3-interactions.css)      │   │
│  ├──────────────────────────────────────────────────────┤   │
│  │ ├─ Form validation styles (.input-error)            │   │
│  │ ├─ Error message display (.error-msg)               │   │
│  │ ├─ Shipping option styling (.shipping-option.active)│   │
│  │ ├─ Cart interaction styles (.quantity)              │   │
│  │ └─ Animations & transitions                         │   │
│  └──────────────────────────────────────────────────────┘   │
│                            ↓                                 │
│  ┌──────────────────────────────────────────────────────┐   │
│  │           Form Submission (POST to PHP)              │   │
│  │  ├─ /php/cart.php (action=update/remove)            │   │
│  │  └─ /php/checkout.php (validation + order)          │   │
│  └──────────────────────────────────────────────────────┘   │
│                                                               │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                    PHP/Backend (Server Side)                 │
├─────────────────────────────────────────────────────────────┤
│  ├─ Session Management ($_SESSION['cart'])                 │
│  ├─ Cart Operations (add/update/remove)                    │
│  └─ Data Persistence (data.php)                            │
└─────────────────────────────────────────────────────────────┘
```

---

## Form Validation Flow

```
User Input on Form Field
         ↓
    ┌────────────────────┐
    │ User blurs field   │
    │ (leaves field)     │
    └────────┬───────────┘
             ↓
    ┌────────────────────────────────────┐
    │  Validation Triggered              │
    │  - Check field type                │
    │  - Apply regex/rules               │
    │  - Return true/false               │
    └────────┬─────────────────────────┬─┘
             ↓                         ↓
        ┌─────────┐              ┌──────────┐
        │  Valid  │              │ Invalid  │
        └────┬────┘              └────┬─────┘
             ↓                        ↓
      ┌────────────┐         ┌──────────────────┐
      │ Clear Error│         │ Show Error:      │
      │ - Remove   │         │ - Add .error     │
      │   .input-  │         │   class          │
      │   error    │         │ - Red border     │
      │ - Remove   │         │ - Append message │
      │   message  │         │ - Red text       │
      │ - Normal   │         │ - Animate in     │
      │   styling  │         └────────┬─────────┘
      └────────────┘                  ↓
             ↓              Message visible below
        User sees        input field
        normal field
             ↓
      ┌──────────────────────────────────┐
      │  User Starts Typing               │
      │  - Input event                    │
      │  - Real-time check                │
      │  - Clear error if valid           │
      └──────────────────────────────────┘
             ↓
      Field becomes valid
      (Or user blurs again)
             ↓
      Continue with next field
             ↓
      ┌──────────────────────────────────┐
      │  Form Submission (onclick)        │
      │  - Validate ALL fields            │
      │  - Show all errors if any         │
      │  - Block submission if invalid    │
      │  - Submit if all valid            │
      └──────────────────────────────────┘
```

---

## Cart Quantity Update Flow

```
User clicks Quantity +/- button
         ↓
┌────────────────────────────┐
│ JavaScript Event Handler   │
│ (Click event listener)     │
└────────┬───────────────────┘
         ↓
    ┌─────────────┐
    │ Get current │
    │ quantity    │
    └────┬────────┘
         ↓
    ┌──────────────────┐
    │ Validate bounds  │
    │ Min = 1, no max  │
    └────┬─────────────┘
         ↓
    ┌─────────────┐
    │ Calculate:  │
    │ New Qty     │
    └────┬────────┘
         ↓
    ┌──────────────────────┐
    │ Update UI            │
    │ - Qty input value    │
    │ - Item total         │
    │ - Subtotal           │
    │ - Shipping           │
    │ - Tax                │
    │ - Grand total        │
    └────┬─────────────────┘
         ↓
    ┌──────────────────┐
    │ Submit form to   │
    │ cart.php with    │
    │ action=update    │
    └────┬─────────────┘
         ↓
Server processes update
(Session recalculated)
         ↓
Page reloads with
new cart state
```

---

## Shipping Option Selection Flow

```
User clicks shipping radio button
              ↓
    ┌─────────────────────────┐
    │ Event Listener Triggered│
    │ (change event)          │
    └────┬────────────────────┘
         ↓
┌────────────────────────────────┐
│ JavaScript Handler             │
│ - Get selected option element  │
│ - Get all .shipping-option     │
│   containers                   │
└────┬───────────────────────────┘
     ↓
┌────────────────────────────────┐
│ Update Styling                 │
│ - Remove .active from all      │
│ - Add .active to selected      │
│   option's container           │
│ - CSS handles visual update:   │
│   * Blue border (#4361ee)      │
│   * Light blue bg (#f0f9ff)    │
│   * Smooth transition 0.3s     │
└────┬───────────────────────────┘
     ↓
┌────────────────────────────────┐
│ Visual Feedback                │
│ User sees:                     │
│ - Blue border on selected      │
│ - Light blue background        │
│ - Previous option returns to   │
│   normal gray border           │
└────────────────────────────────┘
     ↓
Option remains selected
until user clicks another
```

---

## Product Count Update Flow

```
Page loads product-listing.php
              ↓
┌──────────────────────────────────┐
│ JavaScript initializes           │
│ (on DOM ready)                   │
│ initProductListingEnhancements() │
└────┬─────────────────────────────┘
     ↓
┌──────────────────────────────────┐
│ Count initial products           │
│ - Find #productGrid              │
│ - Count visible .card elements   │
│ - Update #productCount innerHTML │
└────┬─────────────────────────────┘
     ↓
User types in search box
     ↓ (or changes filter)
┌──────────────────────────────────┐
│ Event Listener: input/change     │
│ - searchInput addEventListener   │
│ - categoryFilter addEventListener│
│ - sortFilter addEventListener    │
└────┬─────────────────────────────┘
     ↓
JavaScript filters products
     ↓
┌──────────────────────────────────┐
│ Update Product Count             │
│ - Count visible .card elements   │
│ - Change product-count innerHTML │
│ - Apply bold + blue color        │
│ - Smooth color transition        │
└────┬─────────────────────────────┘
     ↓
User sees updated count
reflecting visible products
```

---

## Login Validation Sequence

```
┌────────────────────────────────────────────────────┐
│ LOGIN FORM: loginForm                              │
│ Fields:                                            │
│  - email (#email)                                  │
│  - password (#password)                            │
└────────┬─────────────────────────────────────────┘
         ↓
┌────────────────────────────────────────────────────┐
│ Real-time Validation (on blur)                     │
├────────────────────────────────────────────────────┤
│ Email field:                                       │
│  1. Check if empty → Error                        │
│  2. Validate format (regex) → Error if invalid    │
│  3. Show/clear error dynamically                  │
│                                                    │
│ Password field:                                    │
│  1. Check if empty → Error                        │
│  2. No other rules                                │
│  3. Show/clear error dynamically                  │
└────────┬─────────────────────────────────────────┘
         ↓
┌────────────────────────────────────────────────────┐
│ Form Submission (onclick)                         │
├────────────────────────────────────────────────────┤
│ 1. Prevent default submit                         │
│ 2. Get email and password values                  │
│ 3. Validate email:                                │
│    - if empty or !format → showError()            │
│    - else → clearError()                          │
│ 4. Validate password:                             │
│    - if empty → showError()                       │
│    - else → clearError()                          │
│ 5. If all valid → form.submit()                   │
│    Else → block submission                        │
└────────┬─────────────────────────────────────────┘
         ↓
Server handles:
- localStorage user lookup
- Success/failure message
- Redirect on success
```

---

## Signup Validation Sequence

```
┌────────────────────────────────────────────────────┐
│ SIGNUP FORM: signupForm                            │
│ Fields:                                            │
│  - firstname, lastname, email, phone,             │
│  - password, confirmpassword, terms               │
└────────┬─────────────────────────────────────────┘
         ↓
┌────────────────────────────────────────────────────┐
│ Real-time Validation (on blur + input)            │
├────────────────────────────────────────────────────┤
│ Each field: blur event triggers check             │
│            input event clears error on valid      │
│                                                    │
│ Interdependent:                                   │
│ - If password changes and confirmpass is filled   │
│   → Check if they match → Update error state      │
└────────┬─────────────────────────────────────────┘
         ↓
┌────────────────────────────────────────────────────┐
│ Form Submission (onclick)                         │
├────────────────────────────────────────────────────┤
│ Validate each field:                              │
│ 1. First Name: min 2 chars                        │
│ 2. Last Name: min 2 chars                         │
│ 3. Email: valid format                            │
│ 4. Phone: 10-13 digits                            │
│ 5. Password: 6+, 1 upper, 1 num                   │
│ 6. Confirm: must match password                   │
│ 7. Terms: must be checked                         │
│                                                    │
│ If any invalid:                                   │
│  → Show all errors                                │
│  → Block submission                               │
│                                                    │
│ If all valid:                                     │
│  → form.submit() to PHP                           │
└────────┬─────────────────────────────────────────┘
         ↓
Server handles:
- Check if email exists
- Store in localStorage
- Success message + redirect
```

---

## Checkout Flow Diagram

```
User navigates to checkout.php
         ↓
┌────────────────────────────────────────────────────┐
│ CHECKOUT FORM LOADS                               │
│ - Display order summary (cart items)              │
│ - Show address form fields                        │
│ - Show shipping options (Standard/Express)        │
└────────┬─────────────────────────────────────────┘
         ↓
┌────────────────────────────────────────────────────┐
│ SHIPPING SELECTION                                │
│ - Standard Shipping highlighted (.active)         │
│ - User can click Express Shipping                │
│ - Visual highlight updates smoothly               │
└────────┬─────────────────────────────────────────┘
         ↓
┌────────────────────────────────────────────────────┐
│ ADDRESS FIELD VALIDATION                          │
│ On blur:                                          │
│ - Full Name: required, 3+ chars                   │
│ - Email: required, valid format                   │
│ - Phone: required, 10-13 digits                   │
│ - Address: required, 3+ chars                     │
│                                                    │
│ Invalid field:                                    │
│ - Show error message                              │
│ - Red border on input                             │
│ - Error clears on input                           │
└────────┬─────────────────────────────────────────┘
         ↓
┌────────────────────────────────────────────────────┐
│ PLACE ORDER BUTTON                                │
│ On click:                                         │
│ 1. Validate ALL address fields                    │
│ 2. If any invalid:                                │
│    - Show all errors                              │
│    - Don't submit                                 │
│ 3. If all valid:                                  │
│    - Show success message                         │
│    - Could redirect to payment                    │
└────────┬─────────────────────────────────────────┘
         ↓
Order processing would continue
(in actual Phase 4 implementation)
```

---

## Error Display Flow

```
Invalid Input Detected
         ↓
┌────────────────────────────────────────────────────┐
│ showError(element, message)                       │
├────────────────────────────────────────────────────┤
│ 1. Get .form-group parent of input                │
│ 2. Add .input-error class to input                │
│ 3. Create <span class="error-msg">               │
│ 4. Set span textContent = message                 │
│ 5. Append span to form-group                      │
│ 6. CSS applies:                                   │
│    - Red border (#ef4444)                         │
│    - Light red background (#fef2f2)               │
│    - Error message in red text                    │
│    - Slide-down animation                         │
└────────┬─────────────────────────────────────────┘
         ↓
┌────────────────────────────────────────────────────┐
│ User sees error message below field                │
│ Red border highlights invalid field                │
│ Can start typing to fix                           │
└────────┬─────────────────────────────────────────┘
         ↓
Valid Input Detected
         ↓
┌────────────────────────────────────────────────────┐
│ clearError(element)                               │
├────────────────────────────────────────────────────┤
│ 1. Get .form-group parent of input                │
│ 2. Find .error-msg span in form-group             │
│ 3. Remove .input-error class from input           │
│ 4. Remove error message span                      │
│ 5. CSS returns to normal:                         │
│    - Gray border                                  │
│    - White background                             │
│    - Normal text                                  │
└────────┬─────────────────────────────────────────┘
         ↓
User sees normal field
with no error message
```

---

## Module Initialization Flow

```
Page loads HTML
         ↓
┌────────────────────────────────────────────────────┐
│ Script tags load files (header.php):             │
│ 1. phase3-interactions.css (CSS)                  │
│ 2. Other page-specific CSS                        │
│ 3. (footer.php) ecommerce.js                      │
└────────┬─────────────────────────────────────────┘
         ↓
┌────────────────────────────────────────────────────┐
│ ecommerce.js executes:                            │
│ const EasyCart = (() => {                         │
│   ... all functions defined ...                   │
│   return { init, ... }                            │
│ })()                                              │
└────────┬─────────────────────────────────────────┘
         ↓
┌────────────────────────────────────────────────────┐
│ Check DOM ready:                                  │
│ if (document.readyState === 'loading')            │
│   → Wait for DOMContentLoaded                     │
│ else                                              │
│   → Call EasyCart.init() immediately              │
└────────┬─────────────────────────────────────────┘
         ↓
┌────────────────────────────────────────────────────┐
│ EasyCart.init() runs:                             │
│ 1. addInputTransitions() - inject CSS             │
│ 2. initLoginValidation() - if login form exists   │
│ 3. initSignupValidation() - if signup form        │
│ 4. initCheckoutValidation() - if checkout form    │
│ 5. initCartInteractions() - if cart page          │
│ 6. initProductDetailInteractions() - if PDP       │
│ 7. initShippingSelection() - if checkout          │
│ 8. initProductListingEnhancements() - if PLP      │
└────────┬─────────────────────────────────────────┘
         ↓
All event listeners attached
         ↓
Module ready for user interaction
```

---

## Price Calculation Formula

```
For each item in cart:
  Item Total = Product Price × Quantity

Subtotal = Sum of all Item Totals

Shipping = if (Subtotal > 999)
             FREE (0)
           else
             ₹299 (standard)

Tax = Subtotal × 0.18 (18% tax)

Total = Subtotal + Shipping + Tax
```

---

## CSS Class Application Timeline

```
Page Load
   ↓
EasyCart.addInputTransitions() injects <style>
   ↓
Form fields have default styles
   ↓
User interacts (type, blur, submit)
   ↓
JavaScript event fires
   ↓
┌─ if invalid ──→ addClass('input-error')
│                 + showError() creates .error-msg span
│                 → CSS applies red border + background
│                 → Error message appears
│
└─ if valid ───→ removeClass('input-error')
                 + remove error-msg span
                 → CSS returns to normal
                 → Error message disappears
                 ↓
                User sees field returns to normal
                Can continue to next field
```

---

*These diagrams illustrate the complete flow and architecture of Phase 3 implementation.*
