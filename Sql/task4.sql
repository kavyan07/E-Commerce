create database task4;

use task4;

CREATE TABLE products (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    product_name VARCHAR(100),
    category VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE product_price_history (
    price_id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT,
    price DECIMAL(10,2),
    effective_date DATE,
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

INSERT INTO products (product_name, category)
VALUES 
('iPhone 15', 'Mobile'),
('Samsung TV', 'Electronics');

INSERT INTO product_price_history (product_id, price, effective_date)
VALUES
(1, 70000, '2025-10-01'),
(1, 72000, '2025-11-01'),
(1, 71000, '2025-12-01'),
(1, 75000, '2026-01-15'),

(2, 50000, '2025-09-01'),
(2, 52000, '2025-11-15'),
(2, 51000, '2026-02-01');



WITH price_analysis AS (
SELECT 
p.product_name,
ph.price AS current_price,
	LAG(ph.price) OVER (
            PARTITION BY ph.product_id 
            ORDER BY ph.effective_date
        ) AS previous_price,
        LEAD(ph.price) OVER (
            PARTITION BY ph.product_id 
            ORDER BY ph.effective_date
        ) AS next_price,
        ROUND(
            ((ph.price - LAG(ph.price) OVER (
                PARTITION BY ph.product_id 
                ORDER BY ph.effective_date
            )) 
            / LAG(ph.price) OVER (
                PARTITION BY ph.product_id 
                ORDER BY ph.effective_date
            )) * 100, 2
        ) AS percentage_change,
        ph.effective_date
    FROM product_price_history ph
    JOIN products p ON p.product_id = ph.product_id
)
SELECT *
FROM price_analysis
WHERE effective_date >= CURDATE() - INTERVAL 90 DAY;