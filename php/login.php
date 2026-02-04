<?php
session_start();
$page_title = 'Login - EasyCart';
$page_css = 'login.css';

// Ensure users array exists (for demo signup storage)
if (!isset($_SESSION['users']) || !is_array($_SESSION['users'])) {
    $_SESSION['users'] = [];
}

$login_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim((string)($_POST['email'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $login_error = 'Email and password are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $login_error = 'Please enter a valid email address.';
    } else {
        if (isset($_SESSION['users'][$email]) && $_SESSION['users'][$email]['password'] === $password) {
            // Successful login – store user in session
            $_SESSION['user'] = $_SESSION['users'][$email];
            // Set success flash message
            $_SESSION['flash_message'] = [
                'text' => 'Login successful. Welcome back!',
                'type' => 'success'
            ];
            header('Location: index.php');
            exit;
        } else {
            $login_error = 'Invalid email or password.';
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

    <div class="container">
        <div class="login-container">
            <div class="login-card">
            <h1>🔐 Login</h1>

            <?php if ($login_error): ?>
                <div class="error-msg" style="margin-bottom:1rem;"><?php echo htmlspecialchars($login_error); ?></div>
            <?php endif; ?>

            <form id="loginForm" method="POST" novalidate>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="remember-me">
                    <input type="checkbox" id="remember">
                    <label for="remember">Remember me</label>
                </div>

                <button type="submit" class="login-btn">Login</button>
            </form>


            <div class="forgot-password">
                <a href="#">Forgot Password?</a>
            </div>

                <div class="signup-link">
                    Don't have an account? <a href="signup.php">Sign up here</a>
                </div>
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
