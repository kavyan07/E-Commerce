# 🔄 Complete Admin System Flow Diagram

This document explains the complete request-response flow for the admin system.

---

## 🌊 Flow 1: Admin Login

```
User Browser
    |
    | HTTP GET /admin/login
    |
    v
┌──────────────────────────────────────┐
│ .htaccess (URL Rewriting)            │
│ /admin/login → index.php?route=...   │
└──────────────────────────────────────┘
    |
    v
┌──────────────────────────────────────┐
│ index.php (Front Controller)          │
│ - session_start()                     │
│ - Core_Autoload::register()           │
│ - Parse route: "admin/login"          │
└──────────────────────────────────────┘
    |
    v
┌──────────────────────────────────────┐
│ Autoloader (libs/Core/Autoload.php)  │
│ - Finds Controller_Admin_Login        │
│ - Loads libs/Controller/Admin/Login.php│
└──────────────────────────────────────┘
    |
    v
┌──────────────────────────────────────┐
│ Controller_Admin_Login::execute()     │
│ - If POST:                            │
│   → Fetch email/password from $_POST │
│   → Query admin_users table           │
│   → Verify password                   │
│   → Check is_active                   │
│   → Set $_SESSION['admin_user']       │
│   → redirect(dashboard)               │
│ - Else:                               │
│   → Load login view                   │
└──────────────────────────────────────┘
    |
    v
┌──────────────────────────────────────┐
│ View_Default::toHtml()                │
│ - Load template: admin/login.phtml   │
│ - Wrap with header + footer           │
│ - Return HTML string                  │
└──────────────────────────────────────┘
    |
    v
┌──────────────────────────────────────┐
│ admin/login.phtml (Template)          │
│ - Email input field                   │
│ - Password input field                │
│ - Submit button                       │
└──────────────────────────────────────┘
    |
    v
User sees login form
```

---

## 📊 Flow 2: Admin Dashboard Stats

```
User (Logged In Admin)
    |
    | HTTP GET /admin/dashboard
    |
    v
┌──────────────────────────────────────┐
│ index.php                             │
│ 1. Check if admin session exists      │
│ 2. Route to Controller_Admin_Dashboard│
└──────────────────────────────────────┘
    |
    v
┌──────────────────────────────────────┐
│ Controller_Admin_Dashboard::execute() │
│ Line 12: Total Orders Query           │
│   SELECT COUNT(*) FROM sales_orders   │
│                                        │
│ Line 16: Total Revenue Query          │
│   SELECT SUM(final_amount)            │
│   FROM sales_orders                   │
│                                        │
│ Line 22: Total Products Query         │
│   SELECT COUNT(*)                     │
│   FROM catalog_product_entity         │
└──────────────────────────────────────┘
    |
    v
┌──────────────────────────────────────┐
│ PostgreSQL Database                   │
│ - Execute 3 queries                   │
│ - Return counts and sum               │
└──────────────────────────────────────┘
    |
    v
┌──────────────────────────────────────┐
│ Controller (Continued)                │
│ Line 28-32: Prepare data array        │
│ $stats = [                            │
│   'total_orders' => 15,               │
│   'total_revenue' => 45000.00,        │
│   'total_products' => 120             │
│ ]                                     │
└──────────────────────────────────────┘
    |
    v
┌──────────────────────────────────────┐
│ View_Default                          │
│ - Set template: admin/dashboard       │
│ - Assign stats to view                │
│ - Call toHtml()                       │
└──────────────────────────────────────┘
    |
    v
┌──────────────────────────────────────┐
│ admin/dashboard.phtml                 │
│ Line 23: <?= $stats['total_orders']?> │
│ Line 31: <?= $stats['total_revenue']?>│
│ Line 38: <?= $stats['total_products']?>│
└──────────────────────────────────────┘
    |
    v
User sees dashboard with live stats
```

---

## 📥 Flow 3: CSV Import

```
Admin uploads CSV file
    |
    | HTTP POST /admin/product/import
    | + File: products.csv
    |
    v
┌──────────────────────────────────────┐
│ index.php                             │
│ Route: admin/product/import           │
│ → Controller_Admin_Product_Import     │
└──────────────────────────────────────┘
    |
    v
┌──────────────────────────────────────┐
│ Controller_Admin_Product_Import       │
│ Line 8: Check if POST & file exists   │
│ Line 13: Get temp file path           │
│ Line 22: Open CSV file                │
│ Line 23: Read header row              │
└──────────────────────────────────────┘
    |
    v
┌──────────────────────────────────────┐
│ CSV File Processing Loop              │
│ While (each row in CSV):              │
│   - Parse columns: SKU, Name, Price...│
│   - Validate required fields          │
│   - Check/create brand                │
│   - Prepare INSERT/UPDATE query       │
└──────────────────────────────────────┘
    |
    v
┌──────────────────────────────────────┐
│ PostgreSQL Database                   │
│ Line 46-56: UPSERT Query              │
│ INSERT INTO catalog_product_entity    │
│ VALUES (...)                          │
│ ON CONFLICT (sku)                     │
│ DO UPDATE SET name = EXCLUDED.name    │
└──────────────────────────────────────┘
    |
    v
┌──────────────────────────────────────┐
│ Success Tracking                      │
│ - $imported++ for each success        │
│ - $failed++ for each error            │
└──────────────────────────────────────┘
    |
    v
┌──────────────────────────────────────┐
│ Session Message                       │
│ $_SESSION['import_msg'] =             │
│   "Imported: 50, Failed: 2"           │
│                                        │
│ redirect('admin/dashboard')           │
└──────────────────────────────────────┘
    |
    v
Dashboard shows success message
```

