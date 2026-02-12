# How to Use Coupon Feature

## Available Coupon Codes

The checkout page supports the following coupon codes:

- **SAVE5** → 5% discount on subtotal
- **SAVE10** → 10% discount on subtotal  
- **SAVE15** → 15% discount on subtotal

## How to Apply a Coupon

1. **Add items to your cart** from the product listing or product detail pages
2. **Go to Checkout** page
3. **Scroll down to the "Order Summary"** section on the right side
4. **Find the "Enter promo code"** input field
5. **Type one of the valid coupon codes** (SAVE5, SAVE10, or SAVE15)
6. **Click the "Apply" button**
7. **You will see:**
   - A success toast notification showing the discount amount saved
   - The discount row appears in the order summary
   - The final total is updated automatically

## What Happens When You Apply a Coupon

- ✅ **Valid Coupon**: 
  - Discount is calculated on the subtotal
  - Discount amount is shown in the order summary
  - GST (18%) is calculated on (Subtotal + Shipping - Discount)
  - Final total is updated
  - Success message appears: "Coupon 'SAVE5' applied! You saved ₹XXX"

- ❌ **Invalid Coupon**:
  - Error message appears: "Invalid coupon code"
  - No discount is applied
  - Order total remains unchanged

## Important Notes

- **Only one coupon per order** - You can apply only one coupon code per checkout
- **Coupon is applied to subtotal** - The discount percentage is calculated on the cart subtotal
- **GST calculation** - Tax is calculated on (Subtotal + Shipping - Discount)
- **Session-based** - The applied coupon is stored in PHP session and persists until checkout is completed

## Example Calculation

If your cart subtotal is ₹10,000:
- **SAVE5**: Discount = ₹500, Final = ₹10,000 - ₹500 + Shipping + GST
- **SAVE10**: Discount = ₹1,000, Final = ₹10,000 - ₹1,000 + Shipping + GST
- **SAVE15**: Discount = ₹1,500, Final = ₹10,000 - ₹1,500 + Shipping + GST
