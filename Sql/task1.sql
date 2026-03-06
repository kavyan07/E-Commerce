create database task1;
use task1;


create table employee(
e_id INT PRIMARY KEY AUTO_INCREMENT,
name VARCHAR(50),
m_id INT NOT NULL,
FOREIGN KEY (m_id) REFERENCES  employee(e_id)
);


ALTER table employee MODIFY COLUMN m_id INT NULL;
INSERT INTO employee (e_id, name, m_id) VALUES
(1, 'Alice',   NULL),  -- CEO
(2, 'Bob',     1),     -- reports to Alice
(3, 'Carol',   1),     -- reports to Alice
(4, 'David',   2),     -- reports to Bob
(5, 'Eve',     2),     -- reports to Bob
(6, 'Frank',   3); 


WITH RECURSIVE org AS(
SELECT e.e_id,
e.name,
e.m_id,
0 AS depth,
CAST(e.name AS CHAR(100)) AS path
from employee e
where e.m_id IS NULL

UNION ALL

SELECT c.e_id,
c.name,
c.m_id,
p.depth+1 AS depth,
CONCAT(p.path, '->',c.name) AS 	path
FROM employee c
JOIN org p 
ON c.m_id=p.e_id
)
SELECT* from org;







SELECT *from employee;
