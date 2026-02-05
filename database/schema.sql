-- =========================================================
-- EASYCART ADVANCED DATABASE SCHEMA (PHASE 6)
-- Following Magento-style naming conventions
-- =========================================================

-- Cleanup existing tables (Safe Rebuild)
-- DROP TABLE IF EXISTS sales_order_items CASCADE;
-- DROP TABLE IF EXISTS sales_orders CASCADE;
-- DROP TABLE IF EXISTS sale_cart_product CASCADE;
-- DROP TABLE IF EXISTS sales_cart CASCADE;
-- DROP TABLE IF EXISTS catalog_category_products CASCADE;
-- DROP TABLE IF EXISTS catalog_category_attribute CASCADE;
-- DROP TABLE IF EXISTS catalog_category_entity CASCADE;
-- DROP TABLE IF EXISTS catalog_product_attribute CASCADE;
-- DROP TABLE IF EXISTS catalog_product_entity CASCADE;
-- DROP TABLE IF EXISTS users CASCADE;
-- DROP TABLE IF EXISTS brands CASCADE;

-- 1. Users Table
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Brands Table
CREATE TABLE brands (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

-- 3. Catalog Product Entity
CREATE TABLE catalog_product_entity (
    entity_id SERIAL PRIMARY KEY,
    sku VARCHAR(100) UNIQUE,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(12, 2) NOT NULL,
    original_price DECIMAL(12, 2),
    brand_id INTEGER REFERENCES brands(id) ON DELETE SET NULL,
    image_main VARCHAR(255),
    badge VARCHAR(50),
    shipping_type INTEGER DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. Catalog Product Attribute
CREATE TABLE catalog_product_attribute (
    attribute_id SERIAL PRIMARY KEY,
    product_id INTEGER REFERENCES catalog_product_entity(entity_id) ON DELETE CASCADE,
    attribute_code VARCHAR(50),
    value TEXT,
    sort_order INTEGER DEFAULT 0
);

-- 5. Catalog Category Entity
CREATE TABLE catalog_category_entity (
    entity_id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    parent_id INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 6. Catalog Category Attribute
CREATE TABLE catalog_category_attribute (
    attribute_id SERIAL PRIMARY KEY,
    category_id INTEGER REFERENCES catalog_category_entity(entity_id) ON DELETE CASCADE,
    variation_name VARCHAR(100),
    variation_value TEXT
);

-- 7. Catalog Category Products
CREATE TABLE catalog_category_products (
    increment_id SERIAL PRIMARY KEY,
    category_id INTEGER REFERENCES catalog_category_entity(entity_id) ON DELETE CASCADE,
    product_id INTEGER REFERENCES catalog_product_entity(entity_id) ON DELETE CASCADE
);

-- 8. Sales Cart
CREATE TABLE sales_cart (
    cart_id SERIAL PRIMARY KEY,
    session_id VARCHAR(255) NOT NULL,
    user_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
    guest_id VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 9. Sale Cart Product
CREATE TABLE sale_cart_product (
    entity_id SERIAL PRIMARY KEY,
    cart_id INTEGER REFERENCES sales_cart(cart_id) ON DELETE CASCADE,
    product_id INTEGER REFERENCES catalog_product_entity(entity_id) ON DELETE CASCADE,
    quantity INTEGER NOT NULL DEFAULT 1,
    price DECIMAL(12, 2) NOT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 10. Sales Order
CREATE TABLE sales_orders (
    entity_id SERIAL PRIMARY KEY,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    user_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
    cart_id INTEGER REFERENCES sales_cart(cart_id) ON DELETE SET NULL,
    subtotal DECIMAL(12, 2) NOT NULL,
    tax DECIMAL(12, 2) DEFAULT 0,
    shipping_cost DECIMAL(12, 2) DEFAULT 0,
    final_amount DECIMAL(12, 2) NOT NULL,
    shipping_type VARCHAR(50),
    shipping_name VARCHAR(255),
    shipping_email VARCHAR(255),
    shipping_phone VARCHAR(50),
    shipping_address TEXT,
    billing_address TEXT,
    status VARCHAR(50) DEFAULT 'Processing',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 11. Sales Order Items
CREATE TABLE sales_order_items (
    item_id SERIAL PRIMARY KEY,
    order_id INTEGER REFERENCES sales_orders(entity_id) ON DELETE CASCADE,
    product_id INTEGER REFERENCES catalog_product_entity(entity_id) ON DELETE SET NULL,
    name VARCHAR(255),
    price DECIMAL(12, 2),
    quantity INTEGER,
    total DECIMAL(12, 2)
);

-- =========================================================
-- SEED DATA
-- =========================================================

INSERT INTO brands (name) VALUES ('Nike'), ('Apple'), ('Samsung'), ('Sony'), ('North');

INSERT INTO catalog_category_entity (name, slug) VALUES 
('Fashion', 'fashion'), ('Electronics', 'electronics'), ('Accessories', 'accessories'), ('Gaming', 'gaming'), ('Home', 'home');

INSERT INTO catalog_product_entity (name, description, price, original_price, brand_id, image_main, badge, shipping_type) VALUES
('Gaming Console', 'Pro 4K Console', 49999, 59999, 4, 'public/images/products/console.jpg', 'Freight', 1),
('Wireless Earbuds', 'Noise Cancel', 1999, 2999, 2, 'public/images/products/earbuds.jpg', 'Express', 2),
('Smart Watch', 'Fitness tracker', 3499, 4999, 2, 'public/images/products/smartwatch.jpg', 'Express', 2),
('Premium Backpack', 'Durable bag', 1299, 1999, 5, 'public/images/products/jacket.jpg', 'Express', 2),
('Smart Phone', 'High-perf phone', 24999, 29999, 3, 'public/images/products/phonecase.jpg', 'Freight', 1),
('Running Shoes', 'Lightweight', 3999, 5499, 1, 'public/images/products/sneakers.jpg', 'Express', 2),
('Gaming Keyboard', 'RGB Mechanical', 2499, 3499, 3, 'public/images/products/keyboard.jpg', 'Express', 2),
('Ergonomic Stand', 'Laptop stand', 249, 399, 3, 'public/images/products/laptop-stand.jpg', 'Express', 2);

INSERT INTO catalog_category_products (category_id, product_id) VALUES 
(4, 1), (2, 2), (2, 3), (3, 4), (2, 5), (1, 6), (5, 7), (5, 8);

INSERT INTO catalog_product_attribute (product_id, attribute_code, value) VALUES 
(1, 'color', 'Jet Black'), (1, 'storage', '825GB'),
(6, 'size', 'UK 9'), (6, 'color', 'Neon Blue');
