create database task3;

use task3;

create table customer(
c_id INT PRIMARY KEY AUTO_INCREMENT,
c_name VARCHAR(20),
email VARCHAR(20),
created_date date
);


create table orders(
o_id INT PRIMARY KEY AUTO_INCREMENT,
c_id INT,
o_date date,
FOREIGN KEY (c_id) REFERENCES customer(c_id)
);


CREATE TABLE order_items (
    o_i_id INT PRIMARY KEY,
    o_id INT,
    quantity INT,
    price_per_unit DECIMAL(10,2),
    FOREIGN KEY (o_id) REFERENCES orders(o_id)
);


INSERT INTO customer VALUES 
(1, 'John Doe', 'john@email.com', '2025-01-01'),
(2, 'Jane Smith', 'jane@email.com', '2025-01-01'),
(3, 'Bob Wilson', 'bob@email.com', '2025-01-01'),
(4, 'Alice Brown', 'alice@email.com', '2025-01-01');


INSERT INTO orders VALUES 
(1, 1, '2026-02-10'), (2, 1, '2026-02-15'), (3, 1, '2026-02-20'),
(4, 1, '2026-02-25'), (5, 1, '2026-03-01'),  
(6, 2, '2026-02-12'), (7, 2, '2026-02-28'), 
(8, 3, '2026-03-02'),                       
(9, 4, '2026-02-18');                       


INSERT INTO order_items VALUES 
(1, 1, 2, 100.00), (2, 2, 1, 250.00), (3, 3, 3, 75.00),
(4, 4, 1, 300.00), (5, 5, 2, 125.00),  
(6, 6, 1, 400.00), (7, 7, 2, 150.00),  
(8, 8, 1, 50.00),                      
(9, 9, 3, 80.00);                      


WITH last_30_days AS(
SELECT
c.c_id,
c.c_name,
COUNT(DISTINCT o.o_id) AS purchase_count,
SUM(oi.quantity * oi.price_per_unit) AS total_spending
FROM customer c JOIN orders o ON c.c_id=o.c_id
JOIN order_items oi ON o.o_id=oi.o_id
WHERE o.o_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
GROUP BY c.c_id, c.c_name
),
avg_spending AS(
SELECT AVG(total_spending) AS overall_avg FROM last_30_days
)
SELECT
l.c_name,
l.purchase_count,
ROUND(total_spending,2) AS total_spending,
ROUND(l.total_spending-a.overall_avg) AS amount_above_avg
FROM last_30_days l CROSS JOIN  avg_spending a
Where  l.total_spending> a.overall_avg
ORDER BY l.total_spending DESC;

 
