create database task;
use task;
create table user(
id INT PRIMARY KEY AUTO_INCREMENT,
name VARCHAR(100),
email VARCHAR(100)
);

ALTER TABLE user
ADD  created_by INT,
ADD  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD  updated_by INT,
ADD  updated_at TIMESTAMP,
ADD  deleted_by INT,
ADD  deleted_at TIMESTAMP;

create table project(
P_id INT PRIMARY KEY AUTO_INCREMENT,
P_name VARCHAR(100),
descriptions TEXT,
created_by INT,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_by INT,
updated_at TIMESTAMP,
deleted_by INT,
deleted_at TIMESTAMP
);

ALTER TABLE project
ADD CONSTRAINT f_project_user
FOREIGN KEY  (created_by)
REFERENCES user(id)
ON DELETE SET NULL;


create table task(
t_id INT PRIMARY KEY AUTO_INCREMENT,
project_id INT,
assigend_to INT,
title VARCHAR(20) NOT NULL,
descriptions TEXT,
satus VARCHAR(20) DEFAULT "pending",
due_date DATE,
created_by INT,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_by INT,
updated_at TIMESTAMP,
deleted_by INT,
deleted_at TIMESTAMP
);

ALTER TABLE task
ADD CONSTRAINT f_task_user
FOREIGN KEY (assigend_to)
REFERENCES user(id)
ON DELETE SET NULL;
  
ALTER TABLE task
ADD CONSTRAINT f_task_project
FOREIGN KEY (project_id)
REFERENCES project(p_id)
ON DELETE CASCADE;  


INSERT INTO user (id,name,email) VALUES(1,'kavyan','kp@gmail.com');

select *from user;

DELETE FROM user where id=1;

INSERT INTO user (id,name,email) VALUES(1, 'admin' ,'admin@gmail.com');
INSERT INTO user (name,email,created_by) VALUES ('kavyan','kavyan@gmail.com',1);

INSERT INTO project (p_name,descriptions,created_by) VALUES ('easy-cart','Ecommerce-website',1);

select *from project;

INSERT INTO task (project_id,assigend_to,title,descriptions,due_date,created_by) VALUES(1,1,'Design table' ,'give table relations','2026-03-10',1);

	

INSERT INTO task
(project_id, assigend_to, title, due_date, created_by)
VALUES
(1, 2, 'Create API', '2026-03-15', 1),
(1, 2, 'Frontend UI', '2026-03-20', 1);

UPDATE task  SET title ='update table'  where t_id=1;

UPDATE task SET satus="Completed",updated_by=2,updated_at=CURRENT_TIMESTAMP where  project_id=1;

