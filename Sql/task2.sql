create database task2;

create table products(
P_id INT PRIMARY KEY ,
c_id INT ,
p_name VARCHAR(50),
revenue DECIMAL(10,2)
);


INSERT INTO products (p_id, c_id, p_name, revenue) VALUES
(1, 1, 'Laptop', 1500.00),
(2, 1, 'Smartphone', 1500.00),  
(3, 1, 'Tablet', 1200.00),      
(4, 1, 'Monitor', 1000.00),     
(5, 1, 'Mouse', 500.00),        
(6, 2, 'Fridge', 2000.00),
(7, 2, 'Microwave', 800.00),
(8, 2, 'Toaster', 300.00),
(9, 2, 'Blender', 300.00);


WITH RankedProducts AS (
    SELECT 
        p_id, 
        c_id, 
        p_name, 
        revenue,
        DENSE_RANK() OVER (
            PARTITION BY c_id 
            ORDER BY revenue DESC
        ) AS product_rank
    FROM products
)
SELECT 
    c_id, 
    p_name, 
    revenue, 
    product_rank
FROM RankedProducts
WHERE product_rank <= 3
ORDER BY c_id, product_rank;


