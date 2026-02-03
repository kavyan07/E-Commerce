# 🚀 Phase 6: Advanced Magento-Style Database Integration

Your project has been upgraded to a professional **Entity-Attribute-Value (EAV) Lite** architecture. This structure is similar to how enterprise platforms like Magento handle data.

---

## 1. Catalog Architecture

### **📁 Products (`catalog_product_entity`)**
The main table for products. It stores permanent data like SKU, Name, and Base Price.
*   **Attributes (`catalog_product_attribute`)**: Instead of adding 50 columns for Color, Size, Weight, etc., we use this table. This is highly flexible for future updates.

### **📁 Categories (`catalog_category_entity`)**
Handles the hierarchy of categories.
*   **Category Attributes (`catalog_category_attribute`)**: Stores category-specific variations.
*   **Relationship (`catalog_category_products`)**: Links products to one or more categories using a many-to-many relationship.

---

## 2. Persistent Shopping Cart Logic

This is the most advanced part of the update. The cart is now **Database-Driven**, not just session-based.

### **How it Works:**
1.  **Guest Detection**: If a user is not logged in, the system generates a `guest_id` and stores it in the session.
2.  **Cart Initialization**: When you add an item, the system checks if a `cart_id` exists in your session.
3.  **Database Storage (`sales_cart` & `sale_cart_product`)**:
    *   The cart details are saved in PostgreSQL immediately.
    *   If you close your browser and come back within the same session, your items are still there.
4.  **Login Sync**: If you add items as a guest and then **Log In**, the system runs an `UPDATE sales_cart SET user_id = ...`. Your guest cart automatically becomes your user cart!

---

## 3. Order Processing (`sales_orders`)

When an order is placed, the data is transitioned from the "active" cart to a permanent order record.
*   **Comprehensive Data**: The `sales_orders` table now stores a full snapshot of the Shipping Name, Email, Phone, and the calculated Subtotal, Tax, and Final Amount.
*   **Transactions**: We use PostgreSQL Transactions (`beginTransaction`) to ensure that an order and its items are saved together or not at all.

---

## 4. Key DAO Updates
*   **`CartDAO`**: The logic hub for managing database carts. It handles adding, updating, and removing items directly in PostgreSQL.
*   **`OrderDAO`**: Now supports linking orders to specific `cart_id`s for full traceability.

---

## 5. Security & Refinement
*   **Foreign Keys**: Tables are strictly linked. If you delete a category, the link in `category_products` disappears automatically (`ON DELETE CASCADE`).
*   **UUID/Ids**: We use a mix of auto-increment IDs for performance and generated strings (`ORD-XXX`, `GUEST-XXX`) for user-facing identifiers.
