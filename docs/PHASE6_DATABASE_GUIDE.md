# Phase 6: Database Integration Guide (PostgreSQL)

This guide explains the transition from static arrays to a persistent PostgreSQL database using PHP PDO.

## 1. Directory Structure

To keep the project clean and professional, we have organized the database logic into a `DAO` (Data Access Object) pattern.

```text
/PROJECT_ROOT
│
├── api/                   # JSON Endpoints for AJAX
│   ├── products.php       # Fetches products, categories
│   └── checkout.php       # Handles order placement in DB
│
├── classes/               # PHP Classes (DAO / Models)
│   ├── ProductDAO.php     # Product-specific SQL queries
│   ├── CommonDAO.php      # Category & Brand queries
│   └── OrderDAO.php       # Order & OrderItems persistence
│
├── database/              # SQL Scripts
│   └── schema.sql         # Table definitions & Sample data
│
├── includes/              # Core Utility Files
│   └── db.php             # PDO Connection (PostgreSQL)
│
└── ...                    # Existing files (js, css, images)
```

---

## 2. The Data Flow (Static to Dynamic)

### **A. How Data is Loaded**
1.  **Old Way**: You included `data.php` which had a hardcoded `$products` array.
2.  **New Way**: You initialize a DAO class which runs a `SELECT` query against PostgreSQL.
    *   Example: `$products = (new ProductDAO())->getAllProducts();`

### **B. How Orders are Saved**
1.  **Preparation**: JavaScript collects the user's shipping info and method.
2.  **AJAX**: Browser calls `api/checkout.php` using `fetch()`.
3.  **Validation**: PHP checks the session cart.
4.  **Transaction**: `OrderDAO` starts a DB Transaction:
    *   Inserts into `orders` table.
    *   Inserts multiple rows into `order_items` (linking back to `order_id`).
    *   Commits everything if successful.

---

## 3. Example AJAX Frontend Call

Here is how your JavaScript now interacts with the new database APIs:

```javascript
async function placeOrder(formData) {
    try {
        const response = await fetch('api/checkout.php', {
            method: 'POST',
            body: JSON.stringify(formData),
            headers: { 'Content-Type': 'application/json' }
        });

        const result = await response.json();
        
        if (result.success) {
            alert("Success! Order Number: " + result.order_number);
            window.location.href = 'success.php';
        } else {
            alert("Error: " + result.message);
        }
    } catch (err) {
        console.error("Network Error:", err);
    }
}
```

---

## 4. Key Benefits of this Implementation

1.  **Normalized Schema**: Products are linked to Categories and Brands via IDs, not strings. This prevents data duplication.
2.  **Security**: All queries use **Prepared Statements** (e.g., `WHERE id = ?`). This makes the application immune to SQL Injection.
3.  **Scalability**: By using a database, you can now handle thousands of products and track infinite user orders.
4.  **Atomicity**: Using `beginTransaction()` and `commit()` ensures that you never have a situation where an order is saved but the items are lost (it’s all or nothing).

---

## 5. Next Steps for You
1.  **Run the Schema**: Copy the content of `database/schema.sql` into your PostgreSQL tool (like pgAdmin or terminal).
2.  **Configure db.php**: Update the password in `includes/db.php` to match your local PostgreSQL setup.
3.  **Update index.php**: Instead of `$products` from `data.php`, use `ProductDAO` to fetch items.
