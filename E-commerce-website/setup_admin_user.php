<?php
// Setup admin user with proper credentials
// Email: admin@gmail.com, Password: Admin@123

require_once __DIR__ . '/includes/db.php';

$db = getDb();

try {
    // First, add email column if it doesn't exist
    $db->exec("
        ALTER TABLE admin_users 
        ADD COLUMN IF NOT EXISTS email VARCHAR(255) UNIQUE,
        ADD COLUMN IF NOT EXISTS full_name VARCHAR(255),
        ADD COLUMN IF NOT EXISTS is_active BOOLEAN DEFAULT TRUE
    ");

    // Delete existing admin if exists
    $db->exec("DELETE FROM admin_users WHERE username = 'admin'");

    // Hash the password: Admin@123
    $password = password_hash('Admin@123', PASSWORD_DEFAULT);

    // Insert admin user
    $stmt = $db->prepare("
        INSERT INTO admin_users (username, email, password, full_name, is_active, created_at) 
        VALUES (:username, :email, :password, :full_name, :is_active, CURRENT_TIMESTAMP)
    ");

    $stmt->execute([
        'username' => 'admin',
        'email' => 'admin@gmail.com',
        'password' => $password,
        'full_name' => 'Super Admin',
        'is_active' => TRUE
    ]);

    echo "✅ Admin user created successfully!\n\n";
    echo "===========================================\n";
    echo "📧 Email: admin@gmail.com\n";
    echo "🔐 Password: Admin@123\n";
    echo "===========================================\n\n";

    // Verify
    $stmt = $db->prepare("SELECT id, username, email, full_name, is_active FROM admin_users WHERE username = :username");
    $stmt->execute(['username' => 'admin']);
    $admin = $stmt->fetch();

    if ($admin) {
        echo "✅ Verification successful:\n";
        echo "  ID: {$admin['id']}\n";
        echo "  Username: {$admin['username']}\n";
        echo "  Email: {$admin['email']}\n";
        echo "  Full Name: {$admin['full_name']}\n";
        echo "  Active: " . ($admin['is_active'] ? 'Yes' : 'No') . "\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
