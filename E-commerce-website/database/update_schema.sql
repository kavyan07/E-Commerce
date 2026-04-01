-- =========================================================
-- EASYCART DATABASE UPDATES
-- Run this after the main schema.sql
-- =========================================================

-- Add payment fields to sales_orders
ALTER TABLE sales_orders ADD COLUMN IF NOT EXISTS payment_method VARCHAR(50) DEFAULT 'cod';
ALTER TABLE sales_orders ADD COLUMN IF NOT EXISTS payment_status VARCHAR(50) DEFAULT 'Pending';
ALTER TABLE sales_orders ADD COLUMN IF NOT EXISTS transaction_id VARCHAR(100);
ALTER TABLE sales_orders ADD COLUMN IF NOT EXISTS razorpay_order_id VARCHAR(100);
ALTER TABLE sales_orders ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- Add is_featured flag to products
ALTER TABLE catalog_product_entity ADD COLUMN IF NOT EXISTS is_featured BOOLEAN DEFAULT FALSE;
ALTER TABLE catalog_product_entity ADD COLUMN IF NOT EXISTS stock_qty INTEGER DEFAULT 100;
ALTER TABLE catalog_product_entity ADD COLUMN IF NOT EXISTS is_active BOOLEAN DEFAULT TRUE;

-- Admin users table (if not exists)
CREATE TABLE IF NOT EXISTS admin_users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(255),
    role VARCHAR(50) DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE
);

-- Insert/update default admin (password: Admin@123)
INSERT INTO admin_users (username, email, password, full_name, role, is_active)
VALUES ('admin', 'admin@easycart.com', '$2y$10$TKh8H1.PfXfLNxGHSWjOV.oeV3E/Bp5t1H8vYnJBuDH6EYKrNKDk2', 'Super Admin', 'superadmin', TRUE)
ON CONFLICT (email) DO UPDATE SET password = '$2y$10$TKh8H1.PfXfLNxGHSWjOV.oeV3E/Bp5t1H8vYnJBuDH6EYKrNKDk2', is_active = TRUE;

-- Update products to mark some as featured
UPDATE catalog_product_entity SET is_featured = TRUE WHERE entity_id IN (1, 2, 3, 4);
UPDATE catalog_product_entity SET stock_qty = 50 WHERE entity_id IN (1, 5);
UPDATE catalog_product_entity SET stock_qty = 200 WHERE entity_id NOT IN (1, 5);
