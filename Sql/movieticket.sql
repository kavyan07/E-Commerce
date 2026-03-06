create database movie;
use movie;
create table user(
id INT PRIMARY KEY AUTO_INCREMENT,
name VARCHAR(20) NOT NULL,
email VARCHAR(20) UNIQUE,
phone VARCHAR(20),
created_by INT,
created_at TIMESTAMP DEFAULT  CURRENT_TIMESTAMP,
updated_by INT,
updated_at TIMESTAMP,
deleted_by INT,
deleted_at TIMESTAMP,
CONSTRAINT f_user_created_by FOREIGN KEY (created_by) REFERENCES user(id) ON DELETE SET NULL,
CONSTRAINT f_user_updated_by FOREIGN KEY (updated_by) REFERENCES user(id) ON DELETE SET NULL,
CONSTRAINT f_user_deleted_by FOREIGN KEY (deleted_by) REFERENCES user(id) ON DELETE SET NULL
);

create table cities(
c_id INT PRIMARY KEY AUTO_INCREMENT,
c_name VARCHAR(20),
created_by INT,
created_at TIMESTAMP DEFAULT  CURRENT_TIMESTAMP,
updated_by INT,
updated_at TIMESTAMP,
deleted_by INT,
deleted_at TIMESTAMP,
CONSTRAINT f_cities_created_by FOREIGN KEY (created_by) REFERENCES user(id) ON DELETE SET NULL,
CONSTRAINT f_cities_updated_by FOREIGN KEY (updated_by) REFERENCES user(id) ON DELETE SET NULL,
CONSTRAINT f_cities_deleted_by FOREIGN KEY (deleted_by) REFERENCES user(id) ON DELETE SET NULL
);

create table theaters(
t_id INT PRIMARY KEY  AUTO_INCREMENT,
t_name VARCHAR(20),
city_id INT,
created_by INT,
created_at TIMESTAMP DEFAULT  CURRENT_TIMESTAMP,
updated_by INT,
updated_at TIMESTAMP,
deleted_by INT,
deleted_at TIMESTAMP,
 FOREIGN KEY (created_by) REFERENCES user(id) ON DELETE SET NULL,
 FOREIGN KEY (updated_by) REFERENCES user(id) ON DELETE SET NULL,
 FOREIGN KEY (deleted_by) REFERENCES user(id) ON DELETE SET NULL,
 FOREIGN KEY (city_id) REFERENCES cities(c_id)  ON DELETE CASCADE
 );
 
 create table screens(
s_id INT PRIMARY KEY AUTO_INCREMENT,
theater_id INT NOT NULL,
screen_number INT NOT NULL,
total_seats INT NOT NULL,
created_by INT,
created_at TIMESTAMP DEFAULT  CURRENT_TIMESTAMP,
updated_by INT,
updated_at TIMESTAMP,
deleted_by INT,
deleted_at TIMESTAMP,
 FOREIGN KEY (created_by) REFERENCES user(id) ON DELETE SET NULL,
 FOREIGN KEY (updated_by) REFERENCES user(id) ON DELETE SET NULL,
 FOREIGN KEY (deleted_by) REFERENCES user(id) ON DELETE SET NULL,
 FOREIGN KEY (theater_id) REFERENCES theaters(t_id)  ON DELETE CASCADE
 );
 
 
 CREATE TABLE movies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    duration_minutes INT,
    language VARCHAR(50),
    release_date DATE,

    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_by INT,
    updated_at TIMESTAMP NULL,
    deleted_by INT,
    deleted_at TIMESTAMP NULL,

    FOREIGN KEY (created_by) REFERENCES user(id) ON DELETE SET NULL,
    FOREIGN KEY (updated_by) REFERENCES user(id) ON DELETE SET NULL,
    FOREIGN KEY (deleted_by) REFERENCES user(id) ON DELETE SET NULL
);

