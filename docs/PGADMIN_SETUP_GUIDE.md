# Guide: Connecting and Setting Up with pgAdmin 4

This guide will help you set up your PostgreSQL database using pgAdmin 4 and connect it to your PHP application.

---

## Step 1: Create the Database in pgAdmin 4

1.  **Open pgAdmin 4** and log in with your master password.
2.  In the left sidebar (Browser), right-click on **Servers** -> **PostgreSQL [Version]**.
3.  Right-click on **Databases** -> **Create** -> **Database...**
4.  In the **Database** field, type: `ecommerce_db`
5.  Click **Save**.

---

## Step 2: Run the SQL Schema

Now that you have a database, you need to create the tables.

1.  Select your new `ecommerce_db` in the sidebar.
2.  Look at the top menu bar and click the **Query Tool** icon (looks like a database with a play button).
3.  Open the file `c:\xampp\htdocs\E-commerce-website\database\schema.sql` in your code editor.
4.  **Copy all the SQL code** from that file.
5.  **Paste it** into the Query Tool window in pgAdmin 4.
6.  Press **F5** or click the **Execute** button (Play icon).
7.  You should see a message: "Query returned successfully".

---

## Step 3: Configure PHP to Connect

Now you need to tell your PHP code how to talk to this database.

1.  Open the file: `c:\xampp\htdocs\E-commerce-website\includes\db.php`
2.  Look for these lines and make sure they match your pgAdmin settings:

```php
private $host = 'localhost';
private $port = '5432';
private $db_name = 'ecommerce_db'; // Must match Step 1
private $username = 'postgres';     // Default pgAdmin username
private $password = 'your_password'; // REPLACE THIS with your pgAdmin password
```

---

## Step 4: Verify Tables

In pgAdmin 4, you can now see your tables:
1.  Go to `ecommerce_db` -> **Schemas** -> **public** -> **Tables**.
2.  You should see: `users`, `products`, `categories`, `brands`, `orders`, `order_items`.
3.  Right-click on `products` -> **View/Edit Data** -> **All Rows** to see the sample products I added.

---

## Troubleshooting Tips

*   **Connection Refused**: Ensure PostgreSQL is actually running on your computer.
*   **Authentication Failed**: Double-check the `$password` in `db.php`. It must be the password you set when you installed PostgreSQL.
*   **Table Not Found**: Ensure you selected `ecommerce_db` (not the default 'postgres' database) before running the Query Tool.
