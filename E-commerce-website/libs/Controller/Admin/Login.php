<?php

class Controller_Admin_Login extends Controller_Abstract
{
    public function execute()
    {
        // Handle POST login
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            require_once ROOT_PATH . '/includes/db.php';
            $db = getDb();

            // Check by email OR username for flexibility
            $stmt = $db->prepare("SELECT * FROM admin_users WHERE email = ? OR username = ?");
            $stmt->execute([$email, $email]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin && password_verify($password, $admin['password'])) {
                // Check if admin is active
                if (isset($admin['is_active']) && !$admin['is_active']) {
                    $_SESSION['flash_message'] = ['text' => 'Your account has been deactivated', 'type' => 'error'];
                } else {
                    $_SESSION['admin_user'] = [
                        'id' => $admin['id'],
                        'username' => $admin['username'],
                        'email' => $admin['email']
                    ];
                    header('Location: /E-commerce-website/admin/dashboard');
                    exit;
                }
            } else {
                $_SESSION['flash_message'] = ['text' => 'Invalid email or password', 'type' => 'error'];
            }
        }

        // Render login page WITHOUT header/footer (standalone page)
        $view = new View_Default();
        $view->setTemplate('admin/login');
        $view->page_title = 'Admin Login';

        // For admin pages, render template directly without header/footer
        echo $view->render();
    }
}