CREATE TABLE movies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    duration_minutes INT,
    language VARCHAR(50),
    release_date DATE,

    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_by INT,
    updated_at TIMESTAMP NULL,
    deleted_by INT,
    deleted_at TIMESTAMP NULL,

    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (deleted_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE shows (
    id INT PRIMARY KEY AUTO_INCREMENT,
    movie_id INT NOT NULL,
    screen_id INT NOT NULL,
    show_time DATETIME NOT NULL,
    ticket_price DECIMAL(10,2) NOT NULL,

    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_by INT,
    updated_at TIMESTAMP NULL,
    deleted_by INT,
    deleted_at TIMESTAMP NULL,

    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
    FOREIGN KEY (screen_id) REFERENCES screens(s_id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES user(id) ON DELETE SET NULL,
    FOREIGN KEY (updated_by) REFERENCES user(id) ON DELETE SET NULL,
    FOREIGN KEY (deleted_by) REFERENCES user(id) ON DELETE SET NULL
);


CREATE TABLE seats (
    se_id INT PRIMARY KEY AUTO_INCREMENT,
    screen_id INT NOT NULL,
    seat_number VARCHAR(10) NOT NULL,

    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_by INT,
    updated_at TIMESTAMP NULL,
    deleted_by INT,
    deleted_at TIMESTAMP NULL,

    FOREIGN KEY (screen_id) REFERENCES screens(s_id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES user(id) ON DELETE SET NULL,
    FOREIGN KEY (updated_by) REFERENCES user(id) ON DELETE SET NULL,
    FOREIGN KEY (deleted_by) REFERENCES user(id) ON DELETE SET NULL
);
 
CREATE TABLE bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    show_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,

    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_by INT,
    updated_at TIMESTAMP NULL,
    deleted_by INT,
    deleted_at TIMESTAMP NULL,

    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (show_id) REFERENCES shows(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES user(id) ON DELETE SET NULL,
    FOREIGN KEY (updated_by) REFERENCES user(id) ON DELETE SET NULL,
    FOREIGN KEY (deleted_by) REFERENCES user(id) ON DELETE SET NULL
);

CREATE TABLE booking_tickets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    booking_id INT NOT NULL,
    seat_id INT NOT NULL,

    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_by INT,
    updated_at TIMESTAMP NULL,
    deleted_by INT,
    deleted_at TIMESTAMP NULL,

    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (seat_id) REFERENCES seats(se_id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES user(id) ON DELETE SET NULL,
    FOREIGN KEY (updated_by) REFERENCES user(id) ON DELETE SET NULL,
    FOREIGN KEY (deleted_by) REFERENCES user(id) ON DELETE SET NULL
);


INSERT INTO user(name,email,phone,created_by)
SELECT
 CONCAT('USER',n) AS name,
 CONCAT('user',n,'@gmial,com') AS email,
 CONCAT('9000', LPAD( n, 5,' 0')) AS phone,
 1 AS status
 FROM(
 SELECT @row := @row+1 As n 
 FROM information_schema.tables,
      information_schema.tables t2,
      (SELECT @row :=0)r
      LIMIT 1000
)numbers;

select *from user;

INSERT INTO movies(title,duration_minutes,language,release_date,created_by)
SELECT
     CONCAT('movies',n),
     FLOOR(90+rand()*60),
     'HINDI',
     DATE_ADD('2024-01-01',INTERVAL FLOOR(RAND()*365)DAY),
     1
  FROM (
   SELECT @row2 := @row2 + 1 AS n
    FROM information_schema.tables,
         (SELECT @row2 := 0) r
    LIMIT 100
) numbers;
     
select *from movies;     


INSERT INTO theaters (t_name, city_id, created_by)
SELECT 
    CONCAT('Theater', n),
    (SELECT c_id FROM cities ORDER BY RAND() LIMIT 1),
    1
FROM (
    SELECT @row3 := @row3 + 1 AS n
    FROM information_schema.tables,
         (SELECT @row3 := 0) r
    LIMIT 50
) numbers;


INSERT INTO screens (theater_id, screen_number, total_seats, created_by)
SELECT 
	(SELECT t_id FROM theaters ORDER BY RAND() LIMIT 1),
    n,
    150,
    1
FROM (
    SELECT @row4 := @row4 + 1 AS n
    FROM information_schema.tables,
         (SELECT @row4 := 0) r
    LIMIT 100
) numbers;



INSERT INTO shows (movie_id, screen_id, show_time, ticket_price, created_by)
SELECT 
    FLOOR(1 + RAND()*100),
   (SELECT s_id FROM screens ORDER BY RAND() LIMIT 1),
    DATE_ADD(NOW(), INTERVAL FLOOR(RAND()*30) DAY),
    FLOOR(150 + RAND()*200),
    1
FROM (
    SELECT @row5 := @row5 + 1 AS n
    FROM information_schema.tables,
         (SELECT @row5 := 0) r
    LIMIT 300
) numbers;



INSERT INTO bookings (user_id, show_id, total_amount, created_by)
SELECT 
    (SELECT id FROM user ORDER BY RAND() LIMIT 1),
	(SELECT id FROM shows ORDER BY RAND() LIMIT 1),
    FLOOR(200 + RAND()*500),
    1
FROM (
    SELECT @row6 := @row6 + 1 AS n
    FROM information_schema.tables,
         information_schema.tables t2,
         (SELECT @row6 := 0) r
    LIMIT 1000
) numbers;

select *from bookings;
select *from shows;
select *from theaters;