---

## 📤 Flow 4: CSV Export

```
Admin clicks "Export"
    |
    | HTTP POST /admin/product/export
    |
    v
┌──────────────────────────────────────┐
│ Controller_Admin_Product_Export       │
│ Line 11-12: SELECT all products       │
│   FROM catalog_product_entity         │
└──────────────────────────────────────┘
    |
    v
┌──────────────────────────────────────┐
│ PostgreSQL Database                   │
│ Returns all product rows              │
└──────────────────────────────────────┘
    |
    v
┌──────────────────────────────────────┐
│ CSV Generation                        │
│ Line 16-19: Set download headers      │
│   Content-Type: text/csv              │
│   Content-Disposition: attachment     │
│                                        │
│ Line 21: Open output stream           │
│ Line 24: Write CSV header             │
│ Line 27-29: Write each product row    │
└──────────────────────────────────────┘
    |
    v
┌──────────────────────────────────────┐
│ Browser                               │
│ Downloads file:                       │
│ products_export_2026-02-11.csv        │
└──────────────────────────────────────┘
```

---

## 🔒 Flow 5: Security - Deactivated User

```
User tries to login
    |
    | POST email + password
    |
    v
┌──────────────────────────────────────┐
│ Controller_Admin_Login                │
│ Line 20: Query admin_users by email   │
└──────────────────────────────────────┘
    |
    v
┌──────────────────────────────────────┐
│ Database Returns Admin Record         │
│ {                                     │
│   id: 1,                              │
│   email: 'admin@gmail.com',           │
│   password: '$2y$10$...',             │
│   is_active: FALSE  ⚠️                │
│ }                                     │
└──────────────────────────────────────┘
    |
    v
┌──────────────────────────────────────┐
│ Password Verification                 │
│ Line 25: password_verify(...)         │
│ Result: TRUE ✅ (password correct)     │
└──────────────────────────────────────┘
    |
    v
┌──────────────────────────────────────┐
│ Active Status Check                   │
│ Line 27-28:                           │
│ if (!$admin['is_active']) {           │
│   Set error message                   │
│   BLOCK LOGIN ❌                       │
│ }                                     │
└──────────────────────────────────────┘
    |
    v
User sees: "Your account has been deactivated"
```

---

## 🎯 MVC Layers in Action

```
┌─────────────────────────────────────────────────────────┐
│                    USER REQUEST                          │
│              http://localhost/admin/dashboard            │
└────────────────────┬────────────────────────────────────┘
                     │
                     v
        ┌────────────────────────────┐
        │   CONTROLLER LAYER         │ ← NO SQL HERE
        │ (libs/Controller/Admin/)   │ ← NO HTML HERE
        │ - Request handling         │
        │ - Validation               │
        │ - Flow control             │
        └────────┬───────────────────┘
                 │
                 │ Asks for data
                 v
        ┌────────────────────────────┐
        │   MODEL LAYER              │ ← NO HTML HERE
        │ (libs/Model/)              │ ← Business Logic
        │ - Product.php              │
        │ - User.php                 │
        └────────┬───────────────────┘
                 │
                 │ Needs DB access
                 v
        ┌────────────────────────────┐
        │   RESOURCE LAYER           │ ← SQL ALLOWED HERE
        │ (libs/Core/Resource/)      │ ← Database Operations
        │ - Abstract.php             │
        │ - load(), save(), delete() │
        └────────┬───────────────────┘
                 │
                 │ Executes queries
                 v
        ┌────────────────────────────┐
        │   DATABASE                 │
        │ (PostgreSQL)               │
        │ - sales_orders             │
        │ - catalog_product_entity   │
        └────────┬───────────────────┘
                 │
                 │ Returns data
                 v
        ┌────────────────────────────┐
        │   VIEW LAYER               │ ← NO SQL HERE
        │ (app/Design/view/)         │ ← NO Business Logic
        │ - dashboard.phtml          │ ← ONLY HTML & Display
        │ - toHtml() method          │
        └────────┬───────────────────┘
                 │
                 │ Rendered HTML
                 v
        ┌────────────────────────────┐
        │   USER BROWSER             │
        │ Beautiful Dashboard        │
        └────────────────────────────┘
```

---

## 📝 Key Takeaways

1. **Autoloader is the Hero**: No manual `include()` needed
2. **Clean Separation**: Each layer has ONE job
3. **Security First**: Password hashing, prepared statements, session checks
4. **Database Abstraction**: Resource layer wraps all SQL
5. **Reusable**: Abstract classes make scaling easy

---

This flow ensures:
- ✅ Maintainability
- ✅ Scalability
- ✅ Security
- ✅ Enterprise standards
