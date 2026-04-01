-- Setup Admin User Table and Initial Admin
-- This file creates the admin_users table and inserts the admin credentials

-- Create admin_users table if it doesn't exist
CREATE TABLE IF NOT EXISTS admin_users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE
);

-- Delete existing admin if exists
DELETE FROM admin_users WHERE email = 'admin@gmail.com';

-- Insert admin user with email: admin@gmail.com and password: Admin@123
-- Password hash for 'Admin@123'
INSERT INTO admin_users (username, email, password, full_name, is_active) 
VALUES (
    'admin',
    'admin@gmail.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- This is the hash for 'Admin@123'
    'Super Admin',
    TRUE
);

-- Verify the insert
SELECT id, username, email, full_name, created_at, is_active FROM admin_users WHERE email = 'admin@gmail.com';
